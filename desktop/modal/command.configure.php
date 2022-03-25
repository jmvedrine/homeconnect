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

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$cmd = cmd::byId(init('id'));
if (!is_object($cmd)) {
    throw new Exception('{{Commande non trouvée}}'.' : ' . init('id'));
}
global $JEEDOM_INTERNAL_CONFIG;

$cmdInfo = jeedom::toHumanReadable(utils::o2a($cmd));
$cmdInfo['eqLogicName'] = $cmd->getEqLogic()->getName();
sendVarToJS('cmdInfo', $cmdInfo);
sendVarToJS('cmdInfoSearchString', urlencode(str_replace('#', '', $cmd->getHumanName())));
sendVarToJS('cmdInfoString', $cmd->getHumanName());
$jsonPresent = false;
?>


<div role="tabpanel">
  <div class="tab-content" id="div_displayCmdConfigure" style="overflow-x:hidden">
    <div role="tabpanel" class="tab-pane active" id="cmd_information">
      <br/>
      <div class="row">
        <div class="col-sm-9" >
          <form class="form-horizontal">
            <fieldset>
              <legend><i class="fas fa-cogs"></i> {{Configuration Home Connect}}</legend>
              <div class="form-group">
                <label class="col-xs-3 control-label">{{Path}}</label>
                <div class="col-xs-9">
                	<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="path" placeholder="{{Chemin}}" title="{{Chemin}}" style="display:inline-block"></input>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-3 control-label">{{Key}}</label>
                <div class="col-xs-9">
                	<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="key" placeholder="{{Clé}}" title="{{Clé}}" style="display:inline-block"></input>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-3 control-label">{{Category}}</label>
                <div class="col-xs-9">
                	<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="category" placeholder="{{Catégorie}}" title="{{Catégorie}}" style="display:inline-block"></input>
                </div>
              </div>
              <div class="form-group">
                <div class="col-xs-3"></div>

                <div class="col-xs-9">
                  <?php if ($cmd->getConfiguration('key', '') !== '') {
                      $tableData = homeconnect::appliancesCapabilities();
                      if (isset($tableData[$cmd->getConfiguration('key')])) {
                          $data = $tableData[$cmd->getConfiguration('key')];
                          echo '<span class="label label-default">'.$data['name'].'</span> ';
                          if (isset($data['type'])) {
                              $tr .= '<span class="label label-success">'.$data['type'].'</span><br/>';
                              switch ($data['type']) {
                                case 'Enumeration':
                                  if (isset($data['enum'])) {
                                      foreach ($data['enum'] as $enum => $enumValue) {
                                          if (isset($enumValue['available'])) {
                                              $disabled = $enumValue['available'];
                                          }
                                          $isDisable = (in_array($cmd->getEqLogic()->getConfiguration('type'), $disabled)) ? '': 'disabled';
                                          $tr .= '<span>';
                                          $tr .= '<span class="label label-info data_key">'.$enum.'</span>';
                                          $tr .= ' => ';
                                          $tr .= '<span class="label label-info data_value">'.$enumValue['name'].'</span>';
                                          $tr .= ' <a class="btn btn-success roundedLeft btn-xs bt_testEnum '.$isDisable.'"><i class="fas fa-rss"></i> {{Tester}}</a>';
                                          $tr .= ' <span id="resultTestEnum"></span>';
                                          $tr .= '</span><br/>';
                                      }
                                  }
                                  break;
                                case 'Int':
                                case 'Double':
                                  if (isset($data['constraints'])) {
                                    $tr .= '<span class="label label-info">{{Minimum}} : '.$data['constraints']['min'].'</span>';
                                    $tr .= '<span class="label label-info">{{Maximum}} : '.$data['constraints']['max'].'</span>';
                                  }
                                  break;
                                case 'Boolean':
                                  $tr .= '<span class="label label-info">true</span>';
                                  $tr .= '<span class="label label-info">false</span>';
                                  break;
                                case 'String':
                                  $tr .= '';
                                  break;
                              }
                              echo $tr;
                          }
                          if (isset($data['unit'])) {
                              echo '<span class="label label-info">{{Unité}} : '.$data['unit'].'</span>';
                          }
                      }
                  }
                  ?>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(function() {
  //modal title:
  var title = '{{Configuration commande}}'
  title += ' : ' + cmdInfo.eqLogicName
  title += ' <span class="cmdName">[' + cmdInfo.name + '] <em>(' + cmdInfo.type + ')</em></span>'
  $('#div_displayCmdConfigure').parents('.ui-dialog').find('.ui-dialog-title').html(title)
  if ($('#eqLogicConfigureTab').length) {
    $('#cmdConfigureTab').parents('.ui-dialog').css('top', "50px")
  }
})

$('#div_displayCmdConfigure').setValues(cmdInfo, '.cmdAttr');

$('.bt_testEnum').off('click').on('click',function() {
    var elbutton = $(this);
	$.ajax({ // fonction permettant de faire de l'ajax
		type: "POST", // methode de transmission des données au fichier php
		url: "plugins/homeconnect/core/ajax/homeconnect.ajax.php", // url du fichier php
		data: {
			action: "testEnum",
			data_key: cmdInfo.configuration.key,
            data_value: elbutton.parent().find('.data_value').value(),
            path: cmdInfo.configuration.path,
            eqLogic_id: cmdInfo.eqLogic_id
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
          console.log(data)
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			}
            elbutton.next('#resultTestEnum').html(data)
		}
	});
});
</script>
