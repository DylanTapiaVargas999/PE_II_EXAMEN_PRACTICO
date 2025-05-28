<?php

function controllers_autoload($nombreClase){
    include 'controllers/'.$nombreClase.'.php';
}

spl_autoload_register('controllers_autoload');
