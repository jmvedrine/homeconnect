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
	throw new Exception('401 Unauthorized');
}
$eqLogics = homeconnect::byType('homeconnect');
?>

<table class="table table-condensed tablesorter" id="table_healthpiHole">
	<thead>
		<tr>
			<th>{{Appareil}}</th>
			<th>{{Type}}</th>
			<th>{{Connecté}}</th>
			<th>{{Programme actif}}</th>
		</tr>
	</thead>
	<tbody>
	 <?php
foreach ($eqLogics as $eqLogic) {
	echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
    echo '<tr>';
    echo '<td><span class="label label-info" style="font-size : 1em;">' . $eqLogic->getConfiguration('type') . '</span></td>';
    $cmd = $eqLogic->getCmd('info', 'connected');
	$value = '';
	if (is_object($cmd)) {
		$value = $cmd->execCmd() ? 'Oui' : 'Non';
	}
    echo '<td><span class="label label-info" style="font-size : 1em;">' . $value . '</span></td>';
    $cmd = $eqLogic->getCmd('info', 'programActive');
    $value = '';
    if (is_object($cmd)) {
        $value = $cmd->execCmd();
    }
    echo '<td><span class="label label-info" style="font-size : 1em;">' . $value . '</span></td>';
	echo '</tr>';
}
?>
	</tbody>
</table>
