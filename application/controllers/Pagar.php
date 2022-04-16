<?php

defined('BASEPATH') OR exit('Ação não permitida');

class Pagar extends CI_Controller {

    public function _construct() {
        parent::_construct();

        
        $this->load->library('user_agent');
    }

    public function index() {

        redirect('/');
    }

    public function pag_seguro_session_id() {

        if (!$this->input->is_ajax_request()) {
            exit('Ação não permitida');
        }

        //Configurações do pagseguro
        $config_pagseguro = $this->core_model->get_by_id('config_pagseguro', array('config_id' => 1));

        if ($config_pagseguro->config_ambiente == 1) {

            $url = "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions"; //Sandbox - Teste
        } else {

            $url = "https://ws.pagseguro.uol.com.br/v2/sessions"; //Produtivo - Real
        }

        //Definindo os parametros necessarios para a URL acima
        $parametros = array(
            'email' => $config_pagseguro->config_email,
            'token' => $config_pagseguro->config_token,
        );

        //Iniciando a configuração para requisitar dados á API do pagseguro
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        //Fazendo um count para informar ao curl quantos parametros serão enviados via POST
        curl_setopt($ch, CURLOPT_POST, count($parametros));

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));

        curl_setopt($ch, CURLOPT_POSTFIELDS, 45);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Informando qual é o agente que está requisitando a API do pagseguro
        curl_setopt($ch, CURLOPT_USERAGENT, $this->agent->agent_string());

        if ($config_pagseguro->config_ambiente == 1) {

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        } else {

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        }

        $result = curl_exec($ch);

        curl_close($ch);

        $xml = simplexml_load_string($result);

        $json = json_encode($xml);
        $session = json_decode($json, true);

        $retorno = array();

        //Verifica se foi gerada uma sessão junto á pagseguro
        if ($session['id']) {

            //Sucesso... Sessão foi gerada

            $retorno['erro'] = 0;
            $retorno['session_id'] = $session['id'];
        } else {

            //Não foi gerado a sessão
            $retorno['erro'] = 3;
            $retorno['mensagem'] = 'Não foi gerada uma sessão. Tente novamente';
        }

        header('Content-Type: application/json');
        echo json_encode($retorno);
    }

}
