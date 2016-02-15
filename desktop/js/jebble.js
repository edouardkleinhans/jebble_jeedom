
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



/*
 * Fonction pour l'ajout de commande, appellé automatiquement par plugin.template
 */
 /*
$(".btn-success").on("click", function(e) {
	var matches = [];
	$(".scenarios:checked").each(function() {
		matches.push(this.id);
	});
	$("#display_ids").val(JSON.stringify(matches));
});
*/

function saveEqLogic(_eqLogic) {
    
    var matches = [];
	$(".scenarios:checked").each(function() {
		matches.push(this.id);
	});
    
    _eqLogic.configuration.display_ids = JSON.stringify(matches);
    return _eqLogic;
}

function addCmdToTable(_cmd) {
	console.log('addCmdToTable');
}

function printEqLogic(_qqchose) {
	$.each($('.scenarios'), function(i,v){
		$(this).bootstrapSwitch('state', false);
	});
	if($("#display_ids").val() != '') {
		console.log($("#display_ids").val());
		$.each(JSON.parse($("#display_ids").val()), function(i,v){
			$('#' + v).bootstrapSwitch('state', true);
		});
	}
}