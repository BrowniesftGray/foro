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

	if ($post_id > 0) {
		$sql = "SELECT
					pj.*,
					p.nombre,
					m.nombre as clan,
					CONCAT(a.nombre_es, ' (', a.nombre_jp, ') ') as arquetipo
				FROM ".PERSONAJES_POSTS_TABLE." pj
					INNER JOIN ".PERSONAJES_TABLE." p
						ON p.pj_id = pj.pj_id
					INNER JOIN ".RAMAS_TABLE." m
						ON m.rama_id = p.rama_id_pri
					LEFT JOIN ".ARQUETIPOS_TABLE." a
						ON a.arquetipo_id = p.arquetipo_id
				WHERE pj.pj_id = '$pj_id'
					AND pj.post_id = '$post_id'";

		$query = $db->sql_query($sql);

		if ($db->sql_affectedrows() == 0) {
			$db->sql_freeresult($query);
			$post_id = 0;
		}
	}

	if ($post_id == 0)
	{
		$sql =
			"SELECT p.*,
					m.nombre as clan,
					pf.pf_experiencia as experiencia,
					nv.experiencia as experiencia_old,
					nvup.experiencia as experiencia_sig,
					r.rank_title as rango,
					CONCAT(a.nombre_es, ' (', a.nombre_jp, ') ') as arquetipo
				FROM ".PERSONAJES_TABLE." p
					INNER JOIN ".RAMAS_TABLE." m
						ON m.rama_id = p.rama_id_pri
					INNER JOIN ".PROFILE_FIELDS_DATA_TABLE." pf
						ON pf.user_id = p.user_id
					INNER JOIN ".NIVELES_TABLE." nv
						ON nv.nivel = p.nivel
					INNER JOIN ".NIVELES_TABLE." nvup
						ON nvup.nivel = p.nivel + 1
						OR (p.nivel = (SELECT MAX(nivel) FROM ".NIVELES_TABLE.")
							AND nvup.nivel = p.nivel)
					INNER JOIN ".USERS_TABLE." u
						ON u.user_id = p.user_id
					LEFT JOIN ".RANKS_TABLE." r
						ON r.rank_id = u.user_rank
					LEFT JOIN ".ARQUETIPOS_TABLE." a
						ON a.arquetipo_id = p.arquetipo_id
				WHERE pj_id = '$pj_id'";

		$query = $db->sql_query($sql);
	}

	if ($row = $db->sql_fetchrow($query)) {
		$exp_avance = ($row['experiencia_porc'] ? (int)$row['experiencia_porc'] : -1);

		if ($exp_avance == -1) {
			$exp_req = (int)$row['experiencia_sig'] - (int)$row['experiencia_old'];
			$exp_avance = (int)$row['experiencia'] - (int)$row['experiencia_old'];
			if ($exp_req == 0 || $exp_req <= $exp_avance) {
				$exp_avance = 100;
			} else {
				$exp_avance = floor($exp_avance * 100 / $exp_req);
			}
		}

		$pv_total = calcula_pv($row);
		$pc_total = calcula_pc($row);
		$sta_total = calcula_sta($row);
		$pv_post = (isset($row['pv']) ? $row['pv'] : $pv_total);
		$pc_post = (isset($row['pc']) ? $row['pc'] : $pc_total);
		$sta_post = (isset($row['sta']) ? $row['sta'] : $sta_total);
		$pv_porc = floor($pv_post * 100 / $pv_total);
		$pc_porc = floor($pc_post * 100 / $pc_total);
		$sta_porc = floor($sta_post * 100 / $sta_total);

		$data = array(
			'PJ_NOMBRE'				=> $row['nombre'],
			'PJ_CLAN'				=> $row['clan'],
			'PJ_NIVEL'				=> (int)$row['nivel'],
			'PJ_EXPERIENCIA'		=> (int)$row['experiencia'],
			'PJ_EXPERIENCIA_SIG'	=> (int)$row['experiencia_sig'],
			'PJ_EXPERIENCIA_PORC'	=> ($exp_avance > 100 ? 100 : $exp_avance),
			'PJ_ARQUETIPO_ID'		=> (int)$row['arquetipo_id'],
			'PJ_ARQUETIPO'			=> $row['arquetipo'],
			'PJ_RANGO'				=> $row['rango'],
			'PJ_FUE'				=> (int)$row['fuerza'],
			'PJ_AGI'				=> (int)$row['agilidad'],
			'PJ_VIT'				=> (int)$row['vitalidad'],
			'PJ_CON'				=> (int)$row['concentracion'],
			'PJ_CCK'				=> (int)$row['cck'],
			'PJ_VOL'				=> (int)$row['voluntad'],
			'PJ_ATTR_DISP'			=> get_atributos_disponibles($pj_id),
			'PJ_PV_TOT'				=> $pv_total,
			'PJ_PC_TOT'				=> $pc_total,
			'PJ_STA_TOT'			=> $sta_total,
			'PJ_PV_POST'			=> $pv_post,
			'PJ_PC_POST'			=> $pc_post,
			'PJ_STA_POST'			=> $sta_post,
			'PJ_PV_PORC'			=> ($pv_porc > 100 ? 100 : $pv_porc),
			'PJ_PC_PORC'			=> ($pc_porc > 100 ? 100 : $pc_porc),
			'PJ_STA_PORC'			=> ($sta_porc > 100 ? 100 : $sta_porc),
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
		$arquetipo_select = obtener_arquetipo_select($pj_id, $row['arquetipo_id']);

		$puede_elegir_rama1 = ((int)$row['rama_id1'] == 0);
		$puede_elegir_rama2 = ((int)$row['rama_id2'] == 0);
		$puede_elegir_rama3 = ((int)$row['rama_id3'] == 0 && (int)$row['nivel'] >= 10);
		$puede_elegir_rama4 = ((int)$row['rama_id4'] == 0 && (int)$row['nivel'] >= 15);
		$puede_elegir_rama5 = ((int)$row['rama_id5'] == 0 && (int)$row['nivel'] >= 25);
		$puede_elegir_ramas = ($puede_elegir_rama1 || $puede_elegir_rama2 || $puede_elegir_rama3 || $puede_elegir_rama4 || $puede_elegir_rama5);

		$puede_subir_nivel = $personajePropio && ($attr_disp || $arquetipo_select || $puede_elegir_ramas);

		$template->assign_vars(array(
			'NIVEL' 				=> $row['nivel'],
			'PUEDE_BORRAR'			=> $personajePropio,
			'PUEDE_SUBIR'			=> $puede_subir_nivel,
			'EXPERIENCIA' 			=> $experiencia,
			'PTOS_APRENDIZAJE'		=> $ptos_aprendizaje,
			'PUEDE_MODERAR'			=> $moderador,
			'FICHA_ARQUETIPO' 		=> $arquetipo_select,
			'VISTA_ARQUETIPO' 		=> vista_arquetipo ($row['arquetipo_id']),
			'ID_ARQUETIPO' 			=> $row['arquetipo_id'],
			'FICHA_CAMINO'			=> $str_camino,
			'FICHA_NOMBRE' 			=> stripslashes($row['nombre']),
			'FICHA_ID' 				=> $pj_id,
			'FICHA_EDAD' 			=> $row['edad'],
			'FICHA_CLAN' 			=> get_nombre_rama($row['rama_id_pri']),
			'FICHA_RAMA1' 			=> get_nombre_rama($row['rama_id1']),
			'FICHA_RAMA2' 			=> get_nombre_rama($row['rama_id2']),
			'FICHA_RAMA3' 			=> get_nombre_rama($row['rama_id3']),
			'FICHA_RAMA4' 			=> get_nombre_rama($row['rama_id4']),
			'FICHA_RAMA5' 			=> get_nombre_rama($row['rama_id5']),
			'FICHA_ATRIBUTOS_DISP'	=> $attr_disp,
			'FICHA_ATRIBUTOS_TOT'	=> $attr_tot,
			'FICHA_ATRIBUTOS_MAX'	=> 10 + ((int)$row['nivel'] * 5),
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

		if (!$ver || $puede_elegir_ramas) {
			$exluir_ramas[0] = (int)$row['rama_id_pri'];
			$exluir_ramas[1] = (int)$row['rama_id1'];
			$exluir_ramas[2] = (int)$row['rama_id2'];
			$exluir_ramas[3] = (int)$row['rama_id3'];
			$exluir_ramas[4] = (int)$row['rama_id4'];
			$exluir_ramas[5] = (int)$row['rama_id5'];

			$template->assign_vars(array(
				'PUEDE_ELEGIR_RAMAS'	=> $puede_elegir_ramas,
				'PUEDE_ELEGIR_RAMA1'	=> $puede_elegir_rama1,
				'PUEDE_ELEGIR_RAMA2'	=> $puede_elegir_rama2,
				'PUEDE_ELEGIR_RAMA3'	=> $puede_elegir_rama3,
				'PUEDE_ELEGIR_RAMA4'	=> $puede_elegir_rama4,
				'PUEDE_ELEGIR_RAMA5'	=> $puede_elegir_rama5,
				'RAMAS_PRINCIPALES'		=> get_ramas_select(1, (int)$row['rama_id_pri'], $exluir_ramas),
				'RAMAS_SECUNDARIAS1'	=> get_ramas_select(($ver ? 2 : 0), (int)$row['rama_id1'], $exluir_ramas),
				'RAMAS_SECUNDARIAS2'	=> get_ramas_select(0, (int)$row['rama_id2'], $exluir_ramas),
				'RAMAS_SECUNDARIAS3'	=> get_ramas_select(0, (int)$row['rama_id3'], $exluir_ramas),
				'RAMAS_SECUNDARIAS4'	=> get_ramas_select(0, (int)$row['rama_id4'], $exluir_ramas),
				'RAMAS_SECUNDARIAS5'	=> get_ramas_select(($ver ? 3 : 0), (int)$row['rama_id5'], $exluir_ramas),
			));
		}

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

function get_nombre_rama($rama_id) {
	global $db;
	$nombre = '';

	$query = $db->sql_query("SELECT nombre, aldea
								FROM ".RAMAS_TABLE."
								WHERE rama_id = '$rama_id'");
	if ($row = $db->sql_fetchrow($query)) {
		$nombre = $row['nombre'];
	}
	$db->sql_freeresult($query);

	return $nombre;
}

/* param $principales:
1: rama principal; 2: segunda rama; 3: sexta rama; 0: cualquier otra genérica */
function get_ramas_select($principales, $selected, $exclude){
	global $db;
	$select = '';
	$obligatorias = false;

	if(!isset($exclude)) $exclude = array();
	if ($principales > 1 && count($exclude) == 0) $principales = 0;

	if ($principales >= 2) {
		$not_in = implode(',', $exclude);
		$query = $db->sql_query('SELECT r.rama_id, r.nombre, r.aldea
								FROM '.RAMAS_TABLE.' rp
									INNER JOIN '.RAMAS_TABLE." r
										ON r.rama_id = rp.rama_id_req1
										OR r.rama_id = rp.rama_id_req2
								WHERE rp.rama_id = $exclude[0]
								" . ($principales == 3 && count($exclude) > 0 ? "AND r.rama_id NOT IN($not_in)" : '') . '
								ORDER BY r.nombre ASC');

		$obligatorias = ((int)$db->sql_affectedrows() > 0);
		if(!$obligatorias) {
			$db->sql_freeresult($query);
			$principales = 0;
		}
	}

	if (!$obligatorias) {
		$query = $db->sql_query('SELECT rama_id, nombre, aldea
								FROM '.RAMAS_TABLE."
								WHERE principal = $principales
								ORDER BY primero DESC, nombre ASC");
	}

	if ($principales != 1 && !$obligatorias)
		$select = '<option value="0">-- Ninguna --</option>';

	while($row = $db->sql_fetchrow($query)) {
		$str_selected = ($row['rama_id'] == $selected) ? 'selected' : '';
		if (!in_array($row['rama_id'], $exclude) || $str_selected == 'selected') {
			$select .= "<option $str_selected value='".$row['rama_id']."'>";
			if ($row['aldea'])
				$select .= '(' . $row['aldea'] . ') ';
			$select .= $row['nombre'];
			$select .= "</option>";
		}
	}
	$db->sql_freeresult($query);

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

	$pv = 10 + (int)$datos_pj['fuerza'] + (int)$datos_pj['agilidad'] + (int)$datos_pj['vitalidad'];

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

function registrar_moderacion(array $fields, $user_id = 0){
	global $db, $user;

	$mod = $user->data['username'];
	$fecha = date('Y-m-d' );

	if ($fields['PUNTOS_APRENDIZAJE'] > 0) {
		comprarTecnica($user_id, $fields['PUNTOS_APRENDIZAJE']);
		$fields['RAZON'] = $fields['RAZON']." -".$fields['PUNTOS_APRENDIZAJE']." PA";
	}

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

		$moderacion = array(
			'PJ_ID'	=> $pj_id,
			'RAZON' => 'Compra Habilidad.'
		);
		registrar_moderacion($moderacion);
	}
	else {
		$msg_error = 'Hubo un error buscando tu personaje.';
		return false;
	}

	return true;
}

function comprarTecnica ($user_id, $coste, &$msg_error){

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

		$db->sql_query('UPDATE ' . PROFILE_FIELDS_DATA_TABLE . "
							SET pf_puntos_apren = '$ptos_aprendizaje_restantes'
							WHERE user_id = '$user_id'");
	}
	else {
		$msg_error = 'Hubo un error buscando tu personaje.';
		return false;
	}

}

function registrar_tema($user_id, $enlace, $coste, &$msg_error)
{
	global $db, $user;
	$msg_error = 'Error desconocido. Contactar a la administración.'; // Mensaje por defecto

	$user->get_profile_fields($user_id);
	if (!array_key_exists('pf_experiencia', $user->profile_fields)) {
		$puntos_experiencia = 0;
	}
	else{
		$puntos_experiencia = $user->profile_fields['pf_experiencia'];
	}

	$ptos_experiencia_total = $puntos_experiencia + $coste;

	$pj_id = get_pj_id($user_id);
	if ($pj_id) {

		$db->sql_query('UPDATE ' . PROFILE_FIELDS_DATA_TABLE . "
							SET pf_experiencia = '$ptos_experiencia_total'
							WHERE user_id = '$user_id'");

		$moderacion = array(
			'PJ_ID'	=> $pj_id,
			'RAZON' => $enlace
		);
		registrar_moderacion($moderacion);
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
