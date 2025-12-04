<?php
// Configuración para el sistema de importaciones
// Este archivo contiene las configuraciones para las búsquedas de códigos

// Cargar configuración de base de datos desde database.php
require_once __DIR__ . '/database.php';

return [
    // Configuración de base de datos - usando variables del .env
    'database' => getDbConfigSSP(),

    // Configuración de búsquedas
    // Puedes agregar múltiples tipos de búsqueda aquí
    'consultas' => [
        // Búsqueda principal en vista de importaciones
        'importaciones' => [
            'nombre' => 'Búsqueda en Vista de Importaciones',
            'descripcion' => 'Busca códigos en la vista vw_importaciones',
            'sql' => "SELECT * FROM vw_importaciones WHERE [primera_columna_codigo] = ?",
            'parametros' => ['codigo'], // Solo necesitamos el código exacto
            'campos_resultado' => [
                'codigo_articulo' => 'primera_columna_codigo', // Ajustar según el nombre real de la columna
                'descripcion' => 'descripcion_columna',        // Ajustar según el nombre real de la columna
                'tipo' => 'tipo_columna',                      // Ajustar según el nombre real de la columna
                'informacion_adicional' => 'info_adicional'   // Ajustar según el nombre real de la columna
            ]
        ],

        // Búsqueda alternativa en artículos (como respaldo)
        'articulos' => [
            'nombre' => 'Búsqueda en Artículos',
            'descripcion' => 'Busca códigos en la tabla de artículos',
            'sql' => "SELECT TOP 1 
                        ArtCodigo, 
                        ArtDescripcion, 
                        ArtTipo,
                        'Artículo encontrado' as informacion_adicional
                      FROM Sdt_Articulos 
                      WHERE ArtCodigo = ? 
                         OR ArtDescripcion LIKE ?",
            'parametros' => ['codigo', '%codigo%'], // Cómo se pasan los parámetros
            'campos_resultado' => [
                'codigo_articulo' => 'ArtCodigo',
                'descripcion' => 'ArtDescripcion',
                'tipo' => 'ArtTipo',
                'informacion_adicional' => 'informacion_adicional'
            ]
        ],

        // Puedes agregar más consultas aquí, por ejemplo:
        /*
        'productos' => [
            'nombre' => 'Búsqueda en Productos',
            'descripcion' => 'Busca códigos en la tabla de productos',
            'sql' => "SELECT TOP 1 
                        ProdCodigo, 
                        ProdNombre, 
                        ProdCategoria,
                        'Producto encontrado' as informacion_adicional
                      FROM Sdt_Productos 
                      WHERE ProdCodigo = ? 
                         OR ProdNombre LIKE ?",
            'parametros' => ['codigo', '%codigo%'],
            'campos_resultado' => [
                'codigo_articulo' => 'ProdCodigo',
                'descripcion' => 'ProdNombre',
                'tipo' => 'ProdCategoria',
                'informacion_adicional' => 'informacion_adicional'
            ]
        ],
        */
    ],

    // Configuración de la interfaz
    'interfaz' => [
        'max_codigos_por_proceso' => 1000, // Máximo número de códigos a procesar de una vez
        'timeout_consulta' => 30, // Timeout en segundos para cada consulta
        'mostrar_detalles_error' => true, // Mostrar detalles de errores en desarrollo
    ],

    // Configuración de logs
    'logs' => [
        'habilitar' => true,
        'archivo' => 'logs/importaciones.log',
        'nivel' => 'INFO' // DEBUG, INFO, WARNING, ERROR
    ]
];
?>