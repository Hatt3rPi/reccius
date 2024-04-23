<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
require_once "../../../assets/dependencies/aws.phar";

use  Aws\Credentials\Credentials;
use Aws\S3\S3Client;

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
  http_response_code(403);
  echo json_encode(['error' => 'Acceso denegado']);
  exit;
}

$bucket_name        = getenv('BUCKET_NAME');
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

function setFile($params) {
  global $R2_client, $bucket_name;

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

  try {
    $result = $R2_client->putObject([
      'Bucket' => $bucket_name,
      'Key' => $folder . '/' . $fileName,
      'Body' => $fileBinary,
      'ACL' => 'private'
    ]);
      return json_encode(['success' => $result, 'error' => false]);

  } catch (Aws\Exception\AwsException $e) {
      return json_encode(['success'=> false, 'error' => $e->getMessage()]);
  }

}

function deleteFile($folder, $fileName) {
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

?>
