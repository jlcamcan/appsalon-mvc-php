<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Funcion que detecta el último
function esUltimo ($actual, $proximo):bool{
    if ($actual !== $proximo){
        return true;
    }
    return false;
}

//Función que revisa si un usuario está autenticado
function estaAutenticado() : void{
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}
//Función que revisa si un usuario es administrador.
function esAdmin() : void{
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}


