<?php 

$server = "SCHMFI\\SQLEXPRESS";
// $server = "NATSUME\\SQLEXPRESS";
$database = "ASSET_MANAGEMENT_TRAINING";

try{


     $conn = new PDO(
        "sqlsrv:Server=$server;Database=$database;TrustServerCertificate=1"
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   

}catch(Exception $e)
{
     die("Connection failed: " . $e->getMessage());
}



