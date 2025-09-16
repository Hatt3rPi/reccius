<?php
// pages\backend\cloud\R2_manager.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
require_once "/home/customw2/librerias/aws/aws-autoloader.php";
//require_once "../../../assets/dependencies/aws/aws-autoloader.php";

use  Aws\Credentials\Credentials;
use Aws\S3\S3Client;

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
  header("Location: https://customware.cl/reccius/pages/login.html");
  exit;
}

$bucket_name        = getenv('BUCKET_NAME');
$bucket_url         = getenv('BUCKET_URL');
$worker_url         = getenv('WORKER_URL');
$account_id         = getenv('ACCOUNT_ID');
$access_key_id      = getenv('ACCESS_KEY_ID');
$access_key_secret  = getenv('ACCESS_KEY_SECRET');
$credentials = new Credentials($access_key_id, $access_key_secret);
$options = [
  'region' => 'auto',
  'endpoint' => "https://$account_id.r2.cloudflarestorage.com",
  'version' => 'latest',
  'credentials' => $credentials
];

$R2_client = new S3Client($options);

$mime_types = [
  'pdf' => 'application/pdf',
  'jpg' => 'image/jpeg',
  'jpeg' => 'image/jpeg',
  'png' => 'image/png',
  'gif' => 'image/gif',
  'webp' => 'image/webp',
  'txt' => 'text/plain',
  'csv' => 'text/csv',
  'html' => 'text/html',
  'css' => 'text/css',
  'js' => 'application/javascript',
  'json' => 'application/json',
  'xml' => 'application/xml',
  'xls' => 'application/vnd.ms-excel',
  'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  'doc' => 'application/msword',
  'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];
function setFile($params, $worker=false)
{
  global $R2_client, $bucket_name, $bucket_url, $mime_types, $worker_url;

  if (!is_array($params)) {
    throw new InvalidArgumentException('Se espera un arreglo.');
  }
  if (!isset($params['fileBinary']) || !isset($params['folder']) || !isset($params['fileName'])) {
    throw new InvalidArgumentException('Faltan parámetros requeridos (fileBinary, folder, fileName).');
  }

  // Acceder a los parámetros
  $fileBinary = $params['fileBinary'];
  $folder = $params['folder'];
  $fileName = $params['fileName'];

  $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
  $contentType = isset($mime_types[$fileExtension]) ? $mime_types[$fileExtension] : 'application/octet-stream';

  $final_path = str_replace( ' ', '_', $folder . '/' . $fileName);

  try {
    // Configurar timeout y reintentos para mejorar confiabilidad
    $result = $R2_client->putObject([
      'Bucket' => $bucket_name,
      'Key' => $final_path,
      'Body' => $fileBinary,
      'ContentType' => $contentType,
      'ACL' => 'public-read',
      'Metadata' => [
          'Access-Control-Allow-Origin' => '*'
      ],
      '@http' => [
        'timeout' => 30, // timeout de 30 segundos
        'connect_timeout' => 10 // timeout de conexión de 10 segundos
      ]
    ]);

    if ($worker==true){
      $objectURL = "$worker_url$final_path";
    } else {
      $objectURL = "$bucket_url$final_path";
    }

    return json_encode(['success' => [
      'ObjectURL' => $objectURL,
      'result' => $result,
      'error' => false
    ]]);
  } catch (Aws\Exception\AwsException $e) {
    $error_code = $e->getAwsErrorCode();
    $error_message = $e->getMessage();

    // Log específico para errores intermitentes
    error_log("R2 Upload Error - Code: {$error_code}, Message: {$error_message}, File: {$final_path}");

    // Clasificar tipos de error para mejor diagnóstico
    if (strpos($error_message, 'timeout') !== false || strpos($error_message, 'timed out') !== false) {
      return json_encode(['success' => false, 'error' => 'Timeout de conexión con R2', 'error_type' => 'timeout']);
    } elseif (strpos($error_message, 'network') !== false || strpos($error_message, 'connection') !== false) {
      return json_encode(['success' => false, 'error' => 'Error de red con R2', 'error_type' => 'network']);
    } else {
      return json_encode(['success' => false, 'error' => $error_message, 'error_type' => 'aws_error', 'aws_code' => $error_code]);
    }
  } catch (Exception $e) {
    // Capturar otros errores no relacionados con AWS
    error_log("R2 Upload General Error: " . $e->getMessage() . " - File: {$final_path}");
    return json_encode(['success' => false, 'error' => 'Error general en subida: ' . $e->getMessage(), 'error_type' => 'general']);
  }
}


function deleteFile($folder, $fileName)
{
  global $R2_client, $bucket_name;

  try {
    $result = $R2_client->deleteObject([
      'Bucket' => $bucket_name,
      'Key' => $folder . '/' . $fileName
    ]);
    return json_encode(['success' => $result, 'error' => false]);
  } catch (Aws\Exception\AwsException $e) {
    return json_encode(['success' => false, 'error' => $e->getMessage()]);
  }
}

function deleteFileFromUrl($fileUrl)
{
  global $bucket_name, $R2_client;

  // Extraer la ruta del archivo desde la URL
  $parsedUrl = parse_url($fileUrl);
  $filePath = ltrim($parsedUrl['path'], '/'); // Eliminar cualquier barra inicial

  try {
    $result = $R2_client->deleteObject([
      'Bucket' => $bucket_name,
      'Key' => $filePath
    ]);
    return ['success' => true, 'result' => $result];
  } catch (Aws\Exception\AwsException $e) {
    return ['success' => false, 'error' => $e->getMessage()];
  }
}
