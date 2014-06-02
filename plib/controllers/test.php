<?php 

include 'CLI.php';


$host = "77.77.150.135";
$port = 11106;
$login = "postmaster";
$password = "jR6]FKhi";
$cli = new Modules_Example_CLI_CLI();
$cli->setDebug(1);
$cli->Login($host,$port,$login,$password);



?>