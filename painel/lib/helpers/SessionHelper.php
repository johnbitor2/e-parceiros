<?php
    class SessionHelper
    {
        public function createSession($name, $value)
        {
            $_SESSION[$name] = $value;
            return $this;
        }
        
        public function deleteSession($name)
        {
            unset($_SESSION[$name]);
            return $this;
        }
        
        static function destroySession()
        {
            return session_destroy();
        }
        
        static function selectSession($name, $index = null)
        {
            if($index == null)
                return $_SESSION[$name];
            else
                return $_SESSION[$name][$index];
        }
        
        static function checkSession($name)
        {
            return isset($_SESSION[$name]);
        }
        
        static function updateSession($value, $name, $index = null)
        {
            if($index == null)
                $_SESSION[$name] = $value;
            else
                $_SESSION[$name][$index] = $value;
        }
    }
?>
