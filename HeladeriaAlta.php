<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Helado.php");

if (
    isset($_POST["sabor"]) && isset($_POST["precio"]) &&
    isset($_POST["tipo"]) && isset($_POST["vaso"]) && isset($_POST["stock"])
    && isset($_FILES["imagen"])
) {

    //Data Helado
    $sabor = strtolower($_POST["sabor"]);
    $precio = floatval($_POST["precio"]);
    $tipo = strtolower($_POST["tipo"]);
    $vaso = strtolower($_POST["vaso"]);
    $stock = intval($_POST["stock"]);

    //Data del archivo subido
    $nombre_archivo = $_FILES['imagen']['name'];
    $tipo_archivo = $_FILES['imagen']['type'];
    $tamano_archivo = $_FILES['imagen']['size'];

    //Guardar Imagen
    if ((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 500000)) {

        $extension = substr($tipo_archivo, strpos($tipo_archivo, '/') + 1);
        $cargaFoto = Helado::GuardarFoto($_FILES['imagen'], $sabor, $tipo, $extension);
        if ($cargaFoto) {
            echo "La imagen fue guardada exitosamente.\n\n";
        } else {
            echo "La foto no pudo ser guardada.\n\n";
        }
    } else {
        echo "La extensión o el tamaño de los archivos pueden no ser los correctos.\nSe permiten archivos .png o .jpg.\nSe permiten archivos de 100 Kb máximo.\n\n";
    }

    //Obtener listado de productos desde archivo
    $listaHelados = Helado::ObtenerListaDeProductos();
    //Crear nuevo objeto con los parámetros recibidos
    $heladoNuevo = new Helado($sabor, $precio, $tipo, $vaso, $stock);
    //Chequea si el producto ya existe en la lista
    $indiceH = Helado::VerificarSiExiste($listaHelados, $heladoNuevo);

    if ($indiceH != -1) {
        //Actualiza precio
        if (Helado::ActualizarPrecio($heladoNuevo, $listaHelados, $indiceH)) {
            echo "Precio actualizado con éxito.\n\n";
        } else {
            echo "Precio no actualizado.\n\n";
        }
        //Suma Stock
        if (Helado::SumarStock($heladoNuevo, $listaHelados, $indiceH)) {
            echo "Stock actualizado con éxito.\n\n";
        } else {
            echo "Stock no actualizado.\n\n";
        }
        //Actualizar listado
        if (Helado::GuardarListaDeProductosJSON($listaHelados)) {
            echo "Lista actualizada con éxito.\n\n";
        } else {
            echo "La lista no fue guardada correctamente.\n\n";
        }
    } else {
        //Guarda el producto nuevo en la lista
        array_push($listaHelados, $heladoNuevo);
        if (Helado::GuardarListaDeProductosJSON($listaHelados)) {
            echo "Producto registrado con éxito.\n\n";
        } else {
            echo "El producto no fue guardado correctamente.\n\n";
        }
    }
} else {
    echo "Parametros incorrectos\n\n";
}
