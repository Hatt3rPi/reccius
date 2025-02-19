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

    private function executeQuery($query, $params = [])
    {
        $stmt = mysqli_prepare($this->link, $query);
        if (!$stmt) return false;

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function getPages()
    {
        $query = "SELECT p.*, tp.nombre AS tipo FROM paginas p JOIN tipos_paginas tp ON p.id_tipo_pagina = tp.id";
        return $this->fetchAll($query);
    }

    public function getModules()
    {
        return $this->fetchAll("SELECT * FROM tipos_paginas");
    }

    public function getRolPages()
    {
        return $this->fetchAll("SELECT * FROM roles_pagina");
    }

    public function getPageById($id)
    {
        return $this->fetchAll("SELECT * FROM paginas WHERE id = ?", [$id])[0] ?? null;
    }
    

    public function createPage($id_tipo_pagina, $nombre, $url_page)
    {
        $query = "INSERT INTO paginas (id_tipo_pagina, nombre, url_page) VALUES (?, ?, ?)";
        $success = $this->executeQuery($query, [$id_tipo_pagina, $nombre, $url_page]);
        return $success ? mysqli_insert_id($this->link) : false;
    }

    public function deletePage($id)
    {
        return $this->executeQuery("DELETE FROM paginas WHERE id = ?", [$id]);
    }

    public function getPagesForUser($usuario_id)
    {
        $query = "SELECT p.* FROM paginas p JOIN usuarios_paginas up ON p.id = up.pagina_id WHERE up.usuario_id = ?";
        return $this->fetchAll($query, [$usuario_id]);
    }

    public function createUserPage($usuario_id, $pagina_id, $rol_id = 2)
    {
        mysqli_begin_transaction($this->link);
        try {
            $query1 = "INSERT INTO usuarios_paginas (usuario_id, pagina_id) VALUES (?, ?)";
            if (!$this->executeQuery($query1, [$usuario_id, $pagina_id])) throw new Exception();
            
            $usuario_pagina_id = mysqli_insert_id($this->link);
            $query2 = "INSERT INTO usuarios_paginas_roles (usuario_pagina_id, rol_pagina_id) VALUES (?, ?)";
            if (!$this->executeQuery($query2, [$usuario_pagina_id, $rol_id])) throw new Exception();
            
            mysqli_commit($this->link);
            return $usuario_pagina_id;
        } catch (Exception $e) {
            mysqli_rollback($this->link);
            return false;
        }
    }

    public function getPageRoles()
    {
        return $this->fetchAll("SELECT * FROM roles_pagina ORDER BY id");
    }

    public function getUserPageRole($usuario_id, $pagina_id)
    {
        $query = "SELECT rp.* FROM roles_pagina rp JOIN usuarios_paginas_roles upr ON upr.rol_pagina_id = rp.id JOIN usuarios_paginas up ON up.id = upr.usuario_pagina_id WHERE up.usuario_id = ? AND up.pagina_id = ?";
        return $this->fetchAll($query, [$usuario_id, $pagina_id])[0] ?? null;
    }

    public function deleteUserPage($usuario_id, $pagina_id)
    {
        $query = "DELETE FROM usuarios_paginas WHERE usuario_id = ? AND pagina_id = ?";
        return $this->executeQuery($query, [$usuario_id, $pagina_id]);
    }
}
