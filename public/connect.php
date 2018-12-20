<?php
$serverName = "103.200.37.130, 8082";
$connectionInfo = ["Database"=>"sales_inventory", "UID"=>"osama", "PWD"=>"osama"];
$conn = sqlsrv_connect( $serverName, $connectionInfo);

 if($conn === false) {
     echo "Connection could not be established.\n";
     die( print_r( sqlsrv_errors(), true));
}

$sql = 'SELECT * FROM Board_Info';
$statement = sqlsrv_query($conn, $sql);


if($statement === false) {
    die( print_r( sqlsrv_errors(), true) );
}

$rows = sqlsrv_fetch_array($statement);


var_dump($rows);
?>


