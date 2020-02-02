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
            <label class="col-lg-3 control-label" >{{Pièce par défaut pour les appareils}}</label>
            <div class="col-lg-3">
            <select id="sel_object" class="configKey form-control" data-l1key="defaultParentObject">
              <option value="">{{Aucune}}</option>
              <?php
                foreach (jeeObject::all() as $object) {
                  echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                }
              ?>
            </select>
            </div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label">
				{{Creation de l'application Jeedom sur le siteHome Connect}}
				<sup>
					<i class="fa fa-question-circle tooltips" title="{{Creation des identifiants (https://developer.home-connect.com/applications/add)}}" style="font-size : 1em;color:grey;"></i>
				</sup>
			</label>
			<div class="col-lg-4">
				<a class="btn btn-info" style="margin-bottom : 5px;" title="Creer Application" href="https://developer.home-connect.com/applications/add" target="_blank">
				<i class="fa fa-add"></i>
				{{Créer Application}}
				</a>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label">
				{{Redirect URI}}
				<sup>
					<i class="fa fa-question-circle tooltips" title="{{Cette URL sera demandée sur le site Home Connect pour la création des identifiants (https://developer.home-connect.com/applications/add)}}" style="font-size : 1em;color:grey;"></i>
				</sup>
			</label>
			<div class="col-lg-9">
				<span><?php echo network::getNetworkAccess('external') . '/plugins/homeconnect/core/php/callback.php?apikey=' . jeedom::getApiKey('homeconnect');?></span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">
				{{Client ID}}
				<sup>
					<i class="fa fa-question-circle tooltips" title="{{Récupérez ce paramètre sur le site Home Connect (https://developer.home-connect.com/applications)}}" style="font-size : 1em;color:grey;"></i>
				</sup>
			</label>
			<div class="col-sm-3">
				<input type="text" class="configKey form-control" data-l1key="client_id"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">
				{{Client Secret}}
				<sup>
					<i class="fa fa-question-circle tooltips" title="{{Récupérez ce paramètre sur le site Home Connect (https://developer.home-connect.com/applications)}}" style="font-size : 1em;color:grey;"></i>
				</sup>
			</label>
			<div class="col-sm-3">
				<input type="text" class="configKey form-control" data-l1key="client_secret"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">{{Mode démo (appareils simulés)}}</label>
			<div class="col-sm-2">
				<input id="input_demo_mode" type="checkbox" class="configKey tooltips" data-l1key="demo_mode">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">
				{{Client ID pour le mode démo}}
				<sup>
					<i class="fa fa-question-circle tooltips" title="{{Récupérez ce paramètre sur le site Home Connect (https://developer.home-connect.com/applications) dans le carré API Web Client}}" style="font-size : 1em;color:grey;"></i>
				</sup>
			</label>
			<div class="col-sm-3">
				<input type="text" class="configKey form-control" data-l1key="demo_client_id"/>
			</div>
		</div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Se connecter}}</label>
            <div class="col-sm 3">
                <a class="btn btn-warning" id="bt_loginHomeConnect"><i class="fas fa-sign-in-alt"></i> {{Appareils réels}}</a>                <a class="btn btn-warning" id="bt_loginDemoHomeConnect"><i class="fas fa-sign-in-alt"></i> {{Démo (Simulateurs)}}</a>
            </div>
        </div>


  </fieldset>
</form>

<script>
$('.configKey[data-l1key=demo_mode]').on('change', function() {
	if ($(this).value()=='1') { $('#bt_loginDemoHomeConnect').show(); $('#bt_loginHomeConnect').hide();} else { $('#bt_loginDemoHomeConnect').hide(); $('#bt_loginHomeConnect').show();}
});
$('#bt_loginHomeConnect').on('click', function () {
	$.ajax({ // fonction permettant de faire de l'ajax
		type: "POST", // methode de transmission des données au fichier php
		url: "plugins/homeconnect/core/ajax/homeconnect.ajax.php", // url du fichier php
		data: {
			action: "loginHomeConnect"
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			}
			window.location.href = data.result.redirect;
		}
	});
});
$('#bt_loginDemoHomeConnect').on('click', function () {
	$.ajax({ // fonction permettant de faire de l'ajax
		type: "POST", // methode de transmission des données au fichier php
		url: "plugins/homeconnect/core/ajax/homeconnect.ajax.php", // url du fichier php
		data: {
			action: "loginHomeConnect"
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			}
		}
	});
});
</script>