<?php
    require_once 'models/SuporteModel.php';
    require_once 'models/TicketModel.php';
    
    class Suporte
    {
        public function listarAction()
        {
            $sesID = SessionHelper::selectSession("userData","em_id");
            
            $o_suporte = new SuporteModel();
            $v_suporte =  $o_suporte->_list(DataValidation::cleanString($sesID,true));
            
            $o_view = new View('views/suporte.phtml');
            $o_view->setParams($v_suporte);
            $o_view->showContents();
        }

        public function novoAction()
        {
            $o_red = new RedirectorHelper();
             if($o_red->getCurrentParam('acao') && $o_red->getCurrentParam('acao')=='novo')
             {
                 if(isset($_POST['assunto'])&&isset($_POST['texto'])&&
                         strlen($_POST['assunto'])>4 && strlen($_POST['texto'])>9 )
                 {
                    $sesID = SessionHelper::selectSession("userData","em_id");
                    
                    $o_suporte = new SuporteModel();
                    $o_suporte->setSt_assunto(DataValidation::cleanString($_POST['assunto']));
                    $o_suporte->setSt_status("2");
                    $o_suporte->setSt_conta(DataValidation::cleanString($sesID,true));
                    
                    
                    
                    $o_ticket = new TicketModel();
                    $o_ticket->setSt_autor(DataValidation::cleanString($sesID,true));
                    $o_ticket->setSt_comentario(DataValidation::cleanString($_POST['texto']));
                    
                    $o_DateTime = new DateTime('NOW');
                    $o_ticket->setDt_data($o_DateTime->format('Y-m-d H:i:s'));
                    
                    //falta a imagem
                    
                    $o_imgup = new ImgUploadHelper();
                    if(!empty($_FILES["imagem"]["name"]))
                    {
                        $o_imgup->setFile($_FILES["imagem"]);
                        $o_imgup->setPath("Painel/uploads/Suporte/");
                        $o_imgup->setFileMaxHeight(0)
                                ->setFileMaxWidth(0)
                                ->setFileMaxSize(1000000);
                        $o_imgup->setPermitedExt(array("jpg","jpeg","png","bmp"));
                        
                        // Pega extensão da imagem
			preg_match("/.(png|bmp|jpg|jpeg){1}$/i", $_FILES["imagem"]["name"], $ext);
                        
                        $img_name = md5(uniqid()) . "." . $ext[1];
                        
                        $o_ticket->setSt_img("Painel/uploads/Suporte/".$img_name);
                        
                        $o_imgup->setFileName($img_name);
                        
                        $res = $o_imgup->upload();
                        if(count($res)>0)
                        {
                            
                            $o_red->setUrlParameter("error", $res[0]);
                            $o_red->goToControllerAction("Suporte","novo");
                        }
                    }
                    
                    
                    $in_sp_id = $o_suporte->save();
                    
                    if($in_sp_id == false)
                    {
                        $o_red->setUrlParameter("error", "Falha, tente novamente.");
                        $o_red->goToControllerAction("Suporte","novo");
                    }
                    
                    $o_ticket->setIn_sp_id($in_sp_id);
                    $in_tk_id = $o_ticket->save();
                    
                    if($in_tk_id == false)
                    {
                        $o_red->setUrlParameter("error", "Falha, tente novamente.");
                        $o_red->goToControllerAction("Suporte","novo");
                    }
                        
                    $o_red->goToAction("listar");
                    
                 }else {
                    $o_red->setUrlParameter("error", "Assunto ou texto muito pequeno.");
                    $o_red->goToAction("novo");
                }
             }  
            $o_view = new View('views/novosuporte.phtml');
            $o_view->showContents();
        }
    }
?>
