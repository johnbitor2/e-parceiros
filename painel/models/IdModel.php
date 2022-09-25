<?php
    class IdModel //extends PersistAbstractModel
    {
        private $st_id;
        private $st_senha;
        private $in_coins;
        private $st_email;
        private $dt_lastlogin;
        private $st_nome;
        private $dt_nasc;
        private $in_status; //Conta: validando no email, banida, ok
        private $st_code;
        
        function __construct() {
            //parent::__construct();
        }

        public function getSt_code() {
           return $this->st_code;
        }

        public function setSt_code($st_code) {
           $this->st_code = $st_code;
        }

        
        public function setSt_id($st_id) {
            $this->st_id = $st_id;
        }

        public function setSt_senha($st_senha) {
            $this->st_senha = $st_senha;
        }

        public function setIn_coins($in_coins) {
            $this->in_coins = $in_coins;
        }

        public function setSt_email($st_email) {
            $this->st_email = $st_email;
        }

        public function setDt_lastlogin($dt_lastlogin) {
            $this->dt_lastlogin = $dt_lastlogin;
        }

        public function setSt_nome($st_nome) {
            $this->st_nome = $st_nome;
        }

        public function setDt_nasc($dt_nasc) {
            $this->dt_nasc = $dt_nasc;
        }

        public function setIn_status($in_status) {
            $this->in_status = $in_status;
        }
        public function getSt_id() {
            return $this->st_id;
        }

        public function getSt_senha() {
            return $this->st_senha;
        }

        public function getIn_coins() {
            return $this->in_coins;
        }

        public function getSt_email() {
            return $this->st_email;
        }

        public function getDt_lastlogin() {
            return $this->dt_lastlogin;
        }

        public function getSt_nome() {
            return $this->st_nome;
        }

        public function getDt_nasc() {
            return $this->dt_nasc;
        }

        public function getIn_status() {
            return $this->in_status;
        }

                
                
        /*
        * Retorna um array contendo as informações
        * da id do player(Validate(Session))
        * @param String $st_id
        * @return Array
       */
        public function loadIdInfos($id)
        {
            $connection_string = 'DRIVER={SQL Server};SERVER=dbfutura\SQLEXPRESS;DATABASE=futuradb';

            $user = 'sa';
            $pass = '123456';
            
            $connection = odbc_connect( $connection_string, $user, $pass );
            
            $st_query = "SELECT * FROM e_members WHERE em_id='$id'";
            
            $res = odbc_exec($connection, $st_query);
            $farr = odbc_fetch_array($res);
            if(isset($farr) && !empty($farr))
            {
                $this->st_id    =       $farr["em_id"];
                $this->st_senha =       $farr["em_senha"];
                $this->in_coins =       $farr["em_coins"];
                $this->st_email =       $farr["em_email"];
                $this->st_nome  =       $farr["em_nome"];
                $this->dt_nasc  =       $farr["em_nasc"];
                $this->dt_lastlogin =   $farr["em_lastlogin"];
                $this->in_status    =   $farr["em_status"];
                $this->em_code      =   $farr["em_code"];
                //echo  $this->in_status; exit;
                return true;
            }
            else
                return false;
           /*
            try
            {
                $o_data = $this->o_db->query($st_query);
                $o_ret = $o_data->fetchObject();
                
                $this->st_id    =       $o_ret->em_id;
                $this->st_senha =       $o_ret->em_senha;
                $this->in_coins =       $o_ret->em_coins;
                $this->st_email =       $o_ret->em_email;
                $this->st_nome  =       $o_ret->em_nome;
                $this->dt_nasc  =       $o_ret->em_nasc;
                $this->dt_lastlogin =   $o_ret->em_lastlogin;
                $this->in_status    =   $o_ret->em_status;
            }
            catch (PDOException $e)
            {}*/
            //return $this;
        }
        
        /*
        * Retorna um aviso de adm
        * @return String
       */
        public function getAviso()
        {
            if (file_exists("aviso.txt"))
            {
                $file = fopen("aviso.txt", "r");
                $read = fread($file, filesize("aviso.txt"));
                fclose($file);
                $read = str_replace("\n", "<br />", $read);
                
                return $read;
            }
            else { return null;}
        }
        
        public function create()
        {
            $connection_string = 'DRIVER={SQL Server};SERVER=dbfutura\SQLEXPRESS;DATABASE=futuradb';

            $user = 'sa';
            $pass = '123456';
            
            $connection = odbc_connect( $connection_string, $user, $pass );
            
                $st_query = "INSERT INTO e_members
                                    (
                                            em_id,
                                            em_senha,
                                            em_coins,
                                            em_email,
                                            em_lastlogin,
                                            em_nome,
                                            em_nasc,
                                            em_status,
                                            em_code
                                    )
                                    VALUES
                                    (
                                            '$this->st_id',
                                            '$this->st_senha',
                                            '$this->in_coins',
                                            '$this->st_email',
                                            '$this->dt_lastlogin',
                                            '$this->st_nome',
                                            '$this->dt_nasc',
                                            '$this->in_status',
                                            '$this->st_code'
                                    );";
            
            $q = odbc_exec($connection, $st_query);
            return $q;    
        }

        public function save()
        {
            $connection_string = 'DRIVER={SQL Server};SERVER=dbfutura\SQLEXPRESS;DATABASE=futuradb';

            $user = 'sa';
            $pass = '123456';
            
            $connection = odbc_connect( $connection_string, $user, $pass );
            
            $st_query = "UPDATE e_members
                                    SET
                                            em_senha = '".$this->st_senha."',
                                            em_coins = '".$this->in_coins."',
                                            em_email = '".$this->st_email."',
                                            em_lastlogin = '".$this->dt_lastlogin."',
                                            em_nome = '".$this->st_nome."',
                                            em_nasc = '".$this->dt_nasc."',
                                            em_status =  '".$this->in_status."',
                                            em_code = '".$this->st_code."'
                                    WHERE
                                            em_id = '".$this->st_id."'";

           /* try
            {
                if($this->o_db->exec($st_query) > 0)
                    if(is_null($this->st_id))
                    {
                            return $this->o_db->lastInsertId();	
                    }
                    else
                        return $this->st_id;
            }
            catch (PDOException $e)
            {
                throw $e;
            }
            return false;*/
            
            $q = odbc_exec($connection, $st_query);
            return $q;
        }
        
        public function createAccountDB()
        {
            $connection_string = 'DRIVER={SQL Server};SERVER=dbfutura\SQLEXPRESS;DATABASE=futuradb';

            $user = 'sa';
            $pass = '123456';
            
            $connection = odbc_connect( $connection_string, $user, $pass );
            
            $datahoje = date("m/d/Y H:i:s");

            $query = "INSERT INTO [accountdb].[dbo].[".( strtoupper($this->st_id[0]) ) ."GameUser] ([userid],[Passwd],[GPCode],[RegistDay],[DisuseDay],[inuse],[Grade],[EventChk],[SelectChk],[BlockChk],[SpecialChk],[Credit],[DelChk],[Channel]) 
                values('" . $this->st_id . "','" . $this->st_senha . "','PTP-RUD001','$datahoje','12/12/2020','0','U','0','0','0','0','0','0','" . $_SERVER['REMOTE_IP'] . "')";
            $query2 = "INSERT INTO [accountdb].[dbo].[AllGameUser] ([userid],[Passwd],[GPCode],[RegistDay],[DisuseDay],[inuse],[Grade],[EventChk],[SelectChk],[BlockChk],[SpecialChk],[Credit],[DelChk],[Channel]) 
                values('" . $this->st_id . "','" . $this->st_senha . "','PTP-RUD001','$datahoje','12/12/2020','0','U','0','0','0','0','0','0','" . $_SERVER['REMOTE_IP'] . "')";
            
            $q = odbc_exec($connection, $query);
            $q2 = odbc_exec($connection, $query2);
            if($q && $q2) 
                return true;
            else
                return false;
        }
    }
?>
