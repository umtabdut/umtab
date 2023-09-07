<?php
	include_once 'functions/texts-function.php';
	
	error_reporting(0);
	if(!isset($_GET['json']))
	{
		header('Content-Type: application/json');
	}
	

	//Just for the purpose of utilizing randon_color() function
	include_once 'functions/general-function.php';
	$background = '#'.randon_color();
	
	$aInWords = new AmountInWords;
	
	function span($t,$color=''){
		return isset($_GET['json']) ? $t : '<span style="font-size: 2em; display: block; margin-bottom: .4em;'.(!empty($color) ? 'color: '. $color.';' : '') .'">'.$t.'</span>';}

	$number = preg_replace('/,/', '', $_GET['number']);

	if(empty($number))
	{
		$text = span('Empty!') .' Enter a number!';
	}
	else{
		if(!is_numeric($number)){
			$number='';
			$background = 'rgba(255, 0, 0, .4)';
			$text = span('Erorr!', 'rgba(255, 0, 0, .9)') .'<span class="bld">'.$_GET['number'] .'</span> is NOT real number!';
		}
		else
		{
			$text = $aInWords->generate(number_format($number, 2));
			$number = span(number_format($number, 2));
		}
	}

	//OUTPUTS
	if(!isset($_GET['json']))
	{
		$output['background'] = $background;
	}
	$output['text'] = $text;
	$output['number'] =  $number;

	echo json_encode($output);