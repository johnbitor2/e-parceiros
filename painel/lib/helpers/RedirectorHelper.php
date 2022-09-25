<?php
    /*

     */
    class RedirectorHelper
    {
        protected $parameters = array();
        
        public function getCurrentController()
        {
            global $o_Application;
            return $o_Application->st_controller;
        } 
        
        public function getCurrentAction()
        {
            global $o_Application;
            return $o_Application->st_action;
        }
        
        public function getCurrentParam($name = null)
        {
            global $o_Application;
            if ($name == null)
                return $o_Application->v_param;
            else
            {
                if(array_key_exists($name, $o_Application->v_param))
                    return $o_Application->v_param[$name];
                else
                    return false;
            }
        }

        private function go($data)
        {
            header("Location: /Painel/".$data);
        }
        
        public function setUrlParameter($name, $value)
        {
            $this->parameters[$name] = $value;
        }
        
        private function getUrlParameters()
        {
            $parms = "";
            foreach($this->parameters as $name => $value)
                $parms .= $name.'/'.$value.'/';
            return $parms;
        }

        public function goToAction($action)
        {
            $this->go($this->getCurrentController() .'/'.$action .'/'.$this->getUrlParameters());
        }
        
        public function goToControllerAction($controller, $action)
        {
            $this->go($controller.'/'.$action.'/'.$this->getUrlParameters());
        }
        public function goToIndex()
        {
            $this->goToControllerAction('index', 'index');
        }
        public function goToUrl($url)
        {
            header("Location: ".$url);
        }
    }
?>
