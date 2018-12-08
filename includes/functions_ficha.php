<?php

include($phpbb_root_path . 'config.' . $phpEx);

function ficha_exists($user_id)
{
	global $db;

	$query = $db->sql_query('SELECT pj_id FROM personajes WHERE user_id='.$user_id.'');
	if ($row = $db->sql_fetchrow($query)) {
		$db->sql_freeresult($query);
		return true;
	} else {
		return false;
	}
}

function get_pj_id($user_id) 
{
	global $db;
	$query = $db->sql_query("SELECT pj_id FROM personajes WHERE user_id=$user_id");
	if ($row = $db->sql_fetchrow($query)) {
		$pj_id = $row['pj_id'];
	} else {
		$pj_id = false;
	}
	$db->sql_freeresult($query);
	return $pj_id;
}

function get_pj_name($user_id) {
	global $db;
	$query = $db->sql_query("SELECT nombre FROM personajes WHERE user_id=".$user_id);
	if ($row = $db->sql_fetchrow($query)) {
		$pj_name = $row['nombre'];
	} else {
		$pj_name = false;
	}
	$db->sql_freeresult($query);
	return $pj_name;
}

function get_pj_data($pj_id, $post_id = 0) {
	global $db;
	$data = false;
	
	$query = $db->sql_query(
		"SELECT p.*, pf.pf_experiencia, nv.experiencia
			FROM ".PERSONAJES_TABLE." p
				INNER JOIN ".PROFILE_FIELDS_DATA_TABLE." pf
					ON pf.user_id = p.user_id
				INNER JOIN ".NIVELES_TABLE." nv
					ON nv.nivel = p.nivel + 1
					OR (p.nivel = (SELECT MAX(nivel) FROM ".NIVELES_TABLE.")
						AND nv.nivel = p.nivel)
			WHERE pj_id = '$pj_id'");
			
	if ($row = $db->sql_fetchrow($query)) {
		$data = array(
			'PJ_NOMBRE'				=> $row['nombre'],
			'PJ_CLAN'				=> $row['clan'],
			'PJ_NIVEL'				=> (int)$row['nivel'],
			'PJ_EXPERIENCIA'		=> (int)$row['pf_experiencia'],
			'PJ_EXPERIENCIA_SIG'	=> (int)$row['experiencia'],
			'PJ_FUE'				=> (int)$row['fuerza'],
			'PJ_AGI'				=> (int)$row['agilidad'],
			'PJ_VIT'				=> (int)$row['vitalidad'],
			'PJ_CCK'				=> (int)$row['cck'],
			'PJ_CON'				=> (int)$row['concentracion'],
			'PJ_VOL'				=> (int)$row['voluntad'],
			'PJ_ATTR_DISP'			=> get_atributos_disponibles($pj_id),
			'PJ_PV_TOT'				=> calcula_pv($row),
			'PJ_STA_TOT'			=> calcula_sta($row),
			'PJ_PC_TOT'				=> calcula_pc($row),
			'PJ_PV_POST'			=> ($row['pv'] ? $row['pv'] : calcula_pv($row)),
			'PJ_STA_POST'			=> ($row['sta'] ? $row['sta'] : calcula_sta($row)),
			'PJ_PC_POST'			=> ($row['pc'] ? $row['pc'] : calcula_pc($row)),
		);
	}
	$db->sql_freeresult($query);
	
	return $data;
}

function get_ficha($user_id, $return = false, $ver = false)
{
	global $user, $db, $template, $phpbb_root_path, $auth;
	$hab_disp = false;

	$query = $db->sql_query("SELECT * FROM ".PERSONAJES_TABLE." WHERE user_id=$user_id");
	if ($row = $db->sql_fetchrow($query)) {
		$db->sql_freeresult($query);
		$pj_id = $row['pj_id'];
		//$puede_ver = ($auth->acl_get('m_modera_ficha') || $user->data['user_id'] == $pj) ? true : false;
		
		$queryCamino = $db->sql_query("
			SELECT DISTINCT CONCAT(a.nombre_es, ' (', a.nombre_jp, ')') AS arquetipo
				FROM ".PERSONAJES_HISTORICO_TABLE." ph 
					INNER JOIN ".ARQUETIPOS_TABLE." a
						ON a.arquetipo_id = ph.arquetipo_id
				WHERE ph.pj_id = '$pj_id'
			UNION
			SELECT CONCAT(a.nombre_es, ' (', a.nombre_jp, ')') AS arquetipo
				FROM ".PERSONAJES_TABLE." p
					INNER JOIN ".ARQUETIPOS_TABLE." a
						ON a.arquetipo_id = p.arquetipo_id
				WHERE p.pj_id = '$pj_id'");
		while ($row2 = $db->sql_fetchrow($queryCamino))
		{
			if ($str_camino) $str_camino .= ' &raquo; ';
			$str_camino .= $row2['arquetipo'];
		}
		$db->sql_freeresult($queryCamino);

		$queryModeraciones = $db->sql_query("SELECT * FROM ".MODERACIONES_TABLE." WHERE pj_moderado='$pj_id'");

		while ($row3 = $db->sql_fetchrow($queryModeraciones))
		{
			$template->assign_block_vars('moderaciones', array(
					'RAZON_MODERACION' => $row3['razon'],
					'USER_MODERACION' => $row3['moderador'],
					'FECHA_MODERACION' => $row3['fecha'],
			));
		}
		$db->sql_freeresult($queryModeraciones);
		
		$queryHab = $db->sql_query("SELECT h.* 
										FROM ".HABILIDADES_TABLE." h
											INNER JOIN ".PERSONAJE_HABILIDADES_TABLE." ph
												ON ph.habilidad_id = h.habilidad_id
										WHERE ph.pj_id = '$pj_id'");
		while ($row4 = $db->sql_fetchrow($queryHab)) {
			$template->assign_block_vars('habilidades', array(
					'ID'			=> $row4['habilidad_id'],
					'NOMBRE'		=> $row4['nombre'],
					'EFECTO'		=> $row4['efecto'],
					'URL_IMAGEN'	=> $row4['url_imagen'],
			));
			
			if ($row4['requisitos']) {
				$requisitos = explode('|', $row4['requisitos']);
				$hab_requisitos = array();
				for ($i = 0; $i < count($requisitos); $i++) {
					$hab_requisitos[] = array('REQUISITO' => $requisitos[$i]);
				}
				$template->assign_block_vars_array('habilidades.requisitos', $hab_requisitos);	
			}			
		}
		$db->sql_freeresult($queryHab);
		
		$grupo = $user->data['group_id'];
		$moderador = ($grupo == 5 || $grupo == 4);
		$personajePropio = ($user_id == $user->data['user_id']);
		
		if ($personajePropio) $hab_disp = get_habilidades_disponibles($pj_id);
		if ($hab_disp) {
			$user->get_profile_fields($user_id);
			if (!array_key_exists('pf_puntos_apren', $user->profile_fields)) {
				$ptos_aprendizaje = 0;
			}
			else{
				$ptos_aprendizaje = $user->profile_fields['pf_puntos_apren'];
			}
		
			foreach($hab_disp as $hab) {
				$template->assign_block_vars('habilidades_compra', array(
					'ID'			=> $hab['habilidad_id'],
					'NOMBRE'		=> $hab['nombre'],
					'EFECTO'		=> $hab['efecto'],
					'URL_IMAGEN'	=> $hab['url_imagen'],
					'COSTE'			=> $hab['coste'],
					'PUEDE_COMPRAR' => ($hab['coste'] <= $ptos_aprendizaje),
					'U_ACTION'		=> append_sid("/ficha/hab/$user_id"),
				));
				
				$requisitos = $hab['requisitos'];
				$hab_disp_requisitos = array();
				for ($i = 0; $i < count($requisitos); $i++) {
					if (strlen($requisitos[$i]) > 0)
						$hab_disp_requisitos[] = array('REQUISITO' => $requisitos[$i]);
				}
				
				if (count($hab_disp_requisitos) > 0)
					$template->assign_block_vars_array('habilidades_compra.requisitos', $hab_disp_requisitos);	
			}
		}

		$user->get_profile_fields($user_id);
		if (!array_key_exists('pf_experiencia', $user->profile_fields)) {
			$experiencia = 0;
		}
		else{
			$experiencia = $user->profile_fields['pf_experiencia'];
		}

		if ($ver == true) {
			//Guarda el texto de tal forma que al usar generate_text_for_display muestre correctamente los bbcodes
			$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
			$allow_bbcode = $allow_urls = $allow_smilies = true;
			generate_text_for_storage($row['tecnicas'], $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
			$jutsus = generate_text_for_display($row['tecnicas'], $uid, $bitfield, $options);
		}
		else{
			$uid = $bitfield = $options = '';
			$jutsus = $row['tecnicas'];
		}
		
		$attr_disp = get_atributos_disponibles($pj_id);
		$attr_tot = $attr_disp + $row['fuerza'] + $row['agilidad'] + $row['vitalidad'] + $row['cck'] + $row['concentracion'] + $row['voluntad'];

		$template->assign_vars(array(
			'NIVEL' 				=> $row['nivel'],
			'PUEDE_BORRAR'			=> $personajePropio,
			'PUEDE_SUBIR'			=> $personajePropio && $attr_disp,
			'EXPERIENCIA' 			=> $experiencia,
			'PTOS_APRENDIZAJE'		=> $ptos_aprendizaje,
			'PUEDE_MODERAR'			=> $moderador,
			'FICHA_ARQUETIPO' 		=> obtener_arquetipo_select($pj_id, $row['arquetipo_id']),
			'VISTA_ARQUETIPO' 		=> vista_arquetipo ($row['arquetipo_id']),
			'ID_ARQUETIPO' 			=> $row['arquetipo_id'],
			'FICHA_CAMINO'			=> $str_camino,
			'FICHA_NOMBRE' 			=> stripslashes($row['nombre']),
			'FICHA_ID' 				=> $pj_id,
			'FICHA_EDAD' 			=> $row['edad'],
			'FICHA_CLAN' 			=> $row['clan'],
			'FICHA_RAMA1' 			=> stripslashes($row['rama1']),
			'FICHA_RAMA2' 			=> stripslashes($row['rama2']),
			'FICHA_RAMA3' 			=> stripslashes($row['rama3']),
			'FICHA_RAMA4' 			=> stripslashes($row['rama4']),
			'FICHA_RAMA5' 			=> stripslashes($row['rama5']),
			'FICHA_ATRIBUTOS_DISP'	=> $attr_disp,
			'FICHA_ATRIBUTOS_TOT'	=> $attr_tot,
			'FICHA_FUERZA' 			=> $row['fuerza'],
			'FICHA_AGI' 			=> $row['agilidad'],
			'FICHA_VIT' 			=> $row['vitalidad'],
			'FICHA_CCK' 			=> $row['cck'],
			'FICHA_CON' 			=> $row['concentracion'],
			'FICHA_VOL' 			=> $row['voluntad'],
			'FICHA_FISICO'			=> stripslashes($row['fisico']),
			'FICHA_PSICOLOGICO' 	=> stripslashes($row['psicologico']),
			'FICHA_HISTORIA' 		=> stripslashes($row['historia']),
			'FICHA_FISICO_TXT' 		=> nl2br(stripslashes($row['fisico'])),
			'FICHA_PSICOLOGICO_TXT' => nl2br(stripslashes($row['psicologico'])),
			'FICHA_HISTORIA_TXT' 	=> nl2br(stripslashes($row['historia'])),
			'FICHA_JUTSUS'			=> $jutsus,
			'FICHA_PC'				=> calcula_pc($row),
			'FICHA_PV'				=> calcula_pv($row),
			'FICHA_STA'				=> calcula_sta($row),
			'FICHA_URL'				=> append_sid("/ficha/". $user_id),
			'FICHA_MODERACIONES'	=> append_sid("/ficha/mod/" . $user_id),
			'FICHA_BORRAR_2'		=> append_sid("/ficha/delete/" . $user_id),
			'U_ACTION_LVL'			=> append_sid("/ficha/lvlup/" . $user_id),
		));
		
		return true;
	} else {
		if ($return) {
			return false;
		}

		$template->assign_vars(array(
			'FICHA_EXISTS'			=> false,
		));
		return false;
	}
}

function get_arquetipos_disponibles($pj_id) {
	global $dbhost, $dbuser, $dbpasswd, $dbname, $dbport;
	$data = false;
	
	$connection = mysqli_connect($dbhost, $dbuser, $dbpasswd, $dbname);
	$query = mysqli_query($connection,
		"CALL ObtenerArquetiposDisponibles ('$pj_id')") or die("Query fail: " . mysqli_error());
		
	while ($row = mysqli_fetch_array($query)){
		$data[] = array(
			'id'		=> $row['arquetipo_id'],
			'nombre'	=> $row['nombre_es'],
		);
	}
	
	return $data;
}

function get_habilidades_disponibles($pj_id) {
	global $dbhost, $dbuser, $dbpasswd, $dbname, $dbport;
	$data = false;
	
	// Si tiene arquetipos disponibles, no puede aprender habilidades
	if (get_arquetipos_disponibles($pj_id) !== false)
		return false;
	
	$connection = mysqli_connect($dbhost, $dbuser, $dbpasswd, $dbname);
	$query = mysqli_query($connection,
		"CALL ObtenerHabilidadesDisponibles ('$pj_id')") or die("Query fail: " . mysqli_error());
		
	while ($row = mysqli_fetch_array($query)){
		$data[] = array(
			'habilidad_id'	=> $row['habilidad_id'],
			'nombre'		=> $row['nombre'],
			'requisitos'	=> explode('|', $row['requisitos']),
			'efecto'		=> $row['efecto'],
			'coste'			=> $row['coste'],
			'url_imagen'	=> $row['url_imagen'],
		);
	}
	return $data;
}

function get_atributos_disponibles ($pj_id) {
	global $dbhost, $dbuser, $dbpasswd, $dbname, $dbport;
	$cantidad = false;
	
	$connection = mysqli_connect($dbhost, $dbuser, $dbpasswd, $dbname);
	$query = mysqli_query($connection,
		"CALL ObtenerCantidadAtributosDisponibles ('$pj_id')") or die("Query fail: " . mysqli_error());
		
	if ($row = mysqli_fetch_array($query))
		$cantidad = (int)$row['atributos'];
	
	return $cantidad;
}

function obtener_arquetipo_select($pj_id, $arquetipo){
	$options = get_arquetipos_disponibles($pj_id);
	$select = false;
	
	if ($options !== false) {
		$select = '';
		foreach($options as $option) {
			$select .= "<option value='".$option['id']."'>";
			$select .= $option['nombre'];
			$select .= "</option>";
		}
	}
	return $select;
}

function vista_arquetipo ($arquetipo){
	global $db;
	if ($arquetipo != 0) {
		$query = $db->sql_query("SELECT * FROM ".ARQUETIPOS_TABLE." WHERE arquetipo_id=".$arquetipo."");
		$row = $db->sql_fetchrow($query);
		$db->sql_freeresult($query);
		$nombre = $row['nombre_es'];
	} else{
		$nombre = "Sin arquetipo";
	}
	
	return $nombre;
}

function calcula_pc($datos_pj)
{
	global $db;	
	$pc = $bono = 0;
	
	$pc = (int)$datos_pj['cck'] + (int)$datos_pj['concentracion'] + (int)$datos_pj['voluntad'];
	
	if((int)$datos_pj['arquetipo_id'] > 0) {
		$query = $db->sql_query("SELECT * FROM ".ARQUETIPOS_TABLE." WHERE arquetipo_id=".$datos_pj['arquetipo_id']."");
		$row = $db->sql_fetchrow($query);
		$db->sql_freeresult($query);
		
		if((bool)$row['bono_es_porcentaje']) {
			$bono = round((int)$row['bono_pc'] * $pc / 100);
		} else {
			$bono = (int)$row['bono_pc'];
		}
	}
	
	$pc = $pc + $bono;
	
	return $pc;
}

function calcula_pv($datos_pj)
{
	global $db;	
	$pv = $bono = 0;
	
	$pv = (int)$datos_pj['fuerza'] + (int)$datos_pj['agilidad'] + (int)$datos_pj['vitalidad'];
	
	if((int)$datos_pj['arquetipo_id'] > 0) {
		$query = $db->sql_query("SELECT * FROM ".ARQUETIPOS_TABLE." WHERE arquetipo_id=".$datos_pj['arquetipo_id']."");
		$row = $db->sql_fetchrow($query);
		$db->sql_freeresult($query);
		
		if((bool)$row['bono_es_porcentaje']) {
			$bono = round((int)$row['bono_pv'] * $pv / 100);
		} else {
			$bono = (int)$row['bono_pv'];
		}
	}
	
	$pv = $pv + $bono;
	
	return $pv;
}

function calcula_sta($datos_pj)
{
	global $db;	
	$sta = $bono = 0;
	
	$sta = (int)$datos_pj['fuerza'] + (int)$datos_pj['agilidad'] + (int)$datos_pj['vitalidad'] + (int)$datos_pj['voluntad'];
	
	if((int)$datos_pj['arquetipo_id'] > 0) {
		$query = $db->sql_query("SELECT * FROM ".ARQUETIPOS_TABLE." WHERE arquetipo_id=".(int)$datos_pj['arquetipo_id']."");
		$row = $db->sql_fetchrow($query);
		$db->sql_freeresult($query);
		
		if((bool)$row['bono_es_porcentaje']) {
			$bono = round((int)$row['bono_sta'] * $sta / 100);
		} else {
			$bono = (int)$row['bono_sta'];
		}
	}
	
	$sta = $sta + $bono;
	
	return $sta;
}

function registrar_moderacion(array $fields){
	global $db, $user;

	$mod = $user->data['username'];
	$fecha = date('Y-m-d' );
	
	$sql_array = array(
		'moderador'		=> $mod,
		'razon'			=> $fields['RAZON'],
		'pj_moderado'	=> $fields['PJ_ID'],
		'fecha'			=> $fecha,
	);

	$sql = "INSERT INTO ".MODERACIONES_TABLE. $db->sql_build_array('INSERT', $sql_array);
	$db->sql_query($sql);
}

function comprar_habilidad($user_id, $hab_id, $coste, &$msg_error) 
{
	global $db, $user;
	$msg_error = 'Error desconocido. Contactar a la administración.'; // Mensaje por defecto
	
	$user->get_profile_fields($user_id);
	if (!array_key_exists('pf_puntos_apren', $user->profile_fields)) {
		$ptos_aprendizaje = 0;
	}
	else{
		$ptos_aprendizaje = $user->profile_fields['pf_puntos_apren'];
	}
	
	if ($coste > $ptos_aprendizaje) {
		$msg_error = 'No tienes suficientes Puntos de Aprendizaje.';
		return false;
	}
	$ptos_aprendizaje_restantes = $ptos_aprendizaje - $coste;
	
	$pj_id = get_pj_id($user_id);
	if ($pj_id) {
		$db->sql_query('SELECT 1 FROM '.PERSONAJE_HABILIDADES_TABLE." 
							WHERE pj_id = '$pj_id' AND habilidad_id = '$hab_id'");
		if ((int) $db->sql_affectedrows() > 0) { 
			$msg_error = 'Tu personaje ya posee esa habilidad.';
			return false;
		}
		
		$disponible = false;
		$hab_disp = get_habilidades_disponibles($pj_id);
		foreach ($hab_disp as $hab) {
			if ((int) $hab['habilidad_id'] == $hab_id)
				$disponible = true;
		}
		if (!$disponible) {
			$msg_error = 'Esta habilidad no está disponible para tu personaje.';
			return false;
		}		
		
		$sql_array = array(
			'pj_id'			=> $pj_id,
			'habilidad_id'	=> $hab_id,
		);
		$db->sql_query('INSERT INTO '.PERSONAJE_HABILIDADES_TABLE. $db->sql_build_array('INSERT', $sql_array));
		if ((int) $db->sql_affectedrows() < 1) { 
			$msg_error = 'Hubo un error agregando la habilidad.';
			return false;
		}
		
		$db->sql_query('UPDATE ' . PROFILE_FIELDS_DATA_TABLE . " 
							SET pf_puntos_apren = '$ptos_aprendizaje_restantes'
							WHERE user_id = '$user_id'");
	}
	else {
		$msg_error = 'Hubo un error buscando tu personaje.';
		return false;
	}
	
	return true;
}

function borrar_personaje($pj) {
	global $db;

	$db->sql_query("DELETE FROM ".PERSONAJES_TABLE." WHERE user_id = '$pj'");
	$db->sql_query("DELETE FROM tecnicas WHERE pj_id = '$pj'");
	//$db->sql_query("DELETE FROM ".MODERACIONES_TABLE." WHERE pj_moderado = '$pj'");	// Si se borra accidental y se recupera, se mantienen las moderaciones
}
