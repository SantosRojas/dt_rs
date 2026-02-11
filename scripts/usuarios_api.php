<?php
/**
 * API de Gestión de Usuarios
 * Maneja operaciones CRUD sobre la tabla Sdt_Usuario
 * Nota: La eliminación es lógica (estado=0), no se borran registros físicamente.
 */
session_start();
header('Content-Type: application/json; charset=utf-8');

// Verificar autenticación
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Verificar nivel de acceso (solo administradores)
if ($_SESSION['nivel'] != 'ADMIN') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tiene permisos para esta acción']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$conn = getDbConnection();
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

try {
    switch ($action) {
        case 'listar':
            listarUsuarios($conn);
            break;
        case 'obtener':
            obtenerUsuario($conn);
            break;
        case 'crear':
            crearUsuario($conn);
            break;
        case 'editar':
            editarUsuario($conn);
            break;
        case 'cambiar_estado':
            cambiarEstado($conn);
            break;
        case 'resetear_clave':
            resetearClave($conn);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}

/**
 * Lista todos los usuarios con formato para DataTables server-side
 */
function listarUsuarios($conn)
{
    // Parámetros de DataTables
    $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';

    // Columnas para ordenamiento
    $columns = ['usuario', 'nombres', 'apellidos', 'cargo', 'area', 'nivel', 'estado'];
    $orderColumn = isset($_GET['order'][0]['column']) ? intval($_GET['order'][0]['column']) : 0;
    $orderDir = isset($_GET['order'][0]['dir']) && $_GET['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';
    $orderBy = isset($columns[$orderColumn]) ? $columns[$orderColumn] : 'usuario';

    // Total de registros
    $stmtTotal = $conn->query("SELECT COUNT(*) as total FROM Sdt_Usuario");
    $totalRecords = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Construir consulta con filtro
    $where = '';
    $params = [];
    if (!empty($searchValue)) {
        $where = " WHERE usuario LIKE :search OR nombres LIKE :search2 OR apellidos LIKE :search3 OR cargo LIKE :search4 OR area LIKE :search5";
        $searchParam = "%$searchValue%";
        $params[':search'] = $searchParam;
        $params[':search2'] = $searchParam;
        $params[':search3'] = $searchParam;
        $params[':search4'] = $searchParam;
        $params[':search5'] = $searchParam;
    }

    // Total filtrado
    $stmtFiltered = $conn->prepare("SELECT COUNT(*) as total FROM Sdt_Usuario" . $where);
    foreach ($params as $key => $value) {
        $stmtFiltered->bindValue($key, $value);
    }
    $stmtFiltered->execute();
    $totalFiltered = $stmtFiltered->fetch(PDO::FETCH_ASSOC)['total'];

    // Consulta principal con paginación
    $sql = "SELECT usuario, nombres, apellidos, cargo, area, nivel, estado 
            FROM Sdt_Usuario" . $where . 
            " ORDER BY $orderBy $orderDir
            OFFSET :start ROWS FETCH NEXT :length ROWS ONLY";

    $stmt = $conn->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':length', $length, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mapear niveles para mostrar
    $niveles = [
        'ADMIN' => 'Administrador',
        'EDITOR' => 'Editor',
        'VISOR' => 'Visor'
    ];

    foreach ($data as &$row) {
        $row['nivel_texto'] = isset($niveles[$row['nivel']]) ? $niveles[$row['nivel']] : $row['nivel'];
        $row['estado_texto'] = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
    }

    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => intval($totalRecords),
        'recordsFiltered' => intval($totalFiltered),
        'data' => $data
    ]);
}

/**
 * Obtiene un usuario específico
 */
function obtenerUsuario($conn)
{
    $usuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';

    if (empty($usuario)) {
        echo json_encode(['success' => false, 'message' => 'Usuario no especificado']);
        return;
    }

    $stmt = $conn->prepare("SELECT usuario, nombres, apellidos, cargo, area, nivel, estado FROM Sdt_Usuario WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode(['success' => true, 'data' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }
}

/**
 * Crea un nuevo usuario
 */
function crearUsuario($conn)
{
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $clave = isset($_POST['clave']) ? trim($_POST['clave']) : '';
    $nombres = isset($_POST['nombres']) ? trim($_POST['nombres']) : '';
    $apellidos = isset($_POST['apellidos']) ? trim($_POST['apellidos']) : '';
    $cargo = isset($_POST['cargo']) ? trim($_POST['cargo']) : '';
    $area = isset($_POST['area']) ? trim($_POST['area']) : '';
    $nivel = isset($_POST['nivel']) ? trim($_POST['nivel']) : 'VISOR';
    $estado = isset($_POST['estado']) ? intval($_POST['estado']) : 1;

    // Validaciones
    if (empty($usuario) || empty($clave) || empty($nombres) || empty($apellidos)) {
        echo json_encode(['success' => false, 'message' => 'Los campos usuario, clave, nombres y apellidos son obligatorios']);
        return;
    }

    // Verificar si el usuario ya existe
    $stmtCheck = $conn->prepare("SELECT COUNT(*) as total FROM Sdt_Usuario WHERE usuario = :usuario");
    $stmtCheck->bindParam(':usuario', $usuario);
    $stmtCheck->execute();
    $exists = $stmtCheck->fetch(PDO::FETCH_ASSOC)['total'];

    if ($exists > 0) {
        echo json_encode(['success' => false, 'message' => 'El usuario ya existe']);
        return;
    }

    $sql = "INSERT INTO Sdt_Usuario (usuario, clave, nombres, apellidos, cargo, area, nivel, estado) 
            VALUES (:usuario, :clave, :nombres, :apellidos, :cargo, :area, :nivel, :estado)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':clave', $clave);
    $stmt->bindParam(':nombres', $nombres);
    $stmt->bindParam(':apellidos', $apellidos);
    $stmt->bindParam(':cargo', $cargo);
    $stmt->bindParam(':area', $area);
    $stmt->bindParam(':nivel', $nivel, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente']);
}

/**
 * Edita un usuario existente
 */
function editarUsuario($conn)
{
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $nombres = isset($_POST['nombres']) ? trim($_POST['nombres']) : '';
    $apellidos = isset($_POST['apellidos']) ? trim($_POST['apellidos']) : '';
    $cargo = isset($_POST['cargo']) ? trim($_POST['cargo']) : '';
    $area = isset($_POST['area']) ? trim($_POST['area']) : '';
    $nivel = isset($_POST['nivel']) ? trim($_POST['nivel']) : 'VISOR';
    $estado = isset($_POST['estado']) ? intval($_POST['estado']) : 1;

    if (empty($usuario) || empty($nombres) || empty($apellidos)) {
        echo json_encode(['success' => false, 'message' => 'Los campos usuario, nombres y apellidos son obligatorios']);
        return;
    }

    $sql = "UPDATE Sdt_Usuario SET nombres = :nombres, apellidos = :apellidos, cargo = :cargo, 
            area = :area, nivel = :nivel, estado = :estado WHERE usuario = :usuario";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombres', $nombres);
    $stmt->bindParam(':apellidos', $apellidos);
    $stmt->bindParam(':cargo', $cargo);
    $stmt->bindParam(':area', $area);
    $stmt->bindParam(':nivel', $nivel, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
}

/**
 * Cambia el estado de un usuario (activar/desactivar)
 */
function cambiarEstado($conn)
{
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $estado = isset($_POST['estado']) ? intval($_POST['estado']) : 0;

    if (empty($usuario)) {
        echo json_encode(['success' => false, 'message' => 'Usuario no especificado']);
        return;
    }

    // Evitar que el admin se desactive a sí mismo
    if ($usuario === $_SESSION['usuario'] && $estado == 0) {
        echo json_encode(['success' => false, 'message' => 'No puede desactivar su propia cuenta']);
        return;
    }

    $stmt = $conn->prepare("UPDATE Sdt_Usuario SET estado = :estado WHERE usuario = :usuario");
    $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    $mensaje = $estado == 1 ? 'Usuario activado exitosamente' : 'Usuario eliminado (desactivado) exitosamente';
    echo json_encode(['success' => true, 'message' => $mensaje]);
}


/**
 * Resetea la clave de un usuario
 */
function resetearClave($conn)
{
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $nuevaClave = isset($_POST['nueva_clave']) ? trim($_POST['nueva_clave']) : '';

    if (empty($usuario) || empty($nuevaClave)) {
        echo json_encode(['success' => false, 'message' => 'Usuario y nueva clave son obligatorios']);
        return;
    }

    $stmt = $conn->prepare("UPDATE Sdt_Usuario SET clave = :clave WHERE usuario = :usuario");
    $stmt->bindParam(':clave', $nuevaClave);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Clave reseteada exitosamente']);
}
?>
