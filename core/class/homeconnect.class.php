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

	protected static function lastSegment($key) {
		if (strpos($key, '.') === false) {
			return '';
		}
		$parts = explode('.', $key);
		return $parts[count($parts) - 1];
	}

	protected static function firstSegment($key) {
		if (strpos($key, '.') === false) {
			return '';
		}
		$parts = explode('.', $key);
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
		if ($code =='200' || $code = '204') {
			return $result;
		} else {
			// Traitement des erreurs
			switch ($code) {
				case 400:
					// "Bad Request", desc: "Error occurred (e.g. validation error - value is out of range)"
					break;
				case 401:
					// "Unauthorized", desc: "No or invalid access token"
					throw new \Exception(__("Le jeton d'authentification au serveur est absent ou invalide. Reconnectez-vous.",__FILE__));
					break;
				case 403:
					// Forbidden", desc: "Scope has not been granted or home appliance is not assigned to HC account"
					throw new \Exception(__("Accès à cette ressource non autorisé ou appareil non lié à cet utilisateur.",__FILE__));
					break;
				case 404:
					$result = json_decode($result, true);
					if ($result['error']['key'] == 'SDK.Error.NoProgramActive' || $result['error']['key'] ='SDK.Error.NoProgramSelected') {
						return $result['error']['key'];
					}
					// Not Found", desc: "This resource is not available (e.g. no images on washing machine)"
					throw new \Exception(__("Cette ressource n'est pas disponible.",__FILE__));
					break;
				case 405:
					// "Method not allowed", desc: "The HTTP Method is not allowed for this resource" },
					throw new \Exception(__("La méthode $method n'est pas permise pour cette ressource.",__FILE__));
					break;
				case 406:
					// "Not Acceptable", desc: "The resource identified by the request is only capable of generating response entities which have content characteristics not acceptable according to the accept headers sent in the request."
					throw new \Exception(__("Impossible de fournir une réponse Les entêtes 'Accept' de la requête ne sont pas acceptés.",__FILE__));
					break;
				case 408:
					// "Request Timeout", desc: "API Server failed to produce an answer or has no connection to backend service"
					throw new \Exception(__("Le serveur n'a pas fourni de réponse dans le temps imparti.",__FILE__));
					break;
				case 409:
					// "Conflict", desc: "Command/Query cannot be executed for the home appliance, the error response contains the error details"
					$result = json_decode($result, true);
					$errorMsg = isset($result['error']['description']) ? $result['error']['description'] : '';
					throw new \Exception(__("Cette requête ne peut pas être exécutée pour cet appareil. " . $errorMsg));
					break;
				case 415:
					// "Unsupported Media Type", desc: "The request's Content-Type is not supported"
					throw new \Exception(__("Le type de contenu de la requête n'est pas pris en charge.",__FILE__));
					break;
				case 429:
					//	"Too Many Requests", desc: "E.g. the number of requests for a specific endpoint exceeded the quota of the client"
					throw new \Exception(__("Vous avez dépassé le nombre de requêtes permises au serveur. Réessayez dans 24h.",__FILE__));
					break;
				case 500:
					// "Internal Server Error", desc: "E.g. in case of a server configuration error or any errors in resource files"
					throw new \Exception(__("Erreur interne du serveur.",__FILE__));
					break;
				case 503:
					// "Service Unavailable", desc: "E.g. if a required backend service is not available"
					throw new \Exception(__("Service indisponible.",__FILE__));
					break;
				default:
				   // Erreur inconnue
				   throw new \Exception(__("Erreur inconnue code $code.",__FILE__));
			}
			log::add('homeconnect','debug'," | La requête $method  : $url a retourné un code = " . $code . ' résultat = '.$result);
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
		log::add('homeconnect', 'debug',"│ url = " . $url);
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction authRequest()");
		return $url;
	}

	public static function authDemoRequest() {
	/**
	 * Récupère un code d'authorisation à échanger contre un token.
	 *
	 * @param			|*Cette fonction ne retourne pas de valeur*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"┌────────── Fonction authRequest()");

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
			log::add('homeconnect', 'debug', "│ [Erreur] (code erreur : ".$info['http_code'].") : ".print_r($matches, true));
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
				log::add('homeconnect', 'debug', "│ Code d'authorisation récupéré (".$explode[1].".");
				homeconnect::tokenRequest();
			}
		}

		log::add('homeconnect', 'debug',"└────────── Fin de la fonction authRequest()");
	}

	public static function tokenRequest() {
	/**
	 * Récupère un token permettant l'accès au serveur.
	 *
	 * @param			|*Cette fonction ne prend pas de paramètres*|
	 * @return			|*Cette fonction ne retourne pas de valeur*|
	 */

		log::add('homeconnect', 'debug',"├────────── Fonction tokenRequest()");
		if (!config::byKey('demo_mode','homeconnect')) {
			$clientId = config::byKey('client_id','homeconnect','',true);
		} else {
			$clientId = config::byKey('demo_client_id','homeconnect','',true);
		}
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
		if (!config::byKey('demo_mode','homeconnect')) {
			$post_fields .= '&client_secret='. config::byKey('client_secret','homeconnect','',true);
		}
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
		if (!config::byKey('demo_mode','homeconnect')) {
			$post_fields .= '&client_secret='. config::byKey('client_secret','homeconnect','',true);
		}
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
	 * Récupère la liste des appareils connectés et création des objets associés.
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
					'message' => __('Nouvel appareil detecté', __FILE__).$_device['name'],
				));
				// Création de l'appareil.
				log::add('homeconnect', 'debug', "│ Création d'un appareil Type : ".self::traduction($appliance['type']));
				$eqLogic = new homeconnect();
				$eqLogic->setLogicalId($appliance['haId']);
				$eqLogic->setIsEnable(1);
				$eqLogic->setIsVisible(1);
				$defaultRoom = intval(config::byKey('defaultParentObject','homeconnect','',true));
				if($defaultRoom) $eqLogic->setObject_id($defaultRoom);
				$eqLogic->setEqType_name('homeconnect');
				$eqLogic->setName($appliance['name']);
			}
			$eqLogic->setConfiguration('haid', $appliance['haId']);
			$eqLogic->setConfiguration('vib', $appliance['vib']);
			$eqLogic->setConfiguration('brand', $appliance['brand']);
			$eqLogic->setConfiguration('type', $appliance['type']);
			$eqLogic->save();
			$found_eqLogics = self::findProduct($appliance);
			// Programs
			if ($appliance['type'] !== 'Refrigerator' && $appliance['type'] !== 'FridgeFreezer' && $appliance['type'] !== 'WineCooler') {
				$programs = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/programs', null, 'GET', array());
				if ($programs !== false) {
					$programs = json_decode($programs, true);
					if (isset($programs['data']['programs'])) {
						$eqLogic->setConfiguration('hasPrograms', true);
						foreach($programs['data']['programs'] as $applianceProgram) {
							$programdata = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/programs/available/' . $applianceProgram['key'], null, 'GET', array());
							log::add('homeconnect','debug', ' | Appliance Program ' . print_r($programdata, true));
							if ($programdata !== false) {
								$programdata = json_decode($programdata, true);
								if (isset($programdata['data']['key'])) {
									// Création de la commande action programme
									$programKey = $programdata['data']['key'];
									$cmd = $eqLogic->getCmd(null, 'PUT::' . $programKey);
									if (!is_object($cmd)) {
										if (isset($applianceProgram['name'])) {
											log::add('homeconnect','debug', 'Nom trouvé pour le programme ' . $applianceProgram['name']);
											$programName = $applianceProgram['name'];
										} else {
											$programName = self::traduction(self::lastSegment($programKey));
										}
										if ($eqLogic->cmdNameExists($programName)) {
											$programName = 'Programme ' . $programName;
										}
										log::add('homeconnect', 'debug', " │ Création de la commande action programme : logicalId " . $programKey . ' nom ' . $programName);
										$cmd = new homeconnectCmd();
										$cmd->setLogicalId('PUT::' . $programKey);
										$cmd->setIsVisible(1);
										$cmd->setName($programName);
										$cmd->setIsHistorized(0);
										$cmd->setDisplay('generic_type', 'DONT');
										$cmd->setConfiguration('request', 'programs/selected');
										$cmd->setConfiguration('key', $programKey);
										$cmd->setEqLogic_id($eqLogic->getId());
										// $cmd->setValue(...);
										$cmd->setType('action');
										// A changer selon le type
										$cmd->setSubType('other');
										$cmd->save();
									}
								}
								if (isset($programdata['data']['options'])) {
									log::add('homeconnect', 'debug', " │ Création des commandes options " . print_r($programdata['data']['options'], true));
									// creation des commandes option action et info
									foreach($programdata['data']['options'] as $programOption) {
										$optionKey = $programOption['key'];
										$cmdAction = $eqLogic->getCmd(null, 'PUT::' . $optionKey);
										if (!is_object($cmdAction)) {
											$name = self::traduction(self::lastSegment($optionKey));
											log::add('homeconnect', 'debug', " │ Création de la commande action option : logicalId " . $optionKey . ' nom ' . $name);
											$cmdAction = new homeconnectCmd();
											$cmdAction->setLogicalId('PUT::' . $optionKey);
											$cmdAction->setIsVisible(1);

											if ($eqLogic->cmdNameExists($name) || $eqLogic->cmdNameExists(self::lastSegment($optionKey))) {
												log::add('homeconnect', 'debug', " │ Nom changé en Option " . $name );
												$cmdAction->setName('Option ' . $name);
											} else {
												$cmdAction->setName($name);
											}
											$cmdAction->setIsHistorized(0);
											$cmdAction->setDisplay('generic_type', 'DONT');
											$cmdAction->setConfiguration('request', 'programs/selected/options');
											$cmdAction->setConfiguration('key', $optionKey);
											$cmdAction->setEqLogic_id($eqLogic->getId());
											$cmdAction->setType('action');
											if ($programOption['type'] == 'Int') {
												// commande slider.
												log::add('homeconnect', 'debug', " │ Création d'une commande slider");
												$cmdAction->setSubType('slider');
												$cmdAction->setConfiguration('value', '#slider#');
												if (isset($programOption['unit'])) {
													$cmdAction->setConfiguration('unit', $programOption['unit']);
													if ($programOption['unit'] == 'seconds') {
														$cmdAction->setUnite('s');
													} else {
														$cmdAction->setUnite($programOption['unit']);
													}

												} else {
													$cmdAction->setUnite('');
												}
												if (isset($programOption['constraints']['min']) && isset($programOption['constraints']['max'])) {
													$cmdAction->setConfiguration('minValue', $programOption['constraints']['min']);
													$cmdAction->setConfiguration('maxValue', $programOption['constraints']['max']);
												}
												log::add('homeconnect', 'debug', " │ Min = " . $cmdAction->getConfiguration('minValue') . " Max = " .$cmdAction->getConfiguration('maxValue') . " Unité = " . $cmdAction->getUnite());
												if ($cmdAction->getConfiguration('maxValue') < 1000) {
													$cmdAction->setTemplate('dashboard', 'button');
													$cmdAction->setTemplate('mobile', 'button');
												} else {
													$cmdAction->setTemplate('dashboard', 'bigbutton');
													$cmdAction->setTemplate('mobile', 'bigbutton');
												}
												$arr = $cmdAction->getDisplay('parameters');
												if (!is_array($arr)) {
													$arr = array();
												}
												if (isset($programOption['constraints']['stepsize'])) {
													$cmdAction->setConfiguration('step', $programOption['constraints']['stepsize']);
													$arr['step'] = $programOption['constraints']['stepsize'];
												} else {
													$$arr['step'] = 1;
												}
												if ($cmdAction->getConfiguration('maxValue') >= 1000) {
														$arr['bigstep'] = 900;
												}
												$cmdAction->setDisplay('parameters', $arr);
												$cmdAction->save();
												if (isset($programOption['constraints']['min']) && $programOption['constraints']['min'] > 0) {
													$eqLogic->checkAndUpdateCmd($cmdAction->getLogicalId(), $programOption['constraints']['min']);
												}
											} else if (strpos($programOption['type'], 'EnumType') !== false) {
												// Commande select
												log::add('homeconnect', 'debug', " │ Création d'une commande select");
												$cmdAction->setSubType('select');
												$cmdAction->setConfiguration('value', '#select#');
												$optionValues = array();
												foreach ($programOption['constraints']['allowedvalues'] as $optionValue) {
													$optionValues[] = $optionValue . '|' . self::traduction(self::lastSegment($optionValue));
												}
												$listValue = implode(';', $optionValues);
												$cmdAction->setConfiguration('listValue', $listValue);
												$cmdAction->save();
											} else {
												log::add('homeconnect', 'debug', " │ Problème avec le type " .$programOption['type']);
											}
											$infoLogicalId = self::lastSegment($optionKey);
											if ($appliance['type'] == 'Oven') {
												log::add('homeconnect', 'debug', "Recherche commande info eqLogic = " . $infoLogicalId);
											}
											$cmdInfo = $eqLogic->getCmd('info', $infoLogicalId);
											if (is_object($cmdInfo)) {
												log::add('homeconnect', 'debug', "Trouvé nom " . $cmdInfo->getName());
												log::add('homeconnect', 'debug', "Trouvé logicalId " . $cmdInfo->getLogicalId());
												// On a trouvé la commande info associée.
												log::add('homeconnect', 'debug', " │ Commande action logicalId " . $cmdAction->getLogicalId() . " associée à la commande info logicalId " . $infoLogicalId . ' id ' . $cmdInfo->getId());
												$cmdAction->setValue($cmdInfo->getId());
												$cmdAction->save();
											} else {
												// A voir si on la crée par programme ou dans le fichier json
												log::add('homeconnect', 'debug', " │ Pas de commande info logicalId " . $infoLogicalId . " associée à la commande action logicalId " . 'PUT::' . $optionKey );
											}
										}
									}
								} else {
									log::add('homeconnect', 'debug', " │ Aucune commande option");
								}
							}
						}
					} else {
						$eqLogic->setConfiguration('hasPrograms', false);
					}
				} else {
					$eqLogic->setConfiguration('hasPrograms', false);
				}
			} else {
				$eqLogic->setConfiguration('hasPrograms', false);
			}
			// Status

			$status = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/status', null, 'GET', array());
			log::add('homeconnect', 'debug', " │ Status : " . $status);
			if ($status !== false) {
				$status = json_decode($status, true);
				if (isset($status['data']['status'])) {
					foreach($status['data']['status'] as $applianceStatus) {
						$statusdata = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/status/' . $applianceStatus['key'], null, 'GET', array());
						log::add('homeconnect','debug', ' | Appliance status ' . print_r($statusdata, true));
					}

					$availableStatus = array();
					foreach($status['data']['status'] as $applianceStatus) {
						$logicalId = self::lastSegment($applianceStatus['key']);
						$availableStatus[$logicalId] = $applianceStatus;
					}
					foreach($eqLogic->getCmd() as $cmd) {
						$id = $cmd->getLogicalId();
						// Attention il y a des status qu'il ne faut pas supprimer. A voir comment faire pour leur valeur.
						if (!array_key_exists($id, $availableStatus) && !$cmd->dontRemoveCmd()) {
							if (self::lastSegment($cmd->getConfiguration('request', '')) == 'Status') {
								// log::add('homeconnect','debug', ' | Suppression de la commande état ' . $cmd->getName() . ' logicalId = ' . $cmd->getLogicalId());
								$cmd->remove();
							}
						} else {
							log::add('homeconnect','debug', 'Status pour nom ' . print_r($availableStatus[$id], true));
						}
					}
				}
			}
			// Settings
			$settings = self::request(self::API_REQUEST_URL . '/' . $appliance['haId'] . '/settings', null, 'GET', array());
			log::add('homeconnect', 'debug', " │ Settings : " . $settings);
			if ($settings !== false) {
				$settings = json_decode($settings, true);
				if (isset($settings['data']['settings'])) {
					$availableSettings = array();
					foreach($settings['data']['settings'] as $applianceSetting) {
						$logicalId = self::lastSegment($applianceSetting['key']);
						$availableSettings[$logicalId] = $applianceSetting;
					}
					foreach($eqLogic->getCmd() as $cmd) {
						$id = $cmd->getLogicalId();
						if (!array_key_exists($id, $availableSettings)&& !$cmd->dontRemoveCmd()) {
							if (self::lastSegment($cmd->getConfiguration('request', '')) == 'Setting') {
								// log::add('homeconnect','debug', ' | Suppression de la commande réglage ' . $cmd->getName() . ' logicalId ' . $cmd->getLogicalId());
								$cmd->remove();
							}
						} else {
							log::add('homeconnect','debug', 'Status pour nom ' . print_r($availableSettings[$id], true));
						}
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

					log::add('homeconnect', 'debug', "│ MAJ d'un appareil :");
					log::add('homeconnect', 'debug', "│ Type : ".self::traduction($key['type']));
					log::add('homeconnect', 'debug', "│ Id : ".$key['haId']);
					log::add('homeconnect', 'debug', "│ Connecté : ".$key['connected']);

				} else {
					log::add('homeconnect', 'debug', "├───── [Erreur]");
					log::add('homeconnect', 'debug', "│ La commande connected n'existe pas :");
					log::add('homeconnect', 'debug', "│ Type : ".self::traduction($key['type']));
					log::add('homeconnect', 'debug', "│ Marque : ".$key['brand']);
					log::add('homeconnect', 'debug', "│ Modèle : ".$key['vib']);
					log::add('homeconnect', 'debug', "│ Id : ".$key['haId']);
				}
			}
		}

		log::add('homeconnect', 'debug',"├───── Fin de la fonction majConnected()");
	}

	public static function findProduct($_appliance) {
		log::add('homeconnect', 'debug',"┌────────── Fonction findProduct($_appliance)");
		$eqLogic = self::byLogicalId($_appliance['haId'], 'homeconnect');
		$eqLogic->loadCmdFromConf($_appliance['type']);
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
				'Mild' => __("Doux", __FILE__),
				'Mix' => __("Mix", __FILE__),
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
	 * Fonction exécutée automatiquement toutes les minutes par Jeedom */
	  public static function cron15() {
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

	public function isConnected() {
		$cmdConnected = $this->getCmd(null, 'connected');
		if (is_object($cmdConnected)) {
			if ($this->getIsEnable() && $cmdConnected->execCmd()) {
				return true;
			} else {
				return false;
			}
		} else {
			log::add('homeconnect', 'debug', "├───── [Erreur]");
			log::add('homeconnect', 'debug', "│ La commande connected n'existe pas :");
			log::add('homeconnect', 'debug', "│ Type : " . $this->getConfiguration('type', ''));
			log::add('homeconnect', 'debug', "│ Marque : " . $this->getConfiguration('brand', ''));
			log::add('homeconnect', 'debug', "│ Modèle : " . $this->getConfiguration('vib', ''));
			log::add('homeconnect', 'debug', "│ Id : " . $this->getLogicalId());
		}
	}

	public function loadCmdFromConf($type) {
		log::add('homeconnect', 'debug',"├────────── loadCmdFromConf($type)");
		if (!is_file(dirname(__FILE__) . '/../config/types/' . $type . '.json')) {
			 log::add('homeconnect', 'debug', "│ no config file for type $type");
			return;
		}
		$device = is_json(file_get_contents(dirname(__FILE__) . '/../config/types/' . $type . '.json'), array());
		if (!is_array($device) || !isset($device['commands'])) {
			log::add('homeconnect', 'debug', "│ no command for type $type");
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
	public function updateProgram() {
		if ($this->isConnected()) {
			$eqLogicType = $this->getConfiguration('type');
			if ($eqLogicType == 'Refrigerator' || $eqLogicType == 'FridgeFreezer' || $eqLogicType == 'WineCooler' || !$this->getConfiguration('hasPrograms', true)) {
				// Pas de programme pour ces types d'appareils
				return;
			}
			log::add('homeconnect', 'debug', "│ MAJ du programme actif :");
			log::add('homeconnect', 'debug', "│ Type : ".$this->getConfiguration('type'));
			log::add('homeconnect', 'debug', "│ Id : ".$this->getLogicalId());
			log::add('homeconnect', 'debug', "│");

			$response = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/programs/active', null, 'GET', array());
			if ($response !== false) {
				$response = json_decode($response, true);

				if (isset($response['key']) && $response['key'] !== 'SDK.Error.NoProgramActive') {
					$key = $response['data']['key'];
					// recherche du programme action associé
					$cmdAction = $this->getCmd('action', $key);
					if (!is_object($cmdAction)) {
						log::add('homeconnect', 'debug', "│ dans updateProgram pas de commande action " . $key);
						$programName = self::traduction(self::lastSegment($key));
					} else {
						$programName =$cmdAction->getName();
					}
					// MAJ du programme en cours.
					$cmd = $this->getCmd(null, 'programActive');
					if (is_object($cmd)) {
						$this->checkAndUpdateCmd('programActive',$programName);
						log::add('homeconnect', 'debug', "│ Programme en cours : ".$programName);
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
						$logicalId = self::lastSegment($value['key']);
						$cmd = $this->getCmd(null, $logicalId);
						$reglage = '';
						if (is_object($cmd)) {
							// Récupération de la valeur du programme / option.
							if (isset($value['displayvalue'])) {
									$reglage = $value['displayvalue'];
							} else {
								if (isset($value['value'])) {
									if ($cmd->getSubType() == 'string') {
										$reglage = self::traduction(self::lastSegment($value['value']));
									} else {
										$reglage = $value['value'];
									}
								} else {
									log::add('homeconnect', 'debug', "│ La commande : ".$logicalId." n'a pas de valeur");
								}
							}
							$this->checkAndUpdateCmd($logicalId, $reglage);
							log::add('homeconnect', 'debug', "│ Option : ".$logicalId." - Valeur :".$reglage);
						} else {
							log::add('homeconnect', 'debug', "│ La commande : ".$logicalId." n'existe pas");
						}
					}
				} else {
					// Pas de programme actif
					$this->checkAndUpdateCmd('programActive', __("Pas de programme actif", __FILE__));
				}
			} else {
				$this->checkAndUpdateCmd('programActive', __("Pas de programme actif", __FILE__));
			}
		}
	}

	public function updateStates() {
		if ($this->isConnected()) {
			log::add('homeconnect', 'debug', "│ MAJ des états ".$this->getLogicalId());

			$response = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/status', null, 'GET', array());
			log::add('homeconnect', 'debug', "│ Réponse dans updateStates : " . $response);
			if ($response !== false) {
				$response = json_decode($response, true);
				foreach($response['data']['status'] as $value) {
					log::add('homeconnect', 'debug', "│ status : " . print_r($value, true));
					// Récupération du logicalId du status.
					$logicalId = self::lastSegment($value['key']);
					$cmd = $this->getCmd(null, $logicalId);
					$reglage = '';
					if (is_object($cmd)) {
						// Récupération de la valeur du status.
						if (isset($value['displayvalue'])) {
								$reglage = $value['displayvalue'];
						} else {
							if (isset($value['value'])) {
								if ($cmd->getSubType() == 'string') {
									$reglage = self::traduction(self::lastSegment($value['value']));
								} else {
									$reglage = $value['value'];
								}
							} else {
								log::add('homeconnect', 'debug', "│ le status : ".$logicalId." n'a pas de valeur");
							}
						}
						$this->checkAndUpdateCmd($logicalId, $reglage);
						log::add('homeconnect', 'debug', "│ mise à jour status : ".$logicalId." - Valeur :".$reglage);
					} else {
						log::add('homeconnect', 'debug', "│ La commande : ".$logicalId." n'existe pas");
					}
				}
			}
		}
	}

	public function updateSettings() {
		if ($this->isConnected()) {
			log::add('homeconnect', 'debug', "│ MAJ des réglages ".$this->getLogicalId());

			$response = self::request(self::API_REQUEST_URL . '/' . $this->getLogicalId() . '/settings', null, 'GET', array());
			log::add('homeconnect', 'debug', "│ Réponse updateSettings : " . $response);
			if ($response !== false) {
				$response = json_decode($response, true);
				foreach($response['data']['settings'] as $value) {
					log::add('homeconnect', 'debug', "│ setting : " . print_r($value, true));
					// Récupération du logicalId du setting.
					$logicalId = self::lastSegment($value['key']);
					$cmd = $this->getCmd(null, $logicalId);
					$reglage = '';
					if (is_object($cmd)) {
						// Récupération de la valeur du setting.
						if (isset($value['displayvalue'])) {
								$reglage = $value['displayvalue'];
						} else {
							if (isset($value['value'])) {
								if ($cmd->getSubType() == 'string') {
									$reglage = self::traduction(self::lastSegment($value['value']));
								} else {
									$reglage = $value['value'];
								}
							} else {
								log::add('homeconnect', 'debug', "│ le setting : ".$logicalId." n'a pas de valeur");
							}
						}
						$this->checkAndUpdateCmd($logicalId, $reglage);
						log::add('homeconnect', 'debug', "│ Mise à jour setting : ".$logicalId." - Valeur :".$reglage);
					} else {
						log::add('homeconnect', 'debug', "│ La commande : ".$logicalId." n'existe pas");
					}
				}
			}
		}
	}

	public function updateApplianceData() {
		if ($this->getIsEnable()){
			$response = self::request(self::API_REQUEST_URL, null, 'GET', array());
			$response = json_decode($response, true);
			foreach($response['data']['homeappliances'] as $appliance) {
				if ($this->getLogicalId() == $appliance['haId']) {
					$cmd = $this->getCmd(null, 'connected');
					if (is_object($cmd)) {
						$this->checkAndUpdateCmd('connected', $appliance['connected']);
					}
				}
			}
			$this->updateProgram();
			$this->updateStates();
			$this->updateSettings();
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
	public function dontRemoveCmd() {
		if (in_array($this->getLogicalId(), array('SetpointTemperature'))) {
			return true;
		}
		return false;
	}

	public function execute($_options = array()) {
		// Bien penser dans les fichiers json à mettre dans la configuration
		// key, value, type, constraints et à modifier findProduct
		if ($this->getType() == 'info') {
			return;
		}
		$eqLogic = $this->getEqLogic();
		$haid = $eqLogic->getConfiguration('haid', '');

		if ($this->getLogicalId() == 'DELETE::StopActiveProgram') {
				// Si l'appareil n'a pas de programme on ne peut pas arrêter
				if (!$eqLogic->getConfiguration('hasPrograms', true)) {
					return;
				}
				// S'il n'y a pas de programme sélectionné on ne peut pas lancer
				$response = homeconnect::request(homeconnect::API_REQUEST_URL . '/' . $this->getLogicalId() . '/programs/actif', null, 'GET', array());
				if ($response !== false && $response !== 'SDK.Error.NoProgramActive') {
					log::add('homeconnect', 'debug'," | Pas de programme actif impossible d'arrêter");
				return;
			}
		}
		// S'il n'y a pas de programme sélectionné on ne peut ni lancer ni arrêter

		if ($this->getLogicalId() == 'start') {
			// Si l'appareil n'a pas de programme on ne peut pas lancer
			if (!$eqLogic->getConfiguration('hasPrograms', true)) {
				return;
			}
			// On lance le programme sélectionné à condition qu'il existe
			log::add('homeconnect', 'debug'," | Lancement du programme sélectionné");
			$response = homeconnect::request(homeconnect::API_REQUEST_URL . '/' . $this->getLogicalId() . '/programs/selected', null, 'GET', array());
			if ($response == false || $response == 'SDK.Error.NoProgramSelected') {
				log::add('homeconnect', 'debug'," | Pas de programme sélectionné impossible de lancer");
				return;
			}
			$decodedResponse = json_decode($response, true);
			if(!isset($decodedResponse['data']['key'])) {
				log::add('homeconnect', 'debug'," | Pas de programme dans la réponse");
				return;
			}
			$url = homeconnect::API_REQUEST_URL . '/'. $haid . '/programs/active';
			$response = homeconnect::request($url, $response, 'PUT', array());
			return;

		}
		if ($this->getLogicalId() == 'refresh') {
			$eqLogic->updateApplianceData();
			return;
		}
		$parts = explode('::', $this->getLogicalId());
		if (count($parts) !== 2) {
			log::add('homeconnect', 'debug'," | Wrong number of parts in command eqLogic");
			return;
		}
		$method = $parts[0];
		$key = $parts[1];
		// A voir : faut il ajouter qqchose aux headers par defaut de request
		$headers = array();


		// Bien penser à mettre la partie après haid de l'url dans configuration request de la commande
		$request = $this->getConfiguration('request', '');
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
			// A compléter avec les bons paramètres qui dépendent de la commande
			// Voir pour un système calqué sur Deconz les stocker dans le logicalId séparé par des ::
			$parameters = array('data' => array());
			if ($this->getConfiguration('key', '') !== '') {
				$parameters['data']['key'] = $this->getConfiguration('key', '');
			}
			if ($this->getConfiguration('value', '') !== '') {
				$parameters['data']['value'] = $value = str_replace(array_keys($replace),$replace,$this->getConfiguration('value', ''));
			}
			if ($this->getConfiguration('unit', '') !== '') {
				$parameters['data']['unit'] = $this->getConfiguration('unit', '');
			}
			if ($this->getConfiguration('type', '') !== '') {
				$parameters['data']['type'] = $this->getConfiguration('type', '');
			}
			$payload= json_encode($parameters);
		}
		log::add('homeconnect', 'debug'," | Payload : " . $payload);
		$url = homeconnect::API_REQUEST_URL . '/'. $haid . '/' . $request;
		log::add('homeconnect', 'debug'," | Url : " . $url);
		$response = homeconnect::request($url, $payload, $method, $headers);
		log::add('homeconnect', 'debug'," | Server response : " . $response);
	}

	/** *************************** Getters ********************************* */



	/** *************************** Setters ********************************* */



}
?>
