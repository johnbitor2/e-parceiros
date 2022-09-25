<?php
    /*
     * Classe responsavel pela conexão com o banco de dados
     * 
     */

    abstract class PersistAbstractModel
    {
        /**
	* Variável responsável por guardar dados da conexão do banco
	* @var resource
	*/
	protected $o_db;
        
        function __construct($st_banco = null) {
            
            $st_host = 'localhost';
            if($st_banco == null)
                $st_banco = 'E-PARCEIROS';
            $st_usuario = 'root';
            $st_senha = '';
            
            $st_dsn = "mysql:host=$st_host;dbname=$st_banco";
            $this->o_db = new PDO(
                    $st_dsn, 
                    $st_usuario, 
                    $st_senha
                    );
            
            $this->o_db->setAttribute ( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
        }
    }
?>
