<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Venta.php");

if (isset($_GET["fecha"])) {
    $fecha = $_GET["fecha"];

    if ($fecha == "") {
        // Crear instancia de DateTime para la fecha actual
        $fecha = new DateTime();
        // Modificar la fecha para que sea la fecha de ayer
        $fecha = $fecha->modify('-1 day');
    } else {
        $fecha = new DateTime($fecha);
    }
    $lista = Venta::ObtenerListaDeVentas();
    $nuevaLista = Venta::FiltrarListaPorFechaExacta($lista, $fecha);
    $cantidadVendida = Venta::SumarCantidades($nuevaLista);
    echo "La cantidad de productos vendidos en la fecha " . $fecha->format('Y-m-d') . " es: " . $cantidadVendida;
} else if (isset($_GET["usuario"])) {
    $usuario = $_GET["usuario"];
    $lista = Venta::ObtenerListaDeVentas();
    $nuevaLista = Venta::FiltrarListaPorUsuario($lista, $usuario);
    foreach ($nuevaLista as $venta) {
        $venta->MostrarVenta();
    }
} else if (isset($_GET["fechaMin"]) && isset($_GET["fechaMax"])) {
    $fechaMin = new DateTime($_GET["fechaMin"]);
    $fechaMax = new DateTime($_GET["fechaMax"]);
    $lista = Venta::ObtenerListaDeVentas();
    $nuevaLista = Venta::FiltrarListaPorRangoDeFechas($lista, $fechaMin, $fechaMax);
    $nuevaLista = Venta::OrdenarPorPropiedad($nuevaLista, "_usuario");
    foreach ($nuevaLista as $venta) {
        $venta->MostrarVenta();
    }
} else if (isset($_GET["sabor"])) {
    $sabor = $_GET["sabor"];
    $lista = Venta::ObtenerListaDeVentas();
    $nuevaLista = Venta::OrdenarPorPropiedad($lista, "_sabor");
    foreach ($nuevaLista as $venta) {
        $venta->MostrarVenta();
    }

} else {
    echo "Parametros incorrectos\n\n";
}
