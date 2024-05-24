<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Venta.php");

if (
    isset($vars["pedido"])
) {
    $pedido = intval($vars["pedido"]);

    $lista = Venta::ObtenerListaDeVentas();
    $indiceV = Venta::FiltrarPorPedido($lista, $pedido);
    if ($indiceV != -1) {

        $lista = Venta::EliminarObjetoPorId($lista, $pedido);
        foreach ($lista as $elemento) {
            $elemento->MostrarVenta();
        }
    } else {
        echo "No existe el número de pedido " . $pedido . ".\n\n";
    }
} else {
    echo "Parametros incorrectos\n\n";
}
