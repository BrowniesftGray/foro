<?php
/**
*
* @package ucp
* @version $Id$
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require($phpbb_root_path . 'common.' . $phpEx);
require($phpbb_root_path . 'includes/functions_module.' . $phpEx);
require_once($phpbb_root_path . 'includes/functions_content.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_ficha.' . $phpEx);

// Basic parameter data
$id 	= request_var('i', '');
$mode	= request_var('mode', '');

if (!in_array($mode, array('nueva', 'moderar', 'ver', 'subir')))
{
	trigger_error('La página que buscas no existe.');
}

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('ucp');

// Setting a variable to let the style designer know where he is...
$template->assign_var('S_IN_UCP', true);

$module = new p_master();
$default = false;

// Basic "global" modes
switch ($mode)
{
	case 'nueva':
		 if ($user->data['user_id'] == ANONYMOUS) trigger_error('No puedes acceder aquí sin conectarte.');
		 if (ficha_exists($user->data['user_id'])) {
 			trigger_error('Ya tienes ficha de personaje. Si quieres hacer otra deberás borrar primero la actual en tu perfil.');
 		}
		$module->p_mode = 'create_char';
		$module->load('ucp', 'character');
		$module->display('Nueva ficha :: Shinobi Path');
	break;

	case 'moderar':
	if ($user->data['user_id'] == ANONYMOUS) trigger_error('No puedes acceder aquí sin conectarte.');
	$grupo = $user->data['group_id'];
	if ($grupo == 5 || $grupo == 4){
		$module->p_mode = 'mod_char';
		$module->load('ucp', 'character');
		$module->display('Moderar ficha :: Shinobi Path');
	}
	else{
		trigger_error('No puedes acceder aquí sin ser moderador o administrador.');
	}
	break;

	case 'ver':
		$module->p_mode = 'view_char';
		$module->load('ucp', 'character');
		$module->display('Ficha de personaje :: Shinobi Path');
	break;

	case 'subir':
	$user_id = $user->data['user_id'];
	if (comprobarSubida($user_id) == true) {
		// code...
		$module->p_mode = 'up_char';
		$module->load('ucp', 'character');
		$module->display('Subida de nivel :: Shinobi Path');
	}
	else{
		trigger_error('No puedes subir de nivel aún.');
	}
	break;
}
