<?php
	include_once(realpath(dirname(__FILE__)) . "/../../../fonctions/authplugins.php");
	autorisation("declimax");
?>
<?php
	include_once("pre.php");
	include_once("auth.php");

	include_once("../classes/Declinaison.class.php");
	include_once("../classes/Declinaisondesc.class.php");
	include_once("../classes/Declidisp.class.php");
	include_once("../classes/Declidispdesc.class.php");
	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Rubriquedesc.class.php");
	include_once("../classes/Exdecprod.class.php");
	include_once("../classes/Rubdeclinaison.class.php");

	if(!isset($lang)) $lang=1;
	$action=$_REQUEST['action'];
	if(!isset($action)) $action="";
?>
<table width="100%" cellpadding="5" cellspacing="0">
	<tr class="claire">
		<td class="designation">
			Rubriques associ&eacute;es<br /><br />
			/!\ Pour s&eacute;lectionner ou d&eacute;-s&eacute;lectionner des rubriques, cliquez en pressant la touche de multi-s&eacute;lection (Ctrl sur PC).
		</td>
		<td>
        <?php
			$rubdec = new Rubdeclinaison();
			$query = "select * from $rubdec->table where declinaison=\"".$_REQUEST['id']."\"";
			$resul = mysql_query($query, $rubdec->link);
			$tabrub = array();
			while($row=mysql_fetch_object($resul)){
				array_push($tabrub, $row->rubrique);
			}
		?>
        <input type="hidden" name="declimax" value="ok" />
        <select name="rubriques[]" id="rubriques" multiple="multiple" style="width: 440px;">
        <?php
            echo arbreOptionRubTab(0,1,$tabrub);
        ?>
        </select><br/><br/>
        <?php
			function arbreOptionRubTab($depart, $niveau, $prubrique){
				$rec="";
				$espace="";
				
				$niveau++;
				$trubrique = new Rubrique();
				$trubriquedesc = new Rubriquedesc();
				
				$query = "select * from $trubrique->table where parent=\"$depart\"";
				$resul = mysql_query($query, $trubrique->link);
				
				for($i=0; $i<$niveau; $i++) $espace .="&nbsp;&nbsp;&nbsp;";
				
				while($row=mysql_fetch_object($resul)){
					$trubriquedesc->charger($row->id);
					$trubrique->charger($trubriquedesc->rubrique);
					
					if(in_array($row->id,$prubrique)) $selected="selected"; else $selected="";
					$rec .= "<option value=\"$row->id\" $selected>" . $espace . $trubriquedesc->titre . "</option>";
					$rec .= arbreOptionRubTab($row->id, $niveau, $prubrique, $nbprod);
				}
				return $rec;
			}
        ?>

        </td>
    </tr>
</table>        
<table width="100%" cellpadding="5" cellspacing="0">
	<tr class="claire">
		<td class="designation">
			/!\ Activation / <br />
            D&eacute;sactivation<br />
            des valeurs disponibles<br />
            pour tous les produits<br /><br />
		</td>
		<td>
	<!-- bloc des valeurs disponibles -->
	 		<?php

				$declidisp=new Declidisp();
				$declidispdesc=new Declidispdesc();
                $query = "select * from $declidisp->table where declinaison='".$_REQUEST['id']."'";
                $resul = mysql_query($query);
                
                $declidispdesclang = new Declidispdesc();
               	$i=0;
                while($row = mysql_fetch_object($resul)){
                        $query2 = "select * from $declidispdesc->table where declidisp='$row->id' and lang='1'";
                        $resul2 = mysql_query($query2);
                        while($row2 = mysql_fetch_object($resul2)){
                                $declidispdesc->charger($row2->id, 1);
                                $declidispdesclang->charger_declidisp($row2->declidisp, $lang);
                                
                                if(!($i%2)) $fond="claire";
  								else $fond="fonce";
  								$i++;

            ?>

			<div class="<?php echo($fond); ?>" style="height: auto; padding: 3px;">
					<strong><?php echo($declidispdesc->titre); ?> : </strong>
                    <a href="../client/plugins/declimax/declimax-activer.php?declidisp=<?php echo($row->id); ?>&action=activer" class="climax-jquery" target="_blank">Activer</a> / 
                    <a href="../client/plugins/declimax/declimax-activer.php?declidisp=<?php echo($row->id); ?>&action=desactiver" class="climax-jquery" target="_blank">Désactiver</a> pour tous les produits
			</div>
			 <?php
              }
             }
             ?>
        </td>
    </tr>
</table>
