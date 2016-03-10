<?php
//var_dump(common::generateRandomNumber());
//var_dump(character::generateAccountPersonalNumber());
/*$db = new db();
$char = $db->getCharacterDetails('Tescikowo');
var_dump($char);

$skills = str_split($char['MagicList'], 6);
var_dump($skills);

foreach($skills as $key => $skill) {
	if (false === strpos($skill, '0000')) {
		$skills[$key] = 'FF0000';
	}
}

var_dump($skills);

$newSkills = implode('', $skills);
//var_dump($db->updateCharacterMagicList('Tescikowo', $newSkills));*/
$payments = new payment();
echo $payments->getPaymentWallWidget('widget', 'pablo', 'test@example.com', '482394278942');

?>