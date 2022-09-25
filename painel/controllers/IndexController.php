<?php
require_once 'models/IdModel.php';
    /*
     * @package Painel E-PARCEIROS
     * @version 1.0.0
     */
     class Index
     {
         public function indexAction()
         {
             $red = new RedirectorHelper();
             $red->goToControllerAction('Id', 'mostrar');
         }
         
         public function loginAction()
         {
             $o_red = new RedirectorHelper();
             if($o_red->getCurrentParam('acao') && $o_red->getCurrentParam('acao')=='logar')
             {
                 if(isset($_POST['login'])&&isset($_POST['senha']))
                 {
                     //verificação se falta validar ou banido
                    $o_auth = new AuthHelper();
                    $o_auth->setUser(DataValidation::mssql_cleanString($_POST['login'],true))
                           ->setPass(DataValidation::mssql_cleanString($_POST['senha'],true))
                           ->login();
                    //lastlogin
                 }
             }
             if($o_red->getCurrentParam('cadastro') && $o_red->getCurrentParam('cadastro')=='validado')
             {
                $o_view = new View('views/login.phtml');
                $o_view->setParams(array("cadastro"=>"sucesso"));
                $o_view->showContents();
             }
             $o_view = new View('views/login.phtml');
             $o_view->showContents();
         }
         
         public function logoutAction()
         {
             $o_auth = new AuthHelper();
             $o_auth->setLogoutControllerAction('index', 'login')
                    ->logout();
         }
         
         public function cadastraAction()
         {
             $o_red = new RedirectorHelper();
             if($o_red->getCurrentParam('acao') && $o_red->getCurrentParam('acao')=='cadastrar')
             {
                 if(isset($_POST['nome'])&&isset($_POST['login'])&&isset($_POST['senha'])
                         &&isset($_POST['senha2'])&&isset($_POST['nasc'])
                         &&isset($_POST['email'])/*&&isset($_POST['pais'])*/)
                 {
                     $msg = array();
                     if(strlen($_POST['nome'])<3)
                         $msg[] = "O nome deve conter pelo menos 3 caracteres.";
                     if(strlen($_POST['login'])<3 || strlen($_POST['login'])>15)
                         $msg[] = "O login deve conter entre 3 e 15 caracteres.";
                     if(strlen($_POST['senha'])<5 || strlen($_POST['senha'])>15)
                         $msg[] = "A senha deve conter entre 5 e 15 caracteres.";
                     if($_POST['senha']!=$_POST['senha2'])
                         $msg[] = "Os campos senhas estão diferentes, digite novamente.";
                    
                     if(!DataValidation::isDate($_POST['nasc']))
                         $msg[] = "Data de nascimento inválida.";
                     
                     if(!DataValidation::isEmail($_POST['email']))
                         $msg[] = "Email informado é inválido.";
                     
                     //verificar se email ou id ja existem(melhor por ajax)
                     $connection_string = 'DRIVER={SQL Server};SERVER=dbfutura\SQLEXPRESS;DATABASE=futuradb';
                     $user = 'sa';
                     $pass = '123456';
                     $connection = odbc_connect( $connection_string, $user, $pass );
                     $idTEMP = DataValidation::mssql_cleanString($_POST['login'], true);
                     $st_qu3ry = "SELECT * FROM e_members WHERE em_id='$idTEMP'";
                     $qt_email = odbc_do($connection, $st_qu3ry);
                     $i_email = 0;
                     while(odbc_fetch_row($qt_email)) $i_email++;
                     if($i_email > 0)
                         $msg[] = "ID informada já existe, tente outra ID.";
                     
                     
                     if(count($msg)>0)
                     {
                        $o_view = new View('views/cadastro.phtml');
                        $o_view->setParams($msg);
                        $o_view->showContents();
                     }
                     
                     
                     $code = md5($_POST['email'].uniqid());
                     $link = "http://FUTUROSITE/cadastro.php?l=".base64_encode($_POST['login']).
                             "&c=".$code;
                     $v_link = array('link' => $link);
                     $o_view_email = new View('views/cadastroemail.phtml');
                     $o_view_email->setParams($v_link);
                     $mail = new PHPMailer();
                     $mail->isSMTP();
                     $mail->SMTPDebug = 0;
                     $mail->Host = "futuroemail";
                     $mail->Port = 25;
                     $mail->SMTPAuth = true;
                     $mail->Username = "";
                     $mail->Password = "";
                     $mail->setFrom("", '');
                     $mail->addAddress($_POST['email']);
                     $mail->Subject = 'Account Validation ';
                     $mail->msgHTML($o_view_email->getContents());
                     //send the message, check for errors
                          if (!$mail->send()) {
                            echo "Mailer Error: " . $mail->ErrorInfo . "<br />Sorry about that x.x'";
                            echo '<meta http-equiv="refresh" content="3;URL=/Painel/index/cadastra">';
                        } 
                     
                    
                     $o_id = new IdModel();
                     $o_id->setSt_id(DataValidation::mssql_cleanString($_POST['login'], true));
                     $o_id->setIn_coins(0);
                     $o_id->setSt_nome(DataValidation::mssql_cleanString($_POST['nome']));
                     $o_id->setSt_senha(DataValidation::mssql_cleanString($_POST['senha']));
                     $o_id->setSt_email(DataValidation::mssql_cleanString($_POST['email']));
                     $o_id->setDt_nasc(DataValidation::mssql_cleanString($_POST['nasc']));
                        //$o_id->setDt_lastlogin(data)
                     $o_id->setSt_code($code);
                     //$o_id->setIn_status(0);
                     $o_id->setIn_status(1);
                     $o_id->create();
                      
                     $o_id->createAccountDB();
                         
                     $info = array();
                     $info["id"] = $_POST['login'];
                     $info["nome"] = $_POST['nome'];
                     $info["senha"] = $_POST['senha'];
                     $info["email"] = $_POST['email'];
                     $info["nasc"] = $_POST['nasc'];
                   
                     $o_view = new View('views/cadastrosucesso.phtml');
                     $o_view->setParams($info);
                     $o_view->showContents();
                 }
             }
             $o_view = new View('views/cadastro.phtml');
             $o_view->showContents();
         }
         
         public function validaAction()
         {
             $o_red = new RedirectorHelper();
             if($o_red->getCurrentParam('id') && $o_red->getCurrentParam('code'))
             {
                 $o_id = new IdModel();

                 if ($o_id->loadIdInfos(DataValidation::mssql_cleanString(base64_decode($o_red->getCurrentParam('id')), true)) == false)
                 {
                     $o_red->goToControllerAction("index", "cadastra");
                 }else{
                     //echo "lol"; exit;
                     //if($o_red->getCurrentParam('code') == $o_id->getSt_code())
                     //{
                        //echo $o_id->getSt_code(); exit;
                         $o_id->setIn_status(1);
                         //$o_id->save();
                         //$o_id->createAccountDB();
                        //
                        
                         $o_red->setUrlParameter("cadastro", "validado");
                         $o_red->goToControllerAction("index", "login");
                     //}else{
                     //    $o_red->goToControllerAction("index", "cadastra");
                     //}
                 }
             }
         }
     }
?>
