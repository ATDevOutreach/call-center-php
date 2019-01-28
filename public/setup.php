<?php

$details = [];

function handle()
{
	global $details;

	$details = getDetails($_POST['sessionId']);

	if ($_POST['isActive'] == "0") {
		return callEnded();
	}

	$digits = $_POST['dtmfDigits'];

	error_log("DTMF: $digits");

	if ($details["language_selected"] == false) {
		switch ($digits) {
			case '1':
				return english();
				break;
			
			case '2':
				return pidgin();
				break;
			
			default:
				return base();
				break;
		}
	}

	switch ($digits) {
		case '1':
			return support();
			break;
		
		case '2':
			return sales();
			break;
		
		default:
			return unknownOption();
			break;
	}
}

function base()
{
	$text = "Welcome. Select your language. Press 1 for english, 2 for pidgin.";

	$response .= '<?xml version="1.0" encoding="UTF-8"?>
		<Response>
		  <GetDigits  timeout="10">
		    <Say voice="man" playBeep="true">'.$text.'</Say>
		  </GetDigits>
		</Response>';

	return $response;
}

function callEnded() {
	global $details;

	$details = array_merge($details, $_POST);
	updateDetails($details);
}

function unknownOption()
{
	$text = "You have selected an unknown option";
	if ($language == "pidgin") {
		$text = "We no understand the option wey you select.";
	}

	$response = '<?xml version="1.0" encoding="UTF-8"?>
		    <Say voice="man" playBeep="true">'.$text.'</Say>
		</Response>';

	return $response;
}

function english()
{
	global $details;

	$details['language_selected'] = true;
	$details['language'] = "english";
	updateDetails($details);

	$text = "You selected english, press 1 to speak to support, press 2 to speak to sales.";

	$response .= '<?xml version="1.0" encoding="UTF-8"?>
		<Response>
		  <GetDigits  timeout="10">
		    <Say voice="man" playBeep="true">'.$text.'</Say>
		  </GetDigits>
		</Response>';

	return $response;
}

function pidgin()
{
	global $details;

	$details['language_selected'] = true;
	$details['language'] = "pidgin";
	updateDetails($details);

	$text = "You don select pdigin, press 1 to follow support talk, press 2 for sales.";

	$response .= '<?xml version="1.0" encoding="UTF-8"?>
		<Response>
		  <GetDigits  timeout="10">
		    <Say voice="man" playBeep="true">'.$text.'</Say>
		  </GetDigits>
		</Response>';

	return $response;
}

function support()
{
	global $details;

	$language = $details["language"];

	$text = "Your call is being forwarded to a support agent. Note that this call may be recorded.";
	$phoneNumbers = $_ENV['SUPPORT_PHONES_ENG'];

	if ($language == "pidgin") {
		$text = "We dey connect you to one of our support people. Know say we dey record this call.";
		$phoneNumbers = $_ENV['SUPPORT_PHONES_PNG'];
	}

	$response .= '<?xml version="1.0" encoding="UTF-8"?>
		<Response>
		  <Say voice="man" playBeep="true">'.$text.'</Say>
		  <Dial phoneNumbers="'.$phoneNumbers.'" record="true" sequential="false"/>
		</Response>';

	return $response;
}

function sales()
{
	global $details;

	$language = $details["language"];

	$text = "Your call is being forwarded to a sales agent. Note that this call may be recorded.";
	$phoneNumbers = $_ENV['SALES_PHONES_ENG'];

	if ($language == "pidgin") {
		$text = "We dey connect you to one of our sales people. Know say we dey record this call.";
		$phoneNumbers = $_ENV['SALES_PHONES_PNG'];
	}

	$response .= '<?xml version="1.0" encoding="UTF-8"?>
		<Response>
		  <Say voice="man" playBeep="true">'.$text.'</Say>
		  <Dial phoneNumbers="'.$phoneNumbers.'" record="true" sequential="false"/>
		</Response>';

	return $response;
}

function getDetails($session)
{
	if (!file_exists('../data/'.$session.'.json')) {
		$file = fopen('../data/'.$session.'.json', "w");
		fwrite($file, "{}");
		fclose($file);
	}
	
	$details 	= json_decode(file_get_contents("../data/".$session.'.json'), true);;
	return $details;
}

function updateDetails($details)
{
	$session = $_POST['sessionId'];

	$file = fopen('../data/'.$session.'.json', "w");
	fwrite($file, json_encode($details));
	fclose($file);
}
