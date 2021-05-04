
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
 $('#bt_deleteEqLogic').on('click', function () {
	bootbox.confirm('{{Cette action supprimera tous les appareils. Faites une synchronisation pour les re-créer}}', function(result) {
		if (result) {
			$.ajax({// fonction permettant de faire de l'ajax
				type: "POST", // methode de transmission des données au fichier php
                url: "plugins/homeconnect/core/ajax/homeconnect.ajax.php", // url du fichier php

                data: {
                    action: "deleteEqLogic",
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
                    $('#div_alert').showAlert({message: '{{Suppression effectuée}}', level: 'success'});
                    location.reload();
                }
	        });
        }
    });
 });

 $('#bt_syncHomeConnect').on('click', function () {
	bootbox.confirm('{{Pour se synchroniser correctement vos appareils doivent être allumés, WiFi activé et sans programme en cours.}}', function(result) {
		if (result) {
			$.ajax({ // fonction permettant de faire de l'ajax
				type: "POST", // methode de transmission des données au fichier php
				url: "plugins/homeconnect/core/ajax/homeconnect.ajax.php", // url du fichier php

				data: {
					action: "syncHomeConnect",
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
					$('#div_alert').showAlert({message: '{{Synchronisation réussie}}', level: 'success'});
					location.reload();
				}
			});
		}
	});
});

 $('#bt_healthHomeConnect').on('click', function () {
	$('#md_modal').dialog({title: "{{Santé Home Connect}}"});
	$('#md_modal').load('index.php?v=d&plugin=homeconnect&modal=health').dialog('open');
});

 $('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').on('change',function(){
	if($(this).value() == null){
		return;
	}
	$('#img_device').attr("src", 'plugins/homeconnect/core/config/images/'+$(this).value()+'.png');
});

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
/*
 * Fonction pour l'ajout de commande, appellé automatiquement par plugin.template
 */
function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    var _cmd = {configuration: {}};
  }
  if (!isset(_cmd.configuration)) {
    _cmd.configuration = {};
  }
  var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
  tr += '<td>';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="id" style="display : none;">';
  tr += '<div class="row">';
  tr += '<div class="col-sm-4">';
  tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icône</a>';
  tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
  tr += '</div>';
  tr += '<div class="col-sm-8">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" placeholder="{{Nom}}">';
  tr += '</div>';
  tr += '</div>';
  tr += '</td>';
  tr += '<td>';
  tr += '<input class="cmdAttr form-control type input-sm" data-l1key="type" value="action" disabled style="display:inline-block;margin-bottom:5px;max-width:120px;" />';
  tr += '<input class="cmdAttr form-control type input-sm" data-l1key="subType" value="action" disabled style="display:inline-block;margin-bottom:5px;max-width:120px;" />';
  tr += '</td>';
  tr += '<td>';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
  tr += '</td>';

  tr += '<td>';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:50px;display:inline-block;">';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:50px;display:inline-block;">';	
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}" style="width:50px;display:inline-block;margin-right:5px;">';	
  tr += '</td>';

  tr += '<td>';
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
  }
  tr += '</tr>';
  $('#table_cmd tbody').append(tr);
  $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
  if (isset(_cmd.type)) {
    $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
  }
  jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}
