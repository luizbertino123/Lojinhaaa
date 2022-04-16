<?php
 
defined('BASEPATH') or exit('Ação não permitida');
 
class Carrinho_compras {
 
    public function __construct() {
 
        //Verifica se existe na sessão uma chave chamada 'carrinho'
        if (!isset($_SESSION['carrinho'])) {
 
            $_SESSION['carrinho'] = [];
        }
    }
 
    public function insert($produto_id = NULL, $produto_quantidade = NULL) {
 
        if ($produto_id && $produto_quantidade) {
 
            if (isset($_SESSION['carrinho'][$produto_id])) {
                $_SESSION['carrinho'][$produto_id] += $produto_quantidade;
            } else {
                $_SESSION['carrinho'][$produto_id] = $produto_quantidade;
            }
        }
    }
 
    //Atualiza a quantidade de itens do referido produto
    public function update($produto_id = NULL, $produto_quantidade = NULL) {
 
        if ($produto_id && $produto_quantidade && $produto_quantidade > 0) {
            $_SESSION['carrinho'][$produto_id] = $produto_quantidade;
        }
    }
 
    //Remove do carrinho o produto
    public function delete($produto_id = null) {
 
        unset($_SESSION['carrinho'][$produto_id]);
    }
 
    //Limpa todo o carrinho de compras
    public function clean() {
        unset($_SESSION['carrinho']);
    }
 
    //Lista todos os itens do carrinho (Sessão) de compras
    public function get_all() {
 
        $CI = & get_instance();
        $CI->load->model('carrinho_model'); //Criar
 
        $retorno = array();
        $indice = 0;
 
 
        foreach ($_SESSION['carrinho'] as $produto_id => $produto_quantidade) {
 
            $query = $CI->carrinho_model->get_by_id($produto_id);
 
            $retorno[$indice]['produto_id'] = $query->produto_id;
            $retorno[$indice]['produto_nome'] = $query->produto_nome;
            $retorno[$indice]['produto_valor'] = $query->produto_valor;
            $retorno[$indice]['produto_quantidade'] = $produto_quantidade;
            $retorno[$indice]['subtotal'] = number_format($produto_quantidade * $query->produto_valor, 2, '.', '');
 
            $retorno[$indice]['produto_peso'] = $query->produto_peso;
            $retorno[$indice]['produto_altura'] = $query->produto_altura;
            $retorno[$indice]['produto_largura'] = $query->produto_largura;
            $retorno[$indice]['produto_comprimento'] = $query->produto_comprimento;
            $retorno[$indice]['produto_foto'] = $query->foto_caminho;
            $retorno[$indice]['produto_meta_link'] = $query->produto_meta_link;
 
            //Incrementa a variável índice
            $indice++;
        }
 
        return $retorno;
    }
 
    //Exibe o valor total dos produtos do carrinho (Sessão)
    public function get_total() {
 
        $carrinho = $this->get_all();
        $valor_total_carrinho = 0;
 
        foreach ($carrinho as $indice => $produto) {
            $valor_total_carrinho += $produto['subtotal'];
        }
 
        return number_format($valor_total_carrinho, 2);
    }
 
    //Exibe o total (quantidade) de itens no carrinho (Sessão)
    public function get_total_itens() {
 
        $total_itens = count($this->get_all());
 
        return $total_itens;
    }
 
    //Recupera as dimensões dos produtos do carrinho (Sessão)
    public function get_all_dimensoes() {
 
        $CI = & get_instance();
        $CI->load->model('carrinho_model'); //Criar
 
        $retorno = array();
        $indice = 0;
 
 
        foreach ($_SESSION['carrinho'] as $produto_id => $produto_quantidade) {
 
            $query = $CI->carrinho_model->get_by_id($produto_id);
 
 
            $retorno[$indice]['produto_nome'] = $query->produto_nome;
            $retorno[$indice]['produto_peso'] = $query->produto_peso;
            $retorno[$indice]['produto_altura'] = $query->produto_altura;
            $retorno[$indice]['produto_largura'] = $query->produto_largura;
            $retorno[$indice]['produto_comprimento'] = $query->produto_comprimento;
            $retorno[$indice]['produto_dimensao'] = $query->produto_altura + $query->produto_largura + $query->produto_comprimento;
 
 
            //Incrementa a variável índice
            $indice++;
        }
 
        return $retorno;
    }
 
    //Recupera o produto de maior dimensão para calculcar o frete com base nesse produto
    public function get_produto_maior_dimensao() {
 
 
        $produtos = $this->get_all_dimensoes();
 
        $maior_produto = null;
 
        $item = array();
 
        foreach ($produtos as $indice => $produto) {
 
            if ($maior_produto == null) {
 
                $maior_produto = $produto['produto_dimensao'];
                $item = $produto; //Armazendo na variável item os atributos do objeto $produto
            } else {
 
                if ($produto['produto_dimensao'] > $maior_produto) { // Comparar se o produto do laço corrente é maior que $maior_produto
                    $maior_produto = $produto['produto_dimensao'];
                    $item = $produto;
                }
            }
        }
 
        return $item; //Retorna o produto de maior dimensão
    }
 
    //Retorna o total de pesos dos produtos do carrinho (Sessão)
    public function get_total_pesos() {
 
        $carrinho = $this->get_all();
        $total_pesos = 0;
 
        foreach ($carrinho as $indice => $produto) {
 
            $total_pesos += $produto['produto_peso'] * $produto['produto_quantidade'];
        }
 
        return $total_pesos;
    }
 
}