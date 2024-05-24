<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Helado.php");

if (isset($_POST["sabor"]) && isset($_POST["tipo"])) {

    //Data Helado
    $sabor = strtolower($_POST["sabor"]);
    $tipo = strtolower($_POST["tipo"]);

    //Obtener listado de productos desde archivo
    $listaHelados = Helado::ObtenerListaDeProductos();
    $heladoTest = new Helado($sabor, 0, $tipo, "", 0);

    $indiceH = Helado::VerificarSiExiste($listaHelados, $heladoTest);

    $saborExiste = Helado::VerificarSabor($listaHelados, $sabor);
    $tipoExiste = Helado::VerificarTipo($listaHelados, $tipo);

    if ($indiceH != -1) {
        echo "El helado existe.\n\n";
    } else {
        if ($saborExiste != -1) {
            echo "El sabor existe, pero no coincide el tipo.\n\n";
        } else if ($tipoExiste != -1) {
            echo "El tipo existe, pero no hay sabores que coincidan.\n\n";
        } else {
            echo "No existe ni ese sabor ni ese tipo.\n\n";
        }
    }
} else {
    echo "Parametros incorrectos\n\n";
}
