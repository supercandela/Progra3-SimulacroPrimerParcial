<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Venta.php");

if (
    isset($vars["pedido"]) &&
    isset($vars["usuario"]) &&
    isset($vars["sabor"]) &&
    isset($vars["tipo"]) &&
    isset($vars["vaso"]) &&
    isset($vars["cantidad"])
) {
    $pedido = intval($vars["pedido"]);
    $usuario = $vars["usuario"];
    $sabor = strtolower($vars["sabor"]);
    $tipo = strtolower($vars["tipo"]);
    $vaso = strtolower($vars["vaso"]);
    $cantidad = intval($vars["cantidad"]);

    $lista = Venta::ObtenerListaDeVentas();
    $indiceV = Venta::FiltrarPorPedido($lista, $pedido);
    if ($indiceV != -1) {
        $lista[$indiceV]->ModificarPedido($usuario, $sabor, $vaso, $cantidad);
        if (Venta::GuardarVentasJSON($lista)) {
            echo "Venta actualizada con éxito.\n\n";
        } else {
            echo "La venta no fue actualizada.\n\n";
        }
    } else {
        echo "No existe el número de pedido " . $pedido . ".\n\n";
    }
} else {
    echo "Parametros incorrectos\n\n";
}
