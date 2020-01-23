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


/** *************************** Includes ********************************** */

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';


class homeconnect extends eqLogic {

	/** *************************** Constantes ******************************** */

	const API_AUTH_URL = "/security/oauth/authorize"; //?client_id=XXX&redirect_uri=XXX&response_type=code&scope=XXX&state=XXX
	const API_TOKEN_URL = "/security/oauth/token"; //client_id=XXX&redirect_uri=XXX&grant_type=authorization_code&code=XXX
	const API_REQUEST_URL = "/api/homeappliances";

	/** *************************** Attributs ********************************* */



	/** *************************** Attributs statiques *********************** */



	/** *************************** Méthodes ********************************** */



	/** *************************** Méthodes statiques ************************ */
	public static function baseUrl() {
		if (config::byKey('demo_mode','homeconnect')) {
			return "https://simulator.home-connect.com";
		} else {
			return	"https://api.home-connect.com";
		}
	}
	protected static function buildQueryString(array $params) {
		return http_build_query($params, null, '&', PHP_QUERY_RFC3986);
	}

	public static function request($url, $payload = null, $method = 'POST', $headers = array()) {
		$ch = curl_init(self::baseUrl() . $url);

		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

		$requestHeaders = [
			"Accept: application/vnd.bsh.sdk.v1+json",
			"Accept-Language: " . config::byKey('language', 'core', 'fr_FR'),
			"Authorization: Bearer ".config::byKey('access_token','homeconnect'),
		];

		if($method == 'POST' || $method == 'PUT') {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			$requestHeaders[] = 'Content-Type: application/json';
			$requestHeaders[] = 'Content-Length: ' . strlen($payload);
		}

		if(count($headers) > 0) {
			$requestHeaders = array_merge($requestHeaders, $headers);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
		// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 7.0; SM-G930F Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36');

		$result = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($code =='200') {
			return $result;
		} else if ($code =='201' || $code =='204') {
			// Cas d'un POST ou d'un PUT
			// La requête ou la création a réussi mais rien à retourner.
			return '';
		} else {
			log::add('homconnect','debug',' | Request failed code = ' . $code . ' result = '.$result);
			return false;
		}
	}

	public static function syncHomeConnect() {
	/**
	 * Connexion au compte Home Connect (via token) et récupération des appareils liés.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */
		log::add('homeconnect', 'debug',"┌────────── Fonction syncHomeConnect()");
		if (empty(config::byKey('auth','homeconnect'))) {
			log::add('homeconnect', 'debug', "│ [Erreur] : Code d'authorisation vide.");
			throw new Exception("Erreur : Veuillez connecter votre compte via le menu configuration du plugin.");
			return;
		}

		// Récupération des appareils.
		self::homeappliances();
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction syncHomeConnect()");
	}

	public static function updateAppliances(){
	/**
	 * Lance la mise à jour des informations des appareils (lancement par cron).
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"┌────────── Fonction updateAppliances()");

		// Vérification si le token est expiré.
		if ((config::byKey('expires_in','homeconnect') - time()) < 60) {

			log::add('homeconnect', 'debug', "│ [Warning] : Le token est expiré, renouvellement de ce dernier.");

			// Récupération du token d'accès aux serveurs.
			self::tokenRefresh();
		}

		// Vérification de la présence du token et tentative de récupération si absent.
		if (empty(config::byKey('access_token','homeconnect'))) {

			log::add('homeconnect', 'debug', "│ [Warning] : Le token est manquant, recupération de ce dernier.");

			// Récupération du token d'accès aux serveurs.
			self::tokenRequest();

			if (empty(config::byKey('access_token','homeconnect'))) {

				log::add('homeconnect', 'debug', "│ [Erreur ]: La récupération du token a échouée.");
				return;
			}
		}

		// MAJ du statut de connexion des appareils.
		self::majConnected();

		// MAJ des programes en cours.
		self::majPrograms();

		// MAJ des états
		self::majState();

		log::add('homeconnect', 'debug',"└────────── Fin de la fonction updateAppliances()");
	}

	public static function authRequest() {
	/**
	 * Construit l'url d'authentification.
	 *
	 * @param			|*Cette fonction ne prend pas de paramètres*|
	 * @return			|*Cette fonction retourne l'url d'authentification*|
	 */
		log::add('homeconnect', 'debug',"┌────────── Fonction authRequest()");
		@session_start();
		$authorizationUrl = self::baseUrl() . self::API_AUTH_URL;
		$clientId = config::byKey('client_id','homeconnect','',true);
		$redirectUri = urlencode(network::getNetworkAccess('external') . '/plugins/homeconnect/core/php/callback.php?apikey=' . jeedom::getApiKey('homeconnect'));
		if (config::byKey('demo_mode','homeconnect')) {
			$parameters['scope'] = implode(' ', ['IdentifyAppliance', 'Monitor', 'Settings',
				'CoffeeMaker-Control', 'CleaningRobot-Control',
				'Dishwasher-Control', 'Dryer-Control', 'Freezer-Control',
				'Hood-Control', 'Refrigerator-Control', 'Washer-Control',
				'WasherDryer-Control', 'WineCooler-Control']);
			$parameters['user'] = 'me'; // Can be anything non-zero length
		} else {
			$parameters['scope'] = implode(' ', ['IdentifyAppliance', 'Monitor', 'Settings',
				'CoffeeMaker-Control', 'CleaningRobot-Control',
				'Dishwasher-Control', 'Dryer-Control', 'Freezer-Control',
				'Hood-Control', 'Refrigerator-Control', 'Washer-Control',
				'CookProcessor-Control', 'FridgeFreezer-Control',
				'WasherDryer-Control', 'WineCooler-Control']);
			$parameters['redirect_uri'] = network::getNetworkAccess('external') . '/plugins/homeconnect/core/php/callback.php?apikey=' . jeedom::getApiKey('homeconnect');
		}
		$parameters['client_id'] = config::byKey('client_id','homeconnect','',true);
		$parameters['response_type'] = 'code';
		$state = bin2hex(random_bytes(16));
		$_SESSION['oauth2state'] = $state;
		$parameters['state'] = $state;

		// Construction de l'url.
		$url = $authorizationUrl ."?" . self::buildQueryString($parameters);
		log::add('homeconnect', 'debug',"│ url = " . $url);
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction authRequest()");
		return $url;
	}

	public static function tokenRequest() {
	/**
	 * Récupère un token permettant l'accès au serveur.
	 *
	 * @param			|*Cette fonction ne prend pas de paramètres*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"├────────── Fonction tokenRequest()");
		$clientId = config::byKey('client_id','homeconnect','',true);

		// Vérification de la présence du code d'authorisation avant de demander le token.
		if (empty(config::byKey('auth','homeconnect'))) {

			log::add('homeconnect', 'debug', "│ [Erreur] : Code d'authorisation vide.");
			throw new Exception("Erreur : Veuillez connecter votre compte via le menu configuration du plugin.");
			return;
		}
		$url = self::baseUrl() . self::API_TOKEN_URL;
		log::add('homeconnect', 'debug', "│ Url = ". $url);
		// Création du paramêtre POSTFIELDS.
		$post_fields = 'client_id='. $clientId;
		$post_fields .= '&client_secret='. config::byKey('client_secret','homeconnect','',true);
		$post_fields .= '&redirect_uri='. network::getNetworkAccess('external') . '/plugins/homeconnect/core/php/callback.php?apikey=' . jeedom::getApiKey('homeconnect');
		$post_fields .= '&grant_type=authorization_code';
		$post_fields .= '&code='.config::byKey('auth','homeconnect');
		log::add('homeconnect', 'debug', "│ Post fields = ". $post_fields);
		// Récupération du Token.
		$curl = curl_init();
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => True,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POST => True,
			CURLOPT_POSTFIELDS => $post_fields,
			];
		curl_setopt_array($curl, $options);
		$response = json_decode(curl_exec($curl), true);
		log::add('homeconnect', 'debug', "│ Response = ". print_r($response, true));
		$http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
		curl_close ($curl);

		// Vérification du code réponse.
		if($http_code != 200) {

			log::add('homeconnect', 'debug', "│ [Erreur] (code erreur : ".$http_code.") : Impossible de récupérer le token.");
			throw new Exception("Erreur : Impossible de récupérer le token (code erreur : ".$http_code.").");
			return;

		} else {

			log::add('homeconnect', 'debug', "│ Token récupéré.");
		}

		// Calcul de l'expiration du token.
		$expires_in = time() + $response['expires_in'];

		// Enregistrement des informations dans le plugin.
		config::save('access_token', $response['access_token'], 'homeconnect');
		config::save('refresh_token', $response['refresh_token'], 'homeconnect');
		config::save('token_type', $response['token_type'], 'homeconnect');
		config::save('scope', $response['scope'], 'homeconnect');
		config::save('expires_in', $expires_in, 'homeconnect');
		config::save('id_token', $response['id_token'], 'homeconnect');

		log::add('homeconnect', 'debug',"│ Access token : ".$response['access_token']);
		log::add('homeconnect', 'debug',"│ Refresh token : ".$response['refresh_token']);
		log::add('homeconnect', 'debug',"│ Token type : ".$response['token_type']);
		log::add('homeconnect', 'debug',"│ scope : ".$response['scope']);
		log::add('homeconnect', 'debug',"│ Expires in : ".$expires_in);
		log::add('homeconnect', 'debug',"│ Id token : ".$response['id_token']);
		log::add('homeconnect', 'debug',"├────────── Fin de la fonction tokenRequest()");
	}

	public static function tokenRefresh() {
	/**
	 * Rafraichit un token expiré permettant l'accès au serveur.
	 *
	 * @param			|*Cette fonction ne prend pas de paramètres*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"┌────────── Fonction tokenRefresh()");

		// Vérification de la présence du code d'authorisation avant de demander le token.
		if (empty(config::byKey('auth','homeconnect'))) {

			log::add('homeconnect', 'debug', "│ [Erreur] : Code d'authorisation vide.");
			throw new Exception("Erreur : Veuillez connecter votre compte via le menu configuration du plugin.");
			return;
		}
		$url = self::baseUrl() . self::API_TOKEN_URL;
		log::add('homeconnect', 'debug', "│ Url = ". $url);
		// Création du paramêtre POSTFIELDS.
		$post_fields = 'grant_type=refresh_token';
		$post_fields .= '&client_secret='. config::byKey('client_secret','homeconnect','',true);
		$post_fields .= '&refresh_token='.	config::byKey('refresh_token','homeconnect','',true);

		log::add('homeconnect', 'debug', "│ Post fields = ". $post_fields);
		// Récupération du Token.
		$curl = curl_init();
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => True,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POST => True,
			CURLOPT_POSTFIELDS => $post_fields,
			];
		curl_setopt_array($curl, $options);
		$response = json_decode(curl_exec($curl), true);
		log::add('homeconnect', 'debug', "│ Response = ". print_r($response, true));
		$http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
		curl_close ($curl);

		// Vérification du code réponse.
		if($http_code != 200) {

			log::add('homeconnect', 'debug', "│ [Erreur] (code erreur : ".$http_code.") : Impossible de rafraichir le token.");
			throw new Exception("Erreur : Impossible de rafraichir le token (code erreur : ".$http_code.").");
			return;

		} else {

			log::add('homeconnect', 'debug', "│ Token rafraichi.");
		}

		// Calcul de l'expiration du token.
		$expires_in = time() + $response['expires_in'];

		// Enregistrement des informations dans le plugin.
		config::save('access_token', $response['access_token'], 'homeconnect');
		config::save('refresh_token', $response['refresh_token'], 'homeconnect');
		config::save('token_type', $response['token_type'], 'homeconnect');
		config::save('scope', $response['scope'], 'homeconnect');
		config::save('expires_in', $expires_in, 'homeconnect');
		config::save('id_token', $response['id_token'], 'homeconnect');

		log::add('homeconnect', 'debug',"│ Access token : ".$response['access_token']);
		log::add('homeconnect', 'debug',"│ Refresh token : ".$response['refresh_token']);
		log::add('homeconnect', 'debug',"│ Token type : ".$response['token_type']);
		log::add('homeconnect', 'debug',"│ scope : ".$response['scope']);
		log::add('homeconnect', 'debug',"│ Expires in : ".$expires_in);
		log::add('homeconnect', 'debug',"│ Id token : ".$response['id_token']);
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction tokenRefresh()");
	}

	private static function homeappliances() {
	/**
	 * Récupère la liste des objects connectés et création des objets associés.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"┌────────── Fonction homeappliances()");

		// Vérification si le token est expiré.
		if ((config::byKey('expires_in','homeconnect') - time()) < 60) {

			log::add('homeconnect', 'debug', "│ [Warning] : Le token est expiré, renouvellement de ce dernier.");

			// Récupération du token d'accès aux serveurs.
			self::tokenRefresh();
		}

		// Vérification de la présence du token et tentative de récupération si absent.
		if (empty(config::byKey('access_token','homeconnect'))) {

			log::add('homeconnect', 'debug', "│ [Warning] : Le token est manquant, recupération de ce dernier.");

			// Récupération du token d'accès aux serveurs.
			self::tokenRequest();

			if (empty(config::byKey('access_token','homeconnect'))) {

				log::add('homeconnect', 'debug', "│ [Erreur ]: La récupération du token à échouée.");
				return;
			}
		}

		$response = self::request(self::API_REQUEST_URL, null, 'GET', array());
		log::add('homeconnect', 'debug', "│ Réponse : " . $response);
		$response = json_decode($response, true);

		foreach($response['data']['homeappliances'] as $key => $appliance) {
			/*	haId = Id de l'appareil
				vib = modèle de l'appareil
				brand = marque de l'appareil
				type = type de l'appareil
				name = nom de l'appareil
				enumber = N° de série
				connected = boolean */

				// Vérification que l'appareil n'est pas déjà créé.
				$eqLogic = eqLogic::byLogicalId($appliance['haId'], 'homeconnect');

				if (!is_object($eqLogic)) {
					log::add('homeconnect','info','Nouvel appareil : '.$_device['name']);
					event::add('jeedom::alert', array(
						'level' => 'warning',
						'page' => 'homeconnect',
						'message' => __('Nouveau produit detecté', __FILE__),
					));
					// Création de l'appareil.
					log::add('homeconnect', 'debug', "├──────────");
					log::add('homeconnect', 'debug', "│ Création d'un appareil :");
					log::add('homeconnect', 'debug', "│ Type : ".self::traduction($appliance['type']));
					log::add('homeconnect', 'debug', "│ Marque : ".$appliance['brand']);
					log::add('homeconnect', 'debug', "│ Modèle : ".$appliance['vib']);
					log::add('homeconnect', 'debug', "├──────────");
					$eqLogic = new homeconnect();
					$eqLogic->setLogicalId($appliance['haId']);
					$eqLogic->setIsEnable(1);
					$eqLogic->setIsVisible(1);
					$eqLogic->setEqType_name('homeconnect');
					$eqLogic->setName($appliance['name']);
				}
				$eqLogic->setConfiguration('haid', $appliance['haId']);
				$eqLogic->setConfiguration('vib', $appliance['vib']);
				$eqLogic->setConfiguration('brand', $appliance['brand']);
				$eqLogic->setConfiguration('type', self::traduction($appliance['type']));
				$eqLogic->save();
				$found_eqLogics = self::findProduct($appliance);
				// log::add('homeconnect','debug',json_encode($found_eqLogics));
				$programs = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/programs', null, 'GET', array());
				if ($programs !== false) {
					log::add('homeconnect', 'debug', "│ Programs : " . $programs);
				}
				$status = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/status', null, 'GET', array());
				if ($status !== false) {
					log::add('homeconnect', 'debug', "│ Status : " . $status);
					$availableStatus = array();
					foreach($response['data']['status'] as $applianceStatus) {
						$statusParts = explode('.', $applianceStatus['key']);
						$logicalId = $statusParts[count($statusParts) - 1];
						$availableStatus[$logicalId] = $applianceStatus;
					}
					foreach($eqLogic->getCmd() as $cmd) {
						if (array_key_exists($cmd->getLogicalId(), $availableStatus)) {
							if (isset($availableStatus[$cmd->getLogicalId()]['name'])) {
								$cmd->setName($availableStatus[$cmd->getLogicalId()]['name']);
							}
						} else {
							$cmd->remove();
						}
					}
				}
				$settings = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/settings', null, 'GET', array());
				if ($settings !== false) {
					log::add('homeconnect', 'debug', "│ Settings : " . $settings);
					$availableSettings = array();
					foreach($response['data']['settings'] as $applianceSetting) {
						$settingParts = explode('.', $applianceSetting['key']);
						$logicalId = $settingParts[count($settingParts) - 1];
						$availableSettings[$logicalId] = $applianceSetting;
					}
					foreach($eqLogic->getCmd() as $cmd) {
						if (array_key_exists($cmd->getLogicalId(), $availableSettings)) {
							if (isset($availableSettings[$cmd->getLogicalId()]['name'])) {
								$cmd->setName($availableSettings[$cmd->getLogicalId()]['name']);
							}
						} else {
							$cmd->remove();
						}
					}
				}
		}

		log::add('homeconnect', 'debug',"└────────── Fin de la fonction homeappliances()");
	}


	private static function majConnected() {
	/**
	 * Récupère le statut connecté des l'appareils.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"│");
		log::add('homeconnect', 'debug',"├───── Fonction majConnected()");

		$response = self::request(self::API_REQUEST_URL, null, 'GET', array());
		$response = json_decode($response, true);
		foreach($response['data']['homeappliances'] as $key) {
			/* connected = boolean */

			$eqLogic = eqLogic::byLogicalId($key['haId'], 'homeconnect');
			if (is_object($eqLogic)){
				$cmd = $eqLogic->getCmd(null, 'connected');
				if (is_object($cmd)) {
					$eqLogic->checkAndUpdateCmd('connected', $key['connected']);

					log::add('homeconnect', 'debug', "├─────");
					log::add('homeconnect', 'debug', "│ MAJ d'un appareil :");
					log::add('homeconnect', 'debug', "│ Type : ".self::traduction($key['type']));
					log::add('homeconnect', 'debug', "│ Id : ".$key['haId']);
					log::add('homeconnect', 'debug', "│ Connecté : ".$key['connected']);
					log::add('homeconnect', 'debug', "├─────");
				} else {
					log::add('homeconnect', 'debug', "├───── [Erreur]");
					log::add('homeconnect', 'debug', "│ La commande connected n'existe pas :");
					log::add('homeconnect', 'debug', "│ Type : ".self::traduction($key['type']));
					log::add('homeconnect', 'debug', "│ Marque : ".$key['brand']);
					log::add('homeconnect', 'debug', "│ Modèle : ".$key['vib']);
					log::add('homeconnect', 'debug', "│ Id : ".$key['haId']);
				}
			} else {
				log::add('homeconnect', 'debug', "├───── [Erreur]");
				log::add('homeconnect', 'debug', "│ L'appareil n'existe pas :");
				log::add('homeconnect', 'debug', "│ Type : ".self::traduction($key['type']));
				log::add('homeconnect', 'debug', "│ Marque : ".$key['brand']);
				log::add('homeconnect', 'debug', "│ Modèle : ".$key['vib']);
				log::add('homeconnect', 'debug', "│ Id : ".$key['haId']);
				log::add('homeconnect', 'debug', "├─────");
			}
		}

		log::add('homeconnect', 'debug',"├───── Fin de la fonction majConnected()");
	}

	private static function majPrograms(){
	/**
	 * Récupère le programme et options en cours pour MAJ équipements.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"│");
		log::add('homeconnect', 'debug',"├───── Fonction majPrograms()");

		// Parcours des marchines existantes.
		foreach (eqLogic::byType('homeconnect') as $eqLogic) {

			// MAJ des appareils qui sont connectée.
			// Si l'appareil est connecté, MAJ des infos.
			if ($eqLogic->getConfiguration('connected') == True) {
				log::add('homeconnect', 'debug', "├─────");
				log::add('homeconnect', 'debug', "│ MAJ du programme actif :");
				log::add('homeconnect', 'debug', "│ Type : ".$eqLogic->getConfiguration('type'));
				log::add('homeconnect', 'debug', "│ Id : ".$eqLogic->getLogicalId());
				log::add('homeconnect', 'debug', "│");

				$response = self::request(self::API_REQUEST_URL . '/' . $eqLogic->getLogicalId() . '/programs/active', null, 'GET', array());
				if ($response !== false) {
					log::add('homeconnect', 'debug', "│ Réponse : " . $response);

					$response = json_decode($response, true);
					// MAJ du programme en cours.
					$program = self::traduction(substr(strrchr($response['data']['key'], "."), 1));
					$cmd = $eqLogic->getCmd(null, 'programActive');
					if (is_object($cmd)) {
						$eqLogic->checkAndUpdateCmd('programActive',$program);
						log::add('homeconnect', 'debug', "│ Programme en cours : ".$program);
					} else {
						log::add('homeconnect', 'debug', "│ La commande programActive n'existe pas :");
						log::add('homeconnect', 'debug', "│ Type : ".self::traduction($key['type']));
						log::add('homeconnect', 'debug', "│ Marque : ".$key['brand']);
						log::add('homeconnect', 'debug', "│ Modèle : ".$key['vib']);
						log::add('homeconnect', 'debug', "│ Id : ".$key['haId']);
					}
					// MAJ des options et autres informations du programme en cours.
					foreach ($response['data']['options'] as $value) {
						log::add('homeconnect', 'debug', "│ option : " . print_r($value, true));
						// Récupération du nom du programme / option.
						$program = substr(strrchr($value['key'], "."), 1);

						// Récupération de la valeur du programme / option.
						switch ($program) {

							case "Temperature" :
								$reglage = substr(strrchr($value['value'], "."), 3);
								break;

							case "SpinSpeed" :
								$reglage = substr(strrchr($value['value'], "."), 4);
								break;

							default :
								$reglage = self::traduction($value['value']);

						}

						// Traduction en Français du nom du program / option.
						$program = self::traduction($program);
						$cmd = $eqLogic->getCmd(null, $program);
						if (is_object($cmd)) {
							$eqLogic->checkAndUpdateCmd($program,$reglage);
							log::add('homeconnect', 'debug', "│ Option : ".$program." - Réglage :".$reglage);
						} else {
							log::add('homeconnect', 'debug', "│ La commande : ".$program." n'existe pas");
						}
					}

					log::add('homeconnect', 'debug', "├─────");
				}

			} else {

				// RAZ des info.
				self::razInfo($eqLogic->getLogicalId());
			}

			// MAJ du widget.
			$eqLogic->refreshWidget();
		}

		log::add('homeconnect', 'debug',"├───── Fin de la fonction majPrograms()");
	}

	private static function majState(){
	/**
	 * Récupère les états en cours pour MAJ équipements.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"│");
		log::add('homeconnect', 'debug',"├───── Fonction maState()");

		// Parcours des marchines existantes.
		foreach (eqLogic::byType('homeconnect') as $eqLogic) {

			// MAJ des appareils qui sont connectée.
			// Si l'appareil est connecté, MAJ des infos.
			if ($eqLogic->getConfiguration('connected') == True) {
				log::add('homeconnect', 'debug', "├─────");
				log::add('homeconnect', 'debug', "│ MAJ des états :");
				log::add('homeconnect', 'debug', "│ Type : ".$eqLogic->getConfiguration('type'));
				log::add('homeconnect', 'debug', "│ Id : ".$eqLogic->getLogicalId());
				log::add('homeconnect', 'debug', "│");

				$response = self::request(self::API_REQUEST_URL . '/' . $eqLogic->getLogicalId() . '/status', null, 'GET', array());
				if ($response !== false) {
					log::add('homeconnect', 'debug', "│ Réponse : " . $response);
				}

			}

			// MAJ du widget.
			// $eqLogic->refreshWidget();
		}
		log::add('homeconnect', 'debug',"├───── Fin de la fonction majState()");
	}

	private static function razInfo($haId) {
	/**
	 * Remise à zéro des informations d'une machine.
	 *
	 * @param	$haId		string		Id de la machine en cours.
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"/** ******************** function razInfo ******************** **/");

		$eqLogic = eqLogic::byLogicalId($haId, 'homeconnect');

		foreach($eqLogic->getCmd() as $cmd) {

			if ($cmd->getLogicalId() == "programActive") {

				log::add('homeconnect','debug',"MAJ de la valeur de ".$cmd->getLogicalId()." (NoPrg) de la machine ".$eqLogic->getConfiguration('type'));
				$eqLogic->checkAndUpdateCmd($cmd->getLogicalId(),"NoPrg");

			}else if ($cmd->getLogicalId() != "connected") {

				log::add('homeconnect','debug',"MAJ de la valeur de ".$cmd->getLogicalId()." (null) de la machine ".$eqLogic->getConfiguration('type'));
				$eqLogic->checkAndUpdateCmd($cmd->getLogicalId(), "");
			}
		}

		// MAJ du widget.
		$eqLogic->refreshWidget();
	}

	public static function findProduct($_appliance) {
		log::add('homeconnect', 'debug',"┌────────── Fonction findProduct($_appliance)");
		if(file_exists(__DIR__.'/../config/types/'.$_appliance['type'].'.json')){
		  log::add('homeconnect','debug','Found config file for product type ' . $_appliance['type']);
		  $eqLogic = self::byLogicalId($_appliance['haId'], 'homeconnect');
		  $products = json_decode(file_get_contents(__DIR__.'/../config/types/'.$_appliance['type'].'.json'),true);
		  log::add('homeconnect','debug','Product : '.file_get_contents(__DIR__.'/../config/types/'.$_appliance['type'].'.json'));
		  $link_cmds = array();
		  foreach ($products['commands'] as $product) {
			 log::add('homeconnect','debug','Commande : '.json_encode($product));
			$cmd = $eqLogic->getCmd(null, $product['logicalId']);
			if(is_object($cmd)){
			  continue;
			}
			$cmd = new homeconnectCmd();
			utils::a2o($cmd, $product);
			$cmd->setLogicalId($product['logicalId']);
			$cmd->setEqLogic_id($eqLogic->getId());
			$cmd->setName(__($product['name'], __FILE__));
			$cmd->save();
			if (isset($product['value'])) {
			  $link_cmds[$cmd->getId()] = $product['value'];
			}
		  }
		} else {
			log::add('homeconnect','debug','No config file for product type ' . $_appliance['type']);
		}
		if (count($link_cmds) > 0) {
		  foreach ($eqLogic->getCmd() as $eqLogic_cmd) {
			foreach ($link_cmds as $cmd_id => $link_cmd) {
			  if ($link_cmd == $eqLogic_cmd->getName()) {
				$cmd = cmd::byId($cmd_id);
				if (is_object($cmd)) {
				  $cmd->setValue($eqLogic_cmd->getId());
				  $cmd->save();
				}
			  }
			}
		  }
		}
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction findProduct()");
		return $eqLogic;
	}

	public static function devicesParameters($_type = '') {
		log::add('homeconnect', 'debug',"┌────────── Fonction devicesParameters($_type)");
		$return = array();
		foreach (ls(dirname(__FILE__) . '/../config/types', '*') as $dir) {
			$path = dirname(__FILE__) . '/../config/types/' . $dir;
			if (!is_dir($path)) {
				continue;
			}
			log::add('homeconnect', 'debug', '| Path = '.$path);
			$files = ls($path, '*.json', false, array('files', 'quiet'));
			foreach ($files as $file) {
				try {
					$content = file_get_contents($path . '/' . $file);
					if (is_json($content)) {
						$return += json_decode($content, true);
					}
				} catch (Exception $e) {
				}
			}
		}
		if (isset($_type) && $_type != '') {
			if (isset($return[$_type])) {
				log::add('homeconnect', 'debug', 'devicesParameters return '.json_encode($return[$_type]));
				log::add('homeconnect', 'debug',"└────────── Fin de la fonction devicesParameters()");
				return $return[$_type];
			}
			log::add('homeconnect', 'debug', 'devicesParameters return empty array');
			log::add('homeconnect', 'debug',"└────────── Fin de la fonction devicesParameters()");
			return array();
		}
		log::add('homeconnect', 'debug', 'devicesParameters return '.json_encode($return));
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction devicesParameters()");
		return $return;
	}

	private static function traduction($word){
	/**
	 * Traduction des informations.
	 *
	 * @param	$word		string		Mot en anglais.
	 * @return	$word		string		Mot en Français (ou anglais, si traduction inexistante).
	 */

		$translate = [
				'Auto1' => "Auto 35-45°C",
				'Auto2' => "Auto 45-65°C",
				'Auto3' => "Auto 65-75°C",
				'Cotton' => "Coton",
				'CupboardDry' => "Prêt à ranger",
				'CupboardDryPlus' => "Prêt à ranger Plus",
				'DelicatesSilk' => "Délicat / Soie",
				'DoubleShot' => "Double shot",
				'DoubleShotPlus' => "Double shot +",
				'EasyCare' => "Synthétique",
				'Eco50' => "Eco 50°C",
				'HotAir' => "Air chaud",
				'IronDry' => "Prêt à repasser",
				'Mild' => "Doux",
				'Mix' => "Mix",
				'Normal' => "Normal",
				'PizzaSetting' => "Position Pizza",
				'Preheating' => "Préchauffage",
				'Quick45' => "Rapide 45°C",
				'Strong' => "Fort",
				'Synthetic' => "Synthétique",
				'TopBottomHeating' => "Convection naturelle",
				'VeryStrong' => "Très fort",
				'Wool' => "Laine",
				];

				(array_key_exists($word, $translate) == True) ? $word = $translate[$word] : null;

				return $word;
	}

	/*
	 * Fonction exécutée automatiquement toutes les minutes par Jeedom */
	  public static function cron5() {
		self::updateAppliances();
	  }


	/*
	 * Fonction exécutée automatiquement toutes les heures par Jeedom
	  public static function cronHourly() {

	  }
	 */

	/*
	 * Fonction exécutée automatiquement tous les jours par Jeedom
	  public static function cronDayly() {

	  }
	 */



	/** *************************** Méthodes d'instance************************ */
	public function getImage() {
		$filename = 'plugins/homeconnect/core/config/images/' . $this->getConfiguration('vib') . '.jpg';
		if(file_exists(__DIR__.'/../../../../'.$filename)){
			return $filename;
		}
		return 'plugins/homeconnect/plugin_info/homeconnect_icon.png';
	}

	public function applyModuleConfiguration() {
		log::add('homeconnect', 'debug',"├────────── Fonction applyModuleConfiguration()");
		log::add('homeconnect', 'debug', '│ type = '.$this->getConfiguration('type'));
		$this->setConfiguration('applyType', $this->getConfiguration('type'));
		$this->save();
		if ($this->getConfiguration('type') == '') {
		  log::add('homeconnect', 'debug', '│ applyModuleConfiguration type is empty');
		  log::add('homeconnect', 'debug',"├────────── Fin de la fonction applyModuleConfiguration()");
		  return true;
		}
		log::add('homeconnect', 'debug', '│ applyModuleConfiguration call devicesParameters');
		$device = self::devicesParameters($this->getConfiguration('type'));
		if (!is_array($device)) {
			log::add('homeconnect', 'debug', '│ deviceParameters result is not an array');
			log::add('homeconnect', 'debug',"├────────── Fin de la fonction applyModuleConfiguration()");
			return true;
		}
		log::add('homeconnect', 'debug', '│ applyModuleConfiguration import' . print_r($device, true));
		$this->import($device);
		log::add('homeconnect', 'debug',"├────────── Fin de la fonction applyModuleConfiguration()");
	}

	public function preInsert() {

	}

	public function postInsert() {

	}

	public function preSave() {

	}

	public function postSave() {
	/**
	 * Création / MAJ des commandes des appareils.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */
		log::add('homeconnect', 'debug',"┌────────── Fonction postSave()");
		if ($this->getConfiguration('applyType') != $this->getConfiguration('type')) {
			$this->applyModuleConfiguration();
			$this->refreshWidget();
		}
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction postSave()");
	}

	public function preUpdate() {

	}

	public function postUpdate() {

	}

	public function preRemove() {

	}

	public function postRemove() {

	}

	/*
	 * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
	  public function toHtml($_version = 'dashboard') {

	  }
	 */



	/** *************************** Getters ********************************* */



	/** *************************** Setters ********************************* */



}

class homeconnectCmd extends cmd {

	/** *************************** Constantes ******************************** */



	/** *************************** Attributs ********************************* */



	/** *************************** Attributs statiques *********************** */



	/** *************************** Méthodes ********************************** */



	/** *************************** Méthodes statiques ************************ */



	/** *************************** Méthodes d'instance************************ */

	/*
	 * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
	  public function dontRemoveCmd() {
	  return true;
	  }
	 */

	public function execute($_options = array()) {
		//switch ($this->getType)

	}

	/** *************************** Getters ********************************* */



	/** *************************** Setters ********************************* */



}
?>
