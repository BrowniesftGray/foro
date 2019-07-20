<?php

include($phpbb_root_path . 'config.' . $phpEx);

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
					WHERE ub.user_id = '. $user_id . 
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
	
	return true;
}

function renovar_tier ($user_id) {
	$user_tier = get_user_tier($user_id);
	return asignar_tier($user_id, $user_tier['tier_id']);
}

function asignar_tier ($user_id, $tier_id) {
	if ($tier_id <= 0) return false;
	
	$beneficios = get_beneficios(false, $tier_id);
	
	return true;
}

function asignar_beneficio ($user_id, $beneficio_id) {
	return false;
}

function eliminar_beneficio ($user_id, $beneficio_id) {
	return false;
}