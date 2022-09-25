<?php
class AuthHelper extends PersistAbstractModel
{
    protected $sessionHelper;
    protected $redirectorHelper;
    protected $loginController = 'index', $loginAction = 'index';
    protected $logoutController = 'index', $logoutction = 'index';
    protected $user,$pass,$userColum = 'em_id',$passColum = 'em_senha',$tableName = 'e_members';
    
    function __construct()
    {
        $this->redirectorHelper = new RedirectorHelper();
        $this->sessionHelper = new SessionHelper();
        //parent::__construct();
        return $this;
    }
    
    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function setPass($pass) {
        $this->pass = $pass;
        return $this;
    }

    public function setUserColum($userColum) {
        $this->userColum = $userColum;
        return $this;
    }

    public function setPassColum($passColum) {
        $this->passColum = $passColum;
        return $this;
    }

    public function setTableName($tableName) {
        $this->tableName = $tableName;
        return $this;
    }

    
    public function setLoginControllerAction($controller, $action)
    {
        $this->loginController = $controller;
        $this->loginAction = $action;
        return $this;
    }
        
    public function setLogoutControllerAction($controller, $action)
    {
        $this->logoutController = $controller;
        $this->logoutAction = $action;
        return $this;
    }
    
    public function login()
    {
            $connection_string = 'DRIVER={SQL Server};SERVER=dbfutura\SQLEXPRESS;DATABASE=futuradb';

            $user = 'sa';
            $pass = '123456';
            
            $connection = odbc_connect( $connection_string, $user, $pass );
            
         $st_query = "SELECT count(*) as num FROM ".$this->tableName." WHERE ".
                $this->userColum."='".$this->user."' AND ".$this->passColum."='".$this->pass."'";
   
         $res = odbc_exec($connection, $st_query);
         $lol = odbc_fetch_array($res);
         
         if($lol["num"] > 0)
         {
             $st_query2 = "SELECT * FROM ".$this->tableName." WHERE ".
                $this->userColum."='".$this->user."' AND ".$this->passColum."='".$this->pass."'";
            $res2 = odbc_exec($connection, $st_query2);
            $v_ret = odbc_fetch_array($res2);
            $this->sessionHelper->createSession("userAuth",true)
                                ->createSession("userData",$v_ret);
         }
         
       /* $st_query = "SELECT count(*) FROM `".$this->tableName."` WHERE ".
                $this->userColum."='".$this->user."' AND ".$this->passColum."='".$this->pass."' LIMIT 1";
        $o_rdata = $this->o_db->query($st_query);
        if($o_rdata->fetchColumn() > 0) //numrow
        {
            $st_query = "SELECT * FROM `".$this->tableName."` WHERE ".
                $this->userColum."='".$this->user."' AND ".$this->passColum."='".$this->pass."' LIMIT 1";
            $o_data = $this->o_db->query($st_query);
            $v_ret = $o_data->fetch(PDO::FETCH_ASSOC);
            $this->sessionHelper->createSession("userAuth",true)
                                ->createSession("userData",$v_ret);
        }
        else
        {
            die("Informacoes de usuario invalidas.");
        }*/
        $this->redirectorHelper->goToControllerAction($this->loginController, $this->loginAction);
        return $this;
    }
    
    public function logout()
    {
        SessionHelper::destroySession();
        $this->redirectorHelper->goToControllerAction($this->logoutController, $this->logoutAction);
        return $this;
    }
    
    public function checkLogin($action)
    {
        switch ($action)
        {
            case "boolean":
                return $this->sessionHelper->checkSession("userAuth");
                break;
            case "redirect":
                if(!$this->sessionHelper->checkSession("userAuth"))
                    if($this->redirectorHelper->getCurrentController() != $this->loginController || $this->redirectorHelper->getCurrentAction() != $this->loginAction)
                        $this->redirectorHelper->goToControllerAction($this->loginController,  $this->loginAction);
                break;
            case "stop":
                if(!$this->sessionHelper->checkSession("userAuth"))
                    exit;
                break;
        }
    }
    public function userData($colum)
    {
        $ses = $this->sessionHelper->selectSession("userData");
        return $ses[$colum];
    }
}
?>
