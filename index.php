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

            case 'devolverHelado':
                include 'DevolverHelado.php';
                break;

            default:
                echo "Acción no válida.\n\n";
                break;
        }
    } else {
        echo "No se especificó ninguna acción.\n\n";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['accion'])) {
        $accion = $_GET['accion'];

        switch ($accion) {
            case 'consultaVentas':
                include 'ConsultasVentas.php';
                break;

            default:
                echo "Acción no válida.\n\n";
                break;
        }
    } else {
        echo "No se especificó ninguna acción.\n\n";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $vars);

    if (isset($vars['accion'])) {
        $accion = $vars['accion'];

        switch ($accion) {
            case 'modificarVenta':
                include 'ModificarVenta.php';
                break;

            default:
                echo "Acción no válida.\n\n";
                break;
        }
    } else {
        echo "No se especificó ninguna acción.\n\n";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $vars);
    echo "DELETE\n\n";
    
    if (isset($vars['accion'])) {
        $accion = $vars['accion'];

        switch ($accion) {
            case 'borrarVenta':
                include 'BorrarVenta.php';
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
