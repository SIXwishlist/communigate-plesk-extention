<?php 

include 'CLI.php';


$host = "77.77.150.135";
$port = 11106;
$login = "postmaster";
$password = "jR6]FKhi";
$cli = new Modules_Example_CLI_CLI();
$cli->setDebug(1);
$cli->Login($host,$port,$login,$password);


// // var_dump($rules);

// // var_dump($accounts);

function recursive_array_search($needle,$haystack) {
	foreach($haystack as $key=>$value) {
		$current_key=$key;
		if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
			return $current_key;
		}
	}
	return false;
}

// $vacationMessages = array();
// $subjectsFromVacationMessages = array();

function setVactionMessages($cli)
{
	global $vacationMessages;

	$domain = "test.bg";
	$accounts = array_keys($cli->ListAccounts($domain));
	foreach ($accounts as $account) {
		$rules =$cli->GetAccountRules("$account@$domain");
		if (isset($rules[recursive_array_search('#Vacation', $rules)])) {
			$vacationMessages[$account] = $rules[recursive_array_search('#Vacation', $rules)][3][0][1] . '\eEndDate: ' . 
			$rules[recursive_array_search('#Vacation', $rules)][2][0][2];
		}
	}

}

// // function getSubjectsFromVacationMessages($cli)
// // {
// // 	setVactionMessages($cli);
// // 	global $vacationMessages;
// // 	global $subjectsFromVacationMessages;
// // 	foreach ($vacationMessages as $account => $message) {
// // 		$components = explode("\e", $message);
// // 		if (substr($components[0], 0, 8 ) !== '+Subject') {
// // 			$subjectsFromVacationMessages[$account] = '';
// // 		} else {
// // 		 $subjectsFromVacationMessages[$account] = substr($components[0], strpos($components[0], " ") + 1);	
// // 		}

// // 	}
// // }



setVactionMessages($cli);
var_dump($vacationMessages);

foreach ($vacationMessages as $message) {
	$test = explode("\e\e", $message);
}
foreach ($test as $t) {
	if (substr($t, 1, 7) == 'Subject') {
		$subjectFrom = explode("\e", $t);
	} else{
		$bodyDate= explode("\e", $t);
		foreach ($bodyDate as $key) {
			if (substr($key, 0, 7) == 'EndDate') {
			$answer['endDate'] = str_replace("EndDate: ","", $key);
		} else {
			var_dump($key);
		}

		}

	}
}
var_dump($bodyDate);
// foreach ($test as $t) {
// 		// var_dump(substr($t, 0, 4));
// 	if (substr($t, 1, 7) == 'Subject') {
// 		$answer['Subject'] = str_replace("+Subject: ","", $t);
// 		var_dump('Subject found');
// 	}
// 	if (substr($t, 0, 4) == 'From') {
// 		$answer['From'] = str_replace("From: ","", $t);
// 		var_dump('From found');
// 	}
// 	if (substr($t, 0, 7) == 'EndDate') {
// 		$answer['endDate'] = str_replace("EndDate: ","", $t);
// 	}
// 	if ($t !== '' && substr($t, 0, 4) !== 'From' && substr($t, 1, 7) !== 'Subject' && substr($t, 0, 7) !== 'EndDate') {
// 		$answer['Body'] = $t;
// 	}
// }
// var_dump($answer);

// // var_dump($vacationMessages);

// function recursive_array_search($needle,$haystack) {
// 	foreach($haystack as $key=>$value) {
// 		$current_key=$key;
// 		if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
// 			return $current_key;
// 		}
// 	}
// 	return false;
// }



// function addAutoResponder($cli, $account, $subject, $from, $body)
// {
// 	$updateVacationMessage = sprintf("UPDATEACCOUNTMAILRULE %s".
// 		'( 2, "#Vacation", (("Current Date","greater than","11 Dec 2013 23:24:00"), ("Human Generated", "---"), (From, "not in", "#RepliedAddresses")), ( ("Reply with",'.
// 			' "+Subject: %s\eFrom: %s\e\e%s" ' 
// 			. '), ("Remember \'From\' in", RepliedAddresses) ) )',
// 	$account, $subject, $from, $body);

// 	$cli->SendCommand($updateVacationMessage);
// 	return $cli->parseWords($cli->getWords());

// }

// addAutoResponder($cli, 'gosho@test.bg', 'Re: ^S', 'Todor Nikolov', 'I\'m on vaction, sorry');


// $cli->SendCommand('UPDATEACCOUNTMAILRULE ivan@test.com (1,"#Redirect",(),(("Redirect to",svetoslav.kuzmanov@gmail.com),(Discard,"---")))');
// // $cli->SetAccountRules("drago@test.bg", array(
// // 	array("Redirect to", "svetoslav.kuzmanov@abv.bg"),
// // 	// array("Discard", '---')

// // $cli->GetAccountRights('postmaster@plesk.icncloud.net')
// $value = "ivan@abv.bg";
// // var_dump();
// $whatIWant = substr($value, strpos($value, "@"));
// echo str_replace($whatIWant, "", $value);
// // echo $whatIWant;



// $accounts = array_keys($cli->ListAccounts("test.bg"));
// $accounts = array_combine($accounts, $accounts);
// var_dump($accounts)

$body = "ji\r\nji ji ji ji ij";
$bodyWithRemovedNewLines = preg_replace("/(\r?\n){2}/",'\\e',trim($body));
$body = str_replace("\r\n", '\\e', $body);
echo $body;

// ));
// $rules = $cli->GetAccountRules('ivan@abc.bg');
// print_r($rules);
?>