<?php
require_once 'models/ExtratoModel.php';
require_once 'models/LojaModel.php';
require_once 'models/IdModel.php';
require_once 'models/CharModel.php';

    class Loja
    {
        
        public function premiunsAction()
        {
            $o_loja = new LojaModel();
            $v_itens = $o_loja->_listByType("1");
            
            $o_view = new View("views/loja.phtml");
            $o_view->setParams($v_itens);
            $o_view->showContents();
        }
        
        public function diversosAction()
        {
            $o_loja = new LojaModel();
            $v_itens = $o_loja->_listByType("2");
            
            $o_view = new View("views/loja.phtml");
            $o_view->setParams($v_itens);
            $o_view->showContents();
        }
        
        public function cristaisAction()
        {
            $o_loja = new LojaModel();
            $v_itens = $o_loja->_listByType("3");
            
            $o_view = new View("views/loja.phtml");
            $o_view->setParams($v_itens);
            $o_view->showContents();
        }
        
        public function agingAction()
        {
            $o_loja = new LojaModel();
            $v_itens = $o_loja->_listByType("4");
            
            $o_view = new View("views/loja.phtml");
            $o_view->setParams($v_itens);
            $o_view->showContents();
        }
        
        public function promocionaisAction()
        {
            $o_loja = new LojaModel();
            $v_itens = $o_loja->_listPromo();
            
            $o_view = new View("views/loja.phtml");
            $o_view->setParams($v_itens);
            $o_view->showContents();
        }

        public function extratoAction()
        {
            $sesID = SessionHelper::selectSession("userData","em_id");
            $o_extrato = new ExtratoModel();
            $v_extratos = $o_extrato->_list(DataValidation::mssql_cleanString($sesID,true));
            
            $msg = null;
            $o_red = new RedirectorHelper();
            if($o_red->getCurrentParam("msg"))
                if($o_red->getCurrentParam("msg")==1)
                    $msg = array("sucesso","Item adquirido com sucesso!");
                if($o_red->getCurrentParam("msg")==2)
                    $msg = array("erro","Você não possui ECoins suficientes para compra deste item!");
                
            $v_param = array("extrato"=>$v_extratos, "msg"=>$msg);
            
            $o_view = new View("views/extrato.phtml");
            $o_view->setParams($v_param);
            $o_view->showContents();
        }
        
        public function compraAction()
        {
            $o_red = new RedirectorHelper();
            if($o_red->getCurrentParam("item"))
            {
                $o_item = new LojaModel();
                $st_item = base64_decode($o_red->getCurrentParam("item"));
                
                $o_item->loadItem(DataValidation::mssql_cleanString($st_item));
                
                if(strlen($o_item->getSt_nome())>0)
                {
                
                    $sesID = SessionHelper::selectSession("userData","em_id");

                    $o_id = new IdModel();
                    $o_id->loadIdInfos(DataValidation::mssql_cleanString($sesID,true));

                    
                    if(($o_item->getIn_promocao() == 1) || 
                            (strtotime($o_item->getDt_promo_data()) >= strtotime(date("Y-m-d"))))
                    {
                        $itemValue = $o_item->getIn_preco_promo();
                        $newValue = $o_id->getIn_coins()-$itemValue;
                    }
                    else  
                    {
                        $itemValue = $o_item->getIn_preco();
                        $newValue = $o_id->getIn_coins()-$itemValue;
                    }
                    

                    if($newValue >= 0)
                    {
                        $o_id->setIn_coins($newValue);
                        $o_id->save();

                        SessionHelper::updateSession($o_id->getIn_coins(), "userData", "em_coins");

                        $o_char = new CharModel();
                        
                        $expcod = explode("-", $o_item->getSt_coditem());
                        
                        foreach ($expcod as $value)
                        {    
                            if($value == "gg101")
                                    $o_char->putOnPostBox($sesID, "gg101", 200000);
                            elseif($value == "gg102")
                                    $o_char->putOnPostBox($sesID, "gg101", 500000);
                            elseif($value == "gg103")
                                    $o_char->putOnPostBox($sesID, "gg101", 1000000);
                            else
                                $o_char->putOnPostBox($sesID, $value);
                        }
                        
                        //falta tratar o gold e a mana(quantidade no lugar do spec)
                        
                        $o_extrato = new ExtratoModel();
                        $o_extrato->setIn_id(DataValidation::mssql_cleanString($sesID,true));
                        $o_extrato->setDt_data(date("d/m/Y"));
                        $o_extrato->setIn_valor($itemValue);
                        $o_extrato->setSt_nome($o_item->getSt_nome());
                        $o_extrato->save();
                        
                        $o_red->setUrlParameter("msg", "1");
                        $o_red->goToControllerAction("Loja", "extrato");
                    }
                    else
                    {
                        $o_red->setUrlParameter("msg", "2");
                        $o_red->goToControllerAction("Loja", "extrato");
                    }
                }
                else 
                {
                    $o_red->goToControllerAction("Loja", "extrato");
                }
                
            }
            else
            {
                $o_red->goToControllerAction("Loja", "extrato");
            }
        }
    }
?>
