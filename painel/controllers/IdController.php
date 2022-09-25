<?php
    require_once 'models/IdModel.php';

    class Id
    {
        public function mostrarAction()
        {
            
            $o_id = new IdModel();
            
            $v_aviso = array();
            $v_aviso["aviso"] = $o_id->getAviso();
            
             $o_view = new View('views/principal.phtml');
             //$o_view->setParams(SessionHelper::selectSession("userData"));
             $o_view->setParams($v_aviso);
             $o_view->showContents();
        }
    }
?>
