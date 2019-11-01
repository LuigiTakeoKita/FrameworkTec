<?php
    class Folders 
    {
        private $folders;
        public function __construct ($folders=array())
        {
            $this->folders=$folders;
        }
        /**
         * Get the value of folders
         */ 
        public function getFolders()
        {
                return $this->folders;
        }

        /**
         * Set the value of folders
         *
         * @return  self
         */ 
        public function setFolders($folders)
        {
                $this->folders = $folders;

                return $this;
         }
        public function addFolder($folder)
        {
            array_push($this->folders, $folder);
         }
        public function createFolders($dir)
        {
            foreach ($this->getFolders() as $key => $value) {
                if (!file_exists($value)) {
                    mkdir($dir.$value, 0700);
                 }
             }
         }
        public function createAutoload($dir)
        {
            $autoload = 
            "<?php\n".
            "   spl_autoload_register(function (\$nomeClasse) {\n".
            "   \$folders = array(\"".implode("\", \"", $this->getFolders())."\");\n".
            "   foreach (\$folders as \$folder)\n".
            "       if (file_exists(__DIR__.DIRECTORY_SEPARATOR.\$folder.DIRECTORY_SEPARATOR.\$nomeClasse.\".php\"))\n".
            "           require_once(__DIR__.DIRECTORY_SEPARATOR.\$folder.DIRECTORY_SEPARATOR.\$nomeClasse.\".php\");\n".
            "   });\n".
            "?>";
            $fp = fopen($dir.'autoload.php', 'w');
            fwrite($fp, $autoload);
            fclose($fp);
          }
     }
 ?>