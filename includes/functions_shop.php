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
