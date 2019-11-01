<?php
    require_once "Builder.php";
    $builder = new Builder;
    $builder->init();
    header("Location: src".DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR."index.php");
?>