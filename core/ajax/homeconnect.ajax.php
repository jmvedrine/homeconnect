<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

try {
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	ajax::init();

	if (init('action') == 'loginHomeConnect') {
		if (network::getUserLocation() == 'internal') {
			throw new Exception(__("Connexion impossible : connectez vous à votre Jeedom par l'accès externe pas par l'accès interne", __FILE__));
		}
		if (config::byKey('demo_mode','homeconnect')) {
			homeconnect::authDemoRequest();
			ajax::success();
		} else {
			$url = homeconnect::authRequest();
			ajax::success(array('redirect' => $url));
		}
	}

	if (init('action') == 'syncHomeConnect') {
		homeconnect::syncHomeConnect(filter_var(init('force'), FILTER_VALIDATE_BOOLEAN));
		ajax::success();
	}

	if (init('action') == 'deleteEqLogic') {
		homeconnect::deleteEqLogic();
		ajax::success();
	}

	if (init('action') == 'testEnum') {
        $eqLogic = eqLogic::byId(init('eqLogic_id'));
        if (!is_object($eqLogic)) {
		    throw new Exception(__('EqLogic inexistant', __FILE__));
        }
        if (init('path') == '' || init('data_key') == '' || init('data_value') == '') {
		    	throw new Exception(__('Chemin, clé ou valeur incorrect', __FILE__));
        }
        $url = homeconnect::API_REQUEST_URL . '/'. $eqLogic->getConfiguration('haid') . '/' . init('path');
        $parameters = array('data' => array('key' => init('data_key'), 'value' => init('data_value')));
        log::add('homeconnect', 'debug',"Paramètres de la requête pour exécuter la commande :");
        log::add('homeconnect', 'debug',"Method : " . $method);
        log::add('homeconnect', 'debug',"Url : " . $url);
        log::add('homeconnect', 'debug',"Payload : " . $payload);
        $payload = json_encode($parameters);
        $response = homeconnect::request($url, $payload, 'PUT', array());
		ajax::success($response);
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*	   * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
