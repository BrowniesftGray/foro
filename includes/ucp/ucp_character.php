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
if (!defined('IN_PHPBB'))
{
	exit;
}

ini_set('error_reporting', E_ALL);

/**
* @package ucp
*/
class ucp_character
{
	var $u_action;

	function main($id, $mode)
	{
		global $config, $db, $user, $auth, $template, $phpbb_root_path, $phpEx, $request;
		$request->enable_super_globals();
		include_once($phpbb_root_path . 'includes/functions_ficha.' . $phpEx);

		$submit = (isset($_POST['submit'])) ? true : false;
		$action = request_var('action', '');
		$error = $data = array();
		$s_hidden_fields = '';

		switch ($mode) {
			case 'create_char':
				$this->tpl_name = 'ficha_nueva';

				// Common tpl vars
				$template->assign_vars(array(
					'U_ACTION'				=> append_sid("{$phpbb_root_path}ficha.$phpEx", 'mode=nueva'),
				));

				if ($submit) {

					$atrs = array(
						'FUERZA'			=> (int) request_var('atrFuerza', 1),
						'RESISTENCIA'			=> (int) request_var('atrVit', 1),
						'AGILIDAD'			=> (int) request_var('artAg', 1),
						'ESPIRITU'		=> (int) request_var('atrCCK', 1),
						'CONCENTRACION'				=> (int) request_var('atrCon', 1),
						'VOLUNTAD'			=> (int) request_var('atrVol', 1),
					);
//profile_fields_data -> Tabla donde se encuentra la experiencia
					$fields = array_merge(array(
						'NOMBRE'				=> utf8_normalize_nfc(request_var('nombre', '', true)),
						'EDAD'					=> utf8_normalize_nfc(request_var('edad', '', true)),
						'PRINCIPAL'					=> utf8_normalize_nfc(request_var('principal', '', true)),
						'RAMA1'					=> utf8_normalize_nfc(request_var('rama1', '', true)),
						'RAMA2'					=> utf8_normalize_nfc(request_var('rama2', '', true)),
						'FISICO'				=> utf8_normalize_nfc(request_var('descFis', '', true)),
						'CARACTER'			=> utf8_normalize_nfc(request_var('descPsic', '', true)),
						'HISTORIA'			=> utf8_normalize_nfc(request_var('descHis', '', true)),
						'ELEMENTO'			=> utf8_normalize_nfc(request_var('rama1', '', true)),
						'ESPECIALIDAD'	=> utf8_normalize_nfc(request_var('rama2', '', true)),
						'PUNTOS'				=> 20 - array_sum(array_values($atrs)),
					), $atrs);

					$errores = array();

					if ($fields['PUNTOS'] < 0)
						$errores[] = 'Tan solo tienes 20 puntos para repartir.';
					else if ($fields['PUNTOS'] != 0)
						$errores[] = 'Aún te quedan puntos por repartir.';

					$atraux = array();

					foreach ($atrs as $atr) {
						$atraux[] = ($atr < 1);
					}

					if (in_array(true, $atraux)) {
						$errores[] = 'La mínima puntuación posible en los atributos es 1.';
					}

					if (strlen($fields['NOMBRE']) < 5)
						$errores[] = 'El nombre debe tener al menos 5 caracteres.';

					if (count($errores) == 0) {
						$user_id = (int) $user->data['user_id'];
						guardar_ficha($fields);
						trigger_error('Ficha de personaje creada correctamente.');
					} else {
						$fields['ERRORES'] = implode('<br />', $errores);
					}

					$template->assign_vars($fields);
				} else {
					$template->assign_vars(array(
						'FUERZA'				=> 1,
						'VITALIDAD'		=> 1,
						'AGILIDAD'			=> 1,
						'CCK'			=> 1,
						'CONCENTRACION'	=> 1,
						'VOLUNTAD'			=> 1,
						'PUNTOS'			=> 14
					));
				}
			break;

			case 'view_char':
			$this->tpl_name = 'ficha_ver';
				@$user_id = (int) $_GET['pj'];
				$return = false;
				$ver = true;
				$exists = get_ficha($user_id, $return, $ver);
				if (!$exists)					trigger_error('No existe la ficha para este usuario.');

				$template->assign_vars(array(
					'U_ACTION'				=> append_sid("{$phpbb_root_path}ficha.$phpEx", 'mode=ver&amp;pj=' . $user_id),
				));

				if ($submit) {
					if (confirm_box(true))
			    {
					borrar_personaje($user->data['user_id']);
					trigger_error('Personaje borrado correctamente.');
			    }
			    else
			    {
			        $s_hidden_fields = build_hidden_fields(array(
			            'submit'    => true,
			            )
			        );
			        confirm_box(false, '¿Estás seguro de que quieres borrar el personaje?', $s_hidden_fields);
			    }
				}
			break;

			case 'subir_char':
			$this->tpl_name = 'ficha_subir';

			@$user_id = (int) $_GET['pj'];
			$exists = get_ficha($user_id);
			if (!$exists)					trigger_error('No existe la ficha para este usuario.');

			// Common tpl vars
			$template->assign_vars(array(
				'U_ACTION'				=> append_sid("{$phpbb_root_path}ficha.$phpEx", 'mode=subir&amp;pj=' . $user_id),
			));

			if ($submit) {

				$atrs = array(
					'FUERZA'			=> (int) request_var('atrFuerza', 1),
					'VITALIDAD'			=> (int) request_var('atrVit', 1),
					'AGILIDAD'			=> (int) request_var('artAg', 1),
					'CCK'		=> (int) request_var('atrCCK', 1),
					'CONCENTRACION'				=> (int) request_var('atrCon', 1),
					'VOLUNTAD'			=> (int) request_var('atrVol', 1),
				);

				$fields = array_merge(array(
					'PJ_ID'					=> utf8_normalize_nfc(request_var('pj_id', '', true)),
					'CHAKRA'				=> calcula_pc(utf8_normalize_nfc(request_var('rango', '', true)),$atrs['ESPIRITU'], $atrs['CONCENTRACION'], $atrs['VOLUNTAD'])
				), $atrs);

				$errores = array();

				if (count($errores) == 0) {
					$user_id = (int) $user->data['user_id'];
					subirNivel($user_id, $fields);
					trigger_error('Subida de nivel correcta.');
				} else {
					$fields['ERRORES'] = implode('<br />', $errores);
				}

				$template->assign_vars($fields);
			}
			break;

			case 'mod_char':
			$this->tpl_name = 'ficha_mod';

			@$user_id = (int) $_GET['pj'];
			$exists = get_ficha($user_id);
			if (!$exists)					trigger_error('No existe la ficha para este usuario.');

			// Common tpl vars
			$template->assign_vars(array(
				'U_ACTION'				=> append_sid("{$phpbb_root_path}ficha.$phpEx", 'mode=moderar&amp;pj=' . $user_id),
			));

			if ($submit) {

				$atrs = array(
					'FUERZA'			=> (int) request_var('atrFuerza', 1),
					'RESISTENCIA'			=> (int) request_var('atrVit', 1),
					'AGILIDAD'			=> (int) request_var('artAg', 1),
					'ESPIRITU'		=> (int) request_var('atrCCK', 1),
					'CONCENTRACION'				=> (int) request_var('atrCon', 1),
					'VOLUNTAD'			=> (int) request_var('atrVol', 1),
				);

				$fields = array_merge(array(
					'NOMBRE'				=> utf8_normalize_nfc(request_var('nombre', '', true)),
					'PJ_ID'					=> utf8_normalize_nfc(request_var('pj_id', '', true)),
					'EDAD'					=> utf8_normalize_nfc(request_var('edad', '', true)),
					'RANGO'					=> utf8_normalize_nfc(request_var('rango', '', true)),
					'PRINCIPAL'			=> utf8_normalize_nfc(request_var('principal', '', true)),
					'RAMA1'					=> utf8_normalize_nfc(request_var('rama1', '', true)),
					'RAMA2'					=> utf8_normalize_nfc(request_var('rama2', '', true)),
					'RAMA3'					=> utf8_normalize_nfc(request_var('rama3', '', true)),
					'RAMA4'					=> utf8_normalize_nfc(request_var('rama4', '', true)),
					'RAMA5'					=> utf8_normalize_nfc(request_var('rama5', '', true)),
					'FISICO'				=> utf8_normalize_nfc(request_var('descFis', '', true)),
					'CARACTER'			=> utf8_normalize_nfc(request_var('descPsic', '', true)),
					'HISTORIA'			=> utf8_normalize_nfc(request_var('descHis', '', true)),
					'ELEMENTO'			=> utf8_normalize_nfc(request_var('rama1', '', true)),
					'ESPECIALIDAD'	=> utf8_normalize_nfc(request_var('rama2', '', true)),
					'FISICO'				=> utf8_normalize_nfc(request_var('descFis', '', true)),
					'CARACTER'			=> utf8_normalize_nfc(request_var('descPsic', '', true)),
					'HISTORIA'			=> utf8_normalize_nfc(request_var('descHis', '', true)),
					'TEC_JUTSUS'		=> utf8_normalize_nfc(request_var('tecnicas', '', true)),
					'RAZON'					=> utf8_normalize_nfc(request_var('razon', '', true)),
				), $atrs);

				$errores = array();

				if (strlen($fields['NOMBRE']) < 5)
					$errores[] = 'El nombre debe tener al menos 5 caracteres.';

				if (strlen($fields['FISICO']) < 5)
					$errores[] = 'La descripción física debe tener al menos 200 carácteres.';

				if (strlen($fields['CARACTER']) < 15)
					$errores[] = 'La descripción psicológica debe tener al menos 600 carácteres.';

				if (strlen($fields['HISTORIA']) < 20)
					$errores[] = 'La historia debe contener al menos 800 carácteres.';

				if (strlen($fields['RAZON']) < 1)
					$errores[] = 'La razón debe tener alguna información.';

				if (count($errores) == 0) {
					$user_id = (int) $user->data['user_id'];
					actualizar_Ficha($fields);
					registrar_moderacion($fields);
					trigger_error('Ficha de personaje actualizada correctamente.');
				} else {
					$fields['ERRORES'] = implode('<br />', $errores);
				}

				$template->assign_vars($fields);
			}
			break;
		}
	}
}
