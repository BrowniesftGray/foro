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
* @package module_install
*/
class ucp_character_info
{
	function module()
	{
		return array(
			'filename'	=> 'ucp_character',
			'title'		=> 'Personaje',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'create_char'	=> array('title' => 'Crear personaje', 'auth' => ''),
				'edit_char'	=> array('title' => 'Modificar personaje', 'auth' => ''),
				'delete_char'	=> array('title' => 'Borrar personaje', 'auth' => ''),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}