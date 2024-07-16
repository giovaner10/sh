<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'cad_contas';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'fornecedor', 'dt' => 1 ),
    array( 'db' => 'descricao', 'dt' => 2 ),
    array( 'db' => 'data_vencimento', 'dt' => 3 ),
    array( 'db' => 'valor', 'dt' => 4 ),
    array( 'db' => 'status', 'dt' => 5 ),
    array( 'db' => 'arquivo', 'dt' => 6 ),
    array( 'db' => 'updated', 'dt' => 7 ),
    array( 'db' => 'data_pagamento', 'dt' => 8 ),
    array( 'db' => 'dh_lancamento', 'dt' => 9 ),
    array( 'db' => 'empresa', 'dt' => 10 )

);

// SQL server connection information
$sql_details = array(
    'user' => 'dev_andre',
    'pass' => 'showtiandre',
    'db'   => 'showtecsystem',
    'host' => '192.99.106.11'
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);