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

	public static $_widgetPossibility = array('custom' => true);

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

	public static function lastSegment($separator, $key) {
		if (strpos($key, $separator) === false) {
			return '';
		}
		$parts = explode($separator, $key);
		return $parts[count($parts) - 1];
	}

	public static function firstSegment($separator, $key) {
		if (strpos($key, $separator) === false) {
			return '';
		}
		$parts = explode($separator, $key);
		return $parts[0];
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
		if ($code == '200' || $code == '204') {
			log::add('homeconnect','debug',"La requête $method	: $url a réussi code = " . $code . " résultat = ".$result);
			return $result;
		} else {
			// Traitement des erreurs
			log::add('homeconnect','debug',"La requête $method	: $url a retourné un code d'erreur " . $code . " résultat = ".$result);
			switch ($code) {
				case 400:
					// "Bad Request", desc: "Error occurred (e.g. validation error - value is out of range)"
					break;
				case 401:
					// "Unauthorized", desc: "No or invalid access token"
					throw new \Exception(__("Le jeton d'authentification au serveur est absent ou invalide. Reconnectez-vous",__FILE__));
					break;
				case 403:
					// Forbidden", desc: "Scope has not been granted or home appliance is not assigned to HC account"
					throw new \Exception(__("Accès à cette ressource non autorisé ou appareil non lié à cet utilisateur",__FILE__));
					break;
				case 404:
					$result = json_decode($result, true);
					if ($result['error']['key'] == 'SDK.Error.NoProgramActive' || $result['error']['key'] == 'SDK.Error.NoProgramSelected') {
						return $result['error']['key'];
					}
					// Not Found", desc: "This resource is not available (e.g. no images on washing machine)"
					throw new \Exception(__("Cette ressource n'est pas disponible",__FILE__));
					break;
				case 405:
					// "Method not allowed", desc: "The HTTP Method is not allowed for this resource" },
					throw new \Exception(__("La méthode $method n'est pas permise pour cette ressource",__FILE__));
					break;
				case 406:
					// "Not Acceptable", desc: "The resource identified by the request is only capable of generating response entities which have content characteristics not acceptable according to the accept headers sent in the request."
					throw new \Exception(__("Impossible de fournir une réponse Les entêtes 'Accept' de la requête ne sont pas acceptés",__FILE__));
					break;
				case 408:
					// "Request Timeout", desc: "API Server failed to produce an answer or has no connection to backend service"
					throw new \Exception(__("Le serveur n'a pas fourni de réponse dans le temps imparti",__FILE__));
					break;
				case 409:
					// "Conflict", desc: "Command/Query cannot be executed for the home appliance, the error response contains the error details"
					$result = json_decode($result, true);
					$errorMsg = isset($result['error']['description']) ? $result['error']['description'] : '';
					throw new \Exception(__("Cette action ne peut pas être exécutée pour cet appareil",__FILE__) . ' ' . $errorMsg);
					break;
				case 415:
					// "Unsupported Media Type", desc: "The request's Content-Type is not supported"
					throw new \Exception(__("Le type de contenu de la requête n'est pas pris en charge",__FILE__));
					break;
				case 429:
					//	"Too Many Requests", desc: "E.g. the number of requests for a specific endpoint exceeded the quota of the client"
					throw new \Exception(__("Vous avez dépassé le nombre de requêtes permises au serveur. Réessayez dans 24h",__FILE__));
					break;
				case 500:
					// "Internal Server Error", desc: "E.g. in case of a server configuration error or any errors in resource files"
					throw new \Exception(__("Erreur interne du serveur",__FILE__));
					break;
				case 503:
					// "Service Unavailable", desc: "E.g. if a required backend service is not available"
					throw new \Exception(__("Service indisponible",__FILE__));
					break;
				default:
				   // Erreur inconnue
				   throw new \Exception(__("Erreur inconnue code " . $code,__FILE__));
			}
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
		log::add('homeconnect', 'debug',"Fonction syncHomeConnect()");
		if (empty(config::byKey('auth','homeconnect'))) {
			log::add('homeconnect', 'debug', "[Erreur] : Code d’authentifiaction vide.");
			throw new Exception("Erreur : Veuillez vous connecter à votre compte Home Connect via le menu configuration du plugin.");
			return;
		}

		// Pas besoin de vérifier le token, homeappliances le fait

		// Récupération des appareils.
		self::homeappliances();
		// MAJ du statut de connexion des appareils.
		self::majConnected();

		foreach (eqLogic::byType('homeconnect') as $eqLogic) {
			// MAJ des programes en cours.
			$eqLogic->updateProgram();
			// MAJ des états
			$eqLogic->updateStates();
			// MAJ des réglages
			$eqLogic->updateSettings();
			if ($eqLogic->getIsEnable()) {
				$eqLogic->refreshWidget();
			}
		}
		log::add('homeconnect', 'debug',"Fin de la fonction syncHomeConnect()");
	}

	public static function updateAppliances(){
	/**
	 * Lance la mise à jour des informations des appareils (lancement par cron).
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"Fonction updateAppliances()");

		self::verifyToken(60);

		// MAJ du statut de connexion des appareils.
		self::majConnected();

		foreach (eqLogic::byType('homeconnect') as $eqLogic) {
			// MAJ des programmes en cours.
			$eqLogic->updateProgram();
			// MAJ des états
			$eqLogic->updateStates();
			// MAJ des réglages
			$eqLogic->updateSettings();
			if ($eqLogic->getIsEnable()) {
				$eqLogic->refreshWidget();
			}
		}
		log::add('homeconnect', 'debug',"Fin de la fonction updateAppliances()");
	}

	public static function authRequest() {
	/**
	 * Construit l'url d'authentification.
	 *
	 * @param			|*Cette fonction ne prend pas de paramètres*|
	 * @return			|*Cette fonction retourne l'url d'authentification*|
	 */
		log::add('homeconnect', 'debug',"Fonction authRequest()");
		@session_start();
		$authorizationUrl = self::baseUrl() . self::API_AUTH_URL;
		$clientId = config::byKey('client_id','homeconnect','',true);
		$redirectUri = urlencode(network::getNetworkAccess('external') . '/plugins/homeconnect/core/php/callback.php?apikey=' . jeedom::getApiKey('homeconnect'));
		if (config::byKey('demo_mode','homeconnect')) {
			$parameters['scope'] = implode(' ', ['IdentifyAppliance', 'Monitor', 'Settings',
				'CoffeeMaker-Control', 'Dishwasher-Control',
				 'Dryer-Control', 'Washer-Control']);
			$parameters['user'] = 'me'; // Can be anything non-zero length
			$parameters['client_id'] = config::byKey('demo_client_id','homeconnect','',true);
		} else {
			$parameters['scope'] = implode(' ', ['IdentifyAppliance', 'Monitor', 'Settings',
				'CoffeeMaker-Control', 'Dishwasher-Control',
				'Dryer-Control', 'Freezer-Control', 'Hood-Control',
				'Refrigerator-Control', 'Washer-Control']);
			$parameters['redirect_uri'] = network::getNetworkAccess('external') . '/plugins/homeconnect/core/php/callback.php?apikey=' . jeedom::getApiKey('homeconnect');
			$parameters['client_id'] = config::byKey('client_id','homeconnect','',true);
		}
		$parameters['response_type'] = 'code';
		$state = bin2hex(random_bytes(16));
		$_SESSION['oauth2state'] = $state;
		$parameters['state'] = $state;

		// Construction de l'url.
		$url = $authorizationUrl ."?" . self::buildQueryString($parameters);
		log::add('homeconnect', 'debug',"url = " . $url);
		log::add('homeconnect', 'debug',"Fin de la fonction authRequest()");
		return $url;
	}

	public static function authDemoRequest() {
	/**
	 * Récupère un code d'authorisation à échanger contre un token.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"Fonction authRequest()");

		// Construction de l'url.
		$url = self::authRequest();

		// Envoie d'une requête GET et récupération du header.
		$curl = curl_init();
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => True,
			CURLOPT_SSL_VERIFYPEER => False,
			CURLOPT_HEADER => True,
			CURLINFO_HEADER_OUT => true,
			];
		curl_setopt_array($curl, $options);
		$response = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);

		// Vérification du code réponse.
		if ($info['http_code'] != 302) {

			// Récupération du message d'erreur pour log.
			preg_match("/[\{].*[\}]/", $response, $matches);
			log::add('homeconnect', 'debug', "[Erreur] (code erreur : ".$info['http_code'].") : ".print_r($matches, true));
			throw new Exception("Erreur : " . print_r($matches));
			return;
		}

		$params = parse_url($info['redirect_url']); // Récupération de l'url de redirection avec paramêtre.
		$params = explode("&",$params['query']); // Explode des paramêtres de l'url afin d'isoler l'authorize code.

		// Récupération du code d'authorisation.
		foreach($params as $key => $value) {

			$explode = explode("=", $value);

			if ($explode[0] == "code") {

				config::save('auth', $explode[1], 'homeconnect');
				log::add('homeconnect', 'debug', "Code d'authorisation récupéré (".$explode[1].".");
				homeconnect::tokenRequest();
			}
		}

		log::add('homeconnect', 'debug',"Fin de la fonction authRequest()");
	}

	public static function tokenRequest() {
	/**
	 * Récupère un token permettant l'accès au serveur.
	 *
	 * @param			|*Cette fonction ne prend pas de paramètres*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"Fonction tokenRequest()");
		if (!config::byKey('demo_mode','homeconnect')) {
			$clientId = config::byKey('client_id','homeconnect','',true);
		} else {
			$clientId = config::byKey('demo_client_id','homeconnect','',true);
		}
		// Vérification de la présence du code d'authorisation avant de demander le token.
		if (empty(config::byKey('auth','homeconnect'))) {

			log::add('homeconnect', 'debug', "[Erreur] : Code d'authorisation vide.");
			throw new Exception("Erreur : Veuillez connecter votre compte via le menu configuration du plugin.");
			return;
		}
		$url = self::baseUrl() . self::API_TOKEN_URL;
		log::add('homeconnect', 'debug', "Url = ". $url);
		// Création du paramêtre POSTFIELDS.
		$post_fields = 'client_id='. $clientId;
		if (!config::byKey('demo_mode','homeconnect')) {
			$post_fields .= '&client_secret='. config::byKey('client_secret','homeconnect','',true);
		}
		$post_fields .= '&redirect_uri='. network::getNetworkAccess('external') . '/plugins/homeconnect/core/php/callback.php?apikey=' . jeedom::getApiKey('homeconnect');
		$post_fields .= '&grant_type=authorization_code';
		$post_fields .= '&code='.config::byKey('auth','homeconnect');
		log::add('homeconnect', 'debug', "Post fields = ". $post_fields);
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
		log::add('homeconnect', 'debug', "Response = ". print_r($response, true));
		$http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
		curl_close ($curl);

		// Vérification du code réponse.
		if($http_code != 200) {

			log::add('homeconnect', 'debug', "[Erreur] (code erreur : ".$http_code.") : Impossible de récupérer le token.");
			throw new Exception("Erreur : Impossible de récupérer le token (code erreur : ".$http_code.").");
			return;

		} else {

			log::add('homeconnect', 'debug', "Token récupéré.");
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

		log::add('homeconnect', 'debug',"Access token : ".$response['access_token']);
		log::add('homeconnect', 'debug',"Refresh token : ".$response['refresh_token']);
		log::add('homeconnect', 'debug',"Token type : ".$response['token_type']);
		log::add('homeconnect', 'debug',"scope : ".$response['scope']);
		log::add('homeconnect', 'debug',"Expires in : ".$expires_in);
		log::add('homeconnect', 'debug',"Id token : ".$response['id_token']);
		log::add('homeconnect', 'debug',"Fin de la fonction tokenRequest()");
	}

	public static function tokenRefresh() {
	/**
	 * Rafraichit un token expiré permettant l'accès au serveur.
	 *
	 * @param			|*Cette fonction ne prend pas de paramètres*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"Fonction tokenRefresh()");

		// Vérification de la présence du code d'authorisation avant de demander le token.
		if (empty(config::byKey('auth','homeconnect'))) {

			log::add('homeconnect', 'debug', "[Erreur] : Code d'authorisation vide.");
			throw new Exception("Erreur : Veuillez connecter votre compte via le menu configuration du plugin.");
			return;
		}
		$url = self::baseUrl() . self::API_TOKEN_URL;
		log::add('homeconnect', 'debug', "Url = ". $url);
		// Création du paramêtre POSTFIELDS.
		$post_fields = 'grant_type=refresh_token';
		if (!config::byKey('demo_mode','homeconnect')) {
			$post_fields .= '&client_secret='. config::byKey('client_secret','homeconnect','',true);
		}
		$post_fields .= '&refresh_token='.	config::byKey('refresh_token','homeconnect','',true);

		log::add('homeconnect', 'debug', "Post fields = ". $post_fields);
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
		log::add('homeconnect', 'debug', "Response = ". print_r($response, true));
		$http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
		curl_close ($curl);

		// Vérification du code réponse.
		if($http_code != 200) {

			log::add('homeconnect', 'debug', "[Erreur] (code erreur : ".$http_code.") : Impossible de rafraichir le token.");
			throw new Exception("Erreur : Impossible de rafraichir le token (code erreur : ".$http_code.").");
			return;

		} else {

			log::add('homeconnect', 'debug', "Token rafraichi.");
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

		log::add('homeconnect', 'debug',"Access token : ".$response['access_token']);
		log::add('homeconnect', 'debug',"Refresh token : ".$response['refresh_token']);
		log::add('homeconnect', 'debug',"Token type : ".$response['token_type']);
		log::add('homeconnect', 'debug',"scope : ".$response['scope']);
		log::add('homeconnect', 'debug',"Expires in : ".$expires_in);
		log::add('homeconnect', 'debug',"Id token : ".$response['id_token']);
		log::add('homeconnect', 'debug',"Fin de la fonction tokenRefresh()");
	}

	public static function verifyToken($delay) {
		// Vérification si le token est expiré.
		if ((config::byKey('expires_in','homeconnect') - time()) < $delay) {

			log::add('homeconnect', 'debug', "[Warning] : Le token est expiré, renouvellement de ce dernier.");

			// Récupération du token d'accès aux serveurs.
			// ne pas oublier d'arrêter le deamon avant
			self::tokenRefresh();
		}

		// Vérification de la présence du token et tentative de récupération si absent.
		if (empty(config::byKey('access_token','homeconnect'))) {

			log::add('homeconnect', 'debug', "[Warning] : Le token est manquant, récupération de ce dernier.");

			// Récupération du token d'accès aux serveurs.
			self::tokenRequest();

			if (empty(config::byKey('access_token','homeconnect'))) {
				log::add('homeconnect', 'debug', "[Erreur]: La récupération du token a échoué.");
				return;
			}
			// Dans le cas contraire relancer le deamon
		}
		// Relancer le deamon
	}

	private static function homeappliances() {
	/**
	 * Récupère la liste des appareils connectés et création des objets associés.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"---------- Début de synchronisation ----------");

		self::verifyToken(60);

		$response = self::request(self::API_REQUEST_URL, null, 'GET', array());
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
				event::add('jeedom::alert', array(
					'level' => 'warning',
					'page' => 'homeconnect',
					'message' => __("Nouvel appareil detecté", __FILE__). ' ' .$appliance['name'],
				));
				// Création de l'appareil.
				$eqLogic = new homeconnect();
				$eqLogic->setLogicalId($appliance['haId']);
				$eqLogic->setIsEnable(1);
				$eqLogic->setIsVisible(1);
				$defaultRoom = intval(config::byKey('defaultParentObject','homeconnect','',true));
				if($defaultRoom) $eqLogic->setObject_id($defaultRoom);
				$eqLogic->setEqType_name('homeconnect');
				$eqLogic->setName($appliance['name']);
				$eqLogic->setConfiguration('haid', $appliance['haId']);
				$eqLogic->setConfiguration('vib', $appliance['vib']);
				$eqLogic->setConfiguration('brand', $appliance['brand']);
				$eqLogic->setConfiguration('type', $appliance['type']);
				$eqLogic->save();
				$found_eqLogics = self::findProduct($appliance);
				// certains apareils ne répodent pas pour les programmes et options s'ils ne sont pas connectés
				if ($appliance['connected']) {
				// Programs
				if ($appliance['type'] !== 'Refrigerator' && $appliance['type'] !== 'FridgeFreezer' && $appliance['type'] !== 'WineCooler') {
					$programs = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/programs', null, 'GET', array());
					if ($programs !== false) {
						$programs = json_decode($programs, true);
						if (isset($programs['data']['programs'])) {
							$eqLogic->setConfiguration('hasPrograms', true);
							foreach($programs['data']['programs'] as $applianceProgram) {
								$programdata = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/programs/available/' . $applianceProgram['key'], null, 'GET', array());
								log::add('homeconnect','debug', 'Appliance Program ' . print_r($programdata, true));
								if ($programdata !== false) {
									$programdata = json_decode($programdata, true);
									if (isset($applianceProgram['constraints']['execution'])) {
										if ($applianceProgram['constraints']['execution'] !== 'selectandstart') {
											$path = 'programs/active';
										} else {
											$path = 'programs/selected';
										}
									} else {
										$path = 'programs/selected';
									}
									if (isset($programdata['data']['key'])) {
										// Création de la commande action programme
										$actionCmd = $eqLogic->createActionCmd($programdata['data'], $path , 'Program');
										if ($path == 'programs/selected') {
											$infoCmd = $eqLogic->getCmd('info', 'programSelected');
											if (is_object($infoCmd)) {
												// On a trouvé la commande info associée.
												log::add('homeconnect', 'debug', "setValue sur la commande programme selected " . $actionCmd->getLogicalId() . " commande info " .$infoCmd->getLogicalId());
												$actionCmd->setValue($infoCmd->getId());
												$actionCmd->save();
											} else {
												log::add('homeconnect', 'debug', "Pas de commande info programSelected");
											}
										} else if ($path == 'programs/active') {
											$infoCmd = $eqLogic->getCmd('info', 'programActive');
											if (is_object($infoCmd)) {
												// On a trouvé la commande info associée.
												log::add('homeconnect', 'debug', "setValue sur la commande programme active " . $actionCmd->getLogicalId() . " commande info " .$infoCmd->getLogicalId());
												$actionCmd->setValue($infoCmd->getId());
												$actionCmd->save();
												// A voir : ne pas la rendre visible ?
											} else {
												log::add('homeconnect', 'debug', "Pas de commande info programActive");
											}
										}
									}
									if (isset($programdata['data']['options'])) {
										log::add('homeconnect', 'debug', "Création des commandes options " . print_r($programdata['data']['options'], true));
										// creation des commandes option action et info
										foreach($programdata['data']['options'] as $optionData) {
											if (isset($optionData['key'])) {
												if ($optionData['key'] !== 'BSH.Common.Option.StartInRelative') {
													$optionPath = $path . '/options/' . $optionData['key'];
												} else {
													// Cette option ne peut pas être utilisée avec selected uniquement avec active
													$optionPath = 'programs/active/options/' . $optionData['key'];
												}
												$actionCmd = $eqLogic->createActionCmd($optionData, $optionPath, 'Option');
												$infoCmd = $eqLogic->createInfoCmd($optionData, $optionPath, 'Option', $actionCmd);
												// le setValue est fait dans createInfoCmd
											}
										}
									} else {
										log::add('homeconnect', 'debug', "Aucune commande option");
									}
								} else {
									log::add('homeconnect', 'debug', "La requête /programs/available/ a retourné false");
								}
							}
						} else {
							log::add('homeconnect', 'debug',"Cet appareil n'a pas de programmes");
							$eqLogic->setConfiguration('hasPrograms', false);
						}
					} else {
						log::add('homeconnect', 'debug',"La requête /programs a retourné false");
						$eqLogic->setConfiguration('hasPrograms', false);
					}
				} else {
					log::add('homeconnect', 'debug',"Ce type d'appareil n'a pas de programme");
					$eqLogic->setConfiguration('hasPrograms', false);
				}
					
				// Status

				$allStatus = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/status', null, 'GET', array());
				if ($allStatus !== false) {
					$allStatus = json_decode($allStatus, true);
					if (isset($allStatus['data']['status'])) {
						foreach($allStatus['data']['status'] as $statusData) {
							log::add('homeconnect', 'debug', "Status " . print_r($statusData, true));
							$eqLogic->createInfoCmd($statusData, 'status/' . $statusData['key'], 'Status');
						}
					} else {
						log::add('homeconnect','debug', "Aucun status");
					}
				}
				// Settings

				$allSettings = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/settings', null, 'GET', array());
				log::add('homeconnect', 'debug', "tous les Settings " . $allSettings);
				if ($allSettings !== false) {
					$allSettings = json_decode($allSettings, true);
					if (isset($allSettings['data']['settings'])) {
						foreach($allSettings['data']['settings'] as $setting) {
							log::add('homeconnect', 'debug', "setting key " . $setting['key']);
							$path = '/settings/' . $setting['key'];
							$settingData = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . $path, null, 'GET', array());
							if ($settingData !== false) {
								log::add('homeconnect', 'debug', "Setting " . $settingData);
								$settingData = json_decode($settingData, true);
								// A voir si pas d'access on assume readWrite. est-ce correct ?
								if (isset($settingData['data']['constraints']['access']) && $settingData['data']['constraints']['access'] == 'readWrite') {
									log::add('homeconnect', 'debug', "Le settin est readWrite, on crée aussi la commande setting action");
									$actionCmd = $eqLogic->createActionCmd($settingData['data'], $path, 'Setting');
									log::add('homeconnect', 'debug', "On crée aussi la commande setting info");
									$infoCmd = $eqLogic->createInfoCmd($settingData['data'], $path, 'Setting', $actionCmd);
									// le setValue est fait dans createInfoCmd
								} else {
									// Commande info sans commande action associée
									log::add('homeconnect', 'debug', "Le setting est non readWrite, on ne crée que la commande setting info");
									$infoCmd = $eqLogic->createInfoCmd($settingData['data'], $path, 'Setting');
								}
							}
						}
					} else {
						log::add('homeconnect','debug', "Aucun setting");
					}
				}
				} else {
					// L'appareil n'est pas connecté
					event::add('jeedom::alert', array(
						'level' => 'danger',
						'page' => 'homeconnect',
						'message' => __("L'appareil n'est pas connecté. Merci de le connecter et de refaire une synchronisation", __FILE__),
					));
					sleep(3);
				}
			}
		}
		log::add('homeconnect', 'debug',"---------- Fin de synchronisation ----------");
	}

	private static function majConnected() {
	/**
	 * Récupère le statut connecté des l'appareils.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"Fonction majConnected()");

		// A voir si l'appareil vient de se connecter n'y aurait-il pas des choses à faire ?
		$response = self::request(self::API_REQUEST_URL, null, 'GET', array());
		$response = json_decode($response, true);
		foreach($response['data']['homeappliances'] as $key) {
			/* connected = boolean */

			$eqLogic = eqLogic::byLogicalId($key['haId'], 'homeconnect');
			if (is_object($eqLogic) && $eqLogic->getIsEnable()){
				$cmd = $eqLogic->getCmd(null, 'connected');
				if (is_object($cmd)) {
					$eqLogic->checkAndUpdateCmd('connected', $key['connected']);

					log::add('homeconnect', 'debug', "MAJ du status connected " . $eqLogic->getConfiguration('type', '') . ' ' . $eqLogic->getConfiguration('haId', '') . ' Valeur : '. $key['connected'] ? "Oui" : "Non");

				} else {
					log::add('homeconnect', 'debug', "Erreur La commande connected n'existe pas :" . $eqLogic->getConfiguration('type', '') . ' ' . $eqLogic->getConfiguration('haId', ''));
				}
			}
		}

		log::add('homeconnect', 'debug',"Fin de la fonction majConnected()");
	}

	public static function findProduct($_appliance) {
		$eqLogic = self::byLogicalId($_appliance['haId'], 'homeconnect');
		$eqLogic->loadCmdFromConf($_appliance['type']);
		return $eqLogic;
	}

	public static function devicesParameters($_type = '') {
		$return = array();
		foreach (ls(dirname(__FILE__) . '/../config/types', '*') as $dir) {
			$path = dirname(__FILE__) . '/../config/types/' . $dir;
			if (!is_dir($path)) {
				continue;
			}
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
		if (isset($_type) && $_type !== '') {
			if (isset($return[$_type])) {
				return $return[$_type];
			}
			return array();
		}
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
				'Auto1' => __("Auto 35-45°C", __FILE__),
				'Auto2' => __("Auto 45-65°C", __FILE__),
				'Auto3' => __("Auto 65-75°C", __FILE__),
				'Cotton' => __("Coton", __FILE__),
				'CupboardDry' => __("Prêt à ranger", __FILE__),
				'CupboardDryPlus' => __("Prêt à ranger plus", __FILE__),
				'DelicatesSilk' => __("Délicat / Soie", __FILE__),
				'BeanAmount' => __("Quantité de café", __FILE__),
				'DoubleShot' => __("Double shot", __FILE__),
				'DoubleShotPlus' => __("Double shot plus", __FILE__),
				'EasyCare' => __("Synthétique", __FILE__),
				'Eco50' => __("Eco 50°C", __FILE__),
				'Intensiv70' => __("Intensif 70°C", __FILE__),
				'Normal65' => __("Normal 65°C", __FILE__),
				'Glas40' => __("Verres 40°C", __FILE__),
				'GlassCare' => __("Soin des verres", __FILE__),
				'Quick65' => __("Rapide 65°C", __FILE__),
				'HotAir' => __("Air chaud", __FILE__),
				'IronDry' => __("Prêt à repasser", __FILE__),
				'VeryMild' => __("Très doux", __FILE__),
				'Mild' => __("Doux", __FILE__),
				'Mix' => __("Mélangé", __FILE__),
				'Normal' => __("Normal", __FILE__),
				'PizzaSetting' => __("Position Pizza", __FILE__),
				'Preheating' => __("Préchauffage", __FILE__),
				'Quick45' => __("Rapide 45°C", __FILE__),
				'Strong' => __("Fort", __FILE__),
				'Synthetic' => __("Synthétique", __FILE__),
				'TopBottomHeating' => __("Convection naturelle", __FILE__),
				'VeryStrong' => __("Très fort", __FILE__),
				'Wool' => __("Laine", __FILE__),
				'Ready' => __("Prêt", __FILE__),
				'Inactive' => __("Inactif", __FILE__),
				'Delayed Start' => __("Départ différé", __FILE__),
				'Pause' => __("Pause", __FILE__),
				'Run' => __("Marche", __FILE__),
				'Finished' => __("Terminé", __FILE__),
				'Error' => __("Erreur", __FILE__),
				'Action Required' => __("Action requise", __FILE__),
				'Aborting' => __("Abandon", __FILE__),
				'On' => __("Marche", __FILE__),
				'Off' => __("Arrêt", __FILE__),
				'Standby' => __("En attente", __FILE__),
				'Open' => __("Ouverte", __FILE__),
				'Closed' => __("Fermée", __FILE__),
				'Locked' => __("Verrouillée", __FILE__),
				'Coffee' => __("Café", __FILE__),
				'Duration' => __("Durée", __FILE__),
				'PreHeating' => __("Préchauffage", __FILE__),
				'Temperature' => __("Température", __FILE__),
				'SetpointTemperature' => __("Consigne température", __FILE__),
				'DryingTarget' => __("Cible de séchage", __FILE__),
				'Cold' => __("Froid", __FILE__),
				'CoffeeTemperature' => __("Température du café", __FILE__),
				'SpinSpeed' => __("Essorage", __FILE__),
				'RPM400' => __("400 tr/min", __FILE__),
				'RPM600' => __("600 tr/min", __FILE__),
				'RPM800' => __("800 tr/min", __FILE__),
				'RPM1000' => __("1000 tr/min", __FILE__),
				'RPM1200' => __("1200 tr/min", __FILE__),
				'RPM1400' => __("1400 tr/min", __FILE__),
				'RPM1600' => __("1600 tr/min", __FILE__),
				'StartInRelative' => __("Départ différé", __FILE__),
				'GC20' => __("20°C", __FILE__),
				'GC30' => __("30°C", __FILE__),
				'GC40' => __("40°C", __FILE__),
				'GC50' => __("50°C", __FILE__),
				'GC60' => __("60°C", __FILE__),
				'GC70' => __("70°C", __FILE__),
				'GC80' => __("80°C", __FILE__),
				'GC90' => __("90°C", __FILE__),
				'FillQuantity' => __("Contenance", __FILE__),
				'DoorState' => __("Porte", __FILE__),
				'RemoteControlStartAllowed' => __("Démarrage à distance", __FILE__),
				'RemoteControlActive' => __("Contrôle à distance", __FILE__),
				'LocalControlActive' => __("Appareil en fonctionnement", __FILE__),
				'OperationState' => __("Statut de fonctionnement", __FILE__),
				'PowerState' => __("Statut de puissance", __FILE__),
				'CurrentCavityTemperature' => __("Température actuelle", __FILE__),
				'88C' => __("88°C", __FILE__),
				'90C' => __("90°C", __FILE__),
				'92C' => __("92°C", __FILE__),
				'94C' => __("94°C", __FILE__),
				'95C' => __("95°C", __FILE__),
				'96C' => __("96°C", __FILE__),
		];

		(array_key_exists($word, $translate) == True) ? $word = $translate[$word] : null;

		return $word;
	}

	public static function deleteEqLogic() {
		foreach (eqLogic::byType('homeconnect') as $eqLogic) {
			$eqLogic->remove();
		}
	}
	/*
	 * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom */
	/*public static function cron15() {
		self::updateAppliances();
	  } */

	/*
	 * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom */
	  public static function cron() {
		self::verifyToken(180);
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
	public function createActionCmd($cmdData, $path, $category) {
		$key = $cmdData['key'];
		log::add('homeconnect', 'debug', "Création d'une commande action key=" . $key . " path=" . $path . " category= " . $category);
		$logicalIdCmd = 'PUT::' . $key;
		$cmd = $this->getCmd(null, $logicalIdCmd);
		if (!is_object($cmd)) {
			// La commande n'existe pas, on la créée
			$cmd = new homeconnectCmd();
			$name = self::traduction(self::lastSegment('.', $key));
			if ($this->cmdNameExists($name)) {
				$cmd->setName('Action ' . $name);
			} else {
				$cmd->setName($name);
			}
			$cmd->setLogicalId($logicalIdCmd);
			$cmd->setIsVisible(1);
			$cmd->setIsHistorized(0);
			// A voir en s'inspirant de homebridge homeconnect
			$cmd->setDisplay('generic_type', 'DONT');
			$cmd->setConfiguration('path', $path);
			$cmd->setConfiguration('key', $key);
			$cmd->setConfiguration('category', $category);
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			if ($cmdData['type'] == 'Int' || $cmdData['type'] == 'Double') {
				// commande slider.
				log::add('homeconnect', 'debug', "Nouvelle commande slider logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
				$cmd->setSubType('slider');
				$cmd->setConfiguration('value', '#slider#');
				if (isset($cmdData['unit'])) {
					$cmd->setConfiguration('unit', $cmdData['unit']);
					if ($cmdData['unit'] == 'seconds') {
						$cmd->setUnite('s');
					} else {
						$cmd->setUnite($cmdData['unit']);
					}

				} else {
					$cmd->setUnite('');
				}
				if (isset($cmdData['constraints']['min']) && isset($cmdData['constraints']['max'])) {
					$cmd->setConfiguration('minValue', $cmdData['constraints']['min']);
					$cmd->setConfiguration('maxValue', $cmdData['constraints']['max']);
				}
				log::add('homeconnect', 'debug', "Min = " . $cmd->getConfiguration('minValue') . " Max = " .$cmd->getConfiguration('maxValue') . " Unité = " . $cmd->getUnite());
				if ($cmd->getConfiguration('maxValue') < 1000) {
					$cmd->setTemplate('dashboard', 'button');
					$cmd->setTemplate('mobile', 'button');
				}
				/*else {
					$cmd->setTemplate('dashboard', 'bigbutton');
					$cmd->setTemplate('mobile', 'bigbutton');
				}*/
				$arr = $cmd->getDisplay('parameters');
				if (!is_array($arr)) {
					$arr = array();
				}
				if (isset($cmdData['constraints']['stepsize'])) {
					$cmd->setConfiguration('step', $cmdData['constraints']['stepsize']);
					$arr['step'] = $cmdData['constraints']['stepsize'];
				} else {
					$$arr['step'] = 1;
				}
				/*if ($cmd->getConfiguration('maxValue') >= 1000) {
						$arr['bigstep'] = 900;
				}*/
				$cmd->setDisplay('parameters', $arr);
				$cmd->save();
			} else if (strpos($cmdData['type'], 'EnumType') !== false) {
				// Commande select
				log::add('homeconnect', 'debug', "Nouvelle commande select logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
				$cmd->setSubType('select');
				$cmd->setConfiguration('value', '#select#');
				$optionValues = array();
				foreach ($cmdData['constraints']['allowedvalues'] as $optionValue) {
					$optionValues[] = $optionValue . '|' . self::traduction(self::lastSegment('.', $optionValue));
				}
				$listValue = implode(';', $optionValues);
				$cmd->setConfiguration('listValue', $listValue);
				$cmd->save();
			} else {
				log::add('homeconnect', 'debug', "Nouvelle commande other logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
				$cmd->setSubType('other');
				$cmd->save();
			}
		} else {
			log::add('homeconnect', 'debug', "La commande " . $logicalIdCmd . " et nom " . $cmd->getName() . " existe déjà" );
		}
		return $cmd;
	}

	public function createInfoCmd($cmdData, $path, $category, $actionCmd = null) {
		$key = $cmdData['key'];
		log::add('homeconnect', 'debug', "Création d'une commande info key=" . $key . " path=" . $path . " category= " . $category);
		$logicalIdCmd = 'GET::' . $key;
		$cmd = $this->getCmd(null, $logicalIdCmd);
		if (!is_object($cmd)) {
			// La commande n'existe pas, on la créée
			$cmd = new homeconnectCmd();
			$name = self::traduction(self::lastSegment('.', $key));
			if ($this->cmdNameExists($name)) {
				$cmd->setName('Info ' . $name);
			} else {
				$cmd->setName($name);
			}
			log::add('homeconnect', 'debug', "Nouvelle commande info : logicalId " . $logicalIdCmd . ' et nom ' . $cmd->getName());
			$cmd->setLogicalId($logicalIdCmd);
			$cmd->setIsVisible(1);
			$cmd->setIsHistorized(0);
			// A voir en s'inspirant de homebridge homeconnect
			$cmd->setDisplay('generic_type', 'DONT');
			$cmd->setConfiguration('path', $path);
			$cmd->setConfiguration('key', $key);
			$cmd->setConfiguration('withAction', false);
			$cmd->setConfiguration('category', $category);
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('info');
			if (isset($actionCmd)) {
				// Il y aune commande action associée
				// On ne l'affiche pas
				$cmd->setIsVisible(0);
				$cmd->setConfiguration('withAction', true);
				// Détermination du subtype à partir de la commande action
				if ($actionCmd->getSubType() == 'slider') {
					// commande numeric.
					log::add('homeconnect', 'debug', "Création d'une commande info numeric à partir de la commande action");
					$cmd->setSubType('numeric');
					$cmd->setConfiguration('minValue', $actionCmd->getConfiguration('minValue', 0));
					$cmd->setConfiguration('maxValue', $actionCmd->getConfiguration('maxValue', 100));
					$cmd->setUnite($actionCmd->getUnite());
					log::add('homeconnect', 'debug', "Min = " . $cmd->getConfiguration('minValue') . " Max = " .$cmd->getConfiguration('maxValue') . " Unité = " . $cmd->getUnite());
					$cmd->save();
				} else if ($actionCmd->getSubType() == 'select') {
					// Commande string
					log::add('homeconnect', 'debug', "Création d'une commande info string à partir de la commande action");
					$cmd->setSubType('string');
					$cmd->save();
				} else if ($actionCmd->getSubType() == 'other') {
					// Commande string
					log::add('homeconnect', 'debug', "Création d'une commande info other à partir de la commande action");
					$cmd->setSubType('string');
					$cmd->save();
				} else {
					log::add('homeconnect', 'debug', "Problème avec le subtype de la commande action associée " . $actionCmd->getSubType());
				}
				log::add('homeconnect', 'debug', "setValue sur la commande " . $category  . " " . $actionCmd->getLogicalId() . " commande info " .$cmd->getLogicalId());
				$actionCmd->setValue($cmd->getId());
				$actionCmd->save();
			} else if (isset($cmdData['type'])) {
				// Determination du subType a l'aide de l'étiquette type
				if ($cmdData['type'] == 'Int' || $cmdData['type'] == 'Double') {
					// commande numeric.
					log::add('homeconnect', 'debug', "Création d'une commande info numeric à partir de l'étiquette type");
					$cmd->setSubType('numeric');
					if (isset($cmdData['unit'])) {
						$cmd->setConfiguration('unit', $cmdData['unit']);
						if ($cmdData['unit'] == 'seconds') {
							$cmd->setUnite('s');
						} else {
							$cmd->setUnite($cmdData['unit']);
						}

					} else {
						$cmd->setUnite('');
					}
					if (isset($cmdData['constraints']['min']) && isset($cmdData['constraints']['max'])) {
						$cmd->setConfiguration('minValue', $cmdData['constraints']['min']);
						$cmd->setConfiguration('maxValue', $cmdData['constraints']['max']);
					}
					log::add('homeconnect', 'debug', "Min = " . $cmd->getConfiguration('minValue') . " Max = " .$cmd->getConfiguration('maxValue') . " Unité = " . $cmd->getUnite());
					$cmd->save();
				} else if (strpos($cmdData['type'], 'EnumType') !== false) {
					// Commande string
					log::add('homeconnect', 'debug', "Création d'une commande string à partir de l'étiquette type");
					$cmd->setSubType('string');
					$cmd->save();
				} else if ($cmdData['type'] == 'Boolean') {
					log::add('homeconnect', 'debug', "Création d'une commande binary à partir de l'étiquette type");
					$cmd->setSubType('binary');
					$cmd->save();
				}

			} else if (isset ($cmdData['value'])) {
				// détermination du subtype à partir de value
				if ($cmdData['value'] === true || $cmdData['value'] === false) {
					log::add('homeconnect', 'debug', "Création d'une commande binary à partir de la value");
					$cmd->setSubType('binary');
					$cmd->save();
				} else if (strpos($cmdData['value'], 'EnumType') !== false) {
					log::add('homeconnect', 'debug', "Création d'une commande string à partir de la value");
					$cmd->setSubType('string');
					$cmd->save();
				} else if (is_numeric($cmdData['value'])) {
					log::add('homeconnect', 'debug', "Création d'une commande numeric à partir de la value");
					$cmd->setSubType('numeric');
					$cmd->save();
				} else {
					log::add('homeconnect', 'debug', "Impossible de trouver le subType à partir de value " . print_r($cmdData, true));
				}
			} else {
				log::add('homeconnect', 'debug', "Impossible de trouver le subType " . print_r($cmdData, true));
			}
		} else {
			log::add('homeconnect', 'debug', "La commande " . $logicalIdCmd . " et nom " . $cmd->getName() . " existe déjà" );
		}
		return $cmd;
	}

	public function cmdNameExists($name) {
		$allCmd = cmd::byEqLogicId($this->getId());
		foreach($allCmd as $u) {
			if($name == $u->getName()) {
				return true;
			}
		}
		return false;
	}
	public function getImage() {
		$filename = 'plugins/homeconnect/core/config/images/' . $this->getConfiguration('type') . '.png';
		if(file_exists(__DIR__.'/../../../../'.$filename)){
			return $filename;
		}
		return 'plugins/homeconnect/plugin_info/homeconnect_icon.png';
	}

	public function applyModuleConfiguration() {
		$this->setConfiguration('applyType', $this->getConfiguration('type'));
		$this->save();
		if ($this->getConfiguration('type') == '') {
		  return true;
		}
		$device = self::devicesParameters($this->getConfiguration('type'));
		if (!is_array($device)) {
			return true;
		}
		$this->import($device);
	}

	public function preInsert() {

	}

	public function isConnected() {
		$cmdConnected = $this->getCmd(null, 'connected');
		if (is_object($cmdConnected)) {
			if ($this->getIsEnable() && $cmdConnected->execCmd()) {
				return true;
			} else {
				return false;
			}
		} else {
			log::add('homeconnect', 'debug', "[Erreur] La commande connected n'existe pas :");
			log::add('homeconnect', 'debug', "Type : " . $this->getConfiguration('type', ''));
			log::add('homeconnect', 'debug', "Marque : " . $this->getConfiguration('brand', ''));
			log::add('homeconnect', 'debug', "Modèle : " . $this->getConfiguration('vib', ''));
			log::add('homeconnect', 'debug', "Id : " . $this->getLogicalId());
		}
	}

	public function loadCmdFromConf($type) {
		log::add('homeconnect', 'debug',"Fonction loadCmdFromConf($type)");
		if (!is_file(dirname(__FILE__) . '/../config/types/' . $type . '.json')) {
			 log::add('homeconnect', 'debug', "no config file for type $type");
			return;
		}
		$device = is_json(file_get_contents(dirname(__FILE__) . '/../config/types/' . $type . '.json'), array());
		if (!is_array($device) || !isset($device['commands'])) {
			log::add('homeconnect', 'debug', "no command for type $type");
			return true;
		}
		$this->import($device);
		sleep(1);
		event::add('jeedom::alert', array(
			'level' => 'warning',
			'page' => 'openzwave',
			'message' => '',
		));
	}

	public function adjustProgramOptions($typeProgram, $programKey) {
		// Cette fonction est appelée quand il y a eu un changement de programme (actif ou sélectionné) et ajuste les options en fonction de ce programme
		log::add('homeconnect', 'debug', "Appel de la fonction adjustProgramOptions pour le type de programme $typeProgram clé $programKey");
		$programdata = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/programs/available/' . $programKey, null, 'GET', array());
		log::add('homeconnect', 'debug', "Résultat de la requête " . $programdata);
		$programdata = json_decode($programdata, true);
		if (isset($programdata['data']['options'])) {
			foreach($programdata['data']['options'] as $optionData) {
				if (isset($optionData['key'])) {
					$key = $optionData['key'];
					// Commande option action
					$logicalIdCmd = 'PUT::' . $key;
					log::add('homeconnect', 'debug', "Ajustement de la commande action " . $logicalIdCmd);
					$cmd = $this->getCmd(null, $logicalIdCmd);
					if (is_object($cmd)) {
						if ($cmd->getSubType() == 'slider') {
							// commande slider.
							log::add('homeconnect', 'debug', "Ajustement commande action slider logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
							if (isset($optionData['unit'])) {
								$cmd->setConfiguration('unit', $optionData['unit']);
								if ($optionData['unit'] == 'seconds') {
									$cmd->setUnite('s');
								} else {
									$cmd->setUnite($optionData['unit']);
								}

							} else {
								$cmd->setUnite('');
							}
							if (isset($optionData['constraints']['min']) && isset($optionData['constraints']['max'])) {
								$cmd->setConfiguration('minValue', $optionData['constraints']['min']);
								$cmd->setConfiguration('maxValue', $optionData['constraints']['max']);
							}
							log::add('homeconnect', 'debug', "Min = " . $cmd->getConfiguration('minValue') . " Max = " .$cmd->getConfiguration('maxValue') . " Unité = " . $cmd->getUnite());
							$arr = $cmd->getDisplay('parameters');
							if (!is_array($arr)) {
								$arr = array();
							}
							if (isset($optionData['constraints']['stepsize'])) {
								$cmd->setConfiguration('step', $optionData['constraints']['stepsize']);
								$arr['step'] = $optionData['constraints']['stepsize'];
							} else {
								$$arr['step'] = 1;
							}
							/*if ($cmd->getConfiguration('maxValue') >= 1000) {
									$arr['bigstep'] = 900;
							}*/
							$cmd->setDisplay('parameters', $arr);
							$cmd->save();
						} else if ($cmd->getSubType() == 'select') {
							// Commande select
							log::add('homeconnect', 'debug', "Ajustement commande action select logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
							$optionValues = array();
							foreach ($optionData['constraints']['allowedvalues'] as $optionValue) {
								$optionValues[] = $optionValue . '|' . self::traduction(self::lastSegment('.', $optionValue));
							}
							$listValue = implode(';', $optionValues);
							log::add('homeconnect', 'debug', "listValue " . $listValue);
							$cmd->setConfiguration('listValue', $listValue);
							$cmd->save();
						} else {
							log::add('homeconnect', 'debug', "Commande action other rien à ajuster " . $logicalIdCmd . " nom ". $cmd->getName() . ' subtype ' . $cmd->getSubType());
						}
					} else {
						log::add('homeconnect', 'debug', "La commande " . $logicalIdCmd . " n'existe pas impossible de l'ajuster" );
					}
					// commande option info
					$logicalIdCmd = 'GET::' . $key;
					log::add('homeconnect', 'debug', "Ajustement de la commande info " . $logicalIdCmd);
					$cmd = $this->getCmd(null, $logicalIdCmd);
					if (is_object($cmd)) {
						if ($cmd->getSubType() == 'numeric') {
							// commande numeric.
							log::add('homeconnect', 'debug', "Ajustement commande info numeric logicalId " . $logicalIdCmd . " nom ". $cmd->getName());
							if (isset($optionData['unit'])) {
								$cmd->setConfiguration('unit', $optionData['unit']);
								if ($optionData['unit'] == 'seconds') {
									$cmd->setUnite('s');
								} else {
									$cmd->setUnite($optionData['unit']);
								}
							} else {
								$cmd->setUnite('');
							}
							if (isset($optionData['constraints']['min']) && isset($optionData['constraints']['max'])) {
								$cmd->setConfiguration('minValue', $optionData['constraints']['min']);
								$cmd->setConfiguration('maxValue', $optionData['constraints']['max']);
							}
							log::add('homeconnect', 'debug', "Min = " . $cmd->getConfiguration('minValue') . " Max = " .$cmd->getConfiguration('maxValue') . " Unité = " . $cmd->getUnite());
							$cmd->save();
						} else {
							// Dans les autres cas il n'y a rien à faire.
							log::add('homeconnect', 'debug', "Rien à ajuster pour une commande info de subType " . $cmd->getSubType());
						}
					} else {
						log::add('homeconnect', 'debug', "La commande " . $logicalIdCmd . " n'existe pas impossible de l'ajuster" );
					}
				} else {
					log::add('homeconnect', 'debug', "Pas de key dans optionData" );
				}
			}
		} else {
			log::add('homeconnect', 'debug', "Pas d'options à ajuster" );
		}
	}

	public function updateInfoCmdValue($logicalId, $value) {
		$cmd = $this->getCmd(null, $logicalId);
		$reglage = '';
		if (is_object($cmd)) {
			if ($cmd->getConfiguration('withAction')) {
				// C'est une commande associée à une commande action pas de traduction
				if (isset($value['value'])) {
					$reglage = $value['value'];
				} else {
					log::add('homeconnect', 'debug', "La commande info : ".$logicalId." n'a pas de valeur");
				}
			} else {
					if (isset($value['value'])) {
						if ($cmd->getSubType() == 'string') {
							$reglage = self::traduction(self::lastSegment('.', $value['value']));
						} else {
							$reglage = $value['value'];
						}
					} else {
						log::add('homeconnect', 'debug', "la commande info : ".$logicalId." n'a pas de valeur");
					}
				}
			$this->checkAndUpdateCmd($logicalId, $reglage);
			log::add('homeconnect', 'debug', "Mise à jour setting : ".$logicalId." - Valeur :".$reglage);
		} else {
			log::add('homeconnect', 'debug', "Dans updateInfoCmdValue la commande : ".$logicalId." n'existe pas");
		}
	}

    public function lookProgram($programType) {
		if ($programType == 'selected') {
			$nameCmd = 'ProgramSelected';
		} else {
			$nameCmd = 'ProgramActive';
		}
		$currentProgram = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/programs/' . $programType, null, 'GET', array());
		if ($currentProgram !== false) {
			log::add('homeconnect', 'debug', "Réponse pour program $programType dans lookProgram " . $currentProgram);
			$currentProgram = json_decode($currentProgram, true);
			if (isset($currentProgram['data']['key']) && $currentProgram['data']['key'] !== 'SDK.Error.No' . $nameCmd) {
				$key = $currentProgram['data']['key'];
				log::add('homeconnect', 'debug', "Program $programType key = " . $key);
				// recherche du programme action associé
				$actionCmd = $this->getCmd('action', 'PUT::' . $key);
				if (!is_object($actionCmd)) {
					log::add('homeconnect', 'debug', "dans lookProgram pas de commande action " . 'PUT::' . $key);
					$programName = self::traduction(self::lastSegment('.', $key));
				} else {
					$programName =$actionCmd->getName();
					log::add('homeconnect', 'debug', "Nom de la commande action " . $programName);
				}
				// MAJ de la commande info ProgramSelected ou ProgramActive.
				$cmd = $this->getCmd(null, $nameCmd);
				if (is_object($cmd)) {
					log::add('homeconnect', 'debug', "Mise à jour de la valeur de la commande action $programType = ".$programName);
					$this->checkAndUpdateCmd($nameCmd, $programName);
					return true;
				} else {
					log::add('homeconnect', 'debug', "La commande $programType n'existe pas :");
				}
			} else {
				// Pas de programme actif
				// A voir : mettre à jour les autres commandes (états et réglages)
				log::add('homeconnect', 'debug', "pas de key ou key = SDK.Error.No" . $nameCmd);
				$this->checkAndUpdateCmd($nameCmd, __("Aucun", __FILE__));
			}
		} else {
			log::add('homeconnect', 'debug', "Dans lookProgram request a retourné faux");
		}
		return false;
	}
	
	public function lookProgramOptions($programType) {
		$programOptions = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/programs/' . $programType .'/options', null, 'GET', array());
		if ($programOptions !== false) {
			log::add('homeconnect', 'debug', "options : " . $programOptions);
			$programOptions = json_decode($programOptions, true);
			// MAJ des options et autres informations du programme en cours.
			foreach ($programOptions['data']['options'] as $value) {
				log::add('homeconnect', 'debug', "option : " . print_r($value, true));
				// Récupération du nom du programme / option.
				$logicalId = 'GET::' . $value['key'];
				$optionCmd = $this->getCmd('info', $logicalId);
				if (is_object($optionCmd)) {
					$this->updateInfoCmdValue($logicalId, $value);
				} else {
					log::add('homeconnect', 'debug', "pas commande info $logicalId pour mise à jour valeur d'une option");
				}
			}
		}
	}

	public function updateProgram() {
		if ($this->isConnected()) {
			$eqLogicType = $this->getConfiguration('type');
			if ($eqLogicType == 'Refrigerator' || $eqLogicType == 'FridgeFreezer' || $eqLogicType == 'WineCooler' || !$this->getConfiguration('hasPrograms', true)) {
				log::add('homeconnect', 'debug', "Pas de programme pour ce type d'appareil");
				return;
			}
			log::add('homeconnect', 'debug', "MAJ du programme actif");
            if ($this->lookProgram('active')) {
				// Il y a un programme actif on regarde ses options
				log::add('homeconnect', 'debug', "Il y a un programme actif");
				$this->lookProgramOptions('active');
			} else {
				// Pas de programme actif on essaie le programme sélectionné
				if ($this->lookProgram('selected')) {
					log::add('homeconnect', 'debug', "i y a un programme sélectionné");
					$this->lookProgramOptions('selected');
				}
			}
		}
	}

	public function updateStates() {
		if ($this->isConnected()) {
			log::add('homeconnect', 'debug', "MAJ des états ".$this->getLogicalId());

			$response = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/status', null, 'GET', array());
			log::add('homeconnect', 'debug', "Réponse dans updateStates : " . $response);
			if ($response !== false) {
				$response = json_decode($response, true);
				foreach($response['data']['status'] as $value) {
					log::add('homeconnect', 'debug', "status : " . print_r($value, true));
					// Récupération du logicalId du status.
					$logicalId = 'GET::' .$value['key'];
					$this->updateInfoCmdValue($logicalId, $value);
				}
			}
		} else {
			log::add('homeconnect', 'debug', "Non connecté, pas de mise à jour des états");
		}
	}

	public function updateSettings() {
		if ($this->isConnected()) {
			log::add('homeconnect', 'debug', "MAJ des réglages ".$this->getLogicalId());

			$response = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/settings', null, 'GET', array());
			log::add('homeconnect', 'debug', "Réponse updateSettings : " . $response);
			if ($response !== false) {
				$response = json_decode($response, true);
				foreach($response['data']['settings'] as $value) {
					log::add('homeconnect', 'debug', "setting : " . print_r($value, true));
					// Récupération du logicalId du setting.
					$logicalId = 'GET::' . $value['key'];
					$this->updateInfoCmdValue($logicalId, $value);
				}
			}
		} else {
			log::add('homeconnect', 'debug', "Non connecté, pas de mise à jour des états");
		}
	}

	public function updateApplianceData() {
		log::add('homeconnect', 'debug',"Fonction updateApplianceData()");
		if ($this->getIsEnable()){
			log::add('homeconnect', 'debug',"Mise à jour du status connecté");
			$response = self::request(self::API_REQUEST_URL, null, 'GET', array());
			$response = json_decode($response, true);
			foreach($response['data']['homeappliances'] as $appliance) {
				log::add('homeconnect', 'debug',"Appareil " . print_r($appliance, true));
				if ($this->getLogicalId() == $appliance['haId']) {
					log::add('homeconnect', 'debug',"On a trouvé le bon");
					$cmd = $this->getCmd(null, 'connected');
					if (is_object($cmd)) {
						log::add('homeconnect', 'debug',"Mise à jour commande connectée valeur " . $appliance['connected']);
						$this->checkAndUpdateCmd('connected', $appliance['connected']);
					}
				}
			}
			$this->updateProgram();
			$this->updateStates();
			$this->updateSettings();
			$this->refreshWidget();
		}
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
		if ($this->getConfiguration('applyType') != $this->getConfiguration('type')) {
			$this->applyModuleConfiguration();
			$this->refreshWidget();
		}
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
		// Bien penser dans les fichiers json à mettre dans la configuration
		// key, value, type, constraints et à modifier findProduct
		log::add('homeconnect', 'debug',"Fonction execute()");
		homeconnect::verifyToken(60);

		if ($this->getType() == 'info') {
			log::add('homeconnect', 'debug',"Pas d'execute pour une commande info");
			return;
		}
		$eqLogic = $this->getEqLogic();
		$haid = $eqLogic->getConfiguration('haid', '');
		log::add('homeconnect', 'debug',"logicalId : " . $this->getLogicalId());
		log::add('homeconnect', 'debug',"Options : " . print_r($_options, true));

		if ($this->getLogicalId() == 'DELETE::StopActiveProgram') {
			// Commande Arrêter
			log::add('homeconnect', 'debug',"Commande arrêter");
			// Si l'appareil n'a pas de programme on ne peut pas arrêter
			if (!$eqLogic->getConfiguration('hasPrograms', true)) {
				log::add('homeconnect', 'debug',"L'appareil n'a pas de programmes impossible d'arrêter");
				return;
			}
			// S'il n'y a pas de programme actif on ne peut pas arrêter
			$response = homeconnect::request(homeconnect::API_REQUEST_URL . '/' . $haid . '/programs/active', null, 'GET', array());
			if ($response == false || $response == 'SDK.Error.NoProgramActive') {
				log::add('homeconnect', 'debug',"Pas de programme actif impossible d'arrêter");
				return;
			}
		}
		// Pour la commande arrêter le traitement continue

		if ($this->getLogicalId() == 'start') {
			// Commande Lancer
			log::add('homeconnect', 'debug',"Commande lancer");
			// Si l'appareil n'a pas de programme on ne peut pas lancer
			if (!$eqLogic->getConfiguration('hasPrograms', true)) {
				log::add('homeconnect', 'debug',"L'appareil n'a pas de programmes, impossible de lancer");
				return;
			}

			// On lance le programme sélectionné à condition qu'il existe
			log::add('homeconnect', 'debug',"Recherche du programme sélectionné");
			$response = homeconnect::request(homeconnect::API_REQUEST_URL . '/' . $haid . '/programs/selected', null, 'GET', array());
			log::add('homeconnect', 'debug',"Réponse du serveur pour le programme sélectionné " . $response);
			if ($response == false) {
				log::add('homeconnect', 'debug',"Pas de programme sélectionné impossible de lancer");
				event::add('jeedom::alert', array(
					'level' => 'warning',
					'message' => __("Sélectionnez un programme avant de lancer", __FILE__),
				));
				return;
			}
			$decodedResponse = json_decode($response, true);
			if(!isset($decodedResponse['data']['key'])) {
				log::add('homeconnect', 'debug',"Pas de programme dans la réponse impossible de lancer");
				return;
			}
			$key = $decodedResponse['data']['key'];
			$selectedProgramCmd = $eqLogic->getCmd(null, 'PUT::' . $key);
			if (!is_object($selectedProgramCmd)) {
				// Commande pour le programme sélectionné non trouvée
				log::add('homeconnect', 'debug',"La commande logicalId " . 'PUT::' . $key . " n'existe pas impossible de lancer");
				return;
			}
			// Si ce n'est pas un programme selectandstart impossible de lancer
			if ($selectedProgramCmd->getConfiguration('path', '') !== 'programs/selected') {
				log::add('homeconnect', 'debug',"Le programme sélectionné n'est pas select and start, impossible de lancer");
				return;
			}
			$url = homeconnect::API_REQUEST_URL . '/'. $haid . '/programs/active';
			$payload = '{"data": {"key": "' . $key . '"}}';
			log::add('homeconnect', 'debug',"url pour le lancement " . $url);
			log::add('homeconnect', 'debug',"payload pour le lancement " . $response);
			$result = homeconnect::request($url, $response, 'PUT', array());
			log::add('homeconnect', 'debug',"Réponse du serveur au lancement " . $result);
			$eqLogic->updateApplianceData();
			return;

		}
		if ($this->getLogicalId() == 'refresh') {
			log::add('homeconnect', 'debug',"| Commande refresh");
			$eqLogic->updateApplianceData();
			return;
		}
		log::add('homeconnect', 'debug',"| Commande générique");
		$parts = explode('::', $this->getLogicalId());
		if (count($parts) !== 2) {
			log::add('homeconnect', 'debug',"Wrong number of parts in command eqLogic");
			return;
		}
		$method = $parts[0];
		$key = $parts[1];
		// A voir : faut il ajouter qqchose aux headers par defaut de request
		$headers = array();


		// Bien penser à mettre la partie après haid de l'url dans configuration path de la commande
		$path = $this->getConfiguration('path', '');
		$replace = array();
		switch ($this->getSubType()) {
			case 'slider':
			$replace['#slider#'] = intval($_options['slider']);
			break;
			case 'color':
			$replace['#color#'] = $_options['color'];
			break;
			case 'select':
			$replace['#select#'] = $_options['select'];
			break;
			case 'message':
			$replace['#title#'] = $_options['title'];
			$replace['#message#'] = $_options['message'];
			if ($_options['message'] == '' && $_options['title'] == '') {
			  throw new Exception(__('Le message et le sujet ne peuvent pas être vide', __FILE__));
			}
			break;
		}

		if ($method == 'DELETE') {
			$payload = null;
		} else {
			$parameters = array('data' => array());
			if ($this->getConfiguration('key') !== '') {
				$parameters['data']['key'] = $this->getConfiguration('key', '');
			}
			if ($this->getConfiguration('value') !== '') {
				if ($this->getConfiguration('value') === true || $this->getConfiguration('value') === false) {
					$parameters['data']['value'] = $this->getConfiguration('value');
				} else {
					$parameters['data']['value'] = str_replace(array_keys($replace),$replace,$this->getConfiguration('value', ''));
				}
			}

			if ($this->getConfiguration('unit', '') !== '') {
				$parameters['data']['unit'] = $this->getConfiguration('unit', '');
			}
			if ($this->getConfiguration('type', '') !== '') {
				$parameters['data']['type'] = $this->getConfiguration('type', '');
			}
			$payload= json_encode($parameters);
		}

		$url = homeconnect::API_REQUEST_URL . '/'. $haid . '/' . $path;
		log::add('homeconnect', 'debug',"Paramètres de la requête pour exécuter la commande :");
		log::add('homeconnect', 'debug',"Method : " . $method);
		log::add('homeconnect', 'debug',"Url : " . $url);
		log::add('homeconnect', 'debug',"Payload : " . $payload);
		$response = homeconnect::request($url, $payload, $method, $headers);
		log::add('homeconnect', 'debug',"Réponse du serveur : " . $response);
		// si la requête est de category program il faut mettre à jour les options
		if ($this->getConfiguration('category') == 'Program') {
			$typeProgram = homeconnect::lastSegment('/', $url);
			$eqLogic->adjustProgramOptions($typeProgram, $this->getConfiguration('key'));
			// A voir dans ce cas ce qu'il faut mettre à jour.
		}
		$eqLogic->updateApplianceData();

	}

	/** *************************** Getters ********************************* */



	/** *************************** Setters ********************************* */



}
?>
