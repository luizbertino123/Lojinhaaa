<?php

defined('BASEPATH') OR exit('Ação não permitida');

class Carrinho_model extends CI_Model {

    public function get_by_id($produto_id = null) {

        if ($produto_id) {


            $this->db->select([
                'produtos.produto_id',
                'produtos.produto_nome',
                'produtos.produto_valor',
                'produtos.produto_peso',
                'produtos.produto_altura',
                'produtos.produto_largura',
                'produtos.produto_comprimento',
                'produtos.produto_meta_link',
                'produtos.produto_quantidade_estoque',
                'produtos.produto_descricao',
                'produtos_fotos.foto_caminho',
            ]);

            $this->db->where('produtos.produto_id', $produto_id);
            $this->db->where('produtos.produto_ativo', 1);
            
            $this->db->limit(1);

            $this->db->join('produtos_fotos', 'produtos_fotos.foto_produto_id = produtos.produto_id', 'LEFT');

            return $this->db->get('produtos')->row();
        }
    }

}
