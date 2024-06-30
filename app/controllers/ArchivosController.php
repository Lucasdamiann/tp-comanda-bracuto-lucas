<?php

require_once './archivos/FilesManager.php';
require_once './archivos/PDFManager.php';

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
    if ($_FILES && is_uploaded_file($_FILES['archivo']['tmp_name'])) {
      $destino = "archivos/importadosCSV/";
      $nombreArchivo = $_FILES['archivo']['name'];
      $archivoTemp = $_FILES['archivo']['tmp_name'];
      $rutaArchivo = $destino . $nombreArchivo;
      if (!file_exists($destino)) {
        mkdir($destino, 0777, true);
      }
      $numero = 0;
      while (file_exists($rutaArchivo)) {
        $numero++;
        $rutaArchivo = $destino . pathinfo($nombreArchivo, PATHINFO_FILENAME) . "($numero)." . pathinfo($nombreArchivo, PATHINFO_EXTENSION);
      }
      if (move_uploaded_file($archivoTemp, $rutaArchivo)) {
        if (($pFile = fopen($rutaArchivo, "r")) !== FALSE) {
          while (($data = fgetcsv($pFile, 1000, ",")) !== FALSE) {
            if (count($data) == 5) {
              FilesManager::GuardarEnDB($data);
              $payload = json_encode(array("mensaje" => "Productos ingresados con exito"));
            }else{
              $payload = json_encode(array("mensaje" => "ERROR: Revisar la cantidad de elementos a agregar"));              
            }
          }
          fclose($pFile);
        } else {
          $payload = json_encode(array("mensaje" => "ERROR: No se pudo abrir el archivo CSV"));
        }
      } else {
        $payload = json_encode(array("mensaje" => "ERROR: No se pudo subir el archivo"));
      }
    } else {
      $payload = json_encode(array("mensaje" => "Debe seleccionar un archivo para importar"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  function CrearPDF($request, $response, $args)
  {
    $pdf = new PDFManager();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times', '', 12);
    $lista = Producto::obtenerTodos();
    $pdf->mostrarDatos($lista);
    $pdf->Output();
    $pdfContent = $pdf->Output('S');
    $rutaArchivo = 'archivos/exportadosPDF/productos.pdf';
    file_put_contents($rutaArchivo, $pdfContent);

    return $response;
  }
}
