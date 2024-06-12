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
  
}