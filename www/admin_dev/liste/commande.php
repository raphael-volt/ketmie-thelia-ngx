<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) 2005-2013 OpenStudio                                           */
/*      email : info@thelia.fr                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program.  If not, see <http://www.gnu.org/licenses/>.        */
/*                                                                                   */
/*************************************************************************************/

function lister_commandes($critere, $order, $debut, $nbres, $search = '') {

	$commande = new Commande();

	$i=0;

	$query = "select * from $commande->table where 1 $search order by $critere $order limit $debut,$nbres";

  	$resul = $commande->query($query);

  	while($resul && $row = $commande->fetch_object($resul, 'Commande')){

  		$client = new Client();
  		$client->charger_id($row->client);

  		$statutdesc = new Statutdesc();
  		$statutdesc->charger($row->statut);

		$devise = new Devise();
		$devise->charger($row->devise);

  		$total = formatter_somme($row->total(true, true));

		$date = strftime("%d/%m/%y %H:%M:%S", strtotime($row->date));

  		$fond="ligne_".($i++%2 ? "claire":"fonce")."_rub";
  		?>

<ul class="<?php echo($fond); ?>">
	<li style="width:142px;"><?php echo($row->ref); ?></li>
	<li style="width:104px;"><?php echo($date); ?></li>
	<li style="width:200px;"><?php echo($client->entreprise); ?></li>
	<li style="width:200px;"><a href="client_visualiser.php?ref=<?php echo($client->ref); ?>"><?php echo($client->nom . " " . $client->prenom); ?></a></li>
	<li style="width:59px;"><?php echo($total); ?> <?php echo $devise->symbole; ?></li>
	<li style="width:70px;"><?php echo($statutdesc->titre); ?></li>
	<li style="width:40px;"><a href="commande_details.php?ref=<?php echo($row->ref); ?>">éditer</a></li>
	<?php if ($row->statut != Commande::ANNULE) { ?>
		<li style="width:35px; text-align:center;"><a href="#" onclick="supprimer('<?php echo($row->id); ?>'); return false;"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
	<?php } ?>
</ul>
		<?php
	}
}
?>