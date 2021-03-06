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

function get_user_id($pj_id)
{
	global $db;
	if ($pj_id != '') {
		# code...
		$query = $db->sql_query("SELECT user_id FROM personajes WHERE pj_id=$pj_id");
		if ($row = $db->sql_fetchrow($query)) {
			$pj_id = $row['user_id'];
		} else {
			$pj_id = false;
		}
		$db->sql_freeresult($query);
		return $pj_id;
	}else{
		return "";
	}
}

function get_pj_id_from_post($post_id)
{
	global $db;
	$query = $db->sql_query("SELECT pj_id FROM ".PERSONAJES_POSTS_TABLE." WHERE post_id = $post_id");
	if ($row = $db->sql_fetchrow($query)) {
		$pj_id = $row['pj_id'];
	} else {
		$pj_id = false;
	}
	$db->sql_freeresult($query);
	return $pj_id;
}

function get_max_pj_id()
{
	global $db;
	$query = $db->sql_query('SELECT MAX(pj_id) AS pj_id FROM personajes');
	$query2 = $db->sql_query('SELECT MAX(pj_id) AS pj_id FROM personajes_historico');
	if ($row = $db->sql_fetchrow($query)) {
		$pj_id = $row['pj_id'];
	} else {
		$pj_id = 0;
	}

	if ($row = $db->sql_fetchrow($query2)) {
		if ((int)$row['pj_id'] > $pj_id)
			$pj_id = $row['pj_id'];
	}

	$db->sql_freeresult($query);
	$db->sql_freeresult($query2);

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
					p.rama_id_pri,
					p.nombre,
					m.nombre as clan,
					CONCAT(a.nombre_jp, ' (', a.nombre_es, ') ') as arquetipo
				FROM ".PERSONAJES_POSTS_TABLE." pj
					INNER JOIN ".PERSONAJES_HISTORICO_TABLE." p
						ON p.pj_id = pj.pj_id
					INNER JOIN ".RAMAS_TABLE." m
						ON m.rama_id = p.rama_id_pri
					LEFT JOIN ".ARQUETIPOS_TABLE." a
						ON a.arquetipo_id = pj.arquetipo_id
				WHERE pj.pj_id = '$pj_id'
					AND pj.post_id = '$post_id'
				LIMIT 1";

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
		$exp_avance = (isset($row['experiencia_porc']) ? (int)$row['experiencia_porc'] : -1);

		if ($exp_avance == -1) {
			$exp_req = (int)$row['experiencia_sig'] - (int)$row['experiencia_old'];
			$exp_avance = (int)$row['experiencia'] - (int)$row['experiencia_old'];
			if ($exp_req == 0 || $exp_req <= $exp_avance) {
				$exp_avance = 100;
			} else {
				$exp_avance = floor($exp_avance * 100 / $exp_req);
			}
		}
		
		// Si es el primer post del tema, obtener sus arquetipos pasados para calcular bonos
		if ($post_id == 0)
		{
			$queryCamino = $db->sql_query("
				SELECT DISTINCT a.arquetipo_id
					FROM ".PERSONAJES_HISTORICO_TABLE." ph
						INNER JOIN ".ARQUETIPOS_TABLE." a
							ON a.arquetipo_id = ph.arquetipo_id
					WHERE ph.pj_id = '$pj_id'
				UNION
				SELECT	a.arquetipo_id
					FROM ".PERSONAJES_TABLE." p
						INNER JOIN ".ARQUETIPOS_TABLE." a
							ON a.arquetipo_id = p.arquetipo_id
					WHERE p.pj_id = '$pj_id'");

			while ($row2 = $db->sql_fetchrow($queryCamino))
			{				
				if ($str_arquetipos) $str_arquetipos .= ',';
				$str_arquetipos .= $row2['arquetipo_id'];
			}
			$db->sql_freeresult($queryCamino);
		}
		
		if ($str_arquetipos)
			$row['arquetipos'] = $str_arquetipos;

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
			'PJ_NIVEL_INICIAL'		=> (int)$row['nivel_inicial'],
			'PJ_EXPERIENCIA'		=> (int)$row['experiencia'],
			'PJ_EXPERIENCIA_SIG'	=> (int)$row['experiencia_sig'],
			'PJ_EXPERIENCIA_PORC'	=> ($exp_avance > 100 ? 100 : $exp_avance),
			'PJ_ARQUETIPO_ID'		=> (int)$row['arquetipo_id'],
			'PJ_ARQUETIPOS_TOT'		=> $row['arquetipos'],
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
			'PJ_GOLPE'				=> calcula_golpe($row),
			'PJ_BLOQUEO'			=> calcula_bloqueo($row)
		);
	}
	$db->sql_freeresult($query);

	return $data;
}

function get_ficha($user_id, $return = false, $ver = false)
{
	global $user, $db, $template, $phpbb_root_path, $auth;
	$hab_disp = false;

	$query = $db->sql_query("SELECT * FROM ".PERSONAJES_TABLE." WHERE user_id = '$user_id'");
	if ($row = $db->sql_fetchrow($query)) {
		$db->sql_freeresult($query);
		$pj_id = $row['pj_id'];

		// obtener ramas de técnicas
		$ramas_array[] = array('ID' => $row['rama_id_pri'], 'NOMBRE' => get_nombre_rama($row['rama_id_pri']));
		if ($row['rama_id1'])
			$ramas_array[] = array('ID' => $row['rama_id1'], 'NOMBRE' => get_nombre_rama($row['rama_id1']));
		if ($row['rama_id2'])
			$ramas_array[] = array('ID' => $row['rama_id2'], 'NOMBRE' => get_nombre_rama($row['rama_id2']));
		if ($row['rama_id3'])
			$ramas_array[] = array('ID' => $row['rama_id3'], 'NOMBRE' => get_nombre_rama($row['rama_id3']));
		if ($row['rama_id4'])
			$ramas_array[] = array('ID' => $row['rama_id4'], 'NOMBRE' => get_nombre_rama($row['rama_id4']));
		if ($row['rama_id5'])
			$ramas_array[] = array('ID' => $row['rama_id5'], 'NOMBRE' => get_nombre_rama($row['rama_id5']));

		if ((int) $row['tiene_globales'])
			$ramas_array[] = array('ID' => -1, 'NOMBRE' => 'Técnicas Globales');

		// obtener camino ninja
		$queryCamino = $db->sql_query("
			SELECT DISTINCT 
					a.arquetipo_id,
					CONCAT(a.nombre_jp, ' (', a.nombre_es, ')') AS arquetipo
				FROM ".PERSONAJES_HISTORICO_TABLE." ph
					INNER JOIN ".ARQUETIPOS_TABLE." a
						ON a.arquetipo_id = ph.arquetipo_id
				WHERE ph.pj_id = '$pj_id'
			UNION
			SELECT	a.arquetipo_id,
					CONCAT(a.nombre_jp, ' (', a.nombre_es, ')') AS arquetipo
				FROM ".PERSONAJES_TABLE." p
					INNER JOIN ".ARQUETIPOS_TABLE." a
						ON a.arquetipo_id = p.arquetipo_id
				WHERE p.pj_id = '$pj_id'");

		while ($row2 = $db->sql_fetchrow($queryCamino))
		{
			if ($str_camino) $str_camino .= ' &raquo; ';
			$str_camino .= $row2['arquetipo'];
			
			if ($str_arquetipos) $str_arquetipos .= ',';
			$str_arquetipos .= $row2['arquetipo_id'];
		}
		$db->sql_freeresult($queryCamino);
		
		if ($str_arquetipos)
			$row['arquetipos'] = $str_arquetipos;

		// obtener moderaciones
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

		// obtener grupo activo y permisos
		$grupo = $user->data['group_id'];
		$moderador = ($grupo == 5 || $grupo == 4 || $grupo == 18);
		$admin = ($grupo == 5);

		// obtener si está viendo su propio personaje
		$personajePropio = ($user_id == $user->data['user_id']);

		// obtener experiencia y PA
		$user->get_profile_fields($user_id);
		if (!array_key_exists('pf_puntos_apren', $user->profile_fields)) {
			$ptos_aprendizaje = 0;
		}
		else{
			$ptos_aprendizaje = $user->profile_fields['pf_puntos_apren'];
		}

		if (!array_key_exists('pf_experiencia', $user->profile_fields)) {
			$experiencia = 0;
		}
		else{
			$experiencia = $user->profile_fields['pf_experiencia'];
		}

		// obtener habilidades aprendidas
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

		// obtener habilidades disponibles para aprender
		if ($personajePropio) $hab_disp = get_habilidades_disponibles($pj_id);
		if ($hab_disp) {
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

		// obtener técnicas aprendidas y por aprender
		if ($ramas_array) {
			foreach ($ramas_array as $rama) {
				$template->assign_block_vars('ramas', $rama);	// ID, NOMBRE

				// obtener técnicas aprendidas de la rama
				$tec_apr = get_tecnicas_personaje($pj_id, $rama['ID'], false);
				if ($tec_apr) {
					foreach($tec_apr as $tec) {
						$template->assign_block_vars('ramas.tecnicas', array(
							'ID'			=> $tec['tecnica_id'],
							'INVENCION'		=> ($tec['pj_id_invencion'] == $pj_id),
							'CONTENIDO'		=> $tec['contenido'],
							'U_ACTION_RES'		=> append_sid("/ficha/removeTec/$user_id/1"),
							'U_ACTION_DEL'		=> append_sid("/ficha/removeTec/$user_id/0"),
						));
					}
				}

				// obtener técnicas disponibles para aprender de la rama
				if ($personajePropio) {
					$tec_disp = get_tecnicas_personaje($pj_id, $rama['ID'], true);
					if ($tec_disp) {
						foreach($tec_disp as $tec) {
							$template->assign_block_vars('ramas.tecnicas_compra', array(
								'ID'			=> $tec['tecnica_id'],
								'INVENCION'		=> ($tec['pj_id_invencion'] == $pj_id),
								'CONTENIDO'		=> $tec['contenido'],
								'COSTE'			=> $tec['coste'],
								'PUEDE_COMPRAR' => ($tec['coste'] <= $ptos_aprendizaje),
								'U_ACTION'		=> append_sid("/ficha/tec/$user_id"),
							));
						}
					}
				}
			}
		}

		// obtener las técnicas extra
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

		// obtener datos del pacto de invocación
		if ($row['invocacion_id']) {
			$queryInvo = $db->sql_query("SELECT * FROM ".INVOCACIONES_TABLE." WHERE activo = 1 AND invocacion_id = " . $row['invocacion_id']);
			if ($row5 = $db->sql_fetchrow($queryInvo)) {
				$template->assign_vars(array(
					'INVOCACION_PACTO'		=>	$row5['pacto'],
					'INVOCACION_ESPECIES'	=>	$row5['especies'],
				));
			}
			$db->sql_freeresult($queryInvo);

			// obtener invocaciones
			if (!$row['es_invocacion']) {
				$queryInvo = $db->sql_query("SELECT * FROM ".PERSONAJES_TABLE."
												WHERE activo = 1 AND es_invocacion = 1
												AND invocacion_id = " . $row['invocacion_id']);
				while ($row5 = $db->sql_fetchrow($queryInvo)) {
					$template->assign_block_vars('invocaciones', array(
						'ID'			=> $row5['user_id'],
						'NOMBRE'		=> $row5['nombre'],
						'RANGO'			=> $row5['rango'],
					));
				}
				$db->sql_freeresult($queryInvo);
			}
		}

		// obtener atributos disponibles para aumentar
		$attr_disp = get_atributos_disponibles($pj_id);

		// obtener atributos totales
		$attr_tot = $attr_disp + $row['fuerza'] + $row['agilidad'] + $row['vitalidad'] + $row['cck'] + $row['concentracion'] + $row['voluntad'];

		// obtener combo de selección de arquetipo
		$arquetipo_select = obtener_arquetipo_select($pj_id, $row['arquetipo_id']);

		// obtener si tiene ramas para elegir
		$puede_elegir_rama1 = ((int)$row['rama_id1'] == 0);
		$puede_elegir_rama2 = ((int)$row['rama_id2'] == 0);
		$puede_elegir_rama3 = ((int)$row['rama_id3'] == 0 && (int)$row['nivel'] >= 10);
		$puede_elegir_rama4 = ((int)$row['rama_id4'] == 0 && (int)$row['nivel'] >= 15);
		$puede_elegir_rama5 = ((int)$row['rama_id5'] == 0 && (int)$row['nivel'] >= 25);
		$puede_elegir_ramas = ($puede_elegir_rama1 || $puede_elegir_rama2 || $puede_elegir_rama3 || $puede_elegir_rama4 || $puede_elegir_rama5);

		// obtener si tiene nivel regalado por reencarnación o NPC
		$tiene_nivel_regalado = ((int)$row['nivel_inicial'] > (int)$row['nivel']);

		// obtener su puede mejorar el personaje
		$puede_subir_nivel = $personajePropio && ($attr_disp || $arquetipo_select || $puede_elegir_ramas);

		// asignar variables
		$template->assign_vars(array(
			'PJ_ID'					=> $row['pj_id'],
			'NIVEL' 				=> $row['nivel'],
			'NIVEL_INICIAL'			=> $row['nivel_inicial'],
			'PERSONAJE_PROPIO'		=> $personajePropio,
			'PUEDE_SUBIR'			=> $puede_subir_nivel,
			'TIENE_NIVEL_REGALADO'	=> $tiene_nivel_regalado,
			'EXPERIENCIA' 			=> $experiencia,
			'PTOS_APRENDIZAJE'		=> $ptos_aprendizaje,
			'ES_INVOCACION'			=> (int)$row['es_invocacion'],
			'INVOCACION_ID'			=> $row['invocacion_id'],
			'INVOCACION_OPTIONS'	=> obtener_invocaciones_select($row['invocacion_id']),
			'RANGO_OPTIONS'			=> obtener_rangos_invocacion_select($row['rango']),
			'RANGO'					=> $row['rango'],
			'ES_BIJUU'				=> (int)$row['es_bijuu'],
			'TIENE_GLOBALES'		=> (int)$row['tiene_globales'],
			'PUEDE_MODERAR'			=> $moderador,
			'PUEDE_ADMINISTRAR'		=> $admin,
			'FICHA_ARQUETIPO' 		=> $arquetipo_select,
			'VISTA_ARQUETIPO' 		=> vista_arquetipo($row['arquetipo_id']),
			'ID_ARQUETIPO' 			=> $row['arquetipo_id'],
			'FICHA_ALDEAS' 			=> obtener_aldeas_select($row['aldea_id'], ($moderador || $admin)),
			'VISTA_ALDEA' 			=> vista_aldea($row['aldea_id']),
			'ID_ALDEA'				=> $row['aldea_id'],
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
			'FICHA_ESP'				=> (int)$row['cck'] + (int)$row['concentracion'] + (int)$row['voluntad'],
			'FICHA_FIS'				=> (int)$row['fuerza'] + (int)$row['agilidad'] + (int)$row['vitalidad'],
			'FICHA_ACC_ESP'			=> 2+floor(((int)$row['cck'] + (int)$row['concentracion'] + (int)$row['voluntad']) / 40),
			'FICHA_ACC_FIS'			=> 2+floor(((int)$row['fuerza'] + (int)$row['agilidad'] + (int)$row['vitalidad']) / 40),
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
			'FICHA_GOLPE'			=> calcula_golpe($row),
			'FICHA_BLOQUEO'			=> calcula_bloqueo($row),
			'FICHA_URL'				=> append_sid("/ficha/". $user_id),
			'FICHA_MODERACIONES'	=> append_sid("/ficha/mod/" . $user_id),
			'FICHA_ACTIVAR'			=> append_sid("/ficha/enable/" . $user_id),
			'FICHA_INACTIVAR'		=> append_sid("/ficha/disable/" . $user_id),
			'FICHA_BORRAR_2'		=> append_sid("/ficha/delete/" . $user_id),
			'U_ACTION_LVL'			=> append_sid("/ficha/lvlup/" . $user_id),
			'U_ACTION_SELL'			=> append_sid("/ficha/sellItem/" . $user_id),
			'U_ACTION_UBI'			=> append_sid("/ficha/saveItem/" . $user_id),
			'U_ACTION_ITEM_ADD'		=> append_sid("/ficha/addItem/" . $user_id),
			'U_ACTION_ITEM_DEL'		=> append_sid("/ficha/delItem/" . $user_id),
			'FICHA_NEXT_LVL'		=> append_sid("/ficha/nextlvl/" . $user_id),
			'EDAD_CALC'				=> calcular_edad_personaje($pj_id),
			'ACTIVO'				=> (bool) $row['activo'],
		));

		// asignar variables de ramas
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
				'RAMAS_PRINCIPALES'		=> get_ramas_select(1, (int)$row['rama_id_pri'], $exluir_ramas, $moderador),
				'RAMAS_SECUNDARIAS1'	=> get_ramas_select(($ver ? 2 : 0), (int)$row['rama_id1'], $exluir_ramas, $moderador),
				'RAMAS_SECUNDARIAS2'	=> get_ramas_select(0, (int)$row['rama_id2'], $exluir_ramas, $moderador),
				'RAMAS_SECUNDARIAS3'	=> get_ramas_select(0, (int)$row['rama_id3'], $exluir_ramas, $moderador),
				'RAMAS_SECUNDARIAS4'	=> get_ramas_select(0, (int)$row['rama_id4'], $exluir_ramas, $moderador),
				'RAMAS_SECUNDARIAS5'	=> get_ramas_select(0, (int)$row['rama_id5'], $exluir_ramas, $moderador),
				// usar esta línea en vez de la anterior para obligar el segundo elemento en KG de elemento combinado
				//'RAMAS_SECUNDARIAS5'	=> get_ramas_select(($ver ? 3 : 0), (int)$row['rama_id5'], $exluir_ramas, $moderador),
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
	global $db;
	$data = false;

	$query = $db->sql_query(
		"SELECT	a.arquetipo_id,
				a.nombre_jp,
				a.nombre_es,
				a.bono_pv,
				a.bono_sta,
				a.bono_pc,
				a.bono_es_porcentaje
			FROM ".ARQUETIPOS_TABLE." a, ".PERSONAJES_TABLE." p
			WHERE p.pj_id = '$pj_id'
				AND a.nivel <= p.nivel
				AND a.arquetipo_id != p.arquetipo_id
				AND ((a.arquetipo_id_padre1 = p.arquetipo_id
						OR a.arquetipo_id_padre2 = p.arquetipo_id)
					OR ((p.arquetipo_id = 0 OR p.arquetipo_id IS NULL)
						AND a.arquetipo_id_padre1 IS NULL
						AND a.arquetipo_id_padre2 IS NULL)
					OR (p.nivel >= 5 AND p.cambio_arquetipo = -1
						AND a.arquetipo_id_padre1 IS NULL
						AND a.arquetipo_id_padre2 IS NULL))");

	while ($row = $db->sql_fetchrow($query)){
		$data[] = array(
			'id'		=> $row['arquetipo_id'],
			'nombre'	=> $row['nombre_es'],
		);
	}
	$db->sql_freeresult($query);

	return $data;
}

function get_habilidades_disponibles($pj_id) {
	global $db;
	$data = false;

	// Si tiene arquetipos disponibles, no puede aprender habilidades
	if (get_arquetipos_disponibles($pj_id) != false)
		return false;

	$query = $db->sql_query("SELECT arquetipo_id FROM ".PERSONAJES_TABLE." WHERE pj_id = '$pj_id'");
	if ($row = $db->sql_fetchrow($query)) {
		$arquetipo_id = $row['arquetipo_id'];
	}
	$db->sql_freeresult($query);

	if ($arquetipo_id) {
		$query = $db->sql_query(
			"SELECT	h.habilidad_id,
					h.nombre,
					h.requisitos,
					h.efecto,
					h.coste,
					h.url_imagen
				FROM ".HABILIDADES_TABLE." h
					LEFT JOIN ".PERSONAJE_HABILIDADES_TABLE." ph
						ON ph.habilidad_id = h.habilidad_id
						AND ph.pj_id = '$pj_id'
				WHERE ph.pj_id IS NULL
					AND h.visible = 1
					AND (h.arquetipo_id1 = '$arquetipo_id'
						 OR h.arquetipo_id2 = '$arquetipo_id')
				ORDER BY coste");

		while ($row = $db->sql_fetchrow($query)){
			$data[] = array(
				'habilidad_id'	=> $row['habilidad_id'],
				'nombre'		=> $row['nombre'],
				'requisitos'	=> explode('|', $row['requisitos']),
				'efecto'		=> $row['efecto'],
				'coste'			=> $row['coste'],
				'url_imagen'	=> $row['url_imagen'],
			);
		}
		$db->sql_freeresult($query);
	}

	return $data;
}

function get_tecnicas_personaje($pj_id, $rama_id = false, $disponibles = false) {
	global $db;
	$data = false;

	$query = $db->sql_query("SELECT rama_id_pri, rama_id1, rama_id2, rama_id3, rama_id4, rama_id5
								FROM ".PERSONAJES_TABLE."
								WHERE pj_id = '$pj_id'");
	if ($row = $db->sql_fetchrow($query)) {
		$rama_prin = (int) $row['rama_id_pri'];
		$rama_1 = (int) $row['rama_id1'];
		$rama_2 = (int) $row['rama_id2'];
		$rama_3 = (int) $row['rama_id3'];
		$rama_4 = (int) $row['rama_id4'];
		$rama_5 = (int) $row['rama_id5'];
	}
	$db->sql_freeresult($query);

	$sql =
		"SELECT	DISTINCT
				t.tecnica_id,
				t.rama_id,
				r.nombre AS rama_nombre,
				t.pj_id_invencion,
				t.etiqueta,
				t.coste,
				b.bbcode_match
			FROM ".TECNICAS_TABLE." t
				INNER JOIN ".RAMAS_TABLE." r
					ON r.rama_id = t.rama_id
				INNER JOIN ".BBCODES_TABLE." b
					ON b.bbcode_tag = t.etiqueta
				LEFT JOIN ".PERSONAJE_TECNICAS_TABLE." pt
					ON pt.tecnica_id = t.tecnica_id
					AND pt.pj_id = '$pj_id'
			WHERE ";

	if ($rama_id == -1) {
		if (!$disponibles) {
			$sql .= " rango = 'Rango E' ";
		} else {
			// si intenta traer globales para aprender, no trae nada
			$sql .= " 1 = 0 ";
		}
	} else {
		$sql .= ($disponibles ? " pt.pj_id IS NULL " : " pt.pj_id IS NOT NULL ");
		$sql .= ($rama_id ? " AND t.rama_id = $rama_id " :
				" AND (t.rama_id IN($rama_prin, $rama_1, $rama_2, $rama_3, $rama_4, $rama_5)
					OR t.pj_id_invencion = '$pj_id')");
	}

	$sql .= " ORDER BY etiqueta";

	$query = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($query)){
		$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($row['bbcode_match'], $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
		$contenido = generate_text_for_display($row['bbcode_match'], $uid, $bitfield, $options);

		$data[] = array(
			'tecnica_id'		=> $row['tecnica_id'],
			'rama_id'			=> $row['rama_id'],
			'rama_nombre'		=> $row['rama_nombre'],
			'pj_id_invencion'	=> $row['pj_id_invencion'],
			'contenido'			=> $contenido,
			'coste'				=> $row['coste'],
			'bbcode_match'		=> $row['bbcode_match'],
		);
	}
	$db->sql_freeresult($query);

	return $data;
}

function get_atributos_disponibles ($pj_id) {
	global $db;
	$cantidad = false;

	$query = $db->sql_query(
		"SELECT n.atributos
					- (p.fuerza
						+ p.agilidad
						+ p.vitalidad
						+ p.cck
						+ p.concentracion
						+ p.voluntad) AS atributos
			FROM ".PERSONAJES_TABLE." p
				INNER JOIN ".NIVELES_TABLE." n
					ON n.nivel = p.nivel
			WHERE p.pj_id = '$pj_id'");

	if ($row = $db->sql_fetchrow($query)) {
		$cantidad = ((int)$row['atributos'] > 0 ? (int)$row['atributos'] : false);
	}
	$db->sql_freeresult($query);

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

function obtener_invocaciones_select($invocacion_id) {
	global $db;
	$select = '';

	if(!$invocacion_id) $invocacion_id = 0;

	$query = $db->sql_query("SELECT * FROM ".INVOCACIONES_TABLE." WHERE activo = 1 AND invocacion_id <> $invocacion_id");
	while ($row = $db->sql_fetchrow($query)) {
		$select .= "<option value='".$row['invocacion_id']."'>";
		$select .= $row['pacto'];
		$select .= "</option>";
	}
	$db->sql_freeresult($query);
	return $select;
}

function obtener_rangos_invocacion_select($rango) {
	$rangos = array('C', 'B', 'A', 'S');
	$select = '';

	foreach($rangos as $r) {
		$select .= "<option value='".$r."'";
		if ($r == $rango) $select .= " selected";
		$select .= ">" . $r;
		$select .= "</option>";
	}

	return $select;
}

function obtener_aldeas_select($aldea_id, $mod) {
	global $db;
	$select = false;

	$sql = "SELECT a.*,
				(SELECT COUNT(0)
					FROM ".PERSONAJES_TABLE." p
					WHERE p.aldea_id = a.aldea_id
					AND p.activo = 1) AS pjs
			FROM ".ALDEAS_TABLE." a
			WHERE activo = 1 " .
	(!$mod ? "AND visible = 1 " : "") .
			" ORDER BY orden";

	$query = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($query)) {
		$lleno = false;
		$descripcion = $row['nombre'];

		if ((int)$row['cupo'] > 0) {
			$descripcion .= " (" . $row['pjs'] . " / " . $row['cupo'] . ")";
			$lleno = (int)$row['pjs'] >= (int)$row['cupo'];
		}

		if ((int)$row['nivel_inicial'] > 1) {
			$descripcion .= " - Bono activo: Comienza en nivel " . $row['nivel_inicial'];
		}

		if (!$lleno || $mod)
			$select .= "<option " . ($row['aldea_id'] == $aldea_id ? "selected" : "") . " value='" . $row['aldea_id'] . "'>" . $descripcion . "</option>";
	}
	$db->sql_freeresult($query);

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
function get_ramas_select($principales, $selected, $exclude, $moderador = false){
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
								" . ($principales == 3 && count($exclude) > 0 ? " AND r.rama_id NOT IN($not_in) " : '') . '
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
								" . ($moderador ? '' : ' AND visible = 1 ') . "
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
		$query = $db->sql_query("SELECT nombre_es FROM ".ARQUETIPOS_TABLE." WHERE arquetipo_id = $arquetipo");
		$row = $db->sql_fetchrow($query);
		$db->sql_freeresult($query);
		$nombre = $row['nombre_es'];
	} else{
		$nombre = "Sin arquetipo";
	}

	return $nombre;
}

function vista_aldea($aldea_id) {
	global $db;

	if ((int)$aldea_id > 0) {
		$query = $db->sql_query("SELECT nombre FROM ".ALDEAS_TABLE." WHERE aldea_id = $aldea_id");
		$row = $db->sql_fetchrow($query);
		$db->sql_freeresult($query);
	}

	return $row['nombre'];
}

function calcula_pc($datos_pj)
{
	global $db;
	$pc = $bono = 0;

	$pc = floor((int)$datos_pj['cck'] * 1.5) + (int)$datos_pj['concentracion'] + (int)$datos_pj['voluntad'];
	
	if(!isset($datos_pj['arquetipos']))
		$datos_pj['arquetipos'] = $datos_pj['arquetipo_id'];

	if($datos_pj['arquetipos'] != '0') {
		$query = $db->sql_query("SELECT SUM(bono_pc) AS bono_pc FROM ".ARQUETIPOS_TABLE." WHERE arquetipo_id IN(".$datos_pj['arquetipos'].")");
		$bono = (int) $db->sql_fetchfield('bono_pc');
		$db->sql_freeresult($query);
	}

	$pc = $pc + $bono;

	if((int)$datos_pj['es_bijuu'] == 1)
		$pc = $pc * 3;

	if((int)$datos_pj['rama_id_pri'] == 44)	//clan Uzumaki
		$pc = $pc + ((int)$datos_pj['nivel'] * 5);

	return $pc;
}

function calcula_pv($datos_pj)
{
	global $db;
	$pv = $bono = 0;

	$pv = 10 + floor((int)$datos_pj['fuerza'] * 1.5) + floor((int)$datos_pj['agilidad'] * 0.5) + ((int)$datos_pj['vitalidad'] * 3);
	
	if(!isset($datos_pj['arquetipos']))
		$datos_pj['arquetipos'] = $datos_pj['arquetipo_id'];

	if($datos_pj['arquetipos'] != '0') {
		$query = $db->sql_query("SELECT SUM(bono_pv) AS bono_pv FROM ".ARQUETIPOS_TABLE." WHERE arquetipo_id IN(".$datos_pj['arquetipos'].")");
		$bono = (int) $db->sql_fetchfield('bono_pv');
		$db->sql_freeresult($query);
	}

	$pv = $pv + $bono;

	if((int)$datos_pj['es_bijuu'] == 1)
		$pv = $pv * 3;

	return $pv;
}

function calcula_sta($datos_pj)
{
	global $db;
	$sta = $bono = 0;

	$sta = (int)$datos_pj['fuerza'] + floor((int)$datos_pj['agilidad'] * 1.5) + (int)$datos_pj['vitalidad'] + (int)$datos_pj['voluntad'];
	
	if(!isset($datos_pj['arquetipos']))
		$datos_pj['arquetipos'] = $datos_pj['arquetipo_id'];

	if($datos_pj['arquetipos'] != '0') {
		$query = $db->sql_query("SELECT SUM(bono_sta) AS bono_sta FROM ".ARQUETIPOS_TABLE." WHERE arquetipo_id IN(".$datos_pj['arquetipos'].")");
		$bono = (int) $db->sql_fetchfield('bono_sta');
		$db->sql_freeresult($query);
	}

	$sta = $sta + $bono;

	if((int)$datos_pj['es_bijuu'] == 1)
		$sta = $sta * 3;

	return $sta;
}

function calcula_golpe($datos_pj) {
	$fue = (int)$datos_pj['fuerza'];
	return floor($fue * 0.25);
}

function calcula_bloqueo($datos_pj) {
	$vit = (int)$datos_pj['vitalidad'];
	return floor($vit * 0.25);
}

function registrar_moderacion(array $fields, $user_id = 0){
	global $db, $user;

	$mod = $user->data['username'];
	$fecha = date('Y-m-d H:i:s');

	// if ($fields['PUNTOS_APRENDIZAJE'] > 0) {
	// 	comprarTecnica($user_id, $fields['PUNTOS_APRENDIZAJE']);
	// 	$fields['RAZON'] = $fields['RAZON']." -".$fields['PUNTOS_APRENDIZAJE']." PA";
	// }

	if ((int)$fields['PUNTOS_APRENDIZAJE'] != 0 || (int)$fields['ADD_PUNTOS_EXPERIENCIA'] != 0 || (int)$fields['ADD_PUNTOS_APRENDIZAJE'] != 0 || (int)$fields['ADD_RYOS'] != 0 || (int)$fields['ADD_PUNTOS_EVENTO'] != 0) {
		if (registrar_tema($user_id, (int)$fields['ADD_PUNTOS_EXPERIENCIA'], (int)$fields['ADD_PUNTOS_APRENDIZAJE'], (int)$fields['ADD_RYOS'], (int)$fields['PUNTOS_APRENDIZAJE'], (int)$fields['ADD_PUNTOS_EVENTO']) == true) {
			$puntos_apen_negativos = $fields['PUNTOS_APRENDIZAJE'];
			$puntos_apen = $fields['ADD_PUNTOS_APRENDIZAJE'];

			if ($puntos_apen_negativos > $puntos_apen) {
				$puntos_apen = $puntos_apen_negativos - $puntos_apen;
				$ptos_aprendizaje_total = $ptos_aprendizaje - $puntos_apen;
			}
			else{
				if ($puntos_apen_negativos == $puntos_apen) {
						$puntos_apen = 0;
						$ptos_aprendizaje_total = $ptos_aprendizaje;
				}
				else{
					$puntos_apen = $puntos_apen - $puntos_apen_negativos;
					$ptos_aprendizaje_total = $ptos_aprendizaje + $puntos_apen;
				}
			}

			$fields['RAZON'] = $fields['RAZON']." | ".$fields['ADD_PUNTOS_EXPERIENCIA']." EXP | ".$ptos_aprendizaje_total." PA | ".$fields['ADD_RYOS']." RYOS";
			
			if ((int)$fields['ADD_PUNTOS_EVENTO'] != 0) {
				$fields['RAZON'] .= ' | ' . $fields['ADD_PUNTOS_EVENTO'] . ' PE';
			}
		}
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

function comprar_habilidad($user_id, $hab_id, $nombre, $coste, &$msg_error)
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
			'RAZON' => "Compra Habilidad '$nombre' por $coste PA."
		);
		registrar_moderacion($moderacion);
	}
	else {
		$msg_error = 'Hubo un error buscando tu personaje.';
		return false;
	}

	return true;
}

function vender_item($user_id, $pj_id, $item_id, $cantidad_venta, &$msg_error, $vender = true) {
	global $db, $user;
	$msg_error = 'Error desconocido. Contactar a la administración.'; // Mensaje por defecto

	$sql = "SELECT i.nombre,
				i.precio,
				pi.cantidad
			FROM " . ITEMS_TABLE . " i
				INNER JOIN " . PERSONAJE_ITEMS_TABLE . " pi
					ON pi.item_id = i.item_id
			WHERE pi.pj_id = '$pj_id'
				AND i.item_id = '$item_id'
				AND pi.cantidad >= $cantidad_venta";

	$query = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($query)) {
		$item_nombre = $row['nombre'];
		$precio_venta = round((int)$row['precio'] / 2) * $cantidad_venta;
	}
	else {
		$msg_error = "El personaje no posee el item en su inventario.";
		return false;
	}
	$db->sql_freeresult($query);

	$user->get_profile_fields($user_id);
	if (!array_key_exists('pf_ryos', $user->profile_fields)) {
		$pf_ryos = 0;
	}
	else{
		$pf_ryos = $user->profile_fields['pf_ryos'];
	}
	
	$pf_ryos = $pf_ryos + $precio_venta;

	$db->sql_query('UPDATE ' . PERSONAJE_ITEMS_TABLE . "
					SET cantidad = cantidad - $cantidad_venta
					WHERE pj_id = '$pj_id'
						AND item_id = '$item_id'
						AND cantidad >= $cantidad_venta");
	if ((int) $db->sql_affectedrows() < 1) {
		$msg_error = 'Hubo un error vendiendo el item.';
		return false;
	}

	if ($vender)
	{
		$db->sql_query('UPDATE ' . PROFILE_FIELDS_DATA_TABLE . "
						SET pf_ryos = '$pf_ryos'
						WHERE user_id = '$user_id'");
		if ((int) $db->sql_affectedrows() < 1) {
			$msg_error = 'Hubo un error actualizando tus Ryos.';
			return false;
		}
	}

	return true;
}

function quitar_tecnica($user_id, $pj_id, $tec_id, $coste, &$msg_error) {
	global $db, $user;
	$msg_error = 'Error desconocido. Contactar a la administración.'; // Mensaje por defecto

	$db->sql_query('DELETE FROM ' . PERSONAJE_TECNICAS_TABLE . "
					WHERE pj_id = '$pj_id'
						AND tecnica_id = '$tec_id'");
	if ((int) $db->sql_affectedrows() < 1) {
		$msg_error = 'Hubo un error quitando la técnica.';
		return false;
	}

	$db->sql_query('UPDATE ' . PROFILE_FIELDS_DATA_TABLE . "
					SET pf_puntos_apren = pf_puntos_apren + $coste
					WHERE user_id = '$user_id'");
	if ((int) $db->sql_affectedrows() < 1) {
		$msg_error = 'Hubo un error actualizando los PA.';
		return false;
	}

	return true;
}

function actualizar_item($user_id, $pj_id, $item_id, $ubicacion, $orden, &$msg_error) {
	global $db, $user;
	$b_ubicacion_items = false;

	$msg_error = 'Error desconocido. Contactar a la administración.'; // Mensaje por defecto

	$beneficios = get_beneficios($user_id);
	if ($beneficios) {
		foreach ($beneficios as $key => $val) {
			if ($val['nombre_php'] == BENEFICIO_UBICACION_ITEMS) {
				$b_ubicacion_items = true;
			}
		}
	}

	if (!$b_ubicacion_items) {
		$msg_error = 'No tienes habilitada la ubicación de items en el inventario.';
		return false;
	}

	$sql = "SELECT nombre FROM " . ITEMS_TABLE . " WHERE item_id = '$item_id'";
	$query = $db->sql_query($sql);
	if ($row = $db->sql_fetchrow($query)) {
		$item_nombre = $row['nombre'];
	} else {
		$msg_error = "No se encontró el item.";
		return false;
	}
	$db->sql_freeresult($query);

	$sql_array = array(
		'ubicacion'	=> $ubicacion,
		'orden'		=> $orden,
	);
	
	$db->sql_query('UPDATE ' . PERSONAJE_ITEMS_TABLE . ' SET ' .
					$db->sql_build_array('UPDATE', $sql_array) .
					" WHERE pj_id = '$pj_id'
						AND item_id = '$item_id'");
	if ((int) $db->sql_affectedrows() < 1) {
		$msg_error = 'Hubo un error actualizando el item.';
		return false;
	}

	return true;
}

function comprar_tecnica($user_id, $tec_id, $nombre, $coste, &$msg_error)
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

	$pj_id = get_pj_id($user_id);
	if ($pj_id) {
		$db->sql_query('SELECT 1 FROM '.PERSONAJE_TECNICAS_TABLE."
							WHERE pj_id = '$pj_id' AND tecnica_id = '$tec_id'");
		if ((int) $db->sql_affectedrows() > 0) {
			$msg_error = 'Tu personaje ya posee esa técnica.';
			return false;
		}

		$disponible = false;
		$tec_disp = get_tecnicas_personaje($pj_id, false, true);
		foreach ($tec_disp as $tec) {
			if ((int) $tec['tecnica_id'] == $tec_id) {
				$disponible = true;
				break;
			}
		}
		if (!$disponible) {
			$msg_error = 'Esta técnica no está disponible para tu personaje.';
			return false;
		}

		$sql_array = array(
			'pj_id'			=> $pj_id,
			'tecnica_id'	=> $tec_id,
		);
		$db->sql_query('INSERT INTO '.PERSONAJE_TECNICAS_TABLE. $db->sql_build_array('INSERT', $sql_array));
		if ((int) $db->sql_affectedrows() < 1) {
			$msg_error = 'Hubo un error agregando la técnica.';
			return false;
		}

		$db->sql_query('UPDATE ' . PROFILE_FIELDS_DATA_TABLE . "
							SET pf_puntos_apren = pf_puntos_apren - $coste
							WHERE user_id = '$user_id'");

		$moderacion = array(
			'PJ_ID'	=> $pj_id,
			'RAZON' => "Compra Técnica '$nombre' por $coste PA."
		);
		registrar_moderacion($moderacion);
	}
	else {
		$msg_error = 'Hubo un error buscando tu personaje.';
		return false;
	}

	return true;
}


function registrar_tema($user_id, $experiencia, $puntos_apen, $ryos, $puntos_apen_negativos, $puntos_evento = 0)
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

	if (!array_key_exists('pf_puntos_apren', $user->profile_fields)) {
		$ptos_aprendizaje = 0;
	}
	else{
		$ptos_aprendizaje = $user->profile_fields['pf_puntos_apren'];
	}

	if ($puntos_apen_negativos > $puntos_apen) {
		$puntos_apen = $puntos_apen_negativos - $puntos_apen;
		$ptos_aprendizaje_total = $ptos_aprendizaje - $puntos_apen;
	}
	else{
		if ($puntos_apen_negativos == $puntos_apen) {
				$puntos_apen = 0;
				$ptos_aprendizaje_total = $ptos_aprendizaje;
		}
		else{
			$puntos_apen = $puntos_apen - $puntos_apen_negativos;
			$ptos_aprendizaje_total = $ptos_aprendizaje + $puntos_apen;
		}
	}

	if (!array_key_exists('pf_ryos', $user->profile_fields)) {
		$ptos_ryos = 0;
	}
	else{
		$ptos_ryos = $user->profile_fields['pf_ryos'];
	}

	if (!array_key_exists('pf_puntos_evento', $user->profile_fields)) {
		$ptos_evento = 0;
	}
	else{
		$ptos_evento = $user->profile_fields['pf_puntos_evento'];
	}

	if ($ptos_aprendizaje_total < 0) {
		$msg_error = 'No tienes suficientes Puntos de Aprendizaje para aprender la técnica.';
		trigger_error($msg_error."<br /><a href='/ficha/$user_id'>Volver a la ficha</a>.");
		return false;
	}


	$ptos_experiencia_total = $puntos_experiencia + $experiencia;
	$ptos_ryos = $ptos_ryos + $ryos;
	$ptos_evento = $ptos_evento + $puntos_evento;


	$pj_id = get_pj_id($user_id);
	if ($pj_id) {

		$db->sql_query('UPDATE ' . PROFILE_FIELDS_DATA_TABLE . "
							SET pf_experiencia = '$ptos_experiencia_total',
									pf_puntos_apren = '$ptos_aprendizaje_total',
									pf_ryos = '$ptos_ryos',
									pf_puntos_evento = '$ptos_evento'
							WHERE user_id = '$user_id'");

		// $enlace = $enlace." Experiencia: +".$experiencia." | Puntos de aprendizaje: +".$puntos_apen." | Ryos: +".$ryos;
		// $moderacion = array(
		// 	'PJ_ID'	=> $pj_id,
		// 	'RAZON' => $enlace
		// );
		// registrar_moderacion($moderacion);
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
	//$db->sql_query("DELETE FROM ".MODERACIONES_TABLE." WHERE pj_moderado = '$pj'");	// Si se borra accidental y se recupera, se mantienen las moderaciones
}

function calcular_edad_personaje($pj_id) {
	global $db;
	$nueva_edad = false;
	$i = 0;

	$fecha_hoy = strtotime(date('m/d/Y h:i:s a', time()));

	$query = $db->sql_query("SELECT fecha_historico " .
							" FROM " . PERSONAJES_HISTORICO_TABLE .
							" WHERE pj_id = $pj_id " .
							" ORDER BY fecha_historico ASC " .
							" LIMIT 1");

	if ($row = $db->sql_fetchrow($query)) {
		$fecha_nac = strtotime($row['fecha_historico']);
	} else {
		$fecha_nac = $fecha_hoy;
	}
	$db->sql_freeresult($query);

	$query2 = $db->sql_query("SELECT edad_inicial " .
							" FROM " . PERSONAJES_TABLE .
							" WHERE pj_id = $pj_id");
	if ($row2 = $db->sql_fetchrow($query2)) {
		$edad = (int)$row2['edad_inicial'];
	}
	$db->sql_freeresult($query2);

	$nueva_edad = $edad;

	while (($fecha_nac = strtotime("+1 MONTH", $fecha_nac)) <= $fecha_hoy) {
		$i++;
	}

	$nueva_edad = $nueva_edad + floor($i / 4);

	return $nueva_edad;
}

function premio_exists($pj_id)
{
	global $db;

	$query = $db->sql_query("SELECT * FROM ".PREMIO_DIARIO_TABLE." WHERE pj_id = $pj_id");
	if ($row = $db->sql_fetchrow($query)) {
		$db->sql_freeresult($query);
		return $row;
	}
	else {
		return false;
	}
}

function registrar_premio_diario($user_id, $post_id, &$mensaje = false)
{
	global $db, $user;
	$fecha_post = new DateTime('today');
	$fecha_ahora = date('Y-m-d H:i:s');
	
	$premio_pa = 0;
	$premio_exp = 0;
	$premio_pe = 0;

	//El pj_id para cosas futuras
	$pj_id = get_pj_id($user_id);
	if (!$pj_id) return false;

	//Montemos el array para hacer el INSERT o UPDATE
	$sql_array_prem = array(
		'pj_id'				=> $pj_id,
		'cadena'			=> 1,
		'posts_diarios'		=> 1,
		'premios_totales'	=> 1,
		'fecha'		        => $fecha_post->format('Y-m-d'),
	);

	//El array para moderaciones, desde la función de registrar moderación se registrará el tema y se otorgará la experiencia.
	$moderacion = array(
		'PJ_ID'						=> $pj_id,
		'RAZON'						=> "",
		'PUNTOS_APRENDIZAJE'		=> "",
		'ADD_PUNTOS_EXPERIENCIA'	=> "",
		'ADD_PUNTOS_APRENDIZAJE'	=> "",
		'ADD_RYOS'					=> 0,
	);

    //Obtenemos un select con los resultados del usuario.
    $premio = premio_exists($pj_id);
    if ($premio) {
		$cadena = (int)$premio['cadena'];
		$posts_diarios = (int)$premio['posts_diarios'];
		$premios_totales = (int)$premio['premios_totales'];
		$fecha_anterior = date_create($premio['fecha']);
	}
	else {
		$cadena = 0;
		$posts_diarios = 0;
		$premios_totales = 0;
		$fecha_anterior = new DateTime('yesterday');
	}

    //Comparamos la fecha del último premio con la del post actual
    if ($fecha_post != $fecha_anterior) {
		
		// Reiniciamos los posts diarios
		$posts_diarios = 1;

		// Primero vemos cual es la diferencia entre la fecha anterior y la actual.
		$intervalo = date_diff($fecha_anterior, $fecha_post);

		// Si es un día consecutivo, debe aumentarse la cadena
		if ($intervalo->d == 1) {
			$cadena += 1;
			
			// Si se superó la semana, se reinicia
			if ($cadena > PREMIO_CADENA_COMPLETA_CANTIDAD)
				$cadena = 1;
		}
		else {
			// Si no fue día consecutivo, se reinicia
			$cadena = 1;
		}
		
		$moderacion['RAZON'] = "Premio diario, $cadena día(s). [p$post_id]";
		$mensaje = "¡Premio diario obtenido!";
		
		// dependiendo de la cadena, se suma el premio diario
		if ($cadena == PREMIO_CADENA_COMPLETA_CANTIDAD) {
			$cadena_completa = true;
			$premio_exp += PREMIO_CADENA_COMPLETA_EXP;
			$premio_pa += PREMIO_CADENA_COMPLETA_PA;
			$mensaje = "¡Premio extra por semana completa de rol! ¡Felicitaciones!";
		}
		else {
			$premio_exp += PREMIO_CADENA_BASE_EXP;
			$premio_pe += PREMIO_CADENA_BASE_PE;
		}
		
		// Sumamos un premio diario al total acumulado
		$premios_totales += 1;
		
		// cada 50 premios diarios totales, se da uno especial
		if (fmod($premios_totales, PREMIO_TOTALES_INTERVALO) == 0) {
			$premio_exp += PREMIO_TOTALES_EXP;
			$premio_pa += PREMIO_TOTALES_PA;
			
			$moderacion['RAZON'] .= "Más $premios_totales premios totales.";
			$mensaje .= "<br/>¡Bono extra por $premios_totales premios totales!";
		}
    }
    else {
		// Es un post en la misma fecha que el anterior
		$posts_diarios += 1;
		
		if ($posts_diarios <= PREMIO_POST_BASE_MAX) {
			$premio_exp += PREMIO_POST_BASE_EXP;
			$premio_pe += PREMIO_POST_BASE_PE;
		}
		
		if ($premio_exp > 0) {
			$moderacion['RAZON'] = "Premio por post base. [p$post_id]";
		}
	}
	
	// Buscamos si ya tenía registro de premio diario
	if ($premio) {
		$sql_array_prem['cadena'] = $cadena;
		$sql_array_prem['posts_diarios'] = $posts_diarios;
		$sql_array_prem['premios_totales'] = $premios_totales;
		
		$sql = "UPDATE " . PREMIO_DIARIO_TABLE . " SET " .
				$db->sql_build_array('UPDATE', $sql_array_prem) .
					" WHERE pj_id = '$pj_id'";
		$db->sql_query($sql);
	}
	else {
		//No existen datos en las tablas por tanto, es el primer registro.
		$moderacion['RAZON'] = "Premio diario, primer post de rol ♥. [p$post_id]";
		$sql = "INSERT INTO " . PREMIO_DIARIO_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array_prem);
		$db->sql_query($sql);
	}
	
	// Si hay premios, se registra una moderación
	if ($premio_exp > 0 || $premio_pa > 0 || $premio_pe) {
		
		$moderacion['ADD_PUNTOS_EXPERIENCIA'] = $premio_exp;
		$moderacion['ADD_PUNTOS_APRENDIZAJE'] = $premio_pa;
		$moderacion['ADD_PUNTOS_EVENTO'] = $premio_pe;
		
		registrar_moderacion($moderacion, $user_id);
	}
	
	// Cofre por cadena de premio diario
	$rango_cofre_cadena = COFRES_PREMIO_CADENA;
	$rango_cofre_acumulado = COFRES_PREMIO_ACUMULADO_RANGO;
	$items_extra = 0;
	
	// Si aplica algún cofre...
	if (($cadena_completa && $rango_cofre_cadena) || (fmod($premios_totales, COFRES_PREMIO_ACUMULADO_DIAS) == 0 && $rango_cofre_acumulado)) {
		// Obtener beneficios del usuario
		$beneficios = get_beneficios($user_id);
		
		if ($beneficios) {
			// Se recorren los beneficios buscando items extra para cofres
			foreach ($beneficios as $key => $val) {
				if ($val['nombre_php'] == sprintf(BENEFICIO_COFRE_ITEMS_EXTRA, 1)) {
					$item_extra_1 = true;
				}
				
				if ($val['nombre_php'] == sprintf(BENEFICIO_COFRE_ITEMS_EXTRA, 2)) {
					$item_extra_2 = true;
				}
				
				if ($val['nombre_php'] == sprintf(BENEFICIO_COFRE_ITEMS_EXTRA, 3)) {
					$item_extra_3 = true;
				}
			}
		}
		
		// Se define la cantidad de items extra
		if ($item_extra_1) $items_extra = 1;
		if ($item_extra_2) $items_extra = 2;
		if ($item_extra_3) $items_extra = 3;
	}
	
	// Si completó cadena, se da el cofre correspondiente
	if ($posts_diarios == 1 && $cadena_completa && $rango_cofre_cadena) {
		$sql_array = array(
			'rango'				=> $rango_cofre_cadena,
			'pj_id'				=> $pj_id,
			'items_extra'		=> $items_extra,
			'estado'			=> 'Recibido',
			'fecha_recibido'	=> $fecha_ahora,
		);
		
		$sql = "INSERT INTO " . COFRES_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array);
		$db->sql_query($sql);
		
		$mensaje .= "<br/>¡Cofre Rango $rango_cofre_cadena" . ($items_extra > 0 ? " +$items_extra" : "") . " conseguido!";
	}
	
	// Si alcanzó la cantidad de premios totales configurada, se da el cofre correspondiente
	if ($posts_diarios == 1 && fmod($premios_totales, COFRES_PREMIO_ACUMULADO_DIAS) == 0 && $rango_cofre_acumulado) {
		$sql_array = array(
			'rango'				=> $rango_cofre_acumulado,
			'pj_id'				=> $pj_id,
			'items_extra'		=> $items_extra,
			'estado'			=> 'Recibido',
			'fecha_recibido'	=> $fecha_ahora,
		);
		
		$sql = "INSERT INTO " . COFRES_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array);
		$db->sql_query($sql);
		
		$mensaje .= "<br/>¡Cofre Rango $rango_cofre_acumulado" . ($items_extra > 0 ? " +$items_extra" : "") . " conseguido!";
	}
}

function get_premio_diario_status($pj_id) {
	$fecha_hoy = new DateTime('today');
	$fecha_ahora = new DateTime();
	$fecha_ayer = new DateTime('yesterday');
	$fecha_manana = new DateTime('tomorrow');
	
	// calcular el tiempo faltante para el reinicio
	$tiempo_faltante = date_diff($fecha_ahora, $fecha_manana);
	$tiempo_faltante_str = str_pad($tiempo_faltante->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($tiempo_faltante->i, 2, '0', STR_PAD_LEFT);
	
	$cadena_ok = $posts_diarios_ok = false;
	
	// obtener los datos de premio diario del personaje
	$premio = premio_exists($pj_id);
	
	// si nunca hizo un post de rol, llenar todo en 0 con fecha de ayer
	if (!$premio) {
		$premio = array(
			'pj_id'				=> $pj_id,
			'cadena'			=> 0,
			'posts_diarios'		=> 0,
			'premios_totales'	=> 0,
			'fecha'				=> $fecha_ayer,
		);
	}
	else {
		$fecha_ultimo_premio = date_create(date($premio['fecha']));
		
		// si su último post no es de hoy, no tiene posts diarios
		if ($fecha_ultimo_premio != $fecha_hoy) {
			$premio['posts_diarios'] = 0;
			
			// si lleva más de un día sin postear, no tiene cadena
			if ($fecha_ultimo_premio < $fecha_ayer) {
				$premio['cadena'] = 0;
			}
		}
		else {
			// si su último post es de hoy, ya cumplió la cadena del día
			$cadena_ok = true;
			
			// si llegó a los 10 posts de hoy, cumplió los posts diarios
			if ($premio['posts_diarios'] >= PREMIO_POST_BASE_MAX) {
				$posts_diarios_ok = true;
			}
		}
	}
	
	// generar texto de ayuda
	$premio_help_str = "Tan solo postear en un tema de rol otorga una recompensa instantánea. El primer post de cada día otorga ".PREMIO_CADENA_BASE_EXP." Exp, y al acumular ".PREMIO_CADENA_COMPLETA_CANTIDAD." días consecutivos, ¡ganas ".PREMIO_CADENA_COMPLETA_EXP." Exp y ".PREMIO_CADENA_COMPLETA_PA." PA! Además, todos los posts después del primero, hasta un máximo de ".PREMIO_POST_BASE_MAX." por día, otorgan ".PREMIO_POST_BASE_EXP." Exp extra. Y por si fuera poco, cada vez que acumulas ".PREMIO_TOTALES_INTERVALO." premios diarios, ganas ".PREMIO_TOTALES_EXP." Exp y ".PREMIO_TOTALES_PA." PA. ¡No esperes más y ve a rolear!";
	
	$result = array(
		'PREMIO_CADENA'				=> $premio['cadena'],
		'PREMIO_POSTS_DIARIOS'		=> $premio['posts_diarios'],
		'PREMIO_PREMIOS_TOTALES'	=> $premio['premios_totales'],
		'PREMIO_CADENA_OK'			=> $cadena_ok,
		'PREMIO_POSTS_DIARIOS_OK'	=> $posts_diarios_ok,
		'PREMIO_CADENA_MAX'			=> PREMIO_CADENA_COMPLETA_CANTIDAD,
		'PREMIO_POSTS_DIARIOS_MAX'	=> PREMIO_POST_BASE_MAX,
		'PREMIO_TIEMPO_FALTANTE'	=> $tiempo_faltante_str,
		'PREMIO_HELP'				=> $premio_help_str,
	);
	
	return $result;
}

function get_cofres_pendientes($pj_id) {
	global $db;
	
	$query = $db->sql_query("SELECT COUNT(0) AS qty FROM " . COFRES_TABLE . " WHERE pj_id = '$pj_id' AND fecha_abierto IS NULL");
	$cofres_pendientes = $db->sql_fetchfield('qty');
	$db->sql_freeresult($query);
	
	return $cofres_pendientes;
}