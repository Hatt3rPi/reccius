<?php
// archivo: pages/backend/paginas/pagina_model.php
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
        global $link;
        $this->link = $link;
    }

    public function getPages()
    {
        $query = "SELECT p.*, tp.nombre AS tipo 
          FROM paginas AS p 
          JOIN tipos_paginas AS tp 
            ON p.id_tipo_pagina = tp.id";
        $result = mysqli_query($this->link, $query);
        $pages = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $pages[] = $row;
        }
        return $pages;
    }
    public function  getModules(){
        $query = "SELECT * FROM tipos_paginas";
        $result = mysqli_query($this->link, $query);
        $modules = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $modules[] = $row;
        }
        return $modules;
    }
    public function  getRolPages(){
        $query = "SELECT * FROM roles_pagina";
        $result = mysqli_query($this->link, $query);
        $roles = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $roles[] = $row;
        }
        return $roles;
    }

    // Obtiene una página por su ID
    public function getPageById($id)
    {
        $query = "SELECT * FROM paginas WHERE id = ?";
        $stmt = mysqli_prepare($this->link, $query);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $page = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $page;
    }

    // Crea una nueva página
    public function createPage($id_tipo_pagina, $nombre, $url_page)
    {
        $query = "INSERT INTO paginas (id_tipo_pagina, nombre, url_page) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->link, $query);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'iss', $id_tipo_pagina, $nombre, $url_page);
        $success = mysqli_stmt_execute($stmt);
        if ($success) {
            $id = mysqli_insert_id($this->link);
            mysqli_stmt_close($stmt);
            return $id;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }
    }

    // Elimina una página (física) de la tabla "paginas"
    public function deletePage($id)
    {
        $query = "DELETE FROM paginas WHERE id = ?";
        $stmt = mysqli_prepare($this->link, $query);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // ---------------------------
    // Funciones para la tabla "usuarios_paginas"
    // ---------------------------

    // Obtiene las páginas asignadas a un usuario específico
    public function getPagesForUser($usuario_id)
    {
        $query = "SELECT p.* 
                  FROM paginas p 
                  JOIN usuarios_paginas up ON p.id = up.pagina_id 
                  WHERE up.usuario_id = ?";
        $stmt = mysqli_prepare($this->link, $query);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'i', $usuario_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $pages = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $pages[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $pages;
    }

    // Crea la relación entre un usuario y una página (asigna acceso)
    public function createUserPage($usuario_id, $pagina_id, $rol_id = 2)
    {
        // Iniciar transacción
        mysqli_begin_transaction($this->link);

        try {
            // Insertar en usuarios_paginas
            $query = "INSERT INTO usuarios_paginas (usuario_id, pagina_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($this->link, $query);
            if (!$stmt) throw new Exception("Error preparing statement");
            
            mysqli_stmt_bind_param($stmt, 'ii', $usuario_id, $pagina_id);
            $success = mysqli_stmt_execute($stmt);
            if (!$success) throw new Exception("Error creating user page relation");
            
            $usuario_pagina_id = mysqli_insert_id($this->link);
            mysqli_stmt_close($stmt);

            // Insertar en usuarios_paginas_roles
            $query = "INSERT INTO usuarios_paginas_roles (usuario_pagina_id, rol_pagina_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($this->link, $query);
            if (!$stmt) throw new Exception("Error preparing statement");
            
            mysqli_stmt_bind_param($stmt, 'ii', $usuario_pagina_id, $rol_id);
            $success = mysqli_stmt_execute($stmt);
            if (!$success) throw new Exception("Error creating role relation");
            
            mysqli_stmt_close($stmt);
            
            // Confirmar transacción
            mysqli_commit($this->link);
            return $usuario_pagina_id;
        } catch (Exception $e) {
            mysqli_rollback($this->link);
            return false;
        }
    }

    // Obtener roles de página disponibles
    public function getPageRoles()
    {
        $query = "SELECT * FROM roles_pagina ORDER BY id";
        $result = mysqli_query($this->link, $query);
        $roles = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $roles[] = $row;
        }
        return $roles;
    }

    // Obtener rol de un usuario para una página específica
    public function getUserPageRole($usuario_id, $pagina_id)
    {
        $query = "SELECT rp.* 
                  FROM roles_pagina rp
                  JOIN usuarios_paginas_roles upr ON upr.rol_pagina_id = rp.id
                  JOIN usuarios_paginas up ON up.id = upr.usuario_pagina_id
                  WHERE up.usuario_id = ? AND up.pagina_id = ?";
        
        $stmt = mysqli_prepare($this->link, $query);
        if (!$stmt) return false;
        
        mysqli_stmt_bind_param($stmt, 'ii', $usuario_id, $pagina_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $role = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        return $role;
    }

    // Elimina la relación entre un usuario y una página (quita el acceso)
    public function deleteUserPage($usuario_id, $pagina_id)
    {
        // Primero verificamos si existe la relación
        $checkQuery = "SELECT id FROM usuarios_paginas WHERE usuario_id = ? AND pagina_id = ? LIMIT 1";
        $checkStmt = mysqli_prepare($this->link, $checkQuery);
        if (!$checkStmt) return false;

        mysqli_stmt_bind_param($checkStmt, 'ii', $usuario_id, $pagina_id);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);
        $exists = mysqli_stmt_num_rows($checkStmt) > 0;
        mysqli_stmt_close($checkStmt);

        // Si no existe la relación, consideramos que ya está "eliminada"
        if (!$exists) {
            return true;
        }

        // Si existe, procedemos a eliminarla
        $query = "DELETE FROM usuarios_paginas WHERE usuario_id = ? AND pagina_id = ?";
        $stmt = mysqli_prepare($this->link, $query);
        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, 'ii', $usuario_id, $pagina_id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $success;
    }

    /**
     * Obtiene todas las relaciones de usuarios por página.
     *
     * @return array|false Un arreglo asociativo en el que la clave es el ID de la página y el valor es un arreglo con la información de la página y la lista de usuarios, o false en caso de error.
     */
    public function getAllUserPageRelationships()
    {
        $query = "SELECT 
                    p.id AS pagina_id,
                    p.nombre AS pagina_nombre,
                    p.url_page,
                    u.id AS usuario_id,
                    u.usuario,
                    u.nombre AS usuario_nombre,
                    u.correo,
                    rp.nombre AS rol_nombre,
                    rp.id AS rol_id
                FROM paginas p
                JOIN usuarios_paginas up ON p.id = up.pagina_id
                JOIN usuarios u ON up.usuario_id = u.id
                JOIN usuarios_paginas_roles upr ON up.id = upr.usuario_pagina_id
                JOIN roles_pagina rp ON upr.rol_pagina_id = rp.id
                ORDER BY p.id, u.nombre";

        $result = mysqli_query($this->link, $query);
        if (!$result) {
            return false;
        }

        $relationships = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $pagina_id = $row['pagina_id'];
            if (!isset($relationships[$pagina_id])) {
                $relationships[$pagina_id] = [
                    'pagina_nombre' => $row['pagina_nombre'],
                    'url_page'      => $row['url_page'],
                    'usuarios'      => []
                ];
            }
            $relationships[$pagina_id]['usuarios'][] = [
                'usuario_id'   => $row['usuario_id'],
                'usuario'      => $row['usuario'],
                'nombre'       => $row['usuario_nombre'],
                'correo'       => $row['correo'],
                'rol_nombre'   => $row['rol_nombre'],
                'rol_id'       => $row['rol_id']
            ];
        }

        return $relationships;
    }

    public function getUserPageRelationships($id_page)
    {
        $query = "SELECT * FROM usuarios_paginas WHERE pagina_id = ?";
        
        $stmt = mysqli_prepare($this->link, $query);
        if (!$stmt) {
            return false;
        }
        
        mysqli_stmt_bind_param($stmt, 'i', $id_page);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $data;
    }
    
    /**
     * Crea accesos para un usuario a todas las páginas de un tipo específico
     * @param int $usuario_id ID del usuario
     * @param int $tipo_id ID del tipo de página
     * @param int $rol_id ID del rol (por defecto 2 - Lectura)
     * @return array Resultado de la operación con páginas procesadas y errores
     */
    public function createUserTipoPage($usuario_id, $tipo_id, $rol_id = 2)
    {
        // Iniciar transacción
        mysqli_begin_transaction($this->link);

        try {
            // Obtener todas las páginas del tipo especificado
            $query = "SELECT id FROM paginas WHERE id_tipo_pagina = ?";
            $stmt = mysqli_prepare($this->link, $query);
            if (!$stmt) throw new Exception("Error preparing statement");
            
            mysqli_stmt_bind_param($stmt, 'i', $tipo_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $resultados = [
                'success' => true,
                'paginas_procesadas' => 0,
                'errores' => []
            ];

            // Procesar cada página
            while ($row = mysqli_fetch_assoc($result)) {
                $pagina_id = $row['id'];
                
                // Verificar si ya existe la relación
                $checkQuery = "SELECT id FROM usuarios_paginas WHERE usuario_id = ? AND pagina_id = ?";
                $checkStmt = mysqli_prepare($this->link, $checkQuery);
                mysqli_stmt_bind_param($checkStmt, 'ii', $usuario_id, $pagina_id);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);
                
                // Si no existe la relación, crearla
                if (mysqli_stmt_num_rows($checkStmt) == 0) {
                    $created = $this->createUserPage($usuario_id, $pagina_id, $rol_id);
                    if ($created) {
                        $resultados['paginas_procesadas']++;
                    } else {
                        $resultados['errores'][] = "Error al crear acceso para la página ID: " . $pagina_id;
                        $resultados['success'] = false;
                    }
                }
                mysqli_stmt_close($checkStmt);
            }
            
            mysqli_stmt_close($stmt);
            
            // Si todo salió bien, confirmar la transacción
            if ($resultados['success']) {
                mysqli_commit($this->link);
            } else {
                mysqli_rollback($this->link);
            }
            
            return $resultados;
            
        } catch (Exception $e) {
            mysqli_rollback($this->link);
            return [
                'success' => false,
                'paginas_procesadas' => 0,
                'errores' => ['Error general: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Elimina todos los accesos de un usuario a las páginas de un tipo específico
     * @param int $usuario_id ID del usuario
     * @param int $tipo_id ID del tipo de página
     * @return array Resultado de la operación
     */
    public function deleteUserTipoPage($usuario_id, $tipo_id)
    {
        mysqli_begin_transaction($this->link);
        try {
            // Obtener todas las páginas del tipo especificado
            $query = "SELECT id FROM paginas WHERE id_tipo_pagina = ?";
            $stmt = mysqli_prepare($this->link, $query);
            if (!$stmt) throw new Exception("Error preparing statement");
            
            mysqli_stmt_bind_param($stmt, 'i', $tipo_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $resultados = [
                'success' => true,
                'paginas_procesadas' => 0,
                'errores' => []
            ];

            while ($row = mysqli_fetch_assoc($result)) {
                if (!$this->deleteUserPage($usuario_id, $row['id'])) {
                    $resultados['errores'][] = "Error al eliminar acceso para la página ID: " . $row['id'];
                    $resultados['success'] = false;
                } else {
                    $resultados['paginas_procesadas']++;
                }
            }
            
            mysqli_stmt_close($stmt);
            
            if ($resultados['success']) {
                mysqli_commit($this->link);
            } else {
                mysqli_rollback($this->link);
            }
            
            return $resultados;
            
        } catch (Exception $e) {
            mysqli_rollback($this->link);
            return [
                'success' => false,
                'paginas_procesadas' => 0,
                'errores' => ['Error general: ' . $e->getMessage()]
            ];
        }
    }
}
