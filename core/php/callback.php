<?php
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
include_file('core', 'authentification', 'php');
$State = explode("|",init('state'));
$Element = explode("=",$State[0]);
$Parameter[$Element[0]]=$Element[1];
$Element = explode("=",$State[1]);
$Parameter[$Element[0]]=$Element[1];
if (!jeedom::apiAccess( $Parameter['apikey'], 'homeconnect')) {
	echo 'Clef API non valide, vous n\'êtes pas autorisé à effectuer cette action';
	die();
}
$eqLogic->AccessToken(init('code'));
redirect(network::getNetworkAccess('external','proto:dns') . '/index.php?v=d&p=homeconnect&m=homeconnect&id=' . $eqLogic->getId());
