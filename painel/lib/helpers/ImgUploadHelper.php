<?php
    class ImgUploadHelper
    {
        protected $path = 'ClanContent/';
        protected $file;
        protected $filename;
        protected $fileTmpName;
        protected $fileType;
        protected $fileSize;

        protected $fileMaxSize = 4608;
        protected $permitedExt = 'bmp';
        protected $fileMaxHeight = 32;
        protected $fileMaxWidth = 32;
        


        public function setPath($path)
        {
            $this->path = $path;
        }
        
        public function setFile($file)
        {
            $this->file = $file;
            $this->setFileName();
            $this->setFileTmpName();
            $this->setFileType();
            $this->setFileSize();
        }

        protected function setFileTmpName() {
            $this->fileTmpName = $this->file['tmp_name'];
        }
        
        protected function setFileType() {
            $this->fileType = $this->file['type'];
        }
        
        protected function setFileSize() {
            $this->fileSize = $this->file['size'];
        }
        
        public function setFileName($name = null)
        {
            if($name == null)
                $this->filename = $this->file['name'];
            else
                $this->filename = $name;
        }
    
        public function setFileMaxSize($fileMaxSize) {
            $this->fileMaxSize = $fileMaxSize;
            return $this;
        }

        public function setPermitedExt(array $permitedExt) {
            $exts = implode("|", $permitedExt);
            $this->permitedExt = $exts;
        }
        
        /**
        * Define a altura máxima da imagem a ser upada
	* em pixels, se valor 0 a imagem não tem limite
	* de altura.
	* @param integer $fileMaxHeight
	* @return ImgUploadHelper
        */
        public function setFileMaxHeight($fileMaxHeight) {
            $this->fileMaxHeight = $fileMaxHeight;
            return $this;
        }

        /**
        * Define a largura máxima da imagem a ser upada
	* em pixels, se valor 0 a imagem não tem limite
	* de altura.
	* @param integer $fileMaxHeight
	* @return ImgUploadHelper
        */
        public function setFileMaxWidth($fileMaxWidth) {
            $this->fileMaxWidth = $fileMaxWidth;
            return $this;
        }

                
        public function upload()
        {
            $error = array();
            
            if(!preg_match("~^image\/(".$this->permitedExt.")$~i", $this->fileType))
                 $error[] = "O arquivo não é uma imagem válida.";
            
            $dimensoes = getimagesize($this->fileTmpName);   
            
            if($this->fileMaxHeight !=0 && $this->fileMaxWidth !=0)
            {
                if($dimensoes[0] > $this->fileMaxWidth)  
                    $error[] = "A largura da imagem não deve ultrapassar ".$this->fileMaxWidth." pixels.";   
                if($dimensoes[1] > $this->fileMaxHeight) 
                    $error[] = "Altura da imagem não deve ultrapassar ".$this->fileMaxHeight." pixels.";    
            }
            if($this->fileSize > $this->fileMaxSize) 
                $error[] = "A imagem deve ter no máximo ".$this->fileMaxSize." bytes."; 
     
            if(count($error) == 0)
            {
                if(move_uploaded_file($this->fileTmpName, $_SERVER["DOCUMENT_ROOT"] . $this->path . $this->filename))
                     return array();
                else
                    return array(0=>"Falha ao realizar o upload.");
            }
            else
                return $error;
        }
    }

?>
