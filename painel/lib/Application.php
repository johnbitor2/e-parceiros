<?php
    function __autoload($st_class)
    {
            if(file_exists('lib/'.$st_class.'.php'))
                    require_once 'lib/'.$st_class.'.php';
            else if(file_exists('lib/helpers/'.$st_class.'.php'))
                require_once 'lib/helpers/'.$st_class.'.php';
            else if(file_exists('lib/helpers/email/class.'.$st_class.'.php'))
                require_once 'lib/helpers/email/class.'.$st_class.'.php';
    }
    
    /*
    */
    class Application
    {
        /**
	* Usada pra guardar o nome da classe
	* de controle (Controller) a ser executada
	* @var string
	*/
        public $st_controller;
	
	/**
	* Usada para guardar o nome do metodo da
	* classe de controle (Controller) que deverá ser executado
	* @var string
	*/
        public $st_action;
        
        /**
	* Usada para guardar os parametros que auxiliam a
	* classe de controle (Controller) que deverá ser executado
	* @var array
	*/
        public $v_param;

        /*
         * Objeto que armazena os dados de autenticação
         * da sessão atual
         * @var object
         */
        private $o_auth;


        /**
	* Verifica se os parametros de controlador (Controller) e acao (Action) foram
	* passados via parametros "Post" ou "Get" e os carrega tais dados
	* nos respectivos atributos da classe
	*/
	private function loadRoute()
	{
           $key = isset($_GET['key']) ? $_GET['key'] : 'index/index';
           $separador = explode('/', $key);

           $this->st_controller = $separador[0];
           
           $this->st_action = $separador[1];
           
           unset($separador[0],$separador[1]);
           
           if(end($separador) == null)
               array_pop ($separador);
           
           if(!empty($separador))
           {
               $i=0;
               foreach ($separador as $val)
               {
                   if(($i%2) == 0){
                       $ind[] = $val;
                   }else{
                       $value[] = $val;
                   }
                   $i++;
               }
           }else{
               $ind = array();
               $value = array();
           }
           
           if(count($ind) == count($value) && !empty($ind) && !empty($value))
               $this->v_param = array_combine($ind, $value);
           else
               $this->v_param = array();
          
	}
        
        /*
         * Pega o valor do parâmetro passado pela url.
         * Chamo a função pelo global $o_Application
         * @param string
         * @return string
         */
       /* public function getParam($name)
        {
            return $this->v_param[$name];
        }*/
        
        private function session()
        {
            if(ucfirst($this->st_controller) != "Index" || ($this->st_action != "cadastra" && $this->st_action != "valida"))
            {
                $this->o_auth = new AuthHelper();
                $this->o_auth->setLoginControllerAction('index', 'login')
                             ->checkLogin('redirect');
            }
        }

        /**
	* Instancia classe referente ao Controlador (Controller) e executa
	* método referente e  acao (Action)
	* @throws Exception
	*/
	public function dispatch()
	{
		$this->loadRoute();
                $this->session();
               
                
                //verificando se o arquivo de controle existe
		$st_controller_file = 'controllers/'.ucfirst($this->st_controller).'Controller.php';
		if(file_exists($st_controller_file))
			require_once $st_controller_file;
		else
			throw new Exception('Arquivo '.$st_controller_file.' nao encontrado');
			
		//verificando se a classe existe
		if(class_exists($this->st_controller))
			$o_class = new $this->st_controller;
		else
			throw new Exception("Classe '$this->st_controller' nao existe no arquivo '$st_controller_file'");

		//verificando se o metodo existe
		$st_method = $this->st_action.'Action';
		if(method_exists($o_class,$st_method))
			$o_class->$st_method();
		else
			throw new Exception("Metodo '$st_method' nao existe na classe '$this->st_controller'");	
                 
                 
        }
    }
?>
