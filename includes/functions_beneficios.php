<?php

//include($phpbb_root_path . 'config.' . $phpEx);

function get_tiers() {
	global $db;
	$tiers = false;
	
	$query = $db->sql_query('SELECT tier_id, orden, nombre FROM ' . PATREON_TIERS_TABLE . ' ORDER BY orden ASC');
	while($row = $db->sql_fetchrow($query)) {
		$tiers[] = array(
			'ID'			=>	$row['tier_id'],
			'ORDEN'			=>	$row['orden'],
			'NOMBRE'		=>	$row['nombre'],
		);
	}
	$db->sql_freeresult($query);
	
	return $tiers;
}

function get_user_tier($user_id) {
	global $db;
	$user_tier = false;
	
	$query = $db->sql_query('SELECT	b.tier_id, t.nombre, t.orden, ub.fecha_inicio, ub.fecha_fin
								FROM ' . PATREON_USER_BENEFICIOS_TABLE . ' ub
									INNER JOIN ' . PATREON_BENEFICIOS_TABLE . ' b
										ON b.beneficio_id = ub.beneficio_id
									INNER JOIN ' . PATREON_TIERS_TABLE . ' t
										ON t.tier_id = b.tier_id
								WHERE ub.user_id = '. $user_id . '
										AND (ub.fecha_fin IS NOT NULL
										AND ub.fecha_fin >= NOW())
								ORDER BY t.orden DESC, ub.fecha_inicio DESC, ub.fecha_fin DESC
								LIMIT 1');
	if($row = $db->sql_fetchrow($query)) {
		$user_tier = $row;
	}
	$db->sql_freeresult($query);
	
	return $user_tier;
}

function get_beneficios($user_id = false, $tier_id = false) {
	global $db;
	$beneficios = false;
	
	if ($tier_id) {
		$query = $db->sql_query('SELECT orden FROM ' . PATREON_TIERS_TABLE . ' WHERE tier_id = ' .$tier_id);
		if ($row = $db->sql_fetchrow($query)) {
			$tier_orden = $row['orden'];
		}
	}
	
	if ($user_id) {
		$sql = 'SELECT	b.beneficio_id, b.nombre, b.nombre_php, 
						t.nombre as tier, ub.fecha_inicio, ub.fecha_fin
					FROM ' . PATREON_USER_BENEFICIOS_TABLE . ' ub
						INNER JOIN ' . PATREON_BENEFICIOS_TABLE . ' b
							ON b.beneficio_id = ub.beneficio_id
						INNER JOIN ' . PATREON_TIERS_TABLE . ' t
							ON t.tier_id = b.tier_id
					WHERE ub.user_id = ' . $user_id . 
		  ($tier_id ? ' AND t.orden <= ' . $tier_orden : ' ') . ' 
						AND (ub.fecha_fin IS NOT NULL
						AND ub.fecha_fin >= NOW())
					ORDER BY t.orden ASC, b.nombre ASC';
	} else {
		$sql = 'SELECT	b.beneficio_id, b.nombre, b.nombre_php, t.nombre as tier
					FROM ' . PATREON_BENEFICIOS_TABLE . ' b
						INNER JOIN ' . PATREON_TIERS_TABLE . ' t
							ON t.tier_id = b.tier_id' .
	  ($tier_id ? ' WHERE t.orden <= ' . $tier_orden : ' ') . ' 
					ORDER BY t.orden ASC, b.nombre ASC';
	}
	
	$query = $db->sql_query($sql);
	while($row = $db->sql_fetchrow($query)) {
		$beneficios[] = $row;
	}
	$db->sql_freeresult($query);
	
	return $beneficios;
}

function get_user_beneficios_historico($user_id) {
	global $db;
	$beneficios_historico = false;
	
	$query = $db->sql_query(
		'SELECT	b.beneficio_id,
				b.nombre,
				ub.fecha_inicio,
				ub.fecha_fin,
				ub.moderador_add,
				CASE WHEN ub.fecha_fin >= NOW()
					THEN 1 ELSE 0 END AS activo
			FROM ' . PATREON_USER_BENEFICIOS_TABLE . ' ub
				INNER JOIN ' . PATREON_BENEFICIOS_TABLE . ' b
					ON b.beneficio_id = ub.beneficio_id
				INNER JOIN ' . PATREON_TIERS_TABLE . ' t
					ON t.tier_id = b.tier_id
			WHERE ub.user_id = '. $user_id . '
			ORDER BY ub.fecha_inicio DESC,
					t.orden DESC,
					b.nombre ASC');
	
	while($row = $db->sql_fetchrow($query)) {
		$beneficios_historico[] = $row;
	}
	$db->sql_freeresult($query);
	
	return $beneficios_historico;
}

function limpiar_tier ($user_id) {
	global $db, $user;
	
	$query = $db->sql_query('UPDATE ' . PATREON_USER_BENEFICIOS_TABLE . "
								SET fecha_fin = NOW()
									,moderador_del = '".$user->data['username']."'
								WHERE fecha_fin >= NOW()
									AND user_id = $user_id");
	$db->sql_freeresult($query);
	
	return true;
}

function renovar_tier ($user_id) {
	$user_tier = get_user_tier($user_id);
	return asignar_tier($user_id, $user_tier['tier_id']);
}

function asignar_tier ($user_id, $tier_id) {
	if ($tier_id <= 0) return false;
	
	$beneficios = get_beneficios(false, $tier_id);
	
	foreach ($beneficios as $beneficio) {
		asignar_beneficio($user_id, $beneficio['beneficio_id']);
	}
	
	return true;
}

function asignar_beneficio ($user_id, $beneficio_id) {
	global $db, $user;
	$id_asignado = $fecha_fin = $permanente = false;
	
	$query = $db->sql_query("SELECT id, fecha_fin
								FROM " . PATREON_USER_BENEFICIOS_TABLE . " 
								WHERE user_id = $user_id
									AND beneficio_id = $beneficio_id
									AND (fecha_fin >= NOW()
										OR fecha_fin IS NULL)");
	if ($row = $db->sql_fetchrow($query)) {
		$id_asignado = $row['id'];
		$fecha_fin = ($row['fecha_fin'] ? $row['fecha_fin'] : false);
	}
	$db->sql_freeresult($query);
	
	$query = $db->sql_query("SELECT permanente 
								FROM " . PATREON_BENEFICIOS_TABLE . " 
								WHERE beneficio_id = $beneficio_id");
	if ($row = $db->sql_fetchrow($query)) {
		$permanente  = $row['permanente'];
	}
	$db->sql_freeresult($query);
	
	$now = date('Y-m-d');
	$sql_array = array(
			'user_id'		=> $user_id,
			'beneficio_id'	=> $beneficio_id,
			'moderador_add'	=> $user->data['username']
	);
	
	if (!$permanente) {
		$fecha_fin = date("Y-m-d", strtotime(date("Y-m-d", strtotime($fecha_fin ? $fecha_fin : $now)) . " +1 month"));
		$sql_array['fecha_fin'] = $fecha_fin;
	}
	
	if (!$id_asignado) {
		$sql_array['fecha_inicio']	= $now;
		$sql = 'INSERT INTO ' . PATREON_USER_BENEFICIOS_TABLE . $db->sql_build_array('INSERT', $sql_array);
	} else {
		$sql = 'UPDATE ' . PATREON_USER_BENEFICIOS_TABLE . ' SET ' . 
					$db->sql_build_array('UPDATE', $sql_array) .
					" WHERE id = $id_asignado";
	}
	
	$db->sql_query($sql);
	
	return true;
}

function eliminar_beneficio ($user_id, $beneficio_id) {
	global $db, $user;
	
	$query = $db->sql_query('UPDATE ' . PATREON_USER_BENEFICIOS_TABLE . "
								SET fecha_fin = NOW()
									,moderador_del = '".$user->data['username']."'
								WHERE fecha_fin >= NOW()
									AND user_id = $user_id
									AND beneficio_id = $beneficio_id");
	
	return true;
}