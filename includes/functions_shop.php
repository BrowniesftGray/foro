<?php

include($phpbb_root_path . 'config.' . $phpEx);

function get_shops() {
	global $db;
	
	$query = $db->sql_query('SELECT shop_id, nombre FROM ' . SHOPS_TABLE . " WHERE visible = 1");
	while(($row = mysqli_fetch_assoc($query))) {
		$shops[$row['shop_id']] = $row['nombre'];
	}
	$db->sql_freeresult($query);
	
	return $shops;
}

function get_full_shops() {
	global $db;
	$shops = array();
	
	$query = $db->sql_query('SELECT shop_id, nombre, fa_icon, descripcion 
								FROM ' . SHOPS_TABLE . " WHERE visible = 1");
	while(($row = mysqli_fetch_assoc($query))) {
		$shops[] = array(
			'ID'			=>	$row['shop_id'],
			'NOMBRE'		=>	$row['nombre'],
			'FA_ICON'		=>	$row['fa_icon'],
			'DESCRIPCION'	=>	$row['descripcion'],
			'URL'			=>	get_shop_url($row['nombre'], $row['shop_id']),
		);
	}
	$db->sql_freeresult($query);
	
	return $shops;
}
	
function get_shop_url($shop_name, $shop_id) {
	global $phpbb_root_path;
	return append_sid("{$phpbb_root_path}tienda/" . title_to_url($shop_name) . '-c' . $shop_id);
}

// TO DO: Inventario relativo al post
function get_pj_inventory($pj_id, $post_id = 0, $shop_id = 0) {
	global $db;
	$items = false;
	
	if ($pj_id === false) return false;
	
	$sql = "SELECT i.item_id, 
				i.nombre, 
				i.tipos, 
				i.descripcion, 
				i.url_imagen, 
				i.requisitos, 
				i.efectos,
				pi.cantidad,
				pi.ubicacion
			FROM " . ITEMS_TABLE . " i
				INNER JOIN " . PERSONAJE_ITEMS_TABLE . " pi
					ON pi.item_id = i.item_id
			WHERE pi.pj_id = '$pj_id'
				AND pi.cantidad > 0";
	if ($shop_id > 0) $sql .= " AND i.shop_id = $shop_id ";	
	$sql .=	" ORDER BY i.nombre";
			
	$query = $db->sql_query($sql);
	
	while ($row = $db->sql_fetchrow($query)) {
		
		$items_tipos = array();
		$tipos = explode(';', $row['tipos']);
		
		for ($i = 0; $i < count($tipos); $i++) {
			$items_tipos[] = array(
				'TAG' => $tipos[$i],
			);
		}
			
		$items[] = array(
			'ITEM_ID'		=> $row['item_id'],
			'NOMBRE'		=> $row['nombre'],
			'DESCRIPCION'	=> $row['descripcion'],
			'IMAGEN'		=> '<img src="/images/shop_icons/' . $row['url_imagen'] . '" border="0" />',
			'REQS'			=> $row['requisitos'],
			'EFECTOS'		=> $row['efectos'],
			'CANTIDAD'		=> $row['cantidad'],
			'UBICACION'		=> $row['ubicacion'],
			'tags'			=> $items_tipos,
		);
	}
	$db->sql_freeresult($query);
	
	return $items;
}
