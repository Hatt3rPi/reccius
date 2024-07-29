<?php
session_start();
// /pages/backend/otros/laboratorio.php
require_once "/home/customw2/conexiones/config_reccius.php";

class Laboratorio {
    private $conn;

    public function __construct() {
        global $link;
        $this->conn = $link;
    }

    public function findByName($name) {
        $stmt = $this->conn->prepare("SELECT * FROM laboratorio WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $laboratorio = $result->fetch_assoc();
        $stmt->close();
        return $laboratorio;
    }

    public function create($name) {
        $stmt = $this->conn->prepare("INSERT INTO laboratorio (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    public function updateCorreo($id, $correo) {
        $stmt = $this->conn->prepare("UPDATE laboratorio SET correo = ? WHERE id = ?");
        $stmt->bind_param("si", $correo, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function findOrCreateByName($name) {
        $laboratorio = $this->findByName($name);
        if (!$laboratorio) {
            $this->create($name);
            $laboratorio = $this->findByName($name);
        }
        return $laboratorio;
    }

    public function findAll() {
        $result = $this->conn->query("SELECT * FROM laboratorio");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

?>
