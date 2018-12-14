<?php

/**
*
*
* @package - Activity 24 hours
* @copyright (c) 2015 RMcGirr83
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
// Some characters you may want to copy&paste:
// ’ » “ ” …

$lang = array_merge($lang, array(
	'BOTS_24HOUR_TOTAL'	=> array(
		1 => '%d bot',
		2 => '%d bots',
	),
	'USERS_24HOUR_TOTAL'	=>  '%d registrados,',
	'HIDDEN_24HOUR_TOTAL'	=> ' %d ocultos, ',
	'GUEST_ONLINE_24'		=> array(
		1 => ' y %d invitado',
		2 => ' y %d invitados',
	),
	'LAST_24_HOURS'	=> ' activos en las últimas 24 hs',
	'24HOUR_TOPICS'			=> 'Temas creados %d',
	'24HOUR_POSTS'			=> 'Posts creados %d',
	'24HOUR_USERS'			=> 'Nuevos usuarios %d',
	'NO_ONLINE_BOTS'		=> 'Sin Bots activos',

	'TWENTYFOURHOUR_STATS'	=> 'Actividad en las últimas 24 horas',
	'TOTAL_24HOUR_USERS'	=> array(
		1 => 'En total hubo %d usuario :: ',
		2 => 'En total hubo %d usuarios :: ',
	),
));
