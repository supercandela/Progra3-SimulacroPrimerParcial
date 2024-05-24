<?php

require_once("Helado.php");

class Venta
{
    private $_id;
    private $_pedido;
    private $_fecha;
    private $_usuario;
    private $_cantidad;
    private $_sabor;
    private $_vaso;

    public function __construct($usuario, $cantidad, $sabor, $vaso, $fecha, $id = 0, $pedido = 0)
    {
        $this->_usuario = $usuario;
        $this->_cantidad = $cantidad;
        $this->_sabor = $sabor;
        $this->_vaso = $vaso;
        $this->_fecha = DateTime::createFromFormat("ymdhm", $fecha);
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
    public static function ObtenerListaDeVentas()
    {
        $array = array();
        // Verificar si el archivo existe
        if (file_exists("./ventas.json")) {
            $valores = file_get_contents("./ventas.json");
            $data = json_decode($valores, true);
            if ($data != null) {
                foreach ($data as $elemento) {
                    $venta = new Venta($elemento["usuario"], $elemento["cantidad"], $elemento["sabor"], $elemento["vaso"], $elemento["fecha"], $elemento["id"], $elemento["pedido"]);
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
    public static function GuardarVentasJSON($ventas)
    {
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
        $ventaPublica->usuario = $elemento->_usuario;
        $ventaPublica->cantidad = $elemento->_cantidad;
        $ventaPublica->sabor = $elemento->_sabor;
        $ventaPublica->vaso = $elemento->_vaso;
        $ventaPublica->fecha = $elemento->_fecha->format("ymdhm");
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

    public static function FiltrarListaPorFechaExacta($lista, Datetime $fecha)
    {
        $listaConFiltro = array();
        if (count($lista) > 0) {
            for ($i = 0; $i < count($lista); $i++) {
                if ($lista[$i]->_fecha->format('Y-m-d') == $fecha->format('Y-m-d')) {
                    array_push($listaConFiltro, $lista[$i]);
                }
            }
        }
        return $listaConFiltro;
    }

    public static function SumarCantidades($lista)
    {
        $cantidadTotal = 0;
        if (count($lista) > 0) {
            for ($i = 0; $i < count($lista); $i++) {
                $cantidadTotal += $lista[$i]->_cantidad;
            }
        }
        return $cantidadTotal;
    }

    public static function FiltrarListaPorUsuario($lista, $usuario)
    {
        $listaConFiltro = array();
        if (count($lista) > 0) {
            for ($i = 0; $i < count($lista); $i++) {
                if ($lista[$i]->_usuario == $usuario) {
                    array_push($listaConFiltro, $lista[$i]);
                }
            }
        }
        return $listaConFiltro;
    }

    public function MostrarVenta()
    {
        echo "ID Venta: " . $this->_id . "\n";
        echo "ID Pedido: " . $this->_pedido . "\n";
        echo "Usuario: " . $this->_usuario . "\n";
        echo "Fecha: " . $this->_fecha->format('Y-m-d') . "\n";
        echo "Cantidad: " . $this->_cantidad . "\n";
        echo "Sabor: " . $this->_sabor . "\n";
        echo "Tipo de vaso: " . $this->_vaso . "\n\n";
    }

    public static function FiltrarListaPorRangoDeFechas($lista, Datetime $fechaMin, DateTime $fechaMax)
    {
        $listaConFiltro = array();
        if (count($lista) > 0) {
            for ($i = 0; $i < count($lista); $i++) {
                if (
                    $lista[$i]->_fecha->format('Y-m-d') >= $fechaMin->format('Y-m-d') &&
                    $lista[$i]->_fecha->format('Y-m-d') <= $fechaMax->format('Y-m-d')
                ) {
                    array_push($listaConFiltro, $lista[$i]);
                }
            }
        }
        return $listaConFiltro;
    }

    /**
     * Ordena un array de objetos por una propiedad especificada.
     *
     * @param array $lista Array de objetos a ordenar.
     * @param string $propiedad Propiedad por la cual ordenar.
     * @param string $orden Orden de la clasificación ('asc' para ascendente, 'desc' para descendente).
     * @return array Array ordenado.
     */
    public static function OrdenarPorPropiedad($lista, string $propiedad, string $orden = 'asc')
    {
        usort($lista, function ($a, $b) use ($propiedad, $orden) {
            if (!property_exists($a, $propiedad) || !property_exists($b, $propiedad)) {
                echo "La propiedad '{$propiedad}' no existe en uno de los objetos.\n\n";
            }

            if ($a->$propiedad == $b->$propiedad) {
                return 0;
            }

            if ($orden === 'asc') {
                return ($a->$propiedad < $b->$propiedad) ? -1 : 1;
            } else {
                return ($a->$propiedad > $b->$propiedad) ? -1 : 1;
            }
        });
        return $lista;
    }

    /**
     * Verifica si el numero de pedido existe en el listado
     * Retorna el índice si coincide
     * Retorna -1 si no coincide.
     */
    public static function FiltrarPorPedido($lista, $pedido)
    {
        if (count($lista) === []) {
            echo "Lista vacía. \n\n";
            return -1;
        }
        $indiceARetornar = -1;

        for ($i = 0; $i < count($lista); $i++) {
            if ($lista[$i]->_pedido == $pedido) {
                $indiceARetornar = $i;
                break;
            }
        }
        return $indiceARetornar;
    }

    public function ModificarPedido($usuario, $sabor, $vaso, $cantidad)
    {
        $this->_usuario = $usuario;
        $this->_sabor = $sabor;
        $this->_vaso = $vaso;
        $this->_cantidad = $cantidad;
    }

    // Función para eliminar un objeto del array por su id
    public static function EliminarObjetoPorId($lista, $pedido)
    {
        foreach ($lista as $key => $objeto) {
            if ($objeto->_pedido == $pedido) {
                unset($lista[$key]);
                break;
            }
        }
        return $lista;
    }
}
