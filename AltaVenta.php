<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Helado.php");
require_once("Venta.php");

if (
    isset($_POST["email"]) && isset($_POST["sabor"]) &&
    isset($_POST["tipo"]) && isset($_POST["stock"]) && isset($_POST["vaso"])
    && isset($_FILES["imagen"])
) {

    //Dato Usuario
    $email = $_POST["email"];

    //Data Helado
    $sabor = strtolower($_POST["sabor"]);
    $tipo = strtolower($_POST["tipo"]);
    $stock = intval($_POST["stock"]);
    $vaso = strtolower($_POST["vaso"]);

    //Nueva venta
    $fechaVenta = new DateTime();

    //Data del archivo subido
    $nombre_archivo = $_FILES['imagen']['name'];
    $tipo_archivo = $_FILES['imagen']['type'];
    $tamano_archivo = $_FILES['imagen']['size'];

    //Obtener listado de productos desde archivo
    $listaHelados = Helado::ObtenerListaDeProductos();
    //Crear nuevo objeto con los parámetros recibidos
    $heladoVendido = new Helado($sabor, 0, $tipo, "", $stock);
    //Chequea si el producto ya existe en la lista
    $indiceH = Helado::VerificarSiExiste($listaHelados, $heladoVendido);

    if ($indiceH != -1) {
        if ($listaHelados[$indiceH]->VerificarStock($stock)) {
            //Guardar fecha, numero de pedido y id
            $venta = new Venta($fechaVenta);
            $listaVentas = Venta::ObtenerListaDeVentas();
            array_push($listaVentas, $venta);
            if (Venta::GuardarVentasJSON($listaVentas)) {
                echo "Venta guardada con éxito.\n\n";
            } else {
                echo "La venta no fue guardada.\n\n";
            }

            //Descontar Stock
            if (Helado::RestarStock($heladoVendido, $listaHelados, $indiceH)) {
                echo "El stock fue descontado con éxito.\n\n";
                if (Helado::GuardarListaDeProductosJSON($listaHelados)) {
                    echo "Lista de helados guardada.\n\n";
                } else {
                    echo "Error al guardar la lista de helados.\n\n";
                }
            } else {
                echo "Error al descontar stock.\n\n";
            }

            //Guardar Imagen
            if ((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 500000)) {

                $extension = substr($tipo_archivo, strpos($tipo_archivo, '/') + 1);
                $emailFormateado = explode("@", $email);

                $cargaFoto = Venta::GuardarFoto($_FILES['imagen'], $sabor, $tipo, $vaso, $emailFormateado[0], $fechaVenta->format("mdyhm"), $extension);
                if ($cargaFoto) {
                    echo "La imagen fue guardada exitosamente.\n\n";
                } else {
                    echo "La foto no pudo ser guardada.\n\n";
                }
            } else {
                echo "La extensión o el tamaño de los archivos pueden no ser los correctos.\nSe permiten archivos .png o .jpg.\nSe permiten archivos de 100 Kb máximo.\n\n";
            }
        } else {
            echo "No hay stock suficiente del producto elegido.\n\n";
        }
    } else {
        echo "No existe el producto elegido.\n\n";
    }
} else {
    echo "Parametros incorrectos\n\n";
}
