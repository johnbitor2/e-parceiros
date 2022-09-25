<?php
/*
*/
class LojaModel //extends PersistAbstractModel
{
    private $st_nome;
    private $st_img;
    private $st_coditem;
    private $st_tipo;
    private $st_desc;
    private $in_preco;
    private $in_preco_promo;
    private $dt_promo_data;
    private $in_promocao;
    
    function __construct() {
        //parent::__construct();
    }
    
    public function getSt_nome() {
        return $this->st_nome;
    }

    public function setSt_nome($st_nome) {
        $this->st_nome = $st_nome;
    }
 
    public function getIn_preco() {
        return $this->in_preco;
    }

    public function setIn_preco($in_preco) {
        $this->in_preco = $in_preco;
    }

    public function getSt_img() {
        return $this->st_img;
    }

    public function setSt_img($st_img) {
        $this->st_img = $st_img;
    }

    public function getIn_preco_promo() {
        return $this->in_preco_promo;
    }

    public function setIn_preco_promo($in_preco_promo) {
        $this->in_preco_promo = $in_preco_promo;
    }

    public function getDt_promo_data() {
        return $this->dt_promo_data;
    }

    public function setDt_promo_data($dt_promo_data) {
        $this->dt_promo_data = $dt_promo_data;
    }

    public function getIn_promocao() {
        return $this->in_promocao;
    }

    public function setIn_promocao($in_promocao) {
        $this->in_promocao = $in_promocao;
    }
    
    public function getSt_coditem() {
        return $this->st_coditem;
    }

    public function setSt_coditem($st_coditem) {
        $this->st_coditem = $st_coditem;
    }

    public function getSt_tipo() {
        return $this->st_tipo;
    }

    public function setSt_tipo($st_tipo) {
        $this->st_tipo = $st_tipo;
    }

    public function getSt_desc() {
        return $this->st_desc;
    }

    public function setSt_desc($st_desc) {
        $this->st_desc = $st_desc;
    }

    

    public function _listByType($st_tp, $in_page = null, $in_tam = null)
    {
        $connection_string = 'DRIVER={SQL Server};SERVER=dbfutura\SQLEXPRESS;DATABASE=futuradb';

            $user = 'sa';
            $pass = '123456';
            
            $connection = odbc_connect( $connection_string, $user, $pass );
       
        if($in_tam == null)  
            $in_tam = 8;
        if($in_page != null)
        {
            $st_query = "SELECT count(*) as num FROM loja WHERE lj_tipo = ". $st_tp;
        
            $results = odbc_exec($connection, $st_query);
            $lol = odbc_fetch_array($results);
            $numItens = $lol["num"];
            $numPages = ceil($numItens/$in_tam);
            
            $st_query = "SELECT TOP ".$in_tam." * FROM loja WHERE lj_tipo = ". $st_tp;
        
        }
        else    
        {
            $st_query = "SELECT * FROM loja WHERE lj_tipo = ". $st_tp;
        }
            $res = odbc_exec($connection, $st_query);
            
            $v_itens = array();
            
            while($farr = odbc_fetch_array($res)){
                $o_item = new LojaModel();
                $o_item->setSt_nome($farr["lj_nome"]);
                $o_item->setSt_img($farr["lj_img"]);
                $o_item->setSt_coditem($farr["lj_cod"]);
                $o_item->setSt_tipo($farr["lj_tipo"]);
                $o_item->setSt_desc($farr["lj_desc"]);
                $o_item->setIn_preco($farr["lj_preco"]);
                $o_item->setIn_preco_promo($farr["lj_Promo_preco"]);
                $o_item->setIn_promocao($farr["lj_Promo"]);
                $o_item->setDt_promo_data($farr["lj_promo_Data"]);

                array_push($v_itens, $o_item); 
            }
            
      /*  $v_itens = array();
        try
        {
            $o_data = $this->o_db->query($st_query);
            while ($o_ret = $o_data->fetchObject())
            {
                $o_item = new LojaModel();
                $o_item->setSt_nome($o_ret->lj_nome);
                $o_item->setSt_img($o_ret->lj_img);
                $o_item->setSt_coditem($o_ret->lj_cod);
                $o_item->setSt_tipo($o_ret->lj_tipo);
                $o_item->setSt_desc($o_ret->lj_desc);
                $o_item->setIn_preco($o_ret->lj_preco);
                $o_item->setIn_preco_promo($o_ret->lj_Promo_preco);
                $o_item->setIn_promocao($o_ret->lj_Promo);
                $o_item->setDt_promo_data($o_ret->lj_promo_Data);
                array_push($v_itens, $o_item);
            }
        }
        catch (PDOException $e){}*/
        return $v_itens;
    }

    
    /*
     * Retorna um array contendo os itens
     * da loja pelo tipo(cristal, premiun, aging, etc)
     * @param String $st_tp
     * @return Array
    */
    public function _listPromo()
    {
        $st_query = "SELECT * FROM `loja` WHERE lj_Promo = 1 OR CURDATE() <= lj_promo_Data";
        $v_itens = array();
        try
        {
            $o_data = $this->o_db->query($st_query);
            while ($o_ret = $o_data->fetchObject())
            {
                $o_item = new LojaModel();
                $o_item->setSt_nome($o_ret->lj_nome);
                $o_item->setSt_img($o_ret->lj_img);
                $o_item->setSt_coditem($o_ret->lj_cod);
                $o_item->setSt_tipo($o_ret->lj_tipo);
                $o_item->setSt_desc($o_ret->lj_desc);
                $o_item->setIn_preco($o_ret->lj_preco);
                $o_item->setIn_preco_promo($o_ret->lj_Promo_preco);
                $o_item->setIn_promocao($o_ret->lj_Promo);
                $o_item->setDt_promo_data($o_ret->lj_promo_Data);
                array_push($v_itens, $o_item);
            }
        }
        catch (PDOException $e){}
        return $v_itens;
    }
    
    public function loadItem($st_nome)
    {
        $connection_string = 'DRIVER={SQL Server};SERVER=dbfutura\SQLEXPRESS;DATABASE=futuradb';

            $user = 'sa';
            $pass = '123456';
            
            $connection = odbc_connect( $connection_string, $user, $pass );
            
        $st_query = "SELECT * FROM loja WHERE lj_nome = '". $st_nome ."'";
        
        $res = odbc_exec($connection, $st_query);
        
        $farr = odbc_fetch_array($res);
        
            $this->setSt_nome($farr["lj_nome"]);
            $this->setSt_img($farr["lj_img"]);
            $this->setSt_coditem($farr["lj_cod"]);
            $this->setSt_tipo($farr["lj_tipo"]);
            $this->setSt_desc($farr["lj_desc"]);
            $this->setIn_preco($farr["lj_preco"]);
            $this->setIn_preco_promo($farr["lj_Promo_preco"]);
            $this->setIn_promocao($farr["lj_Promo"]);
            $this->setDt_promo_data($farr["lj_promo_Data"]);
        /*try
        {
            $o_data = $this->o_db->query($st_query);
            $o_ret = $o_data->fetchObject();
            
            $this->setSt_nome($o_ret->lj_nome);
            $this->setSt_img($o_ret->lj_img);
            $this->setSt_coditem($o_ret->lj_cod);
            $this->setSt_tipo($o_ret->lj_tipo);
            $this->setSt_desc($o_ret->lj_desc);
            $this->setIn_preco($o_ret->lj_preco);
            $this->setIn_preco_promo($o_ret->lj_Promo_preco);
            $this->setIn_promocao($o_ret->lj_Promo);
            $this->setDt_promo_data($o_ret->lj_promo_Data);
            
        }
        catch (PDOException $e){}
        */
        return $this;
    }
}
?>
