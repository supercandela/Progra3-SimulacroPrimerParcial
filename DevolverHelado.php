<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Venta.php");

if (isset($_POST["pedido"]) && 
    isset($_POST["causa"]) && 
    isset($_FILES["imagen"])
    ) {
    
        $pedido = intval($_POST["pedido"]);
        $causa = $_POST["causa"];
        
        //Data del archivo subido
        $nombre_archivo = $_FILES['imagen']['name'];
        $tipo_archivo = $_FILES['imagen']['type'];
        $tamano_archivo = $_FILES['imagen']['size'];

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





    // a- Se ingresa el número de pedido y la causa de la devolución. 
    // El número de pedido debe existir, 
    // se ingresa una foto del cliente enojado,
    // esto debe generar un cupón de descuento (id, devolucion_id, porcentajeDescuento, estado[usado/no usado]) 
    // con el 10% de descuento para la próxima compra.
    
    // Guardar en el archivo (devoluciones.json y cupones.json):

    



    // //Dato Usuario
    // $email = $_POST["email"];

    // //Data Helado
    // $sabor = strtolower($_POST["sabor"]);
    // $tipo = strtolower($_POST["tipo"]);
    // $vaso = strtolower($_POST["vaso"]);

    // //Nueva venta
    // $fechaVenta = new DateTime();

    // // Modificar la fecha para que sea la fecha de ayer
    // // $fechaVenta = $fechaVenta->modify('-2 day');


    // //Obtener listado de productos desde archivo
    // $listaHelados = Helado::ObtenerListaDeProductos();
    // //Crear nuevo objeto con los parámetros recibidos
    // $heladoVendido = new Helado($sabor, 0, $tipo, $vaso, $stock);
    // //Chequea si el producto ya existe en la lista
    // $indiceH = Helado::VerificarSiExiste($listaHelados, $heladoVendido);

    // if ($indiceH != -1) {
    //     if (Helado::VerificarStock($listaHelados[$indiceH], $stock)) {
    //         //Guardar fecha, numero de pedido y id
    //         $venta = new Venta($email, $stock, $sabor, $vaso, $fechaVenta->format("ymdhm"));
    //         $listaVentas = Venta::ObtenerListaDeVentas();
    //         array_push($listaVentas, $venta);
    //         if (Venta::GuardarVentasJSON($listaVentas)) {
    //             echo "Venta guardada con éxito.\n\n";
    //         } else {
    //             echo "La venta no fue guardada.\n\n";
    //         }

    //         //Descontar Stock
    //         if (Helado::RestarStock($heladoVendido, $listaHelados, $indiceH)) {
    //             echo "El stock fue descontado con éxito.\n\n";
    //             if (Helado::GuardarListaDeProductosJSON($listaHelados)) {
    //                 echo "Lista de helados guardada.\n\n";
    //             } else {
    //                 echo "Error al guardar la lista de helados.\n\n";
    //             }
    //         } else {
    //             echo "Error al descontar stock.\n\n";
    //         }

    //         //Guardar Imagen
    //         if ((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 500000)) {

    //             $extension = substr($tipo_archivo, strpos($tipo_archivo, '/') + 1);
    //             $emailFormateado = explode("@", $email);

    //             $cargaFoto = Venta::GuardarFoto($_FILES['imagen'], $sabor, $tipo, $vaso, $emailFormateado[0], $fechaVenta->format("ymdhm"), $extension);
    //             if ($cargaFoto) {
    //                 echo "La imagen fue guardada exitosamente.\n\n";
    //             } else {
    //                 echo "La foto no pudo ser guardada.\n\n";
    //             }
    //         } else {
    //             echo "La extensión o el tamaño de los archivos pueden no ser los correctos.\nSe permiten archivos .png o .jpg.\nSe permiten archivos de 100 Kb máximo.\n\n";
    //         }
    //     } else {
    //         echo "No hay stock suficiente del producto elegido.\n\n";
    //     }
    // } else {
    //     echo "No existe el producto elegido.\n\n";
    // }
} else {
    echo "Parametros incorrectos\n\n";
}
