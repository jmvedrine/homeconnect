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
	
	const CLIENT_ID = "C53A4FFB6885FC6FF5B516128506BE4F466C54143BED715859127D350756AC37";
	const CLIENT_SECRET = "703795835EC5BF1D869AD98453323A93CD712C05296370602497F9263F31A3E3";
	const CLIENT_REDIRECT_URL = "https://apiclient.home-connect.com/o2c.html";
	const API_AUTH_URL = "https://developer.home-connect.com/security/oauth/authorize"; //?client_id=XXX&redirect_uri=XXX&response_type=code&scope=XXX&state=XXX
	const API_TOKEN_URL = "https://developer.home-connect.com/security/oauth/token"; //client_id=XXX&redirect_uri=XXX&grant_type=authorization_code&code=XXX
	const API_REQUEST_URL = "https://developer.home-connect.com/api/homeappliances";
	
	
	
    /** *************************** Attributs ********************************* */
	
	
	
	/** *************************** Attributs statiques *********************** */
	
	
	
	/** *************************** Méthodes ********************************** */
	
	
	
	/** *************************** Méthodes statiques ************************ */

	public static function loginHomeConnect() {
	/**
     * Connexion au compte Home Connect et récupération du code d'authorisation.
     *
     * @param 			|*Cette fonction ne retourne pas de valeur*|
     * @return 			|*Cette fonction ne retourne pas de valeur*|	
     */	
		
		if (empty(config::byKey('auth','homeconnect'))) {
				
				// Identification auprès du serveur.
				homeconnect::authRequest();
		}
	}
	
	public static function syncHomeConnect() {
	/**
     * Connexion au compte Home Connect (via token) et récupération des machines liées.
     *
     * @param 			|*Cette fonction ne retourne pas de valeur*|
     * @return 			|*Cette fonction ne retourne pas de valeur*|	
     */	
		
		if (empty(config::byKey('auth','homeconnect'))) {
			
				// Si le code d'authorisation du serveur est absent.
				// Identification auprès du serveur.
				homeconnect::authRequest();
		}
		
		// Récupération du token d'accès aux serveurs.
		homeconnect::tokenRequest();
		
		// Récupération des machines.
		homeconnect::homeappliances();
	}
	
	public static function majMachine(){
	/**
     * Lance la mise à jour des informations des machines (lancement par cron).
     *
     * @param 			|*Cette fonction ne retourne pas de valeur*|
     * @return 			|*Cette fonction ne retourne pas de valeur*|
     */
	
		log::add('homeconnect', 'debug',"┌────────── Fonction majMachine()");
		
		// Vérification si le token est expiré.
		if ((config::byKey('expires_in','homeconnect') - time()) < 60) {
			
			log::add('homeconnect', 'debug', "│ [Warning] : Le token est expiré, renouvellement de ce dernier.");
			
			// Récupération du token d'accès aux serveurs.
			homeconnect::tokenRequest();
		}
		
		// Vérification de la présence du token et tentative de récupération si absent.
		if (empty(config::byKey('access_token','homeconnect'))) {
			
			log::add('homeconnect', 'debug', "│ [Warning] : Le token est manquant, recupération de ce dernier.");
			
			// Récupération du token d'accès aux serveurs.
			homeconnect::tokenRequest();
			
			if (empty(config::byKey('access_token','homeconnect'))) {
				
				log::add('homeconnect', 'debug', "│ [Erreur ]: La récupération du token à échouée.");
				return;
			}
		} 
		
		// MAJ du statut de connexion des machines.
		homeconnect::majConnected();
		
		// MAJ des programes en cours.
		homeconnect::majPrograms();
		
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction majMachine()");
	}
	
	
	private static function authRequest() {
	/**
     * Récupère un code d'authorisation à échanger contre un token.
     *
     * @param			|*Cette fonction ne retourne pas de valeur*|
     * @return 			|*Cette fonction ne retourne pas de valeur*|
     */
	
		log::add('homeconnect', 'debug',"┌────────── Fonction authRequest()");
		
		// Construction de l'url.
		$url = homeconnect::API_AUTH_URL."?client_id=".homeconnect::CLIENT_ID."&redirect_uri".homeconnect::CLIENT_REDIRECT_URL;
		$url .= "&response_type=code&scope=IdentifyAppliance+Monitor";
		log::add('homeconnect', 'debug', "url : " . $url);
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
            log::add('homeconnect', 'debug', "info : " . print_r($info, true));
            log::add('homeconnect', 'debug', "response : " . $response);
			log::add('homeconnect', 'debug', "│ [Erreur] (code erreur : ".$info['http_code'].") : ".print_r($matches));
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
			}	
		}
		
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction authRequest()");
	}
	
	private static function tokenRequest() {
	/**
     * Récupère un token permettant l'accès au serveur.
     *
     * @param 			|*Cette fonction ne retourne pas de valeur*|
     * @return 			|*Cette fonction ne retourne pas de valeur*|
     */
	
		log::add('homeconnect', 'debug',"┌────────── Fonction tokenRequest()");
		
		// Vérification de la présence du code d'authorisation avant de demander le token.
		if (empty(config::byKey('auth','homeconnect'))) {
			
			log::add('homeconnect', 'debug', "│ [Erreur] : Code d'authorisation vide.");
			throw new Exception("Erreur : Veuillez connecter votre compte via le menu configuration du plugin.");
			return;
		}
		
		// Création du paramêtre POSTFIELDS.
		$post_fields = 'client_id='. homeconnect::CLIENT_ID;
		$post_fields .= '&redirect_uri='. homeconnect::CLIENT_REDIRECT_URL;
		$post_fields .= '&grant_type=authorization_code';
		$post_fields .= '&code='.config::byKey('auth','homeconnect');

		// Récupération du Token.
		$curl = curl_init();
		$options = [
			CURLOPT_URL => homeconnect::API_TOKEN_URL,
			CURLOPT_RETURNTRANSFER => True,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POST => True,
			CURLOPT_POSTFIELDS => $post_fields,
			];
		curl_setopt_array($curl, $options);
		$response = json_decode(curl_exec($curl), true);
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
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction tokenhRequest()");
	}
	
	private static function homeappliances() {
	/**
     * Récupère la liste des objects connectés et création des objets associés.
     *
     * @param 			|*Cette fonction ne retourne pas de valeur*|
     * @return 			|*Cette fonction ne retourne pas de valeur*|
     */
		
		log::add('homeconnect', 'debug',"┌────────── Fonction homeappliances()");
		
		// Vérification si le token est expiré.
		if ((config::byKey('expires_in','homeconnect') - time()) < 60) {
			
			log::add('homeconnect', 'debug', "│ [Warning] : Le token est expiré, renouvellement de ce dernier.");
			
			// Récupération du token d'accès aux serveurs.
			homeconnect::tokenRequest();
		}
		
		// Vérification de la présence du token et tentative de récupération si absent.
		if (empty(config::byKey('access_token','homeconnect'))) {
			
			log::add('homeconnect', 'debug', "│ [Warning] : Le token est manquant, recupération de ce dernier.");
			
			// Récupération du token d'accès aux serveurs.
			homeconnect::tokenRequest();
			
			if (empty(config::byKey('access_token','homeconnect'))) {
				
				log::add('homeconnect', 'debug', "│ [Erreur ]: La récupération du token à échouée.");
				return;
			}
		} 		
		
		$headers = [
			"Accept: application/vnd.bsh.sdk.v1+json",
			"Authorization: Bearer ".config::byKey('access_token','homeconnect'),
			];

		$curl = curl_init();	
		$options = [
			CURLOPT_URL => homeconnect::API_REQUEST_URL,
			CURLOPT_RETURNTRANSFER => True,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_HTTPHEADER => $headers,
			];
		curl_setopt_array($curl, $options);
		$response = json_decode(curl_exec($curl), true);
		$http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);		
		curl_close ($curl);
		
		// Vérification du code réponse.
		if($http_code != 200) {
			
			log::add('homeconnect', 'debug', "│ [Erreur] (code erreur : ".$http_code.") ".print_r($response));
			throw new Exception("Erreur : ".print_r($response));
			return;
		}

		foreach($response['data']['homeappliances'] as $key) {
			/* 	haId = Id de la machine
				vib = modèle de la machine
				brand = marque de la machine
				type = type de machine
				name = nom de la machine
				enumber = N° de série
				connected = boolean */
			
			// Si la machine n'est pas connectée, nous ne la créons pas.
			if ($key['connected'] == True) {
				
				// Vérification que la machine n'est pas déjà créée.
				$eqLogic = eqLogic::byLogicalId($key['haId'], 'homeconnect');
				
				if (!is_object($eqLogic)) {
					
					// Création de la machine.
					$eqLogic = new homeconnect();
				}
				
				$eqLogic->setLogicalId($key['haId']);
				$eqLogic->setIsEnable(1);
				$eqLogic->setIsVisible(1);
				$eqLogic->setEqType_name('homeconnect');
				$eqLogic->setName($key['name']);
				$eqLogic->setConfiguration('haid', $key['haId']);
				$eqLogic->setConfiguration('vib', $key['vib']);
				$eqLogic->setConfiguration('brand', $key['brand']);
				$eqLogic->setConfiguration('type', homeconnect::traduction($key['type']));
				$eqLogic->save();
				
				log::add('homeconnect', 'debug', "├──────────");
				log::add('homeconnect', 'debug', "│ Création d'une machine :");
				log::add('homeconnect', 'debug', "│ Type : ".homeconnect::traduction($key['type']));
				log::add('homeconnect', 'debug', "│ Marque : ".$key['brand']);
				log::add('homeconnect', 'debug', "│ Modèle : ".$key['vib']);
				log::add('homeconnect', 'debug', "├──────────");
			}
		}
		
		log::add('homeconnect', 'debug',"└────────── Fin de la fonction homeappliances()");
	}
	
	
	private static function majConnected() {
	/**
     * Récupère le statut connecté de la machine.
     *
     * @param			|*Cette fonction ne retourne pas de valeur*|
     * @return 			|*Cette fonction ne retourne pas de valeur*|
     */
		
		log::add('homeconnect', 'debug',"│");
		log::add('homeconnect', 'debug',"├───── Fonction majConnected()");

		$headers = [
			"Accept: application/vnd.bsh.sdk.v1+json",
			"Authorization: Bearer ".config::byKey('access_token','homeconnect'),
			];

		$curl = curl_init();	
		$options = [
			CURLOPT_URL => homeconnect::API_REQUEST_URL,
			CURLOPT_RETURNTRANSFER => True,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_HTTPHEADER => $headers,
			];
		curl_setopt_array($curl, $options);
		$response = json_decode(curl_exec($curl), true);
		$http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);		
		curl_close ($curl);
		
		// Vérification du code réponse.
		if($http_code != 200) {
			
			log::add('homeconnect', 'debug', "│ [Erreur] (code erreur : ".$http_code.") ".print_r($response));
			return;
		}

		foreach($response['data']['homeappliances'] as $key) {
			/* connected = boolean */				
				
			$eqLogic = eqLogic::byLogicalId($key['haId'], 'homeconnect');
          	if (is_object($eqLogic)){
            
				$eqLogic->checkAndUpdateCmd('connected', $key['connected']);
				
				log::add('homeconnect', 'debug', "├─────");
				log::add('homeconnect', 'debug', "│ MAJ d'une machine :");
				log::add('homeconnect', 'debug', "│ Type : ".homeconnect::traduction($key['type']));
				log::add('homeconnect', 'debug', "│ Id : ".$key['haId']);
				log::add('homeconnect', 'debug', "│ Connecté : ".$key['connected']);
				log::add('homeconnect', 'debug', "├─────");
			
			} else {
				
				log::add('homeconnect', 'debug', "├───── [Erreur]");
				log::add('homeconnect', 'debug', "│ La machine n'existe pas :");
				log::add('homeconnect', 'debug', "│ Type : ".homeconnect::traduction($key['type']));
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
     * Récupère le programe et options en cours pour MAJ équipements.
     *
     * @param			|*Cette fonction ne retourne pas de valeur*|
     * @return 			|*Cette fonction ne retourne pas de valeur*|
     */
	
		log::add('homeconnect', 'debug',"│");
		log::add('homeconnect', 'debug',"├───── Fonction majPrograms()");
		
		// Parcours des marchines existantes.
      	foreach (eqLogic::byType('homeconnect') as $eqLogic) {
			
			// MAJ des machines étant connectée.
			// Si la machine est connecté, MAJ des infos.
			if ($eqLogic->getConfiguration('connected') == True) {
				
				log::add('homeconnect', 'debug', "├─────");
				log::add('homeconnect', 'debug', "│ MAJ d'une machine :");
				log::add('homeconnect', 'debug', "│ Type : ".$eqLogic->getConfiguration('type'));
				log::add('homeconnect', 'debug', "│ Id : ".$eqLogic->getLogicalId());
				log::add('homeconnect', 'debug', "│");
			
				$headers = array("Accept: application/vnd.bsh.sdk.v1+json",
								"Accept-Language: en-GB",
								"Authorization: Bearer ".config::byKey('access_token','homeconnect'));

				$curl = curl_init();	
				$options = [
					CURLOPT_URL => homeconnect::API_REQUEST_URL. "/" .$eqLogic->getLogicalId()."/programs/active",
					CURLOPT_RETURNTRANSFER => True,
					CURLOPT_SSL_VERIFYPEER => FALSE,
					CURLOPT_HTTPHEADER => $headers,
					];
				curl_setopt_array($curl, $options);		
				$response = json_decode(curl_exec($curl), true);
				$http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
			
				// Code 200 = Ok.
				// Code 404 = Pas de programe actif.
				// Code autre = erreur.
			  
				if ($http_code != 200 && $http_code !=404) {
					
					log::add('homeconnect', 'debug', "│ [Erreur] (code erreur : ".$http_code.") : ".$response);
					return;			
					
				} elseif ($http_code == 404) {
					
					log::add('homeconnect', 'debug', "│ Programe : Pas de lavage en cours;");
					// RAZ des info.
					homeconnect::razInfo($eqLogic->getLogicalId());
				
				} else {
					
					// MAJ du programe en cours.
					$program = homeconnect::traduction(substr(strrchr($response['data']['key'], "."), 1));
					$eqLogic->checkAndUpdateCmd('Programs',$program);
					
					
					log::add('homeconnect', 'debug', "│ Programe en cours : ".$program);
					
					// MAJ des options et autres informations du programe en cours.
					foreach ($response['data']['options'] as $value) {
						
						// Récupération du nom du programe / option.
						$program = substr(strrchr($value['key'], "."), 1);
						
						// Récupération de la valeur du programe / option.
						switch ($program) {
				
							case "Temperature" :
								$reglage = substr(strrchr($value['value'], "."), 3);
								break;
							
							case "SpinSpeed" :
								$reglage = substr(strrchr($value['value'], "."), 4);
								break;
							
							default :
								$reglage = homeconnect::traduction($value['value']);
							
						}
						
						// Traduction en Français du nom du program / option.
						$program = homeconnect::traduction($program);
						
						$eqLogic->checkAndUpdateCmd($program,$reglage);
						log::add('homeconnect', 'debug', "│ Option : ".$program." - Réglage :".$reglage);
					}
					
					log::add('homeconnect', 'debug', "├─────");
				}
				
			} else {
				
				// RAZ des info.
				homeconnect::razInfo($eqLogic->getLogicalId());
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
     * @return 			|*Cette fonction ne retourne pas de valeur*|
     */
	
		log::add('homeconnect', 'debug',"│");
		log::add('homeconnect', 'debug',"├───── Fonction maState()");
		
		
		
	 
		log::add('homeconnect', 'debug',"├───── Fin de la fonction majState()");
	}
	private static function razInfo($haId) {
	/**
     * Remise à zéro des informations d'une machine.
     *
     * @param	$haId		string		Id de la machine en cours.
     * @return 			|*Cette fonction ne retourne pas de valeur*|
     */	
		
		log::add('homeconnect', 'debug',"/** ******************** function razInfo ******************** **/");
		
		$eqLogic = eqLogic::byLogicalId($haId, 'homeconnect');
		
		foreach($eqLogic->getCmd() as $cmd) {
			
			if ($cmd->getLogicalId() == "Programs") {
				
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
	
	private static function traduction($word){
	/**
     * Traduction des informations.
     *
     * @param	$word		string		Mot en anglais.
     * @return 	$word		string		Mot en Français (ou anglais, si traduction inexistante).
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
      public static function cron() {
		//homeconnect::majMachine();
		
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

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
    /**
     * Création / MAJ des commandes des machines.
     *
     * @param 			|*Cette fonction ne retourne pas de valeur*|
     * @return 			|*Cette fonction ne retourne pas de valeur*|
     */

		// Statut connecté.
		$connected = $this->getCmd(null, 'connected');
		if (!is_object($connected)) {
			$connected = new homeconnectCmd();
			$connected->setLogicalId('connected');
			$connected->setName(__('Connecté(e)', __FILE__));
			$connected->setIsVisible(1);
			$connected->setIsHistorized(0);
			$connected->setOrder(0);
		}
		$connected->setUnite('');
		$connected->setType('info');
		$connected->setSubType('binary');
		$connected->setConfiguration('repeatEventManagement', 'never');
		$connected->setEqLogic_id($this->getId());
		$connected->save();
	 
		/** Machine à café **/
		if ($this->getConfiguration('type') == "CoffeeMaker"){
			
			// Contrôle à distance.
			$remote = $this->getCmd(null, 'RemoteControlStartAllowed');
			if (!is_object($connected)) {
				$remote = new homeconnectCmd();
				$remote->setLogicalId('RemoteControlStartAllowed');
				$remote->setName(__('Contrôle à distance', __FILE__));
				$remote->setIsVisible(1);
				$remote->setIsHistorized(0);
				$remote->setOrder(1);
			}
			$remote->setUnite('');
			$remote->setType('info');
			$remote->setSubType('binary');
			$remote->setConfiguration('repeatEventManagement', 'never');
			$remote->setEqLogic_id($this->getId());
			$remote->save();
			
			// Programe en cours.
			$programs = $this->getCmd(null, 'Programs');
			if (!is_object($programs)) {
				$programs = new homeconnectCmd();
				$programs->setLogicalId('Programs');
				$programs->setName(__('Programe', __FILE__));
				$programs->setIsVisible(1);
				$programs->setIsHistorized(0);
			}
			$programs->setUnite('');
			$programs->setType('info');
			$programs->setSubType('string');
			$programs->setConfiguration('repeatEventManagement', 'never');
			$programs->setEqLogic_id($this->getId());
			$programs->save();
			
			// Sélectionner l'intensité du café.
			$bean_amount = $this->getCmd(null, 'BeanAmount');
			if (!is_object($bean_amount)) {
				$bean_amount = new homeconnectCmd();
				$bean_amount->setLogicalId('BeanAmount');
				$bean_amount->setName(__('Intensité café', __FILE__));
				$bean_amount->setIsVisible(1);
				$bean_amount->setIsHistorized(0);
			}
			$bean_amount->setUnite('');
			$bean_amount->setType('info');
			$bean_amount->setSubType('string');
			$bean_amount->setConfiguration('repeatEventManagement', 'never');
			$bean_amount->setEqLogic_id($this->getId());
			$bean_amount->save();
			
			// Taille de la tasse.
			$fill_quantity = $this->getCmd(null, 'FillQuantity');
			if (!is_object($fill_quantity)) {
				$fill_quantity = new homeconnectCmd();
				$fill_quantity->setLogicalId('FillQuantity');
				$fill_quantity->setName(__('Taille tasse', __FILE__));
				$fill_quantity->setIsVisible(1);
				$fill_quantity->setIsHistorized(0);
			}
			$fill_quantity->setUnite('ml');
			$fill_quantity->setType('info');
			$fill_quantity->setSubType('numeric');
			$fill_quantity->setConfiguration('repeatEventManagement', 'never');
			$fill_quantity->setEqLogic_id($this->getId());
			$fill_quantity->save();
			
			// Avancement du programe en cours.
			$progress = $this->getCmd(null, 'ProgramProgress');
			if (!is_object($progress)) {
				$progress = new homeconnectCmd();
				$progress->setLogicalId('ProgramProgress');
				$progress->setName(__('Avancement', __FILE__));
				$progress->setIsVisible(1);
				$progress->setIsHistorized(0);
			}
			$progress->setUnite('%');
			$progress->setType('info');
			$progress->setSubType('numeric');
			$progress->setConfiguration('repeatEventManagement', 'never');
			$progress->setEqLogic_id($this->getId());
			$progress->save();
		}
		
		/** Lave-vaiselle **/
		if ($this->getConfiguration('type') == "Dishwasher"){
			
			// Contrôle à distance.
			$remote = $this->getCmd(null, 'RemoteControlStartAllowed');
			if (!is_object($connected)) {
				$remote = new homeconnectCmd();
				$remote->setLogicalId('RemoteControlStartAllowed');
				$remote->setName(__('Contrôle à distance', __FILE__));
				$remote->setIsVisible(1);
				$remote->setIsHistorized(0);
				$remote->setOrder(1);
			}
			$remote->setUnite('');
			$remote->setType('info');
			$remote->setSubType('binary');
			$remote->setConfiguration('repeatEventManagement', 'never');
			$remote->setEqLogic_id($this->getId());
			$remote->save();
			
			// Etat de la porte.
			$door = $this->getCmd(null, 'DoorState');
			if (!is_object($connected)) {
				$door = new homeconnectCmd();
				$door->setLogicalId('DoorState');
				$door->setName(__('Porte', __FILE__));
				$door->setIsVisible(1);
				$door->setIsHistorized(0);
				$door->setOrder(2);
			}
			$door->setUnite('');
			$door->setType('info');
			$door->setSubType('string');
			$door->setConfiguration('repeatEventManagement', 'never');
			$door->setEqLogic_id($this->getId());
			$door->save();
			
			// Programe en cours.
			$programs = $this->getCmd(null, 'Programs');
			if (!is_object($programs)) {
				$programs = new homeconnectCmd();
				$programs->setLogicalId('Programs');
				$programs->setName(__('Programe', __FILE__));
				$programs->setIsVisible(1);
				$programs->setIsHistorized(0);
			}
			$programs->setUnite('');
			$programs->setType('info');
			$programs->setSubType('string');
			$programs->setConfiguration('repeatEventManagement', 'never');
			$programs->setEqLogic_id($this->getId());
			$programs->save();
		
			// Temps restant du programe en cours.
			$remaining_time = $this->getCmd(null, 'RemainingProgramTime');
			if (!is_object($remaining_time)) {
				$remaining_time = new homeconnectCmd();
				$remaining_time->setLogicalId('RemainingProgramTime');
				$remaining_time->setName(__('Durée restante', __FILE__));
				$remaining_time->setIsVisible(1);
				$remaining_time->setIsHistorized(0);
			}
			$remaining_time->setUnite('s');
			$remaining_time->setType('info');
			$remaining_time->setSubType('numeric');
			$remaining_time->setConfiguration('repeatEventManagement', 'never');
			$remaining_time->setEqLogic_id($this->getId());
			$remaining_time->save();
			
			// Délai avant démarrage.
			$start_in_relative = $this->getCmd(null, 'StartInRelative');
			if (!is_object($start_in_relative)) {
				$start_in_relative = new homeconnectCmd();
				$start_in_relative->setLogicalId('StartInRelative');
				$start_in_relative->setName(__('Délai démarrage', __FILE__));
				$start_in_relative->setIsVisible(1);
				$start_in_relative->setIsHistorized(0);
			}
			$start_in_relative->setUnite('s');
			$start_in_relative->setType('info');
			$start_in_relative->setSubType('numeric');
			$start_in_relative->setConfiguration('repeatEventManagement', 'never');
			$start_in_relative->setEqLogic_id($this->getId());
			$start_in_relative->save();
			
			// Avancement du programe en cours.
			$progress = $this->getCmd(null, 'ProgramProgress');
			if (!is_object($progress)) {
				$progress = new homeconnectCmd();
				$progress->setLogicalId('ProgramProgress');
				$progress->setName(__('Avancement', __FILE__));
				$progress->setIsVisible(1);
				$progress->setIsHistorized(0);
			}
			$progress->setUnite('%');
			$progress->setType('info');
			$progress->setSubType('numeric');
			$progress->setConfiguration('repeatEventManagement', 'never');
			$progress->setEqLogic_id($this->getId());
			$progress->save();
		}
		
		/** Sèche-linge **/
		if ($this->getConfiguration('type') == "Dryer"){
			
			// Contrôle à distance.
			$remote = $this->getCmd(null, 'RemoteControlStartAllowed');
			if (!is_object($connected)) {
				$remote = new homeconnectCmd();
				$remote->setLogicalId('RemoteControlStartAllowed');
				$remote->setName(__('Contrôle à distance', __FILE__));
				$remote->setIsVisible(1);
				$remote->setIsHistorized(0);
				$remote->setOrder(1);
			}
			$remote->setUnite('');
			$remote->setType('info');
			$remote->setSubType('binary');
			$remote->setConfiguration('repeatEventManagement', 'never');
			$remote->setEqLogic_id($this->getId());
			$remote->save();
			
			// Etat de la porte.
			$door = $this->getCmd(null, 'DoorState');
			if (!is_object($connected)) {
				$door = new homeconnectCmd();
				$door->setLogicalId('DoorState');
				$door->setName(__('Porte', __FILE__));
				$door->setIsVisible(1);
				$door->setIsHistorized(0);
				$door->setOrder(2);
			}
			$door->setUnite('');
			$door->setType('info');
			$door->setSubType('string');
			$door->setConfiguration('repeatEventManagement', 'never');
			$door->setEqLogic_id($this->getId());
			$door->save();
			
			// Programe en cours.
			$programs = $this->getCmd(null, 'Programs');
			if (!is_object($programs)) {
				$programs = new homeconnectCmd();
				$programs->setLogicalId('Programs');
				$programs->setName(__('Programe', __FILE__));
				$programs->setIsVisible(1);
				$programs->setIsHistorized(0);
			}
			$programs->setUnite('');
			$programs->setType('info');
			$programs->setSubType('string');
			$programs->setConfiguration('repeatEventManagement', 'never');
			$programs->setEqLogic_id($this->getId());
			$programs->save();
		
			// Sécheresse souhaitée.
			$drying_target = $this->getCmd(null, 'DryingTarget');
			if (!is_object($drying_target)) {
				$drying_target = new homeconnectCmd();
				$drying_target->setLogicalId('DryingTarget');
				$drying_target->setName(__('Séchage', __FILE__));
				$drying_target->setIsVisible(1);
				$drying_target->setIsHistorized(0);
			}
			$drying_target->setUnite('');
			$drying_target->setType('info');
			$drying_target->setSubType('string');
			$drying_target->setConfiguration('repeatEventManagement', 'never');
			$drying_target->setEqLogic_id($this->getId());
			$drying_target->save();
			
			// Temps restant du programe en cours.
			$remaining_time = $this->getCmd(null, 'RemainingProgramTime');
			if (!is_object($remaining_time)) {
				$remaining_time = new homeconnectCmd();
				$remaining_time->setLogicalId('RemainingProgramTime');
				$remaining_time->setName(__('Durée restante', __FILE__));
				$remaining_time->setIsVisible(1);
				$remaining_time->setIsHistorized(0);
			}
			$remaining_time->setUnite('s');
			$remaining_time->setType('info');
			$remaining_time->setSubType('numeric');
			$remaining_time->setConfiguration('repeatEventManagement', 'never');
			$remaining_time->setEqLogic_id($this->getId());
			$remaining_time->save();
			
			// Avancement du programe en cours.
			$progress = $this->getCmd(null, 'ProgramProgress');
			if (!is_object($progress)) {
				$progress = new homeconnectCmd();
				$progress->setLogicalId('ProgramProgress');
				$progress->setName(__('Avancement', __FILE__));
				$progress->setIsVisible(1);
				$progress->setIsHistorized(0);
			}
			$progress->setUnite('%');
			$progress->setType('info');
			$progress->setSubType('numeric');
			$progress->setConfiguration('repeatEventManagement', 'never');
			$progress->setEqLogic_id($this->getId());
			$progress->save();
		}
		
		/** Frigidaire **/
		if ($this->getConfiguration('type') == "FridgeFreezer"){
			// Aucun programe et option pour le frigidaire.
			
			// Etat de la porte.
			$door = $this->getCmd(null, 'DoorState');
			if (!is_object($connected)) {
				$door = new homeconnectCmd();
				$door->setLogicalId('DoorState');
				$door->setName(__('Porte', __FILE__));
				$door->setIsVisible(1);
				$door->setIsHistorized(0);
				$door->setOrder(2);
			}
			$door->setUnite('');
			$door->setType('info');
			$door->setSubType('string');
			$door->setConfiguration('repeatEventManagement', 'never');
			$door->setEqLogic_id($this->getId());
			$door->save();
		}
		
		/** Four **/
		if ($this->getConfiguration('type') == "Oven"){
			
			// Contrôle à distance.
			$remote = $this->getCmd(null, 'RemoteControlStartAllowed');
			if (!is_object($connected)) {
				$remote = new homeconnectCmd();
				$remote->setLogicalId('RemoteControlStartAllowed');
				$remote->setName(__('Contrôle à distance', __FILE__));
				$remote->setIsVisible(1);
				$remote->setIsHistorized(0);
				$remote->setOrder(1);
			}
			$remote->setUnite('');
			$remote->setType('info');
			$remote->setSubType('binary');
			$remote->setConfiguration('repeatEventManagement', 'never');
			$remote->setEqLogic_id($this->getId());
			$remote->save();
			
			// Etat de la porte.
			$door = $this->getCmd(null, 'DoorState');
			if (!is_object($connected)) {
				$door = new homeconnectCmd();
				$door->setLogicalId('DoorState');
				$door->setName(__('Porte', __FILE__));
				$door->setIsVisible(1);
				$door->setIsHistorized(0);
				$door->setOrder(2);
			}
			$door->setUnite('');
			$door->setType('info');
			$door->setSubType('string');
			$door->setConfiguration('repeatEventManagement', 'never');
			$door->setEqLogic_id($this->getId());
			$door->save();
			
			// Programe en cours.
			$programs = $this->getCmd(null, 'Programs');
			if (!is_object($programs)) {
				$programs = new homeconnectCmd();
				$programs->setLogicalId('Programs');
				$programs->setName(__('Programe', __FILE__));
				$programs->setIsVisible(1);
				$programs->setIsHistorized(0);
			}
			$programs->setUnite('');
			$programs->setType('info');
			$programs->setSubType('string');
			$programs->setConfiguration('repeatEventManagement', 'never');
			$programs->setEqLogic_id($this->getId());
			$programs->save();
		
			// Température de consigne.
			$temperature = $this->getCmd(null, 'SetpointTemperature');
			if (!is_object($temperature)) {
				$temperature = new homeconnectCmd();
				$temperature->setLogicalId('SetpointTemperature');
				$temperature->setName(__('Température consigne', __FILE__));
				$temperature->setIsVisible(1);
				$temperature->setIsHistorized(0);
			}
			$temperature->setUnite('°C');
			$temperature->setType('info');
			$temperature->setSubType('numeric');
			$temperature->setConfiguration('repeatEventManagement', 'never');
			$temperature->setEqLogic_id($this->getId());
			$temperature->save();
			
			// Durée de préchaufage.
			$duration = $this->getCmd(null, 'Duration');
			if (!is_object($duration)) {
				$duration = new homeconnectCmd();
				$duration->setLogicalId('Duration');
				$duration->setName(__('Temps préchaufage', __FILE__));
				$duration->setIsVisible(1);
				$duration->setIsHistorized(0);
			}
			$duration->setUnite('s');
			$duration->setType('info');
			$duration->setSubType('numeric');
			$duration->setConfiguration('repeatEventManagement', 'never');
			$duration->setEqLogic_id($this->getId());
			$duration->save();
			
			// Temps restant du programe en cours.
			$remaining_time = $this->getCmd(null, 'RemainingProgramTime');
			if (!is_object($remaining_time)) {
				$remaining_time = new homeconnectCmd();
				$remaining_time->setLogicalId('RemainingProgramTime');
				$remaining_time->setName(__('Durée restante', __FILE__));
				$remaining_time->setIsVisible(1);
				$remaining_time->setIsHistorized(0);
			}
			$remaining_time->setUnite('s');
			$remaining_time->setType('info');
			$remaining_time->setSubType('numeric');
			$remaining_time->setConfiguration('repeatEventManagement', 'never');
			$remaining_time->setEqLogic_id($this->getId());
			$remaining_time->save();
			
			// Temps écoulé du programe en cours.
			$elapsed_time = $this->getCmd(null, 'ElapsedProgramTime');
			if (!is_object($elapsed_time)) {
				$elapsed_time = new homeconnectCmd();
				$elapsed_time->setLogicalId('ElapsedProgramTime');
				$elapsed_time->setName(__('Durée écoulée', __FILE__));
				$elapsed_time->setIsVisible(1);
				$elapsed_time->setIsHistorized(0);
			}
			$elapsed_time->setUnite('s');
			$elapsed_time->setType('info');
			$elapsed_time->setSubType('numeric');
			$elapsed_time->setConfiguration('repeatEventManagement', 'never');
			$elapsed_time->setEqLogic_id($this->getId());
			$elapsed_time->save();
			
			// Avancement du programe en cours.
			$progress = $this->getCmd(null, 'ProgramProgress');
			if (!is_object($progress)) {
				$progress = new homeconnectCmd();
				$progress->setLogicalId('ProgramProgress');
				$progress->setName(__('Avancement', __FILE__));
				$progress->setIsVisible(1);
				$progress->setIsHistorized(0);
			}
			$progress->setUnite('%');
			$progress->setType('info');
			$progress->setSubType('numeric');
			$progress->setConfiguration('repeatEventManagement', 'never');
			$progress->setEqLogic_id($this->getId());
			$progress->save();
		}
		
		/** Lave-linge **/
		if ($this->getConfiguration('type') == "Washer"){
			
			// Contrôle à distance.
			$remote = $this->getCmd(null, 'RemoteControlStartAllowed');
			if (!is_object($connected)) {
				$remote = new homeconnectCmd();
				$remote->setLogicalId('RemoteControlStartAllowed');
				$remote->setName(__('Contrôle à distance', __FILE__));
				$remote->setIsVisible(1);
				$remote->setIsHistorized(0);
				$remote->setOrder(1);
			}
			$remote->setUnite('');
			$remote->setType('info');
			$remote->setSubType('binary');
			$remote->setConfiguration('repeatEventManagement', 'never');
			$remote->setEqLogic_id($this->getId());
			$remote->save();
			
			// Etat de la porte.
			$door = $this->getCmd(null, 'DoorState');
			if (!is_object($connected)) {
				$door = new homeconnectCmd();
				$door->setLogicalId('DoorState');
				$door->setName(__('Porte', __FILE__));
				$door->setIsVisible(1);
				$door->setIsHistorized(0);
				$door->setOrder(2);
			}
			$door->setUnite('');
			$door->setType('info');
			$door->setSubType('string');
			$door->setConfiguration('repeatEventManagement', 'never');
			$door->setEqLogic_id($this->getId());
			$door->save();
			
			// Programe en cours.
			$programs = $this->getCmd(null, 'Programs');
			if (!is_object($programs)) {
				$programs = new homeconnectCmd();
				$programs->setLogicalId('Programs');
				$programs->setName(__('Programe', __FILE__));
				$programs->setIsVisible(1);
				$programs->setIsHistorized(0);
			}
			$programs->setUnite('');
			$programs->setType('info');
			$programs->setSubType('string');
			$programs->setConfiguration('repeatEventManagement', 'never');
			$programs->setEqLogic_id($this->getId());
			$programs->save();
		
			// Température de consigne.
			$temperature = $this->getCmd(null, 'Temperature');
			if (!is_object($temperature)) {
				$temperature = new homeconnectCmd();
				$temperature->setLogicalId('Temperature');
				$temperature->setName(__('Température consigne', __FILE__));
				$temperature->setIsVisible(1);
				$temperature->setIsHistorized(0);
			}
			$temperature->setUnite('°C');
			$temperature->setType('info');
			$temperature->setSubType('numeric');
			$temperature->setConfiguration('repeatEventManagement', 'never');
			$temperature->setEqLogic_id($this->getId());
			$temperature->save();
			
			// Vitesse essorage.
			$spin_speed = $this->getCmd(null, 'SpinSpeed');
			if (!is_object($spin_speed)) {
				$spin_speed = new homeconnectCmd();
				$spin_speed->setLogicalId('SpinSpeed');
				$spin_speed->setName(__('Vitesse essorage', __FILE__));
				$spin_speed->setIsVisible(1);
				$spin_speed->setIsHistorized(0);
			}
			$spin_speed->setUnite('tr/min');
			$spin_speed->setType('info');
			$spin_speed->setSubType('numeric');
			$spin_speed->setConfiguration('repeatEventManagement', 'never');
			$spin_speed->setEqLogic_id($this->getId());
			$spin_speed->save();
			
			// Temps restant du programe en cours.
			$remaining_time = $this->getCmd(null, 'RemainingProgramTime');
			if (!is_object($remaining_time)) {
				$remaining_time = new homeconnectCmd();
				$remaining_time->setLogicalId('RemainingProgramTime');
				$remaining_time->setName(__('Durée restante', __FILE__));
				$remaining_time->setIsVisible(1);
				$remaining_time->setIsHistorized(0);
			}
			$remaining_time->setUnite('s');
			$remaining_time->setType('info');
			$remaining_time->setSubType('numeric');
			$remaining_time->setConfiguration('repeatEventManagement', 'never');
			$remaining_time->setEqLogic_id($this->getId());
			$remaining_time->save();
			
			// Avancement du programe en cours.
			$progress = $this->getCmd(null, 'ProgramProgress');
			if (!is_object($progress)) {
				$progress = new homeconnectCmd();
				$progress->setLogicalId('ProgramProgress');
				$progress->setName(__('Avancement', __FILE__));
				$progress->setIsVisible(1);
				$progress->setIsHistorized(0);
			}
			$progress->setUnite('%');
			$progress->setType('info');
			$progress->setSubType('numeric');
			$progress->setConfiguration('repeatEventManagement', 'never');
			$progress->setEqLogic_id($this->getId());
			$progress->save();
		}
		
		/** table de cuisson **/
		if ($this->getConfiguration('type') == "Cooktop"){
			// API support is planned to be released later in 2017.
		}
		
		/** Hôte **/
		if ($this->getConfiguration('type') == "Hood"){
			// API support is planned to be released later in 2017.
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
		//switch ($this->getType)
        
    }

    /** *************************** Getters ********************************* */
	
	
	
	/** *************************** Setters ********************************* */
	
	
	
}

?>
