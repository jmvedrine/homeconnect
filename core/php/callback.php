<?php
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
include_file('core', 'authentification', 'php');
log::add('homeconnect', 'debug',"┌────────── Callback");
log::add('homeconnect', 'debug',"│ state = " . init('state'));
log::add('homeconnect', 'debug',"│ code = " . init('code'));
log::add('homeconnect', 'debug',"│ apikey = " . init('apikey'));

if (!jeedom::apiAccess(init('apikey'), 'homeconnect')) {
	echo 'Clef API non valide, vous n\'êtes pas autorisé à effectuer cette action';
	die();
}
if (!isConnect()) {
	echo 'Vous ne pouvez appeller cette page sans être connecté. Veuillez vous connecter <a href=' . network::getNetworkAccess() . '/index.php>ici</a> avant et refaire l\'opération de synchronisation';
	die();
}

if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
	unset($_SESSION['oauth2state']);
	exit('Invalid state');
}

    config::save('auth', init('code'), 'homeconnect');
    log::add('homeconnect', 'debug', "│ Code d'authorisation récupéré (".init('code').").");
	homeconnect::tokenRequest();

redirect(network::getNetworkAccess('external','proto:dns') . '/index.php?v=d&p=plugin&id=homeconnect');
