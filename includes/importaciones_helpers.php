<?php
// Funciones auxiliares para el sistema de importaciones

/**
 * Limpia y valida una lista de códigos
 * @param array $codigos Array de códigos a limpiar
 * @return array Array con códigos limpios y estadísticas
 */
function limpiarCodigos($codigos) {
    $resultado = [
        'codigos_limpios' => [],
        'codigos_duplicados' => [],
        'codigos_invalidos' => [],
        'estadisticas' => [
            'originales' => count($codigos),
            'limpios' => 0,
            'duplicados' => 0,
            'invalidos' => 0
        ]
    ];
    
    $codigos_vistos = [];
    
    foreach ($codigos as $codigo) {
        // Limpiar el código
        $codigo_limpio = trim($codigo);
        $codigo_limpio = preg_replace('/\s+/', ' ', $codigo_limpio); // Normalizar espacios
        
        // Validar que no esté vacío
        if (empty($codigo_limpio)) {
            $resultado['codigos_invalidos'][] = $codigo;
            $resultado['estadisticas']['invalidos']++;
            continue;
        }
        
        // Validar formato (puedes personalizar esta validación)
        if (strlen($codigo_limpio) > 50) { // Longitud máxima
            $resultado['codigos_invalidos'][] = $codigo;
            $resultado['estadisticas']['invalidos']++;
            continue;
        }
        
        // Verificar duplicados
        $codigo_key = strtoupper($codigo_limpio);
        if (isset($codigos_vistos[$codigo_key])) {
            $resultado['codigos_duplicados'][] = $codigo_limpio;
            $resultado['estadisticas']['duplicados']++;
            continue;
        }
        
        // Código válido
        $codigos_vistos[$codigo_key] = true;
        $resultado['codigos_limpios'][] = $codigo_limpio;
        $resultado['estadisticas']['limpios']++;
    }
    
    return $resultado;
}

/**
 * Registra una operación en el log
 * @param string $usuario Usuario que realiza la operación
 * @param string $operacion Descripción de la operación
 * @param array $datos Datos adicionales
 */
function registrarLog($usuario, $operacion, $datos = []) {
    $log_dir = __DIR__ . '/../logs';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $log_file = $log_dir . '/importaciones_' . date('Y-m') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    $log_entry = sprintf(
        "[%s] Usuario: %s | IP: %s | Operación: %s | Datos: %s\n",
        $timestamp,
        $usuario,
        $ip,
        $operacion,
        json_encode($datos, JSON_UNESCAPED_UNICODE)
    );
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

/**
 * Formatea un número para mostrar en la interfaz
 * @param int $numero
 * @return string
 */
function formatearNumero($numero) {
    return number_format($numero, 0, ',', '.');
}

/**
 * Sanitiza texto para mostrar en HTML
 * @param string $texto
 * @return string
 */
function sanitizarTexto($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

/**
 * Valida la estructura de la base de datos
 * @param PDO $pdo Conexión a la base de datos
 * @return array Resultado de la validación
 */
function validarEstructuraBD($pdo) {
    try {
        // Verificar que existe la vista
        $sql = "SELECT COUNT(*) as existe FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'vw_importaciones'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch();
        
        if ($resultado['existe'] == 0) {
            return [
                'success' => false,
                'message' => 'La vista vw_importaciones no existe en la base de datos'
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Estructura de base de datos válida'
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Error al validar estructura: ' . $e->getMessage()
        ];
    }
}
?>