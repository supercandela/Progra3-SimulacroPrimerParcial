<?php

/*

Alumna: BOGADO, Candela

4- (1 pts.)ConsultasVentas.php: (por GET)
Datos a consultar:

b- El listado de ventas de un usuario ingresado.
c- El listado de ventas entre dos fechas ordenado por nombre.
d- El listado de ventas por sabor ingresado.
e- El listado de ventas por vaso Cucurucho.


*/

// require_once("Helado.php");
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
} else {
    echo "Parametros incorrectos\n\n";
}