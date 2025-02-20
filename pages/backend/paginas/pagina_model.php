<?php
// archivo: pages/backend/paginas/PaginaModel.php
require_once "/home/customw2/conexiones/config_reccius.php";

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}

class PaginaModel
{
    private $link;

    public function __construct($link)
    {
        if (!$link || !($link instanceof mysqli)) {
            throw new Exception("Se requiere una conexión mysqli válida");
        }
        $this->link = $link;
    }

    private function fetchAll($query, $params = [])
    {
        $stmt = mysqli_prepare($this->link, $query);
        if (!$stmt) return false;

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        return $data;
    }

    public function getModules()
    {
        return $this->fetchAll("SELECT * FROM tipos_paginas");
    }

    public function getRolPages()
    {
        return $this->fetchAll("SELECT * FROM roles_pagina");
    }

    public function getModuleRelationships($modulo_id) {
        $query = "SELECT 
            u.id as usuario_id,
            u.nombre as usuario_nombre,
            u.correo as usuario_correo,
            u.cargo as usuario_cargo,
            um.id as usuario_modulo_id,
            rp.id as rol_id,
            rp.nombre as rol_nombre,
            rp.descripcion as rol_descripcion
        FROM usuarios_modulos um
        JOIN usuarios u ON u.id = um.usuario_id
        JOIN usuarios_modulos_roles umr ON umr.usuario_modulo_id = um.id
        JOIN roles_pagina rp ON rp.id = umr.rol_pagina_id
        WHERE um.tipo_pagina_id = ?";
        
        return $this->fetchAll($query, [$modulo_id]);
    }
}
