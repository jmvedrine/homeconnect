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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>

<form class="form-horizontal">
    <fieldset>
    <div class="form-group">
        <label class="col-lg-2 control-label">{{Client ID}}</label>
        <div class="col-lg-2">
            <input id="homeconnectclientid" class="configKey form-control" data-l1key="clientId" style="margin-top:-5px" placeholder="{{Client ID sur site développeur}}"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">{{Client Secret}}</label>
        <div class="col-lg-2">
            <input id="homeconnectclientsecret" class="configKey form-control" data-l1key="clientSecret" style="margin-top:-5px" placeholder="{{Client secret sur site développeur}}"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">{{Client Redirect URL}}</label>
        <div class="col-lg-2">
            <input id="homeconnectredirecturl" class="configKey form-control" data-l1key="redirectUrl" style="margin-top:-5px" placeholder="{{Url de redirection sur site développeur}}"/>
        </div>
    </div>
	
		<?php 
		if (empty(config::byKey('auth','homeconnect'))){
			echo ('
				<div class="form-group">
					<label class="col-lg-2 control-label">{{Se connecter}}</label>
					<div class="col-lg-2">
						<a class="btn btn-warning" id="bt_loginHomeConnect"><i class="fas fa-sign-in-alt"></i> {{Se connecter}}</a>
					</div>
				</div>
			');
		} 
		?>
			
  </fieldset>
</form>

<script>

	$('#bt_loginHomeConnect').on('click', function () {
		
		$.ajax({ // fonction permettant de faire de l'ajax

			type: "POST", // methode de transmission des données au fichier php
			url: "plugins/homeconnect/core/ajax/homeconnect.ajax.php", // url du fichier php
		
			data: {
				action: "loginHomeConnect",
			},
			
			dataType: 'json',
			
			error: function (request, status, error) {
				handleAjaxError(request, status, error);
			},

			success: function (data) { // si l'appel a bien fonctionné
				if (data.state != 'ok') {
					$('#div_alert').showAlert({message: data.result, level: 'danger'});
					return;
				}
			}
		});
	});
	
</script>