<?php 

include 'CLI.php';


$host = "77.77.150.135";
$port = 11106;
$login = "postmaster";
$password = "jR6]FKhi";
$cli = new Modules_Example_CLI_CLI();
$cli->setDebug(1);
$cli->Login($host,$port,$login,$password);

$info = $cli->GetAccountRules('pesho@abc.bg');
var_dump($info);

// $rules = 
// 	array(
// 		'5', // priority
// 		'nameee', // name of the rule
// 		array(
// 			array( // array with rule settings
// 				'Return-Path', //data
// 				'is', //operation
// 				'param' //parameter
// 				),
// 			),
// 		array(
// 			array( //array with actions of the rule
// 				'Mark', //action
// 				'read' //parameter
// 				),
// 			),
		
// 	);
// array_push($info, $rules);
// var_dump($info);
// $cli->SetAccountRules('pesho@abc.bg', $info);
// // function startsWith($haystack, $needle)
// {
//     return $needle === "" || strpos($haystack, $needle) === 0;
// }

// var_dump(startsWith('dataFilter' , 'dataFilter'));

?>