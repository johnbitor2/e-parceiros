<?php

    class TicketModel extends PersistAbstractModel
    {
        private $in_id;
        private $st_autor;
        private $st_comentario;
        private $dt_data;
        private $st_img;
        private $in_sp_id;
        
        public function getIn_id() {
            return $this->in_id;
        }

        public function getSt_autor() {
            return $this->st_autor;
        }

        public function getSt_comentario() {
            return $this->st_comentario;
        }

        public function getDt_data() {
            return $this->dt_data;
        }

        public function getSt_img() {
            return $this->st_img;
        }

        public function getIn_sp_id() {
            return $this->in_sp_id;
        }

        public function setIn_id($in_id) {
            $this->in_id = $in_id;
        }

        public function setSt_autor($st_autor) {
            $this->st_autor = $st_autor;
        }

        public function setSt_comentario($st_comentario) {
            $this->st_comentario = $st_comentario;
        }

        public function setDt_data($dt_data) {
            $this->dt_data = $dt_data;
        }

        public function setSt_img($st_img) {
            $this->st_img = $st_img;
        }

        public function setIn_sp_id($in_sp_id) {
            $this->in_sp_id = $in_sp_id;
        }

        /**
	* Retorna um array contendo os tickets
	* de um determinado suporte
	* @param integer $in_sp_id
	* @return Array
	*/
        public function _listTicket($in_sp_id)
        {
            $st_query = "SELECT * FROM `ticket` 
                WHERE tk_suporte_id='$in_sp_id' ORDER BY tk_data";
            $v_tickets = array();
            try
            {
                $o_data = $this->o_db->query($st_query);
                while ($o_ret = $o_data->fetchObject())
                {
                    $o_tket = new TicketModel();
                    $o_tket->setIn_sp_id($o_ret->tk_suporte_id);
                    $o_tket->setSt_comentario($o_ret->tk_comentario);
                    $o_tket->setDt_data($o_ret->tk_data);
                    $o_tket->setSt_autor($o_ret->tk_autor);
                    $o_tket->setSt_img($o_ret->tk_img);
                    $o_tket->setIn_id($o_ret->tk_id);

                    array_push($v_tickets, $o_tket);
                }
            }
            catch (PDOException $e)
            {}
            return $v_tickets;
        }
        
        
        /**
        * Salva dados contidos na instancia da classe
	* na tabela de ticket. Se o ID for passado,
	* um UPDATE será executado, caso contrário, um
	* INSERT será executado
	* @throws PDOException
	* @return integer
        */
        public function save()
        {
            if(is_null($this->in_id))
                $st_query = "INSERT INTO `ticket`
                                    (
                                            tk_autor,
                                            tk_comentario,
                                            tk_data,
                                            tk_suporte_id,
                                            tk_img
                                    )
                                    VALUES
                                    (
                                            '$this->st_autor',
                                            '$this->st_comentario',
                                            '$this->dt_data',
                                            '$this->in_sp_id',
                                            '$this->st_img'
                                    );";
            else
                $st_query = "UPDATE `ticket`
                                    SET
                                            tk_autor = '$this->st_autor',
                                            tk_comentario = '$this->st_comentario',
                                            tk_data = '$this->dt_data',
                                            tk_suporte_id = '$this->in_sp_id',
                                            tk_img = '$this->st_img'
                                    WHERE
                                            tk_id = $this->in_id";

            try
            {
                if($this->o_db->exec($st_query) > 0)
                    if(is_null($this->in_id))
                        return $this->o_db->lastInsertId();	
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
