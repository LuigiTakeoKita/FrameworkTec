<?php
    require_once "Builder.php";
    $builder = new Builder;
    $dir=$builder->init();
    // header("Location: ".$dir.DIRECTORY_SEPARATOR."view".DIRECTORY_SEPARATOR."index.php");
?>