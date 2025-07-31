# Documentación del Backend

## Como trabajar con modelos

Los modelos en nuestro backend siguen una estructura orientada a objetos que encapsula la lógica de acceso a datos. Cada modelo sigue estos principios:

### 1. Estructura Básica

- Cada modelo es una clase PHP que representa una tabla en la base de datos
- El constructor establece la conexión a la base de datos
- Los métodos representan operaciones específicas (CRUD)

### 2. Operaciones Comunes

- **Find**: Métodos para buscar registros (por ID, nombre, etc.), esta puede tener variantes como findAll.
- **Create**: Métodos para crear nuevos registros
- **Update**: Métodos para actualizar registros existentes
- **Delete**: Métodos para eliminar registros

### 3. Buenas Prácticas

- Usar consultas preparadas para prevenir SQL injection
- Manejar errores y excepciones apropiadamente
- Mantener métodos específicos para relaciones entre tablas

### 4. Ejemplo de Estructura Base

```php
class ModeloEjemplo {
    private $conn;

    public function __construct() {
        global $link;
        $this->conn = $link;
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tabla WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
```

## Ejemplo Modelo Laboratorio

Este documento explica el funcionamiento del modelo `Laboratorio` ubicado en `/pages/backend/otros/laboratorio.php`.

### Descripción General

La clase `Laboratorio` maneja todas las operaciones relacionadas con laboratorios en la base de datos.

### Métodos Principales

#### Operaciones Básicas

- `findByName($name)`: Busca un laboratorio por su nombre
- `create($name)`: Crea un nuevo laboratorio
- `findAll()`: Obtiene todos los laboratorios
- `updateCorreo($name, $correo)`: Actualiza el correo de un laboratorio

#### Operaciones con Copias (CC)

- `findCCByCorreoAndLab($correo, $idLab)`: Busca copias asociadas a un laboratorio
- `addCorreoToLaboratorioWithName($laboratorioId, $name, $correo)`: Agrega un correo CC a un laboratorio
- `getCorreosByLaboratorioName($name)`: Obtiene todos los correos CC de un laboratorio
- `deleteCorreosCCByCorreo($correo, $idLab)`: Elimina un correo CC

### Ejemplo de Uso

```php
$laboratorio = new Laboratorio();

// Crear o encontrar un laboratorio
$lab = $laboratorio->findOrCreateByName("Laboratorio Ejemplo");

// Agregar un correo CC
$laboratorio->addCorreoToLaboratorioWithName($lab['id'], $lab['name'], "correo@ejemplo.com");

// Obtener todos los correos CC
$correos = $laboratorio->getCorreosByLaboratorioName("Laboratorio Ejemplo");
```
