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

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>THELIA / BACK OFFICE</title>
<link rel="SHORTCUT ICON" href="favicon.ico" />
<link href="styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../lib/jquery/jquery.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
$(document).ready(
	function()
	{
		$( "#arbomenu" ).menu({position: { my: "left top", at: "right top", collision:"flip" }});
	}
);
</script>	
<style>
<!--
#subwrapper {position: relative;}
#treemenu
{
	position: absolute;
	top:127px;
	left:10px;
	z-index:999;
}
#arbomenu {display:inline-flex;}
#arbomenu a {white-space: nowrap;}
.max300
{
	max-height: 300px;
	overflow-y: auto;
	overflow-x: hidden;
}
-->
</style>
<?php ActionsAdminModules::instance()->inclure_module_admin("title"); ?>