<?php

/*

Alumna: BOGADO, Candela

*/

echo "Heladería\n\n";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        
        switch ($accion) {
            case 'altaHelado':
                include 'HeladeriaAlta.php';
                break;

            case 'consultarHelado':
                include 'HeladoConsultar.php';
                break;

            case 'altaVenta':
                include 'AltaVenta.php';
                break;

            default:
                echo "Acción no válida.\n\n";
                break;
        }
    } else {
        echo "No se especificó ninguna acción.\n\n";
    }
} else {
    echo "Método no soportado.\n\n";
}
?>