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
        $numero = 0;
        if (!file_exists($destino)) {
            mkdir($destino, 0777, true);
        }
        do {
            $numero++;
            $file = $destino . pathinfo($clase, PATHINFO_FILENAME) . "($numero)." . pathinfo($clase, PATHINFO_EXTENSION);;
        } while (file_exists($file));

        if (($pFile = fopen($file, "a")) != FALSE) {
            foreach ($array_string as $array) {
                fputcsv($pFile, $array);
            }
            return fclose($pFile);
        }
    }

    public static function GuardarEnDB($data)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productos SET tipo = :tipo, sector = :sector, precio = :precio, estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $data[0], PDO::PARAM_INT);
        $consulta->bindValue(':tipo', $data[1], PDO::PARAM_STR);
        $consulta->bindValue(':sector', $data[2], PDO::PARAM_STR);
        $consulta->bindValue(':precio', $data[3], PDO::PARAM_INT);
        $consulta->bindValue(':estado', $data[4], PDO::PARAM_STR);
        $consulta->execute();
        if ($consulta->rowCount() === 0) {
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (id, tipo, sector, precio, estado) VALUES (:id, :tipo, :sector, :precio, :estado)");
            $consulta->bindValue(':id', $data[0], PDO::PARAM_INT);
            $consulta->bindValue(':tipo', $data[1], PDO::PARAM_STR);
            $consulta->bindValue(':sector', $data[2], PDO::PARAM_STR);
            $consulta->bindValue(':precio', $data[3], PDO::PARAM_INT);
            $consulta->bindValue(':estado', $data[4], PDO::PARAM_STR);
            $consulta->execute();
        }
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
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
