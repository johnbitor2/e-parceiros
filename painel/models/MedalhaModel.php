<?php
    class MedalhaModel extends PersistAbstractModel
    {
        private $st_nome;
        private $st_descricao;
        private $dt_data;
        private $st_img;
        
        function __construct() {
            //parent::__construct();
        }
        
        public function setSt_nome($st_nome) {
            $this->st_nome = $st_nome;
        }

        public function setSt_descricao($st_descricao) {
            $this->st_descricao = $st_descricao;
        }

        public function setDt_data($dt_data) {
            $this->dt_data = $dt_data;
        }

        public function setSt_img($st_img) {
            $this->st_img = $st_img;
        }
        
        public function getSt_nome() {
            return $this->st_nome;
        }

        public function getSt_descricao() {
            return $this->st_descricao;
        }

        public function getDt_data() {
            return $this->dt_data;
        }

        public function getSt_img() {
            return $this->st_img;
        }

        
                
        /*
        * Retorna um array contendo objetos de medalhas do char
        * @param String $st_charName
        * @return Array
       */
        public function _list($st_charName)
        {
            /*$st_query = "SELECT medalha.md_nome as nome,medalha.md_img as img,
                medalha.md_descricao as desc,medalha_char.mdc_data as data 
            FROM medalha, medalha_char 
            WHERE mdc_char='$st_charName' and medalha.md_id=medalha_char.mdc_medalha_id";
            */
              
             $st_query  = "SELECT medalha.md_nome,medalha.md_img,medalha.md_descricao,medalha_char.mdc_data
                 FROM medalha
                 INNER JOIN medalha_char
                 ON medalha.md_id=medalha_char.mdc_medalha_id
                 WHERE medalha_char.mdc_char='$st_charName'";
             

             $v_medalhas = array();
            /* try 
             {
                 $o_data = $this->o_db->query($st_query);
                 while ($o_ret = $o_data->fetchObject())
                 {
                      $o_medal = new MedalhaModel();
                      $o_medal->setSt_nome($o_ret->nome);
                      $o_medal->setSt_descricao($o_ret->desc);
                      $o_medal->setSt_img($o_ret->img);
                      $o_medal->setDt_data($o_ret->data);
                      array_push($v_medalhas, $o_medal);

                 }
             }
             catch (PDOException $e)
             {}*/
             
             
             
             return $v_medalhas;
        }
    }
?>
