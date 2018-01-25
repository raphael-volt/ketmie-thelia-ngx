<?php
/**
 * Plugin TinyMCE 4 Thelia 1.5
 *
 * @package	Thelia
 * @author	Benoit Asselin <contact@ab-d.fr>
 * @version	tinymce4_admin_title.php, 2014/01/21
 * @link	http://www.ab-d.fr/
 *
 */


include_once(dirname(__FILE__) . '/../../../fonctions/authplugins.php');
include_once(dirname(__FILE__) . '/Tinymce4.class.php');

autorisation('tinymce4');



$pages_autorisees = array(
	'produit_modifier',
	'rubrique_modifier',
	'contenu_modifier',
	'dossier_modifier',
	);
if(Tinymce4::controle_acces($pages_autorisees)) :
	extract(Tinymce4::pre_config());
	
	// http://www.tinymce.com/wiki.php/Configuration
?>
	<script type="text/javascript" src="<?php echo $thelia_path ?>tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	tinymce.init({
		language: 'fr_FR',
		selector: 'textarea[name="description"]',
		plugins: [
			'autolink link advlist lists textcolor importcss',
			'contextmenu paste image media responsivefilemanager code'
		],
		menubar: false,
		toolbar1: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | removeformat code',
		toolbar2: 'image media link | bullist numlist | forecolor | paste pastetext outdent indent',
		toolbar3: 'styleselect | fontselect fontsizeselect',
		height: '350',
		content_css: '<?php echo $thelia_styles ?>',
		importcss_append: true,
		importcss_groups: [{title:'Thelia'}],
		image_advtab: true,
		relative_urls: false,
		external_filemanager_path: '<?php echo $thelia_path ?>filemanager/',
		filemanager_title: 'Explorateur de fichiers',
		external_plugins: { 'filemanager': '../filemanager/plugin.min.js' }
	});
	</script>
<?php
endif;


