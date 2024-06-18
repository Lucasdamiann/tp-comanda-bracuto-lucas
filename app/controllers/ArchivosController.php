<?php

require_once './archivos/FilesManager.php';

class ArchivosController
{

  public function ExportarCSV($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $categoria = $parametros['categoria'];
    if ($categoria !== NULL) {
      if (FilesManager::GuardarEnCSV($categoria)) {
        $payload = json_encode(array("mensaje" => "Archivo exportado con exito"));
        $response->getBody()->write($payload);
      }
    } else {
      $payload = json_encode(array("mensaje" => "ERROR: Falta el parametro categoria"));
      $response->getBody()->write($payload);
    }
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ImportarCSV($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $tabla = $parametros["tabla"];
    if ($_FILES && is_uploaded_file($_FILES['file']['tmp_name'])) {
      $destino = "archivos/importadosCSV/";
      $nombreArchivo = $_FILES['file']['name'];
      $archivoTemp = $_FILES['file']['tmp_name'];

      // Mover el archivo temporal al destino final
      $rutaArchivo = $destino . $nombreArchivo;
      if (!file_exists($destino)) {
        mkdir($destino, 0777, true);
      }
      if (move_uploaded_file($archivoTemp, $rutaArchivo)) {
        echo "Archivo subido correctamente.";

        // Procesar el archivo CSV y guardar en la base de datos
        if (($pFile = fopen($rutaArchivo, "r")) !== FALSE) {
          while (($data = fgetcsv($pFile, 1000, ",")) !== FALSE) {
            // Aquí deberías procesar cada línea del CSV y guardar en la base de datos
            // Suponiendo que tienes una función para procesar cada línea
            // Ejemplo: procesarLineaCSV($data);
            // Aquí deberías implementar la lógica para guardar en la base de datos
            // $data contiene los valores de cada línea del CSV
            FilesManager::GuardarEnDB($data, $tabla);
            $payload = json_encode($data);
            $response->getBody()->write($payload);
          }
          fclose($pFile);
        } else {
          echo "Error al abrir el archivo CSV.";
        }
      } else {
        echo "Error al subir el archivo.";
      }
    } else {
      echo "No se ha subido ningún archivo.";
    }
  }
}
