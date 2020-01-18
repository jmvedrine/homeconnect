Présentation
===
Ce plugin permet de récupérer des informations des appareils utilisant le protocole Home Connect .
Il a été développé à l'origine par Sartog.


Installation/Configuration
===

Association de vos appareils avec Home Connect
---

Installez l'app Home Connect sur votre Smartphone depuis l'App Store Apple (IOS) ou depuis Google play (Android).

Créez un compte en indiquant votre adresse mail, cliquez sur le lien de validation dans le mail reçu puis retournez sur l'app et connectez vous à votre compte Home Connect.

Connectez les appareils à votre réseau local soit à l'aide de l'app ou en utilisant le WPS de votre box/routeur.

Associez les appareils à l'app en suivant le guide joint aux appareils.

Obtension d'un Home Connect application Client ID
---

Adhérez au programme des développeurs Home Connect et créez un compte sur le site https://developer.home-connect.com/user/register

Enregistrez une nouvelle application sur la page https://developer.home-connect.com/applications/add

Assurez vous que le compte utilise bien la même adresse mail que celle utilisée dans l'app Home Connect à l'étape précédente.

Indiquez pour le champ Redirect URI, l'url de retour disponible sur la page de configuration du plugin de la forme https://xxxxxxxxxxx.jeedom.com/plugins/homeconnect/core/php/callback.php

Enregistrez le Client ID et le Client secret Obtenus et reportez les dans le champs correspondants de la page configuration du plugin. Sauvegardez.

FAQ
===

*J'aimerais remonter des erreurs/modifications au sujet de ce plugin ?*

C'est tout à fait possible via le forum communautaire Jeedom https://community.jeedom.com/ catégorie Plugins -> Objets connectés. Créez un nouveau sujet et n'oubliez pas de lui ajouter le tag "plugin-homeconect".
