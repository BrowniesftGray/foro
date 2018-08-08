<?php

$rangos = array('Estudiante', 'Genin', 'Chunin', 'Jounin', 'Especial', 'ANBU', 'Kage', 'Lider', 'T. Jounin', 'Ninja libre', 'Retirado');

function get_rango($rankid)
{
	global $rangos;
	return $rangos[$rankid];
}

function genera_rangos($selected = 0)
{
	global $rangos;

	$output = '<select name="rango">';
	foreach ($rangos as $i => $r)
	{
		$output .= '<option value="'.$i.'"'.(($selected == $i) ? 'selected="selected"' : '').'>'.$r.'</option>';
	}
	$output .= '</select>';
	return $output;
}

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

function get_ficha($user_id)
{
	global $user, $db, $template, $phpbb_root_path, $auth;

	$user_id = $user_id;
	$query = $db->sql_query("SELECT * FROM personajes WHERE user_id=".$user_id."");
	if ($row = $db->sql_fetchrow($query)) {
		$db->sql_freeresult($query);
		$pj_id = $row['pj_id'];
			//$puede_ver = ($auth->acl_get('m_modera_ficha') || $user->data['user_id'] == $pj) ? true : false;

			$queryTec = $db->sql_query("SELECT * FROM tecnicas WHERE pj_id=".$pj_id."");
			$row2 = $db->sql_fetchrow($queryTec);
			$db->sql_freeresult($queryTec);

			$queryModeraciones = $db->sql_query("SELECT * FROM moderaciones WHERE pj_moderado=".$pj_id."");

			while ($row3 = $db->sql_fetchrow($queryModeraciones))
			{
			    $template->assign_block_vars('loopname', array(
						'RAZON_MODERACION' => $row3['razon'],
						'USER_MODERACION' => $row3['moderador'],
						'FECHA_MODERACION' => $row3['fecha'],
			    ));
			}
			$grupo = $user->data['group_id'];

			if ($grupo == 5 || $grupo == 4){
					$moderador = true;
				}
				else{
					$moderador = false;
				}

			$user->get_profile_fields($user_id);
			if (!array_key_exists('pf_experiencia', $user->profile_fields)) {
				$experiencia = 0;
			}
			else{
				$experiencia = $user->profile_fields['pf_experiencia'];
			}
			$subida = comprobarNivel($experiencia, $row['nivel']);


			$template->assign_vars(array(
				//'FICHA_COMPLETA'		=> $puede_ver,
				'NIVEL' => $row['nivel'],
				'EXPERIENCIA' => $experiencia,
				'PUEDE_SUBIR' => $subida[1],
				'EXPERIENCIA_F' => $subida[2],
				'PUEDE_MODERAR'	=> $moderador,
				'FICHA_RANGO' => $row['rango'],
				'FICHA_NOMBRE' => stripslashes($row['nombre']),
				'FICHA_ID' => $pj_id,
				'FICHA_EDAD' => $row['edad'],
				'FICHA_ALDEA' => stripslashes($row['aldea']),
				'FICHA_OJOS' => $row['ojos'],
				'FICHA_PELOS' => $row['pelo'],
				'FICHA_ALTURA' => $row['altura'],
				'FICHA_PESO' => $row['peso'],
				'FICHA_COMPLEXION' => $row['complexion'],
				'FICHA_CLAN' => $row['clan'],
				'TECNICAS_CLAN' => $row2['clan'],
				'FICHA_ELEMENTO1' => stripslashes($row['elemento1']),
				'TECNICAS_ELEMENTO1' => $row2['elemento1'],
				'FICHA_ELEMENTO2' => stripslashes($row['elemento2']),
				'TECNICAS_ELEMENTO2' => $row2['elemento2'],
				'FICHA_ESPECIALIDAD1' => stripslashes($row['especialidad1']),
				'TECNICAS_ESPECIALIDAD1' => $row2['especialidad1'],
				'FICHA_ESPECIALIDAD2' => stripslashes($row['especialidad2']),
				'TECNICAS_ESPECIALIDAD2' => $row2['especialidad2'],
				//'GRUPO' => $user->data['group_id'],
				'FICHA_INVOCACION' => stripslashes($row['invocacion']),
				'TECNICAS_INVOCACION' => $row2['invocacion'],
				'FICHA_FUERZA' => $row['fuerza'],
				'FICHA_AGI' => $row['agilidad'],
				'FICHA_RES' => $row['resistencia'],
				'FICHA_ESP' => $row['espiritu'],
				'FICHA_CON' => $row['concentracion'],
				'FICHA_VOL' => $row['voluntad'],
				'FICHA_FISICO' => nl2br(stripslashes($row['fisico'])),
				'FICHA_PSICOLOGICO' => nl2br(stripslashes($row['psicologico'])),
				'FICHA_HISTORIA' => nl2br(stripslashes($row['historia'])),
				'FICHA_PC'				=> calcula_pc($row['rango'], $row['concentracion'], $row['espiritu'], $row['voluntad']),
				'FICHA_URL'				=> append_sid("{$phpbb_root_path}ficha.php", 'mode=ver&pj=' . $user_id),
				'FICHA_MODERACIONES'	=> append_sid("{$phpbb_root_path}ficha.php", 'mode=moderar&pj=' . $user_id),
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

function calcula_pc($rango, $cck, $intel, $vol)
{
	switch ($rango) {
		case 'Genin':
			return 50 + $cck + $intel + $vol;
		break;

		case 'Chunin':
			return 100 + $cck + $intel + $vol;
		break;

		case 'Jonin':
			return 150 + $cck + $intel + $vol;
		break;

		case 'Anbu':
			return 200 + $cck + $intel + $vol;
		break;

		case 'Kage':
			return 250 + $cck + $intel + $vol;
		break;

	}
}

function guardar_ficha(array $fields)
{
	global $db, $user;

	$fields['HISTORIA'] = addslashes($fields['HISTORIA']);
	$fields['FISICO'] = addslashes($fields['FISICO']);
	$fields['CARACTER'] = addslashes($fields['CARACTER']);
	$idUsuario = $user->data['user_id'];
//		$sql = 'INSERT INTO ' . FICHAS_TABLE . " (user_id, nivel, rango, nombre, clan, kekkei_genkai, elementos, fisico, caracter, historia, fuerza, destreza, constitucion, cck, inteligencia, agilidad, velocidad, presencia, voluntad, bbcode_uid, bbcode_bitfield, bbcode_options, tecnicas) VALUES ('{$user->data['user_id']}', '1', '0', '{$fields['NOMBRE']}', '{$fields['CLAN']}', '{$fields['KEKKEI']}', '{$fields['ELEMENTOS']}', '{$fields['FISICO']}', '{$fields['CARACTER']}', '{$fields['HISTORIA']}', '{$fields['FUERZA']}', '{$fields['DESTREZA']}', '{$fields['CONSTITUCION']}', '{$fields['CCK']}', '{$fields['INTELIGENCIA']}', '{$fields['AGILIDAD']}', '{$fields['VELOCIDAD']}', '0', '{$fields['VOLUNTAD']}', '', '', '0', '')";
$sql = "INSERT INTO personajes (user_id, nivel, nombre, edad, rango, aldea, ojos, pelo, altura, peso, complexion, clan, elemento1, especialidad1, elemento2, especialidad2, invocacion, fuerza, resistencia, agilidad, espiritu, concentracion, voluntad, fisico, psicologico, historia)";
$sql .= "values (	$idUsuario, '1', '{$fields['NOMBRE']}', '{$fields['EDAD']}', '{$fields['RANGO']}', '{$fields['ALDEA']}', '{$fields['OJOS']}', '{$fields['PELO']}', '{$fields['ALTURA']}', '{$fields['PESO']}',";
$sql .="'{$fields['COMPLEXION']}', '{$fields['CLAN']}', '{$fields['ELEMENTO']}', '{$fields['ESPECIALIDAD']}', '{$fields['ELEMENTO2']}', '{$fields['ESPECIALIDAD2']}', '{$fields['INVOCACION']}', '{$fields['FUERZA']}', '{$fields['RESISTENCIA']}', '{$fields['AGILIDAD']}', '{$fields['ESPIRITU']}', '{$fields['CONCENTRACION']}', '{$fields['VOLUNTAD']}', '{$fields['FISICO']}', '{$fields['CARACTER']}', '{$fields['HISTORIA']}')";
$db->sql_query($sql);

}

function guardarTecnicasBase($user_id){

	global $db, $user;

	$query = $db->sql_query('SELECT pj_id FROM personajes WHERE user_id='.$user_id.'');
	$row = $db->sql_fetchrow($query);
	$db->sql_freeresult($query);

	$pj_id = $row['pj_id'];

	$sql = "INSERT INTO tecnicas (pj_id, clan, elemento1, elemento2, especialidad1, especialidad2, invocacion)";
	$sql .= "values (".$pj_id.", 'Sin técnicas', 'Sin técnicas', 'Sin técnicas', 'Sin técnicas', 'Sin técnicas', 'Sin invocación')";
	$db->sql_query($sql);
}

function actualizar_Ficha(array $fields){

	global $db, $user;

	$fields['HISTORIA'] = addslashes($fields['HISTORIA']);
	$fields['FISICO'] = addslashes($fields['FISICO']);
	$fields['CARACTER'] = addslashes($fields['CARACTER']);

	$sql = "UPDATE personajes SET ";
	$sql .= "nombre = '{$fields['NOMBRE']}', edad = '{$fields['EDAD']}', rango = '{$fields['RANGO']}', aldea = '{$fields['ALDEA']}', ojos = '{$fields['OJOS']}', pelo = '{$fields['PELO']}', altura = '{$fields['ALTURA']}', peso = '{$fields['PESO']}', complexion = '{$fields['COMPLEXION']}',";
	$sql .= "clan = '{$fields['CLAN']}', elemento1 = '{$fields['ELEMENTO']}', especialidad1 = '{$fields['ESPECIALIDAD']}', elemento2 = '{$fields['ELEMENTO2']}', especialidad2 = '{$fields['ESPECIALIDAD2']}', invocacion = '{$fields['INVOCACION']}',";
	$sql .= "fuerza = '{$fields['FUERZA']}', resistencia = '{$fields['RESISTENCIA']}', agilidad = '{$fields['AGILIDAD']}', espiritu = '{$fields['ESPIRITU']}', concentracion = '{$fields['CONCENTRACION']}', voluntad = '{$fields['VOLUNTAD']}',";
	$sql .= "fisico = '{$fields['FISICO']}', psicologico = '{$fields['CARACTER']}', historia = '{$fields['HISTORIA']}'";
	$sql .= "WHERE pj_id = '{$fields['PJ_ID']}'";

	$db->sql_query($sql);
}

function actualizar_Tecnicas(array $fields){

	global $db, $user;

	$fields['TEC_CLAN'] = addslashes($fields['TEC_CLAN']);
	$fields['TEC_ELEMENTO'] = addslashes($fields['TEC_ELEMENTO']);
	$fields['TEC_ESPECIALIDAD'] = addslashes($fields['TEC_ESPECIALIDAD']);
	$fields['TEC_ELEMENTO2'] = addslashes($fields['TEC_ELEMENTO2']);
	$fields['TEC_ESPECIALIDAD2'] = addslashes($fields['TEC_ESPECIALIDAD2']);
	$fields['TEC_INVOCACION'] = addslashes($fields['TEC_INVOCACION']);

	$sql = "UPDATE tecnicas SET ";
	$sql .= "clan = '{$fields['TEC_CLAN']}', elemento1 = '{$fields['TEC_ELEMENTO']}', elemento2 = '{$fields['TEC_ELEMENTO2']}', especialidad1 = '{$fields['TEC_ESPECIALIDAD']}', especialidad2 = '{$fields['TEC_ESPECIALIDAD2']}', invocacion =  '{$fields['TEC_INVOCACION']}'";
	$sql .= "WHERE pj_id = '{$fields['PJ_ID']}'";

		$db->sql_query($sql);
}

function registrar_moderacion(array $fields){

	global $db, $user;

	$mod = $user->data['username'];
	$fecha = date('Y-m-d' );

	$sql = "INSERT INTO moderaciones (moderador, razon, pj_moderado, fecha) ";
	$sql .= "values ('".$mod."', '{$fields['RAZON']}', '{$fields['PJ_ID']}','".$fecha."')";

	$db->sql_query($sql);
}
function borrar_personaje($pj) {

	global $db;

	$db->sql_query("DELETE FROM personajes WHERE user_id = '$pj'");
	$db->sql_query("DELETE FROM tecnicas WHERE pj_id = '$pj'");
	$db->sql_query("DELETE FROM moderaciones WHERE pj_moderado = '$pj'");
}

function comprobarNivel($experiencia, $nivel){

	$niveles = array(
		1 => "55",
		2 => "60",
		3 => "66",
		4 => "72",
		5 => "79",
		6 => "86",
		7 => "94",
		8 => "103",
		9 => "115",
		10 => "128",
		11 => "143",
		12 => "160",
		13 => "179",
		14 => "200",
		15 => "224",
		16 => "250",
		17 => "280",
		18 => "313",
		19 => "356",
		20 => "405",
		21 => "461",
		22 => "525",
		23 => "598",
		24 => "681",
		25 => "776",
		26 => "884",
		27 => "1007",
		28 => "1147",
		29 => "1307"
	);

	if ($experiencia >= $niveles[$nivel]) {
		$respuesta = array(
			1 => true,
			2 => 0
		);
	}
	else{
		$respuesta = array(
			1 => false,
			2 => $niveles[$nivel] - $experiencia
		);
	}

	return $respuesta;
}

function comprobarSubida($user_id){

	global $user, $db, $template, $phpbb_root_path, $auth;

	$user_id = $user_id;
	$query = $db->sql_query("SELECT * FROM personajes WHERE user_id=".$user_id."");
	if ($row = $db->sql_fetchrow($query)) {
		$db->sql_freeresult($query);

		$user->get_profile_fields($user_id);
		if (!array_key_exists('pf_experiencia', $user->profile_fields)) {
			$experiencia = 0;
		}
		else{
			$experiencia = $user->profile_fields['pf_experiencia'];
		}

		$subida = comprobarNivel($experiencia, $row['nivel']);

		if ($subida[1] == true) {
			return true;
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
}

function subidaNivel($user_id, array $fields){

	global $db, $user;

	$sql = "UPDATE personajes SET ";
	$sql .= "fuerza = '{$fields['FUERZA']}', resistencia = '{$fields['RESISTENCIA']}', agilidad = '{$fields['AGILIDAD']}', espiritu = '{$fields['ESPIRITU']}', concentracion = '{$fields['CONCENTRACION']}', voluntad = '{$fields['VOLUNTAD']}',";
	$sql .= "WHERE pj_id = '{$fields['PJ_ID']}'";

	$db->sql_query($sql);
}
