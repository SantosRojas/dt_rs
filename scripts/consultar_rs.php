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
$table = "vw_rs";
// Table's primary key
$primaryKey = "RegID";
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier - in this case object
// parameter names
$columns = [
    [
        "db" => "RegID",
        "dt" => "DT_RowId",
        "formatter" => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return "ID_" . $d;
        },
    ],
    ["db" => "ArtID", "dt" => "ArtID"],
    ["db" => "ArtCodigo", "dt" => "ArtCodigo"],
    ["db" => "ArtDescripcion", "dt" => "ArtDescripcion"],
    ["db" => "ArtTipo", "dt" => "ArtTipo"],
    ["db" => "RegNumero", "dt" => "RegNumero"],
    ["db" => "RegResolucion", "dt" => "RegResolucion"],
    [
        "db" => "RegFechaEmision",
        "dt" => "RegFechaEmision",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    [
        "db" => "RegFechaAprobacion",
        "dt" => "RegFechaAprobacion",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    [
        "db" => "RegFechaVencimiento",
        "dt" => "RegFechaVencimiento",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    ["db" => "RegEstadoVencimiento", "dt" => "RegEstadoVencimiento"],
    [
        "db" => "RegFechaCreacion",
        "dt" => "RegFechaCreacion",
        "formatter" => function ($d, $row) {
            return date("d/m/Y", strtotime($d));
        },
    ],
    ["db" => "RegUsuarioCreacion", "dt" => "RegUsuarioCreacion"],
    [
        "db" => "RegFechaModificacion",
        "dt" => "RegFechaModificacion",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y", strtotime($d));
            }
        },
    ],
    ["db" => "RegUsuarioModificacion", "dt" => "RegUsuarioModificacion"],
    ["db" => "ArtCodigoGMDN", "dt" => "ArtCodigoGMDN"],
    ["db" => "ArtCodigoUMDNS", "dt" => "ArtCodigoUMDNS"],
    ["db" => "ArtProyectoRAAE", "dt" => "ArtProyectoRAAE"],
    ["db" => "ArtProyectoRAAE2", "dt" => "ArtProyectoRAAE2"],
    ["db" => "RegObservaciones", "dt" => "RegObservaciones"],
    ["db" => "ArtFabricante", "dt" => "ArtFabricante"],
    ["db" => "ArtOrigen", "dt" => "ArtOrigen"],
    ["db" => "ArtLugarFabricacion", "dt" => "ArtLugarFabricacion"],
    ["db" => "ArtFormaFarma", "dt" => "ArtFormaFarma"],
    ["db" => "ArtConcentracion", "dt" => "ArtConcentracion"],
    ["db" => "ArtPresentacion", "dt" => "ArtPresentacion"],
    ["db" => "ArtNivelRiesgo", "dt" => "ArtNivelRiesgo"],
    ["db" => "ArtMaterialPCB", "dt" => "ArtMaterialPCB"],
    ["db" => "ArtComercializado", "dt" => "ArtComercializado"],
    ["db" => "ArtLabelAlemania", "dt" => "ArtLabelAlemania"],
    ["db" => "ArtExoneracionCM", "dt" => "ArtExoneracionCM"],
    ["db" => "ArtBarraUn", "dt" => "ArtBarraUn"],
    ["db" => "ArtBarraCj", "dt" => "ArtBarraCj"],
    ["db" => "ArtEAN13Un", "dt" => "ArtEAN13Un"],
    ["db" => "ArtEAN14Cj", "dt" => "ArtEAN14Cj"],
    ["db" => "ArtGTINUn", "dt" => "ArtGTINUn"],
    ["db" => "ArtGTINCj", "dt" => "ArtGTINCj"],
    ["db" => "ArtProyecto", "dt" => "ArtProyecto"],
    ["db" => "ArtProveedor", "dt" => "ArtProveedor"],
    ["db" => "ArtProveedorDes", "dt" => "ArtProveedorDes"],
    [
        "db" => "ArtFechaCreacion",
        "dt" => "ArtFechaCreacion",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y H:i", strtotime($d));
            }
        },
    ],
	["db" => "ArtUsuarioCreacion", "dt" => "ArtUsuarioCreacion"],
    [
        "db" => "ArtFechaModificacion",
        "dt" => "ArtFechaModificacion",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y H:i", strtotime($d));
            }
        },
    ],
	["db" => "ArtUsuarioModificacion", "dt" => "ArtUsuarioModificacion"],
    [
        "db" => "RegFechaCreacion",
        "dt" => "RegFechaCreacion",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y H:i", strtotime($d));
            }
        },
    ],
	["db" => "RegUsuarioCreacion", "dt" => "RegUsuarioCreacion"],
    [
        "db" => "RegFechaModificacion",
        "dt" => "RegFechaModificacion",
        "formatter" => function ($d, $row) {
            if($d=="" || $d==null){
                return "";
            } else {
                return date("d/m/Y H:i", strtotime($d));
            }
        },
    ],
	["db" => "RegUsuarioModificacion", "dt" => "RegUsuarioModificacion"],	
    ["db" => "Rutas", "dt" => "Rutas"],
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
    $where = "ArtTipo='$filter2' AND RegEstadoVencimiento ='VENCIDO'";
} elseif ($filter == 'PRORROGADO') {
    $where = "ArtTipo='$filter2' AND RegEstadoVencimiento ='PRORROGADO'";
} elseif ($filter == 'VENCE_3_MESES') {
    $where = "ArtTipo='$filter2' AND RegEstadoVencimiento ='VENCE 3 MESES'";
} elseif ($filter == 'VENCE_2_MESES') {
    $where = "ArtTipo='$filter2' AND RegEstadoVencimiento ='VENCE 2 MESES'";
} elseif ($filter == 'VENCE_1_MES') {
    $where = "ArtTipo='$filter2' AND RegEstadoVencimiento ='VENCE 1 MES'";
} else {
    $where = "ArtTipo='$filter2'";
}
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
require "ssp.class.php";
echo json_encode(
    SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $where)
);
