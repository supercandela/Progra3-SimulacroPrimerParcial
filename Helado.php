<?php

class Helado
{
    private $_id;
    private $_sabor;
    private $_precio;
    private $_tipo;
    private $_vaso;
    private $_stock;

    /**
     * Constructor de clase.
     * Crea un ID autoincremental (emulado, puede ser un random de 1 a 10.000), si no posee uno.
     */
    public function __construct($sabor, $precio, $tipo, $vaso, $stock, $id = 0)
    {
        $this->_sabor = $sabor;
        $this->_precio = $precio;
        $this->_tipo = $tipo;
        $this->_vaso = $vaso;
        $this->_stock = $stock;
        if ($id == 0) {
            $this->_id = rand(1, 100);
        } else {
            $this->_id = $id;
        }
    }

    /**
     * Obtener array de productos desde archivo json
     * Retorna un array de objetos
     */
    public static function ObtenerListaDeProductos()
    {
        $array = array();
        // Verificar si el archivo existe
        if (file_exists("./heladeria.json")) {
            $valores = file_get_contents("./heladeria.json");
            $data = json_decode($valores, true);
            if ($data != null) {
                foreach ($data as $elemento) {
                    $producto = new Helado($elemento["sabor"], $elemento["precio"], $elemento["tipo"], $elemento["vaso"], $elemento["stock"], $elemento["id"]);
                    array_push($array, $producto);
                }
            }
        }
        return $array;
    }

    /**
     * Recibe un array y sobreescribe el archivo json con el nuevo contenido
     * Retorna false si no pudo completar el guardado completo
     * Retorna true si guardó todos los elementos en el archivo
     */
    public static function GuardarListaDeProductosJSON($productos)
    {
        if (count($productos) > 0) {
            $productosValoresPublicos = array();
            foreach ($productos as $producto) {
                $nuevoP = Helado::convertirAtributosAPublico($producto);
                array_push($productosValoresPublicos, $nuevoP);
            }
            $json = json_encode($productosValoresPublicos, JSON_PRETTY_PRINT);
            // Escribir el JSON en el archivo
            return file_put_contents("./heladeria.json", $json);
        }
        return false;
    }

    /**
     * sube la imagen al servidor en la carpeta /ImagenesDeHelados/2024
     */
    public static function GuardarFoto($foto, $sabor, $tipo, $tipo_archivo)
    {
        //Carpeta donde voy a guardar los archivos
        $carpeta_archivos = 'ImagenesDeHelados/2024/';
        // Ruta final, carpeta + nombre del archivo
        $destino = $carpeta_archivos . $sabor . "-" . $tipo . "." . $tipo_archivo;

        if (move_uploaded_file($foto['tmp_name'], $destino)) {
            return true;
        } else {
            return false;
        }
    }

    public static function convertirAtributosAPublico($elemento)
    {
        $productoPublico = new stdClass();
        $productoPublico->sabor = $elemento->_sabor;
        $productoPublico->precio = $elemento->_precio;
        $productoPublico->tipo = $elemento->_tipo;
        $productoPublico->vaso = $elemento->_vaso;
        $productoPublico->stock = $elemento->_stock;
        $productoPublico->id = $elemento->_id;
        return $productoPublico;
    }

    /**
     * Verifica si producto a comparar es igual a algún producto del listado
     * Retorna el índice del producto si el producto coincide
     * Retorna -1 si no coincide.
     */
    public static function VerificarSiExiste($arrayDeProductos, $producto)
    {
        if (count($arrayDeProductos) === []) {
            echo "Lista de productos vacía. \n\n";
            return -1;
        }
        $productoRegistrado = -1;

        for ($i = 0; $i < count($arrayDeProductos); $i++) {
            if ($arrayDeProductos[$i]->_sabor == $producto->_sabor && $arrayDeProductos[$i]->_tipo == $producto->_tipo) {
                $productoRegistrado = $i;
                break;
            }
        }
        return $productoRegistrado;
    }

    /**
     * Verifica si el sabor existe en el listado
     * Retorna el índice del producto si el producto coincide
     * Retorna -1 si no coincide.
     */
    public static function VerificarSabor($arrayDeProductos, $sabor)
    {
        if (count($arrayDeProductos) === []) {
            echo "Lista de productos vacía. \n\n";
            return -1;
        }
        $productoRegistrado = -1;

        for ($i = 0; $i < count($arrayDeProductos); $i++) {
            if ($arrayDeProductos[$i]->_sabor == $sabor) {
                $productoRegistrado = $i;
                break;
            }
        }
        return $productoRegistrado;
    }

    /**
     * Verifica si el tipo existe en el listado
     * Retorna el índice del producto si el producto coincide
     * Retorna -1 si no coincide.
     */
    public static function VerificarTipo($arrayDeProductos, $tipo)
    {
        if (count($arrayDeProductos) === []) {
            echo "Lista de productos vacía. \n\n";
            return -1;
        }
        $productoRegistrado = -1;

        for ($i = 0; $i < count($arrayDeProductos); $i++) {
            if ($arrayDeProductos[$i]->_tipo == $tipo) {
                $productoRegistrado = $i;
                break;
            }
        }
        return $productoRegistrado;
    }

    /**
     * Verifica si el stock alcanza
     * Retorna true si alcanza
     * Retorna false si no alcanza
     */
    public function VerificarStock($stock)
    {
        return $this->_stock >= $stock;
    }

    /**
     * Actualiza el precio del elemento
     */
    public static function ActualizarPrecio($producto, $arrayDeProductos, $i)
    {
        if (!(isset($producto->_sabor) && isset($producto->_tipo)
            && isset($producto->_precio)) && count($arrayDeProductos) >= $i) {
            echo "Datos erróneos. \n\n";
            return false;
        }
        $productoModificado = false;
        if (
            $arrayDeProductos[$i]->_sabor == $producto->_sabor &&
            $arrayDeProductos[$i]->_tipo == $producto->_tipo
        ) {
            $arrayDeProductos[$i]->_precio = $producto->_precio;
            $productoModificado = true;
        }
        return $productoModificado;
    }

    /**
     * Suma el stock al producto existente
     */
    public static function SumarStock($producto, $arrayDeProductos, $i)
    {
        if (!(isset($producto->_sabor) && isset($producto->_tipo)
            && isset($producto->_stock)) && count($arrayDeProductos) >= $i) {
            echo "Datos erróneos. \n\n";
            return false;
        }
        $productoModificado = false;
        if (
            $arrayDeProductos[$i]->_sabor == $producto->_sabor &&
            $arrayDeProductos[$i]->_tipo == $producto->_tipo
        ) {
            $arrayDeProductos[$i]->_stock = $arrayDeProductos[$i]->_stock + $producto->_stock;
            $productoModificado = true;
        }
        return $productoModificado;
    }

    /**
     * Resta el stock al producto existente
     */
    public static function RestarStock($producto, $arrayDeProductos, $i)
    {
        if (!(isset($producto->_sabor) && isset($producto->_tipo)
            && isset($producto->_stock)) && count($arrayDeProductos) >= $i) {
            echo "Datos erróneos. \n\n";
            return false;
        }
        $productoModificado = false;
        if (
            $arrayDeProductos[$i]->_sabor == $producto->_sabor &&
            $arrayDeProductos[$i]->_tipo == $producto->_tipo
        ) {
            $arrayDeProductos[$i]->_stock = $arrayDeProductos[$i]->_stock - $producto->_stock;
            $productoModificado = true;
        }
        return $productoModificado;
    }
}
