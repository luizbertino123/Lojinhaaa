<?php

defined('BASEPATH') or exit('Ação não permitida');

class Carrinho extends CI_Controller {

    public function _construct() {
        parent::__construct();

        $this->load->library('carrinho_compras');
    }

    public function index() {

        $data = array(
            'titulo' => 'Carrinho de compra',
            'scripts' => array(
                'mask/jquery.mask.min.js',
                'mask/custom.js',
                'js/carrinho.js',
            ),
        );

        $carrinho = array(
            'carrinho' => $this->carrinho_compras->get_all(),
        );

        $this->load->view('web/layout/header', $data);
        $this->load->view('web/carrinho', $carrinho);
        $this->load->view('web/layout/footer');
    }

    public function insert() {

        $produto_id = (int) $this->input->post('produto_id');
        $produto_quantidade = (int) $this->input->post('produto_quantidade');

        $retorno = array();

        if (!$produto_id || $produto_quantidade < 1) {

            $retorno['erro'] = 3;
            $retorno['mensagem'] = 'Informe a quantidade maior que zero';
        } else {

            //continua o processamento... verificamos a existencia do produto

            if (!$produto = $this->core_model->get_by_id('produtos', array('produto_id' => $produto_id))) {

                $retorno['erro'] = 3;
                $retorno['mensagem'] = 'Produto não encontrado';
            } else {

                //Sucesso... O produto existe... Agora comparamos a quantidade em estoque com o que veio via a POST

                if ($produto_quantidade > $produto->produto_quantidade_estoque) {

                    $retorno['erro'] = 3;
                    $retorno['mensagem'] = 'Infelizmente só temos ' . $produto->produto_quantidade_estoque . 'em estoque';
                } else {

                    //Sucesso... Estoque disponivel
                    $this->carrinho_compras->insert($produto_id, $produto_quantidade);

                    $retorno['erro'] = 0;
                    $retorno['mensagem'] = '<i class="fa fa-check-square" aria-hidden="true"></i>&nbsp;Adicionado com sucesso!';
                }
            }
        }

        echo json_encode($retorno);
    }

    public function delete() {

        $retorno = array();

        if ($produto_id = (int) $this->input->post('produto_id')) {

            $this->carrinho_compras->delete($produto_id);

            $retorno['erro'] = 0;
            $retorno['mensagem'] = 'Produto excluido com sucesso';
        }

        echo json_encode($retorno);
    }

    public function update() {

        $produto_id = (int) $this->input->post('produto_id');
        $produto_quantidade = (int) $this->input->post('produto_quantidade');

        $retorno = array();

        if ($produto_quantidade == "" || $produto_quantidade < 1) {

            $retorno['erro'] = 3;
            $retorno['mensagem'] = 'Informe a quantidade maior que zero';
        } else {

            //Valida se o produto existe

            if (!$produto = $this->core_model->get_by_id('produtos', array('produto_id' => $produto_id))) {
                $retorno['erro'] = 3;
                $retorno['mensagem'] = 'Produto não encontrado';
            } else {

                //Sucesso... Produto existe... Continua o processamento

                if ($produto_quantidade > $produto->produto_quantidade_estoque) {
                    $retorno['erro'] = 3;
                    $retorno['mensagem'] = 'Infelizmente só temos ' . $produto->produto_quantidade_estoque . ' unidade(s) em estoque';
                } else {

                    ///Sucesso... Atuliza a quantidade do item no carrinho, pois há estoque suficiente.
                    $this->carrinho_compras->update($produto_id, $produto_quantidade);

                    $retorno['erro'] = 0;
                    $retorno['mensagem'] = 'Carrinho atualizado com sucesso';
                }
            }
        }

        echo json_encode($retorno);
    }

    public function clean() {

        $retorno = array();

        if ($this->input->post('clean') && $this->input->post('clean') == true) {

            $this->carrinho_compras->clean();
            $retorno['erro'] = 0;
            $retorno['mensagem'] = 'Carrinho está vazio';
        }

        echo json_encode($retorno);
    }

    public function calcula_frete() {

        $this->form_validation->set_rules('cep', 'CEP destino', 'trim|required|exact_length[9]');

        if ($this->form_validation->run()) {

            //Sucesso... continua o processamento

            $cep_destino = str_replace('-', '', $this->input->post('cep'));

            $retorno = array();

            //Montando a url para consultar o endereço
            $url_endereco = 'https://viacep.com.br/ws/';
            $url_endereco .= $cep_destino;
            $url_endereco .= '/json/';

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, $url_endereco);

            $resultado = curl_exec($curl);

            $resultado = json_decode($resultado);

            if (isset($resultado->erro)) {
                $retorno['erro'] = 3;
                $retorno['mensagem'] = 'Não encontramos o CEP em nossa base de dados';
                $retorno['retorno_endereco'] = 'Não encontramos o CEP em nossa base de dados';
            } else {
                $retorno['erro'] = 0;
                $retorno['mensagem'] = 'Sucesso';
                $retorno_endereco = $retorno['retorno_endereco'] = $resultado->logradouro . ', ' . $resultado->bairro . ', ' . $resultado->localidade . ' - ' . $resultado->uf . ', ' . $resultado->cep;
            }

            //Final da consulta ao Web service via Cep

            /*
             * Inicio da consulta ao web service dos correios
             */

            //Montando a url para os correios exibir o valor do frete
            /*
             * http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=08082650&sDsSenha=564321&sCepOrigem=70002900&sCepDestino=04547000&nVlPeso=1
             * &nCdFormato=1 &nVlComprimento=20 &nVlAltura=20 &nVlLargura=20 &sCdMaoPropria=n &nVlValorDeclarado=0 &sCdAvisoRecebimento=n
             *  &nCdServico=04510 &nVlDiametro=0 &StrRetorno=xml &nIndicaCalculo=3
             */

            //Informações dos correios no banco de dados
            $config_correios = $this->core_model->get_by_id('config_correios', array('config_id' => 1));

            //Recuperando o maior produto e o total de pesos dos itens do carrinho
            $produto = $this->carrinho_compras->get_produto_maior_dimensao();
            $total_pesos = $this->carrinho_compras->get_total_pesos();
            
            $url_correios = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?';
            $url_correios .= 'nCdEmpresa=08082650';
            $url_correios .= '&sDsSenha=564321';
            $url_correios .= '&sCepOrigem=' . str_replace('-', '', $config_correios->config_cep_origem);
            $url_correios .= '&sCepDestino=' . $cep_destino;            
            $url_correios .= '&nVlPeso=' . $total_pesos;
            $url_correios .= '&nCdFormato=1';
            $url_correios .= '&nVlComprimento=' . $produto['produto_comprimento'];
            $url_correios .= '&nVlAltura=' . $produto['produto_altura'];
            $url_correios .= '&nVlLargura=' . $produto['produto_largura'];
            $url_correios .= '&sCdMaoPropria=n';
            $url_correios .= '&nVlValorDeclarado=' . $config_correios->config_valor_declarado;
            $url_correios .= '&sCdAvisoRecebimento=n';
            $url_correios .= '&nCdServico=' . $config_correios->config_codigo_pac;
            $url_correios .= '&nCdServico=' . $config_correios->config_codigo_sedex;
            $url_correios .= '&nVlDiametro=0';
            $url_correios .= '&StrRetorno=xml';
            $url_correios .= '&nIndicaCalculo=3';

//              echo json_encode($url_correios);
//              exit();
//              
            //Transformando o documento xml em um objeto
            $xml = simplexml_load_file($url_correios);
            $xml = json_encode($xml);

            $consulta = json_decode($xml);

            //Garantindo que houve sucesso na consulta ao web service dos correios
            if ($consulta->cServico[0]->Valor == '0,00') {

                $retorno['erro'] = 3;
                $retorno['retorno_endereco'] = 'Não foi possível calcular o frete. Por favor entre em contato com o nosso suporte';                
                exit();
            } else {
                //Sucesso.....valor e prazo gerados
                
                //Recuperando o valor total dos produtos do carrinho para somar á variável $valor_calculado dentro do foreach                
                $valor_total_produtos = str_replace(',','', $this->carrinho_compras->get_total());

                $frete_calculado = "";

                foreach ($consulta->cServico as $dados) {

                    $valor_formatado = str_replace(',', '.', $dados->Valor);

                    number_format($valor_calculado = ($valor_formatado + $config_correios->config_somar_frete), 2, '.', '');
                    
                    $valor_final_carrinho = $valor_total_produtos + $valor_calculado;

                    //      $frete_calculado .= '<p>' . ($dados->Codigo == '04510' ? 'PAC' : 'Sedex') . '&nbsp;R$&nbsp;' . $valor_calculado . ',&nbsp;<span class="badge badge-info">' . $dados->PrazoEntrega . '</span> dias úteis</p>';
                
                    $frete_calculado .= '<div class="custom-control custom-radio">
                                      <input type="radio" class="custom-control-input" id="' . $dados->Codigo . '" name="opcao_frete_carrinho" value="'.$valor_calculado.'" data-valor_frete="'. $valor_calculado .'" data-valor_final_carrinho = "'. number_format($valor_final_carrinho, 2) . '">
                                      <label class="custom-control-label" for="' . $dados->Codigo .'"> '. ($dados->Codigo == '04510' ? 'PAC' : 'Sedex') .' &nbsp;R$&nbsp;' . $valor_calculado . '&nbsp;&nbsp;&nbsp; A partir de <span class="badge badge-info">' . $dados->PrazoEntrega . '</span> dias úteis após confirmação do pagamento</label>
                                  </div>'; 
                    
                }
            }

            $retorno['erro'] = 0;
            $retorno['retorno_endereco'] = $retorno_endereco . '<br>' . $frete_calculado;
        } else {

            //Erro de validação  
            
            $retorno['erro'] = 3;
            $retorno['retorno_endereco'] = validation_errors();
        }

        echo json_encode($retorno);
    }

}
