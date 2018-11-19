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
	
function get_shop_url($shop_name, $shop_id) {
	return append_sid("{$phpbb_root_path}tienda/" . title_to_url($shop_name) . '-c' . $shop_id);
}
