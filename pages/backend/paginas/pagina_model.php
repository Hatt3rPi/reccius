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

    public function addUserToModuleRelationship($userId, $moduleId, $rolId = 2)
    {
        mysqli_begin_transaction($this->link);
        try {
            // Validar que el usuario y módulo existan
            $checkUser = $this->fetchAll("SELECT id FROM usuarios WHERE id = ?", [$userId]);
            if (empty($checkUser)) {
                throw new Exception("Usuario no encontrado");
            }

            $checkModule = $this->fetchAll("SELECT id FROM tipos_paginas WHERE id = ?", [$moduleId]);
            if (empty($checkModule)) {
                throw new Exception("Módulo no encontrado");
            }

            // Verificar si ya existe la relación
            $checkRelation = $this->fetchAll(
                "SELECT id FROM usuarios_modulos WHERE usuario_id = ? AND tipo_pagina_id = ?",
                [$userId, $moduleId]
            );
            if (!empty($checkRelation)) {
                throw new Exception("La relación ya existe");
            }

            // Insertar en usuarios_modulos
            $query1 = "INSERT INTO usuarios_modulos (usuario_id, tipo_pagina_id) VALUES (?, ?)";
            if (!$this->executeQuery($query1, [$userId, $moduleId])) {
                throw new Exception("Error al crear la relación usuario-módulo: " . mysqli_error($this->link));
            }
            
            $usuarioModuloId = mysqli_insert_id($this->link);
            if (!$usuarioModuloId) {
                throw new Exception("Error al obtener el ID de la relación");
            }
            
            // Insertar en usuarios_modulos_roles
            $query2 = "INSERT INTO usuarios_modulos_roles (usuario_modulo_id, rol_pagina_id) VALUES (?, ?)";
            if (!$this->executeQuery($query2, [$usuarioModuloId, $rolId])) {
                throw new Exception("Error al asignar el rol: " . mysqli_error($this->link));
            }

            mysqli_commit($this->link);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->link);
            throw $e;
        }
    }
}
