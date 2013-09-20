<?php
	/**
	 * GMODSkinFoundation skin
	 *
	 * @file
	 * @ingroup Skins
	 * @author Flavien BOSSIAUX (http://gmod.org/bflavien)
	 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0
	 */

	if( !defined( 'MEDIAWIKI' ) ) die( "This is an extension to the MediaWiki package and cannot be run standalone." );

	$wgExtensionCredits['skin'][] = array(
		'path'				=> __FILE__,
		'name'				=> 'GMODSkinFoundation',
		'url'				=> "http://gmod.org/bflavien",
		'author'			=> 'Flavien BOSSIAUX',
		'descriptionmsg'	=> 'myskin-desc',
	);

	$wgValidSkinNames['gmodskin-foundation']			= 'GMODSkinFoundation';
	$wgAutoloadClasses['SkinGMODSkinFoundation']		= dirname(__FILE__).'/GMODSkinFoundation.skin.php';
	$wgExtensionMessagesFiles['GMODSkinFoundation']		= dirname(__FILE__).'/GMODSkinFoundation.i18n.php';

	$wgResourceModules['skins.gmodskin-foundation'] = array(
		'scripts'	=> array(
			'gmodskin-foundation/js/vendor/custom.modernizr.js'	=> array('media' => 'all'),
		),
		'styles'	=> array(
			'gmodskin-foundation/css/app.css'					=> array('media' => 'all'),
		),
		'remoteBasePath' => &$GLOBALS['wgStylePath'],
		'localBasePath' => &$GLOBALS['wgStyleDirectory'],
	);
?>
