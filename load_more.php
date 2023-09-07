<?php
header("Content-Type: application/json");

$c = $_GET['c'];

$otp['name'] = 'Umar';
$otp['age'] = 23;
$otp['city'] = 'Dutse';

foreach ($_GET as $key => $value) {
	$otp['sentData'] = [$key, $value];
}

$html = '<hr class="mg-tb">';

for ($i=$c; $i <= ($c + 5); $i++) { 
	
	$html .= '
	<div class="bs bd bd-rad">
		<h1>More Data - '. $_GET['addMore'].' i:('.$i.') c:('.$c.')</h1>
		<p>
			More Data..More Data..More Data..More Data..More Data..More Data..More Data..More Data..

			More Data..More Data..More Data..More Data..
		</p>
	</div>';
	
}

$otp['html'] = $html;

//$otp = '{"name": "Umar", "age": "9", "content": '.json_encode($html).'}';

echo json_encode($otp);

//echo $otp;