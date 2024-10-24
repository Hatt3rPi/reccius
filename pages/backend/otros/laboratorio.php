<?php
session_start();
// /pages/backend/otros/laboratorio.php
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
class Laboratorio
{
    private $conn;

    public function __construct(){
        global $link;
        $this->conn = $link;
    }

    public function findByName($name){
        $stmt = $this->conn->prepare("SELECT * FROM laboratorio WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $laboratorio = $result->fetch_assoc();
        $stmt->close();
        return $laboratorio;
    }

    public function create($name){
        $stmt = $this->conn->prepare("INSERT INTO laboratorio (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    public function updateCorreo($name, $correo){
        $stmt = $this->conn->prepare("UPDATE laboratorio SET correo = ? WHERE name = ?");
        $stmt->bind_param("ss", $correo, $name);
        $stmt->execute();
        $stmt->close();
    }

    public function findOrCreateByName($name){
        $laboratorio = $this->findByName($name);
        if (!$laboratorio) {
            $this->create($name);
            $laboratorio = $this->findByName($name);
        }
        return $laboratorio;
    }

    public function findAll(){
        $result = $this->conn->query("SELECT * FROM laboratorio");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findCCByCorreoAndLab($correo, $idLab){
        $stmt = $this->conn->prepare("SELECT * FROM laboratorio_con_copia WHERE correo = ? AND laboratorio_id = ?");
        $stmt->bind_param("si", $correo, $idLab);
        $stmt->execute();
        $result = $stmt->get_result();
        $laboratorio = $result->fetch_assoc();
        $stmt->close();
        return $laboratorio;
    }
    
    // Nuevo método para agregar correo a laboratorio_con_copia con el nombre del laboratorio
    public function addCorreoToLaboratorioWithName($laboratorioId, $name, $correo) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO laboratorio_con_copia (laboratorio_id, name, correo) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $laboratorioId, $name, $correo);
            $stmt->execute();
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Código de error para duplicado en MySQL
                echo "El correo ya existe para este laboratorio.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    // Obtener los correos asociados por nombre de laboratorio
    public function getCorreosByLaboratorioName($name){
        $laboratorio = $this->findByName($name);
        
        if (!$laboratorio) {
            return []; 
        }
        
        $laboratorioId = $laboratorio['id'];
    
        $stmt = $this->conn->prepare("SELECT * FROM laboratorio_con_copia WHERE laboratorio_id = ?");
        $stmt->bind_param("i", $laboratorioId);
        $stmt->execute();
        $result = $stmt->get_result();
        $correos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $correos;
    }
    

    public function deleteCorreosCCByCorreo($correo,$idLab){
        $laboratorio = $this->findCCByCorreo($correo, $idLab);
        if ($laboratorio) {
            $stmt = $this->conn->prepare("DELETE FROM laboratorio_con_copia WHERE laboratorio_id = ?, correo = ?");
            $stmt->bind_param("is", $idLab, $correo);
            $stmt->execute();
            $stmt->close();
            return 'success';
        }
        return 'Error';
    }
}
