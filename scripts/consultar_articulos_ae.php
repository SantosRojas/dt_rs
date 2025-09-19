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
$table = "Sdt_Articulos_AE";
// Table's primary key
$primaryKey = "ArtID_AE";
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier - in this case object
// parameter names
$columns = [
    [
        "db" => "ArtID_AE",
        "dt" => "DT_RowId",
        "formatter" => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return "ID_" . $d;
        },
    ],
    ["db" => "ArtCodigo_AE", "dt" => "ArtCodigo"],
    ["db" => "ArtDescripcion_AE", "dt" => "ArtDescripcion"],
    // ["db" => "ArtTipo", "dt" => "ArtTipo"],
];
$sql_details = [
    "user" => "sa_bbmpe",
    "pass" => "ItPeru22$#",
    "db" => "DP_BBRAUN_SAP",
    "host" => "pe01-wsqlprd01.bbmag.bbraun.com",
];
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
require "ssp.class.php";
echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
