<?php
    class FooterBuilder
    {
        public function createFooter($dir)
        {
            $footer =
            "\t\t<script src=\"https://code.jquery.com/jquery.js\"></script>\n".
            // "\t\t<script src=\"js/bootstrap.min.js\"></script>\n".
            "\t</body>\n".
            "</html>";
            $f = fopen($dir."view". DIRECTORY_SEPARATOR."footer.php", "w");
            fwrite($f, $footer);
            fclose($f);
        }
     }
 ?>