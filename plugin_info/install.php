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

function homeconnect_install() {
	if ( version_compare(jeedom::version(), "4", "<")) {
		// Copie des templates dans le répertoire du plugin widget pour pouvoir éditer les commandes sans perte de la template associée.
		$srcDir	 = __DIR__ . '/../core/template/dashboard';
		$resuDir = __DIR__ . '/../../widget/core/template/dashboard';
		if (file_exists($resuDir)) { // plugin widget déjà installé
			$file = '/cmd.info.numeric.dureev3.html';
			shell_exec("cp $srcDir$file $resuDir");
		}
		$srcDir	 = __DIR__ . '/../core/template/mobile';
		$resuDir = __DIR__ . '/../../widget/core/template/mobile';
		if (file_exists($resuDir)) { // plugin widget déjà installé
			$file = '/cmd.info.numeric.dureev3.html';
			shell_exec("cp $srcDir$file $resuDir");
		}
	}
}

function homeconnect_update() {
	if ( version_compare(jeedom::version(), "4", "<")) {
		// Copie des templates dans le répertoire du plugin widget pour pouvoir éditer les commandes sans perte de la template associée.
		$srcDir	 = __DIR__ . '/../core/template/dashboard';
		$resuDir = __DIR__ . '/../../widget/core/template/dashboard';
		if (file_exists($resuDir)) { // plugin widget déjà installé
			$file = '/cmd.info.numeric.dureev3.html';
			shell_exec("cp $srcDir$file $resuDir");
		}
		$srcDir	 = __DIR__ . '/../core/template/mobile';
		$resuDir = __DIR__ . '/../../widget/core/template/mobile';
		if (file_exists($resuDir)) { // plugin widget déjà installé
			$file = '/cmd.info.numeric.dureev3.html';
			shell_exec("cp $srcDir$file $resuDir");
		}
	}
    message::add('homeconnect', 'Merci pour la mise à jour de ce plugin, faites une synchronisation pour mettre à jour les commandes.');
}


function homeconnect_remove() {
    
}

?>
