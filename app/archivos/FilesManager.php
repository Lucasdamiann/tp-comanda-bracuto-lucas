<?php

class FilesManager
{
    public static function CargarFotoPedido($file, $pedido, $mesa)
    {
        try {
            $nombreArchivo = $pedido->cliente . "-" . $pedido->numeroPedido . "-" . $mesa->codigoMesa;
            $destino = "./fotos/";
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }
            $nombreAnterior = $file['foto']->getClientFilename();
            $extension = explode(".", $nombreAnterior);
            $extension = array_reverse($extension);
            $file['foto']->moveTo($destino . $nombreArchivo . "." . $extension[0]);
            $foto = $nombreArchivo . "." . $extension[0];
            Pedido::ModificarFoto($pedido->id, $foto);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function GuardarEnCSV($clase)
    {
        $destino = "archivos/exportadosCSV/";
        $resultado = $clase::obtenerTodos();
        $file = $destino . $clase . '.csv';
        $array_string = self::retornarAtributos($resultado);
        if (!file_exists($destino)) {
            mkdir($destino, 0777, true);
        }
        if (($pFile = fopen($file, "a")) != FALSE) {
            foreach ($array_string as $array) {
                fputcsv($pFile, $array);
            }
            return fclose($pFile);
        }
    }

    public static function GuardarEnDB($data, $tabla)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos VALUES ($placeholders)");
        $consulta->bind_param(str_repeat('s', count($data)), ...$data);
        $consulta->bindValue(':tabla', $tabla, PDO::PARAM_STR);
        $consulta->execute();
    }

    private static function retornarAtributos($objetos)
    {
        $array_string = [];
        if ($objetos !== null) {
            foreach ($objetos as $objeto) {
                $atributos = get_object_vars($objeto);
                $valores = array_values($atributos);
                $array_string[] = $valores;
            }
        }
        return $array_string;
    }
}
