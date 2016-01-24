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

require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
require_once dirname(__FILE__) . "/../class/jebble.class.php";
if (isset($argv)) {
	foreach ($argv as $arg) {
		$argList = explode('=', $arg);
		if (isset($argList[0]) && isset($argList[1])) {
			$_REQUEST[$argList[0]] = $argList[1];
		}
	}
}
if (trim(config::byKey('api')) == '') {
	echo 'Vous n\'avez aucune clé API configurée, veuillez d\'abord en générer une (Page Général -> Administration -> Configuration';
	log::add('jebble', 'error', 'Vous n\'avez aucune clé API configurée, veuillez d\'abord en générer une (Page Général -> Administration -> Configuration');
	die();
}

if (init('apikey') != '' || init('api') != '') {
	try {
		if (config::byKey('api') != init('apikey') && config::byKey('api') != init('api')) {
			connection::failed();
			throw new Exception('Clé API non valide, vous n\'êtes pas autorisé à effectuer cette action (jeeApi). Demande venant de :' . getClientIp() . 'Clé API : ' . init('apikey') . init('api'));
		}
		$sql = "SELECT 1 FROM config WHERE plugin=:plugin AND `key`='active' AND `value`='1'";
		$results = DB::Prepare($sql, array('plugin' => 'jebble'), DB::FETCH_TYPE_ALL);
		$ids = jebble::getDisplayIds();
		log::add('jebble', 'debug', 'retrived ids are ' . $ids);
		$decodeIds = implode("','", json_decode($ids));
		if(count($results) == 1) {
			if($decodeIds != "") {
				$values = array(
					'ids' => $decodeIds
				);
				$sql = "SELECT s.id, s.name, s.group FROM scenario s WHERE s.id in ('" . $decodeIds . "') ORDER BY s.group, s.name";
			} else {
				$values = array();
				$sql = "SELECT s.id, s.name, s.group FROM scenario s WHERE s.isActive = 1 ORDER BY s.group, s.name";
			}
			$scenarios = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
			echo json_encode($scenarios);
		} else {
			log::add('jebble', 'error', 'Le plugin Jebble semble non installé ou désactivé');
		}
	} catch (Exception $e) {
		echo $e->getMessage();
		log::add('jebble', 'error', $e->getMessage());
	}
	die();
}
?>
