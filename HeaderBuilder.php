<?php
    class HeaderBuilder
    {
        private function generateNavItens($tables)
        {
            $navItens = "";
            foreach ($tables as $key => $value) {
                $navItens .= "\t\t\t\t\t<a class=\"nav-item nav-link\" href=\"".$value->getName()."SelectView.php\">".ucfirst($value->getName())."</a>\n";
            }
            return $navItens;
        }
        public function createHeader($dir, $tables, $info)
        {
            $header = 
            "<html>\n".
            "\t<head>\n".
            "\t\t<title> :pName </title>\n".
            "\t\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n".
            // "\t\t<link href=\"../css/bootstrap.min.css\" rel=\"stylesheet\" media=\"screen\">\n".
            "\t\t<link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css\" integrity=\"sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO\" crossorigin=\"anonymous\">\n".
            "\t </head>\n".
            "\t<body>\n".
            "\t\t<nav class=\"navbar navbar-expand-lg navbar-dark bg-dark\">\n".
            "\t\t\t<a class=\"navbar-brand\" href=\"index.php\">".$info->getProjectName()."</a>\n".
            "\t\t\t<button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarNavAltMarkup\" aria-controls=\"navbarNavAltMarkup\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">\n".
            "\t\t\t\t<span class=\"navbar-toggler-icon\"></span>\n".
            "\t\t\t </button>\n".
            "\t\t\t<div class=\"collapse navbar-collapse\" id=\"navbarNavAltMarkup\">\n".
            "\t\t\t\t<div class=\"navbar-nav\">\n".
            "\t\t\t\t\t<a class=\"nav-item nav-link active\" href=\"index.php\">Home<span class=\"sr-only\">(current)</span></a>\n".
            ":navitens".
            "\t\t\t\t </div>\n".
            "\t\t\t </div>\n".
            "\t\t </nav>";
            $header = str_replace(":pName", "\$pName", $header);
            $header = str_replace(":navitens", $this->generateNavItens($tables), $header);
            $f = fopen($dir."view". DIRECTORY_SEPARATOR."header.php", "w");
            fwrite($f, $header);
            fclose($f);
        }
     }
 ?>