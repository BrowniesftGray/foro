<?php
/**
*
* @package phpBB3 Shop Mod
* @version 1.0.1
* @copyright (c) 2012 mvader <rd4091@gmail.com>
* @copyright (c) 2018 mgomez <crashmars@gmail.com>

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_ficha.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_shop.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

class phpbb_shop {
	private function render($title, $tpl) {
		global $template;
		page_header($title);
		$template->set_filenames(array(
			'body' => $tpl . '.html')
		);
		page_footer();
	}
	
	public function mode_default() {
		$this->mode_ver();
	}
	
	public function mode_buy() {
		global $db, $template, $user;
		$shop = (int) request_var('shop_id', 0);
		$shops = get_shops();
		
		if (!array_key_exists($shop, $shops)) {
			reset($shops);
			$shop_id = (int) key($shops);
		} else {
			$shop_id = (int) $shop;
		}
		
		$item_id = (int) request_var('item_id', 0);
		$quantity = (int) request_var('quantity', 0);
		$user_id = $user->data['user_id'];
		
		$user->get_profile_fields($user_id);
		$user_ryos = (int) $user->profile_fields['pf_ryos'];
		
		$pj_id = get_pj_id($user_id);
		
		if ($pj_id === false) 
			trigger_error('No puedes comprar objetos sin ficha creada.<br /><a href="'.get_shop_url($shops[$shop_id],$shop_id).'#item'.$item_id.'">Volver a la tienda</a>.');
		
		$sql = $db->sql_build_query('SELECT', array(
			'SELECT'	=> 'i.precio, i.nombre, i.comprable, i.cantidad_max, pi.cantidad',
			'FROM'		=> array(
				PERSONAJES_TABLE		=> 'p',
				ITEMS_TABLE		=> 'i'
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(PERSONAJE_ITEMS_TABLE => 'pi'),
					'ON'	=> 'pi.item_id = i.item_id AND pi.pj_id = p.pj_id',
				)
			),
			'WHERE'		=> "p.pj_id = '$pj_id' AND i.item_id = '$item_id'"
		));
		
		if ($row = $db->sql_fetchrow($db->sql_query($sql))) {	
			if ($row['comprable'] != 1) {
				trigger_error('Este objeto no puede comprarse, solo puede ser asignado por el Staff.<br /><a href="'.get_shop_url($shops[$shop_id],$shop_id).'#item'.$item_id.'">Volver a la tienda</a>.');
			}
			
			if ($user_ryos - $row['precio'] * $quantity < 0) {
				trigger_error('No puedes comprar '.$quantity.' objetos de ese tipo, no tienes suficientes Ryōs.<br /><a href="'.get_shop_url($shops[$shop_id],$shop_id).'#item'.$item_id.'">Volver a la tienda</a>.');
			}
			
			$cantidad_max = (int) $row['cantidad_max'];
			if(($cantidad_max > 0) && ($quantity + (int) $row['cantidad'] > $cantidad_max)) {
				trigger_error('No puedes comprar '.$quantity.' objetos de ese tipo, supera el máximo permitido de '.$cantidad_max.' unidad(es).<br /><a href="'.get_shop_url($shops[$shop_id],$shop_id).'#item'.$item_id.'">Volver a la tienda</a>.');
			}
		} else {
			trigger_error('El objeto especificado no existe.<br /><a href="'.get_shop_url($shops[$shop_id],$shop_id).'#item'.$item_id.'">Volver a la tienda</a>.');
		}
		
	    if (confirm_box(true)) {
			$db->sql_query('UPDATE ' . PERSONAJE_ITEMS_TABLE . " SET cantidad = cantidad + $quantity WHERE pj_id = '$pj_id' and item_id = '$item_id'");
			
			if ((int) $db->sql_affectedrows() < 1) {
				$db->sql_query('INSERT INTO ' . PERSONAJE_ITEMS_TABLE . " (pj_id, item_id, cantidad) VALUES ('$pj_id', '$item_id', '$quantity')");
			}
			
			$new_ryos = $user_ryos - ($row['precio'] * $quantity);
			$db->sql_query('UPDATE '.PROFILE_FIELDS_DATA_TABLE." SET pf_ryos = '$new_ryos' WHERE user_id = '$user_id'");
			
			$moderacion = array(
				'PJ_ID'	=> $pj_id,
				'RAZON' => "Compra $quantity x '" . $row['nombre'] . "' por " . ($quantity * $row['precio']) . " Ryos"
			);
			registrar_moderacion($moderacion);
			
			trigger_error('Objeto comprado exitosamente.<br /><a href="'.get_shop_url($shops[$shop_id],$shop_id).'#item'.$item_id.'">Volver a la tienda</a>.');
	    } else {
	        $s_hidden_fields = build_hidden_fields(array(
	            'submit'    => true,
	            'shop_id' 	=> $shop_id,
				'item_id'	=> $item_id,
				'quantity'	=> $quantity,
			));

	        confirm_box(false, '¿Comprar ' . $quantity . ' x ' . $row['nombre'] . ' por ' . ($quantity * $row['precio']) . ' Ryōs?', $s_hidden_fields);
			trigger_error('Compra cancelada.<br /><a href="'.get_shop_url($shops[$shop_id],$shop_id).'#item'.$item_id.'">Volver a la tienda</a>.');
	    }
	}
	
	public function mode_ver() {
		global $db, $template, $user, $phpbb_root_path;
		$shop = (int) request_var('shop_id', 0);
		$shops = get_shops();
		$user_id = $user->data['user_id'];
		$pj_id = get_pj_id($user_id);
		
		if ($pj_id === false) $pj_id = 0;
		
		if (!array_key_exists($shop, $shops)) {
			reset($shops);
			$shop_id = (int) key($shops);
		} else {
			$shop_id = (int) $shop;
		}
		
		$result = $db->sql_query('SELECT i.item_id, 
										i.nombre, 
										i.tipos, 
										i.descripcion, 
										i.url_imagen, 
										i.requisitos, 
										i.efectos, 
										i.precio, 
										i.cantidad_max,
										i.comprable,
										i.pj_id_invencion,
										pi.cantidad
									FROM ' . ITEMS_TABLE . ' i
										LEFT JOIN ' . PERSONAJE_ITEMS_TABLE . " pi
											ON pi.item_id = i.item_id
											AND pi.pj_id = $pj_id
									WHERE shop_id = '$shop_id'
									ORDER BY i.nombre");
									
		while ($row = $db->sql_fetchrow($result)) {
			
			$cantidad_comprada = (int)$row['cantidad'];
			$cantidad_max = ($row['cantidad_max'] == 0 ? '∞' : $row['cantidad_max']);
			$comprable = $row['comprable'];
			
			if ($row['pj_id_invencion']) {
				$comprable = ($pj_id == $row['pj_id_invencion']);
				$query_inv = $db->sql_query('SELECT SUM(cantidad) AS cantidad
												FROM '. PERSONAJE_ITEMS_TABLE . ' 
												WHERE item_id = '. $row['item_id']);
				if ($row_inv = $db->sql_fetchrow($query_inv)) {
					$cantidad_comprada = (int)$row_inv['cantidad'];
				}
			}
			
			$str_max = ($cantidad_comprada > 0) ? $cantidad_comprada . '/' . $cantidad_max : $cantidad_max;
			
			if ((int)$row['cantidad_max'] > 0 && $cantidad_comprada >= $row['cantidad_max']) {
				$comprable = false;
			}
			
			$template->assign_block_vars('items', array(
				'ID'					=> $row['item_id'],
				'ITEM_NAME'				=> $row['nombre'],
				'DESC'					=> $row['descripcion'],
				'IMAGEN'				=> '<img src="/images/shop_icons/' . $row['url_imagen'] . '" border="0" />',
				'REQS'					=> $row['requisitos'],
				'EFECTOS'				=> $row['efectos'],
				'PRECIO'				=> $row['precio'],
				'MAX'					=> $str_max,
				'COMPRABLE'				=> $comprable,
				'U_BUY'					=> append_sid("/shop.php", 'mode=buy&amp;item_id=' . $row['item_id'] . '&amp;shop_id=' . $shop_id),
			));
			
			$items_tipos = array();
			$tipos = explode(';', $row['tipos']);
			
			for ($i = 0; $i < count($tipos); $i++) {
				$items_tipos[] = array(
					'TAG' => $tipos[$i],
				);
			}
			
			$template->assign_block_vars_array('items.tipos', $items_tipos);
		}
		
		$user->get_profile_fields($user_id);
		$template->assign_vars(array(
			'SHOP_NAME'			=> ucfirst($shops[$shop_id]),
			'RYOS'				=> (int) $user->profile_fields['pf_ryos'],
		));
		
		$this->render('Ver Tienda de ' . $shops[$shop_id], 'shop_ver');
	}
	
	public static function get_modes() {
		return array(
			'mode_default',
			'mode_buy',
			'mode_ver',
		);
	}
}

$mode = request_var('mode', 'default');
$shop = request_var('shop_id', 0);
if (!$mode)
	$mode = 'default';

if (!in_array('mode_' . $mode, phpbb_shop::get_modes()))
	$mode = 'default';
$_shop = new phpbb_shop();
call_user_func(array($_shop, 'mode_' . $mode));