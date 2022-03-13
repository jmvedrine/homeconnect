
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
  var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">'
  tr += '<td class="hidden-xs">'
  tr += '    <span class="cmdAttr" data-l1key="id" style="display:none;"></span>'
  tr += '    <div class="input-group">'
  tr += '        <input class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom de la commande}}">'
  tr += '        <span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>'
  tr += '        <span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;background:var(--btn-default-color) !important";width:2%;></span>'
  tr += '    </div>'
  tr += '    <select class="cmdAttr form-control input-sm" data-l1key="value" style="display:none;margin-top:5px;max-width:50%" title="{{Commande info liée}}">'
  tr += '        <option value="">{{Aucune}}</option>'
  tr += '    </select>'
  tr += '</td>'

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
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a> ';
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="remove"><i class="fas fa-minus-circle"></i></a>';
  }
  tr += '</tr>';
  $('#table_cmd tbody').append(tr);
  var tr = $('#table_cmd tbody tr').last()
  jeedom.eqLogic.buildSelectCmd({
    id:  $('.eqLogicAttr[data-l1key=id]').value(),
    filter: {type: 'info'},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (result) {
      tr.find('.cmdAttr[data-l1key=value]').append(result)
      tr.setValues(_cmd, '.cmdAttr')
      jeedom.cmd.changeType(tr, init(_cmd.subType))
    }
  })
  jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}
