<?php
/*

*/
class SuporteModel extends PersistAbstractModel
{
    private $in_id;
    private $in_status;
    private $st_assunto;
    private $st_conta;
    
    
    function __construct() {
        parent::__construct();
    }
    
    public function getIn_id() {
        return $this->in_id;
    }

    public function setIn_id($in_id) {
        $this->in_id = $in_id;
    }

    public function getSt_status() {
        return $this->in_status;
    }

    public function setSt_status($st_status) {
        $this->in_status = $st_status;
    }

    public function getSt_assunto() {
        return $this->st_assunto;
    }

    public function setSt_assunto($st_assunto) {
        $this->st_assunto = $st_assunto;
    }

    public function getSt_conta() {
        return $this->st_conta;
    }

    public function setSt_conta($st_conta) {
        $this->st_conta = $st_conta;
    }

    
    /*
     * Retorna um array contendo os suportes
     * da id do player(Validate(Session))
     * @param String $st_id
     * @return Array
    */
    public function _list($st_id)
    {
        $st_query = "SELECT * FROM `suporte` WHERE sp_conta='$st_id' ORDER BY sp_status DESC";
        $v_suportes = array();
        try
        {
            $o_data = $this->o_db->query($st_query);
            while ($o_ret = $o_data->fetchObject())
            {
                $o_suporte = new SuporteModel();
                $o_suporte->setIn_id($o_ret->sp_id);
                $o_suporte->setSt_status($o_ret->sp_status);
                $o_suporte->setSt_assunto($o_ret->sp_assunto);
                $o_suporte->setSt_conta($o_ret->sp_conta);
                
                array_push($v_suportes, $o_suporte);
            }
        }
        catch (PDOException $e)
        {}
        return $v_suportes;
    }
    
    /**
        * Salva dados contidos na instancia da classe
	* na tabela de suporte. Se o ID for passado,
	* um UPDATE será executado, caso contrário, um
	* INSERT será executado
	* @throws PDOException
	* @return integer
    */
    public function save()
    {
        if(is_null($this->in_id))
            $st_query = "INSERT INTO `suporte`
				(
					sp_status,
					sp_conta,
                                        sp_assunto
				)
				VALUES
				(
					'$this->in_status',
					'$this->st_conta',
					'$this->st_assunto'
				);";
	else
            $st_query = "UPDATE `suporte`
				SET
                                        sp_status = '$this->in_status',
                                        sp_conta = '$this->st_conta',
                                        sp_assunto = '$this->st_assunto'
				WHERE
					sp_id = '$this->in_id'";
        
        try
        {
            if($this->o_db->exec($st_query) > 0)
		if(is_null($this->in_id))
		{
			return $this->o_db->lastInsertId();	
		}
		else
                    return $this->in_id;
	}
	catch (PDOException $e)
	{
            throw $e;
	}
	return false;		
    }
  
}
?>
