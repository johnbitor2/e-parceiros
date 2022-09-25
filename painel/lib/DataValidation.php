<?php
    /**
     * Classe designada a validacao de formato de dados
    * @version 1.0.0
     */
    class DataValidation
    {
        /**
	* Verifica se o dado passado esta vazio
	* @param mixed $mx_value
	* @return boolean
	*/
        static function isEmpty( $mx_value )
	{
		if(!(strlen($mx_value) > 0))
			return true;	
		return false;
	}
        /**
	* Verifica se o dado passado e um numero
	* @param mixed $mx_value;
	* @return boolean
	*/
        static function isNumeric( $mx_value )
	{
		$mx_value = str_replace(',', '.', $mx_value);
		if(!(is_numeric($mx_value)))
			return false;
		return true;
	}
        /**
	* Verifica se o dado passado e um numero inteiro
	* @param mixed $mx_value;
	* @return boolean
	*/
        static function isInteger( $mx_value )
	{
		if(!DataValidator::isNumeric($mx_value))
			return false;
		
		if(preg_match('/[[:punct:]&^-]/', $mx_value) > 0)
			return false;
		return true;
	}
        /**
	* Retira caracteres nao numericos da string
        * Retorna um decimal formado pela junção dos números na string 
	* @param string $st_data
	* @return string
	*/
	static function numeric( $st_data )
	{
		$st_data = preg_replace("([[:punct:]]|[[:alpha:]]| )",'',$st_data);
		return $st_data;	
	}
        /**
	 * 
	 * Retira tags HTML / XML e adiciona "\" antes
	 * de aspas simples e aspas duplas e se necessário
         * retira espaços em brancos
	 * @param unknown_type $st_string
         * @param boolean $trim
	 */
	static function cleanString( $st_string, $trim = false )
	{
            if(!$trim)
		return addslashes(strip_tags($st_string));
            else
                return trim(addslashes(strip_tags($st_string)));
	}
        
        
        static function addslashes_mssql($str){
            if (is_array($str)) 
            {
                foreach($str AS $id => $value) {
                    $str[$id] = addslashes_mssql($value);
                }
            } 
            else 
            {
                $str = str_replace("'", "''", $str);    
            }

            return $str;
        }
        
        static function stripslashes_mssql($str){
            if (is_array($str)) {
                foreach($str AS $id => $value) {
                    $str[$id] = stripslashes_mssql($value);
                }
            } else {
                $str = str_replace("''", "'", $str);    
            }

            return $str;
        }
        
        /**
	 * 
	 * Retira tags HTML / XML e adiciona "'" antes
	 * de aspas simples e aspas duplas e se necessário
         * retira espaços em brancos
	 * @param unknown_type $st_string
         * @param boolean $trim
	 */
	static function mssql_cleanString( $st_string, $trim = false )
	{
            if(!$trim)
		return DataValidation::addslashes_mssql(strip_tags($st_string));
            else
                return trim(DataValidation::addslashes_mssql(strip_tags($st_string)));
	}
        
        /**
	 * verifica se o valor informado é uma data.
         * Formato: DD/MM/YYYY
	 * @param string $dt_date
	 * @return boolean
	 */
        static function isDate($dt_date)
        {
            if(strlen($dt_date) !=10)
                return false;
            
             $expnasc = explode("/", $dt_date);
             if(count($expnasc) != 3)
                 return false;
             
             if (!checkdate((int)$expnasc[1], (int)$expnasc[0], (int)$expnasc[2]))
                     return false;
             
             return true;
             
        }
        
        /**
	 * verifica se o valor informado é um email válido.
	 * @param string $st_email
	 * @return boolean
	 */
        static function isEmail($st_email)
        {
            $conta = "^[a-zA-Z0-9\._-]+@";

            $domino = "[a-zA-Z0-9\._-]+.";

            $extensao = "([a-zA-Z]{2,4})$";

            $pattern = "~".$conta.$domino.$extensao."~";

            if (preg_match($pattern, $st_email))
                    return true;
            else
                    return false;

             
        }
    }

?>
