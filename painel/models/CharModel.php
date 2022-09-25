<?php
/*

*/
class CharModel
{
    private $st_charName;
    
    private $in_lvl;
    
    private $st_classe;
    
    private $st_head;
    
    // pastas
    private $rootDir = "C:/slasla/";
    private $dir_userData = "DataServer/userdata/";
    private $dir_userInfo = "DataServer/userinfo/";
    private $dir_userDelete = "DataServer/deleted/";
    private $dir_postbox    = "PostBox/";
    
    //Getters e Setters
    public function getSt_charName() {
        return $this->st_charName;
    }

    public function setSt_charName($st_charName) {
        $this->st_charName = $st_charName;
    }

    public function getIn_lvl() {
        return $this->in_lvl;
    }

    public function setIn_lvl($in_lvl) {
        $this->in_lvl = $in_lvl;
    }

    public function getSt_classe() {
        return $this->st_classe;
    }

    public function setSt_classe($st_classe) {
        $this->st_classe = $st_classe;
    }
    
    public function getSt_head() {
        return $this->st_head;
    }

        
    /*
     * Retorna a pasta(numero) em que o Dat  se encontra.
     * @param String $name
     * @return int
     */
    private function lpNumDir($name)
    {
	$name = strtoupper($name);
		
	$total = 0;
        for($i=0;$i<strlen($name);$i++)
        {
            $total+= ord($name[$i]);
            if($total>=256)
                $total -= 256;
        }
		
        return $total;
		
    }
    
    /*
     * Retorna um array contendo o objeto de
     * cada Char da ID
     * @param String $st_id
     * @return Array
    */
    public function _list($st_id)
    {
        $charInfo = $this->rootDir . $this->dir_userInfo . 
                $this->lpNumDir($st_id) . "/" . $st_id . ".dat";
        
        if(file_exists($charInfo) && ( filesize($charInfo)==240) )
        {
            $v_chars = array();
            
            $fOpen = fopen($charInfo, "r");
            $fRead =fread($fOpen,filesize($charInfo));
            @fclose($fOpen);
            
            $charNameArr=array( 
                    trim(substr($fRead,0x30,15),"\x00"),
                    trim(substr($fRead,0x50,15),"\x00"),
                    trim(substr($fRead,0x70,15),"\x00"),
                    trim(substr($fRead,0x90,15),"\x00"),
                    trim(substr($fRead,0xb0,15),"\x00")
            );
            foreach($charNameArr as $value)
            {
                if(strlen($value)>0)
                {
                    //Gambiarra para pegar a string até o primeiro byte 0
                    $expname = explode("\x00",$value);
                    //Fim da gambiarra
                    $o_char = new CharModel();
                    $o_char->setSt_charName($expname[0]);
                    array_push($v_chars, $o_char);
                }
            }
            if(count($v_chars) > 0)
                return $v_chars;
            else
                return array();
        }
        else{ return array(); }
    }
    
    
    public function updateCharInfo($charN = null)
    {
        if($charN != null)
            $this->st_charName = $charN;
        
        $charDat = $this->rootDir . $this->dir_userData . 
                $this->lpNumDir($this->st_charName) . "/" . $this->st_charName . ".dat";
        
	if(file_exists($charDat) && 
                ( (filesize($charDat)==16384) || (filesize($charDat)==111376) || (filesize($charDat)==220976) ) )
        {
            $fOpen = fopen($charDat, "r");
            $fRead =fread($fOpen,filesize($charDat));
            @fclose($fOpen);

            $this->in_lvl = Ord(substr($fRead,0xc8,1));
            $classNumber= substr($fRead,0xc4,1);
            //$charName = trim(substr($fRead,0x10,15),"\x00");
            //$charID = trim(substr($fRead,0x2c0,10),"\x00");
            
            switch (ord($classNumber))
            {
                case 1: $this->st_classe = 'Fighter'; break;
                case 2: $this->st_classe = 'Mechanician'; break;
                case 3: $this->st_classe = 'Archer'; break;
                case 4: $this->st_classe = 'Pikeman'; break;
                case 5: $this->st_classe = 'Atalanta'; break;
                case 6: $this->st_classe = 'Knight'; break;
                case 7: $this->st_classe = 'Magician'; break;
                case 8: $this->st_classe = 'Priestess'; break;
            }
            
        }
    }
    
    public function getHead($charN = null)
    {
        if($charN != null)
            $this->st_charName = $charN;
        
        $charDat = $this->rootDir . $this->dir_userData . 
                $this->lpNumDir($this->st_charName) . "/" . $this->st_charName . ".dat";
        
        if(file_exists($charDat) && 
                ( (filesize($charDat)==16384) || (filesize($charDat)==111376) || (filesize($charDat)==220976) ) )
        {
            $fOpen = fopen($charDat, "r");
            $fRead =fread($fOpen,filesize($charDat));
            @fclose($fOpen);
            
            $charHead = substr($fRead,0x70,28);
            $exphead = explode("\x00",$charHead);
            
            $vish = str_replace("\x63\x68\x61\x72\x5C\x74\x6D\x41\x42\x43\x44\x5C", "", $exphead[0]);
            $head = str_replace(".inf", ".png", $vish);
            $this->st_head = "/Painel/template/imgsHAIR/".$head;
            
            //$this->st_head = !file_exists($this->st_head) ?
            //    $this->st_head : "/Painel/template/imgsHAIR/noimage.png";
            //if(!file_exists($this->st_head))
		//$this->st_head = "/Painel/template/imgsHAIR/noimage.png";
            
            
        }
    }
    
    public function putOnPostBox($st_id, $coditem, $spec = 0)
    {
       $dados_item = "* ".$coditem." ".$spec." \"\"". "\r\n";
        
        $idDist = $this->rootDir.$this->dir_postbox.$this->lpNumDir($st_id)."/".$st_id.".dat";
        
        //if(file_exists($idDist) && filesize($idDist) < 2)
            //unlink($idDist);
        
            $fp = fopen($idDist, "a+");
            fwrite($fp, $dados_item);
            fclose($fp);
    }

}
?>
