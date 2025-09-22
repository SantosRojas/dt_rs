<?php
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See https://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - https://datatables.net/license_mit
 */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
// DB table to use
$table = "vw_rs_ae";
// Table's primary key
$primaryKey = "RegID_AE";
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier - in this case object
// parameter names
$columns = [
    [
        "db" => "RegID_AE",
        "dt" => "DT_RowId",
        "formatter" => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return "ID_" . $d;
        },
    ],
    ["db" => "ArtID_AE", "dt" => "ArtID_AE"],
    ["db" => "ArtCodigo_AE", "dt" => "ArtCodigo_AE"],
    ["db" => "ArtDescripcion_AE", "dt" => "ArtDescripcion_AE"],
    ["db" => "RegNumero_AE", "dt" => "RegNumero_AE"],
    ["db" => "RegResolucion_AE", "dt" => "RegResolucion_AE"],
    [
        "db" => "RegFechaEmision_AE",
        "dt" => "RegFechaEmision_AE",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    [
        "db" => "RegFechaAprobacion_AE",
        "dt" => "RegFechaAprobacion_AE",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    [
        "db" => "RegFechaVencimiento_AE",
        "dt" => "RegFechaVencimiento_AE",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    [
        "db" => "RegFechaCreacion_AE",
        "dt" => "RegFechaCreacion_AE",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    [
        "db" => "RegFechaModificacion_AE",
        "dt" => "RegFechaModificacion_AE",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    [
        "db" => "FechaCreacion_AE",
        "dt" => "FechaCreacion_AE",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    [
        "db" => "FechaModificacion_AE",
        "dt" => "FechaModificacion_AE",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    ["db" => "RegUsuarioCreacion_AE", "dt" => "RegUsuarioCreacion_AE"],
    ["db" => "RegUsuarioModificacion_AE", "dt" => "RegUsuarioModificacion_AE"],
    ["db" => "UsuarioCreacion_AE", "dt" => "UsuarioCreacion_AE"],
    ["db" => "UsuarioModificacion_AE", "dt" => "UsuarioModificacion_AE"],
    ["db" => "RegEstado_AE", "dt" => "RegEstado_AE"],
    ["db" => "ArtFabricante_AE", "dt" => "ArtFabricante_AE"],
    ["db" => "ArtPaisOrigen_AE", "dt" => "ArtPaisOrigen_AE"],
    ["db" => "ArtLugarFabricacion_AE", "dt" => "ArtLugarFabricacion_AE"],
    ["db" => "NivelRiesgoPeru_AE", "dt" => "NivelRiesgoPeru_AE"],
    ["db" => "ExoneracionCM_AE", "dt" => "ExoneracionCM_AE"],
    ["db" => "CodigoEAN_13", "dt" => "CodigoEAN_13"],
    ["db" => "CodigoEAN_14", "dt" => "CodigoEAN_14"],
    ["db" => "CodigoGTIN", "dt" => "CodigoGTIN"],
    ["db" => "Codigo_GMDN_UMDNS","dt" => "Codigo_GMDN_UMDNS"],
    ["db" => "Etiqueta_AE", "dt" => "Etiqueta_AE"],
    ["db" => "EsEsteril_AE", "dt" => "EsEsteril_AE"],
    ["db" => "NumeroIFU_AE", "dt" => "NumeroIFU_AE"],
    ["db" => "Cambios_AE", "dt" => "Cambios_AE"],
    ["db" => "ProblemaDimensiones_AE", "dt" => "ProblemaDimensiones_AE"],
    ["db" => "RegObservacion_AE", "dt" => "RegObservacion_AE"],
    ["db" => "Rutas", "dt" => "Rutas"],
    //agregar CAMBIOS_AE
];
$sql_details = [
    "user" => "sa_bbmpe",
    "pass" => "ItPeru22$#",
    "db" => "DP_BBRAUN_SAP",
    "host" => "pe01-wsqlprd01.bbmag.bbraun.com",
];
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$filter2 = isset($_GET['filter2']) ? $_GET['filter2'] : '';

if ($filter == 'VENCIDO') {
    $where = "RegEstado_AE ='VENCIDO'";
} elseif ($filter == 'PRORROGADO') {
    $where = "RegEstado_AE ='PRORROGADO'";
} elseif ($filter == 'VENCE_3_MESES') {
    $where = "RegEstado_AE ='VENCE 3 MESES'";
} elseif ($filter == 'VENCE_2_MESES') {
    $where = "RegEstado_AE ='VENCE 2 MESES'";
} elseif ($filter == 'VENCE_1_MES') {
    $where = "RegEstado_AE ='VENCE 1 MES'";
} else {
    $where = "";
}
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
require "ssp.class.php";
echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null,$where)
);
