<?php

class Venta
{
    private $_id;
    private $_pedido;
    private $_fecha;
    private $_cantidad;
    private $_usuario;

    public function __construct ($fecha = new DateTime(), $id = 0, $pedido = 0) {
        $this->_fecha = $fecha;
        if ($id == 0) {
            $this->_id = rand(1, 100);
        } else {
            $this->_id = $id;
        }
        if ($pedido == 0) {
            $this->_pedido = rand(100000, 999999);
        } else {
            $this->_pedido = $pedido;
        }
    }

    /**
     * Obtener array de ventas desde ventas.json
     * Retorna un array de ventas
     */
    public static function ObtenerListaDeVentas () {
        $array = array();
        // Verificar si el archivo existe
        if (file_exists("./ventas.json")) {
            $valores = file_get_contents("./ventas.json");
            $data = json_decode($valores, true);
            if ($data != null) {
                foreach ($data as $elemento) {
                    $venta = new Venta($elemento["fecha"], $elemento["id"], $elemento["pedido"]);
                    array_push($array, $venta);
                }
            }
        }
        return $array;
    }

    /**
     * Recibe un array de ventas y sobreescribe el archivo ventas.json con el nuevo contenido
     * Retorna false si no pudo completar el guardado completo
     * Retorna true si guardó todos los elementos en el archivo
     */
    public static function GuardarVentasJSON ($ventas) {
        if (count($ventas) > 0) {
            $ventasValoresPublicos = array();
            foreach ($ventas as $venta) {
                $nuevaV = Venta::convertirAtributosAPublico($venta);
                array_push($ventasValoresPublicos, $nuevaV);
            }
            $json = json_encode($ventasValoresPublicos, JSON_PRETTY_PRINT);
            // Escribir el JSON en el archivo
            return file_put_contents("./ventas.json", $json);
        }
        return false;
    }

    public static function convertirAtributosAPublico($elemento)
    {
        $ventaPublica = new stdClass();
        $ventaPublica->fecha = $elemento->_fecha;
        $ventaPublica->id = $elemento->_id;
        $ventaPublica->pedido = $elemento->_pedido;
        return $ventaPublica;
    }

    /**
     * sube la imagen al servidor en la carpeta /ImagenesDeLaVenta/2024.
     */
    public static function GuardarFoto($foto, $sabor, $tipo, $vaso, $email, $fecha, $tipo_archivo)
    {
        //Carpeta donde voy a guardar los archivos
        $carpeta_archivos = 'ImagenesDeLaVenta/2024/';
        // Ruta final, carpeta + nombre del archivo
        $destino = $carpeta_archivos . $sabor . "-" . $tipo . "-" . $vaso . "-" . $email . "-" . $fecha . "." . $tipo_archivo;

        if (move_uploaded_file($foto['tmp_name'], $destino)) {
            return true;
        } else {
            return false;
        }
    }

}