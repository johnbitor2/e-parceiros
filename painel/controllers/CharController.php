<?php
    require_once 'models/CharModel.php';
    require_once 'models/ClanModel.php';
    //require_once 'models/MedalhaModel.php';

    class Char
    {
        public function listarAction()
        {
            
            $sesID = SessionHelper::selectSession("userData","em_id");
            
            $o_char = new CharModel();
            
            $v_char = $o_char->_list($sesID);
            
            $v_clan = array();
            
            foreach ($v_char as $value) {
                $value->updateCharInfo();
                //echo $value->getSt_charName() . " - " . $value->getIn_lvl() . " - " . $value->getSt_classe() . "<br>";
                $o_clan = new ClanModel();
                $v_clan[] = $o_clan->updateClanInfo(DataValidation::mssql_cleanString($value->getSt_charName(), true));
                //$v_clan[] = $o_clan->updateClan(DataValidation::mssql_cleanString($sesID, true));
            }
            
            $v_infos = array(
                "char" => $v_char,
                "clan" => $v_clan
                    );
            
            $o_view = new View("views/personagens.phtml");
            $o_view->setParams($v_infos);
            $o_view->showContents();
           
        }
        
        public function infoAction()
        {
             $o_red = new RedirectorHelper();
             if($o_red->getCurrentParam('name'))
             {
                 $charName = $o_red->getCurrentParam('name');
                 //$sesID = SessionHelper::selectSession("userData","em_id");
                 
                 $o_char = new CharModel();
                 $o_char->updateCharInfo($charName);
                 $o_char->getHead($charName);
                 
                 $o_clan = new ClanModel();
                 $o_clan->updateClanInfo(DataValidation::mssql_cleanString($charName, true));
                 
                 //$o_medalha = new MedalhaModel();
                 //$v_medalhas = $o_medalha->_list(DataValidation::cleanString($charName, true));
                 
                 $v_medalhas = array();
                 
                 $v_infos = array(
                     "char" => $o_char,
                     "clan" => $o_clan,
                     "medalhas" => $v_medalhas
                    );
                 
                 $o_view = new View('views/infosChar.phtml');
                 $o_view->setParams($v_infos);
                 $o_view->showContents();
             }
        }
    }
?>
