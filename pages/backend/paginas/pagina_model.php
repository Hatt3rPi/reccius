<?php
// archivo: pages/backend/paginas/pagina_model.php
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
class PaginaModel {
    private $link;
    public function __construct($link) {
        global $link;
        $this->link = $link;
    }

    public function getPages() {
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

    // Obtiene una página por su ID
    public function getPageById($id) {
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
    public function createPage($id_tipo_pagina, $nombre, $url_page) {
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
    public function deletePage($id) {
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
    public function getPagesForUser($usuario_id) {
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
    public function createUserPage($usuario_id, $pagina_id) {
        $query = "INSERT INTO usuarios_paginas (usuario_id, pagina_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($this->link, $query);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'ii', $usuario_id, $pagina_id);
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

    // Elimina la relación entre un usuario y una página (quita el acceso)
    public function deleteUserPage($usuario_id, $pagina_id) {
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
    public function getAllUserPageRelationships() {
      // La consulta une las tres tablas: paginas, usuarios_paginas y usuarios
      $query = "SELECT 
                    p.id AS pagina_id,
                    p.nombre AS pagina_nombre,
                    p.url_page,
                    u.id AS usuario_id,
                    u.usuario,
                    u.nombre AS usuario_nombre,
                    u.correo
                FROM paginas p
                JOIN usuarios_paginas up ON p.id = up.pagina_id
                JOIN usuarios u ON up.usuario_id = u.id
                ORDER BY p.id, u.nombre";
      
      $result = mysqli_query($this->link, $query);
      if (!$result) {
          return false;
      }
      
      $relationships = [];
      while ($row = mysqli_fetch_assoc($result)) {
          $pagina_id = $row['pagina_id'];
          if (!isset($relationships[$pagina_id])) {
              // Inicializamos la entrada para esta página
              $relationships[$pagina_id] = [
                  'pagina_nombre' => $row['pagina_nombre'],
                  'url_page'       => $row['url_page'],
                  'usuarios'       => []
              ];
          }
          // Agregamos la información del usuario a la lista de usuarios para esta página
          $relationships[$pagina_id]['usuarios'][] = [
              'usuario_id'   => $row['usuario_id'],
              'usuario'      => $row['usuario'],
              'nombre'       => $row['usuario_nombre'],
              'correo'       => $row['correo']
          ];
      }
      
      return $relationships;
  }
}
?>
