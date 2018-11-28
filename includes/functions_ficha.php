<?php

include($phpbb_root_path . 'config.' . $phpEx);

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

function get_pj_id($user_id) 
{
	global $db;
	$query = $db->sql_query("SELECT pj_id FROM personajes WHERE user_id=$user_id");
	if ($row = $db->sql_fetchrow($query)) {
		$pj_id = $row['pj_id'];
	} else {
		$pj_id = false;
	}
	return $pj_id;
}

function get_ficha($user_id, $return = false, $ver = false)
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
			$borrar = $user->data['user_id'];

			if ($grupo == 5 || $grupo == 4){
					$moderador = true;
				}
				else{
					$moderador = false;
				}

			if ($borrar == $user_id) {
				$borrarPersonaje = true;
			}
			else{
				$borrarPersonaje = false;
			}

			$user->get_profile_fields($user_id);
			if (!array_key_exists('pf_experiencia', $user->profile_fields)) {
				$experiencia = 0;
			}
			else{
				$experiencia = $user->profile_fields['pf_experiencia'];
			}
			$subida = comprobarNivel($experiencia, $row['nivel']);

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

			$template->assign_vars(array(
				//'FICHA_COMPLETA'		=> $puede_ver,
				'NIVEL' => $row['nivel'],
				'PUEDE_BORRAR' => $borrarPersonaje,
				'EXPERIENCIA' => $experiencia,
				'PUEDE_SUBIR' => $subida[1],
				'EXPERIENCIA_F' => $subida[2],
				'PUEDE_MODERAR'	=> $moderador,
				'FICHA_RANGO' => $row['rango'],
				'FICHA_ARQUETIPO' => obtener_arquetipo ($pj_id, $row['arquetipo_id']),
				'VISTA_ARQUETIPO' => vista_arquetipo ($row['arquetipo_id']),
				'ID_ARQUETIPO' => $row['arquetipo_id'],
				'FICHA_NOMBRE' => stripslashes($row['nombre']),
				'FICHA_ID' => $pj_id,
				'FICHA_EDAD' => $row['edad'],
				'FICHA_CLAN' => $row['clan'],
				'TECNICAS_CLAN' => $row2['clan'],
				'FICHA_RAMA1' => stripslashes($row['rama1']),
				'FICHA_RAMA2' => stripslashes($row['rama3']),
				'FICHA_RAMA3' => stripslashes($row['rama2']),
				'FICHA_RAMA4' => stripslashes($row['rama4']),
				//'PUNTOS'				=> $row['puntos'],
				//'GRUPO' => $user->data['group_id'],
				'FICHA_RAMA5' => stripslashes($row['rama5']),
				'FICHA_FUERZA' => $row['fuerza'],
				'FICHA_AGI' => $row['agilidad'],
				'FICHA_VIT' => $row['vitalidad'],
				'FICHA_CCK' => $row['cck'],
				'FICHA_CON' => $row['concentracion'],
				'FICHA_VOL' => $row['voluntad'],
				'FICHA_FISICO' => nl2br(stripslashes($row['fisico'])),
				'FICHA_PSICOLOGICO' => nl2br(stripslashes($row['psicologico'])),
				'FICHA_HISTORIA' => nl2br(stripslashes($row['historia'])),
				'FICHA_JUTSUS'			=> $jutsus,
				'FICHA_PC'				=> calcula_pc($row),
				'FICHA_PV'				=> calcula_pv($row),
				'FICHA_STA'				=> calcula_sta($row),
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

function obtener_arquetipo($usuario, $arquetipo){
	global $dbhost, $dbuser, $dbpasswd, $dbname, $dbport;
	$variableA = 0;

		// code...
	//connect to database
  $connection = mysqli_connect($dbhost, $dbuser, $dbpasswd, $dbname);

  //run the store proc
  $result = mysqli_query($connection,
     "CALL ObtenerArquetiposDisponibles ('".$usuario."')") or die("Query fail: " . mysqli_error());

  //loop the result set
  while ($row = mysqli_fetch_array($result)){
		$variableA .= "<option value='".$row['arquetipo_id']."'>";
		$variableA .= $row['nombre_es'];
		$variableA .= "</option>";
  }

	return $variableA;
}

function vista_arquetipo ($arquetipo){
	global $db;
	if ($arquetipo != 0) {
		$query = $db->sql_query("SELECT * FROM arquetipos WHERE arquetipo_id=".$arquetipo."");
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
		$query = $db->sql_query("SELECT * FROM arquetipos WHERE arquetipo_id=".$datos_pj['arquetipo_id']."");
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
		$query = $db->sql_query("SELECT * FROM arquetipos WHERE arquetipo_id=".$datos_pj['arquetipo_id']."");
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
		$query = $db->sql_query("SELECT * FROM arquetipos WHERE arquetipo_id=".(int)$datos_pj['arquetipo_id']."");
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

function guardar_ficha(array $fields)
{
	global $db, $user;

	$fields['HISTORIA'] = addslashes($fields['HISTORIA']);
	$fields['FISICO'] = addslashes($fields['FISICO']);
	$fields['CARACTER'] = addslashes($fields['CARACTER']);
	$idUsuario = $user->data['user_id'];
//		$sql = 'INSERT INTO ' . FICHAS_TABLE . " (user_id, nivel, rango, nombre, clan, kekkei_genkai, elementos, fisico, caracter, historia, fuerza, destreza, constitucion, cck, inteligencia, agilidad, velocidad, presencia, voluntad, bbcode_uid, bbcode_bitfield, bbcode_options, tecnicas) VALUES ('{$user->data['user_id']}', '1', '0', '{$fields['NOMBRE']}', '{$fields['CLAN']}', '{$fields['KEKKEI']}', '{$fields['ELEMENTOS']}', '{$fields['FISICO']}', '{$fields['CARACTER']}', '{$fields['HISTORIA']}', '{$fields['FUERZA']}', '{$fields['DESTREZA']}', '{$fields['CONSTITUCION']}', '{$fields['CCK']}', '{$fields['INTELIGENCIA']}', '{$fields['AGILIDAD']}', '{$fields['VELOCIDAD']}', '0', '{$fields['VOLUNTAD']}', '', '', '0', '')";
	$sql = "INSERT INTO personajes (user_id, nivel, rango, arquetipo_id, nombre, edad, clan, rama1, rama2, rama3, rama4, rama5, tecnicas, fuerza, vitalidad, agilidad, cck, concentracion, voluntad, fisico, psicologico, historia)";
	$sql .= "values (	$idUsuario, '1', 'Estudiante', '0', '{$fields['NOMBRE']}', '{$fields['EDAD']}',";
	$sql .="'{$fields['PRINCIPAL']}', '{$fields['RAMA1']}', '{$fields['RAMA2']}', 'No seleccionada', 'No seleccionada', 'No seleccionada', '', '{$fields['FUERZA']}', '{$fields['RESISTENCIA']}', '{$fields['AGILIDAD']}', '{$fields['ESPIRITU']}', '{$fields['CONCENTRACION']}', '{$fields['VOLUNTAD']}', '{$fields['FISICO']}', '{$fields['CARACTER']}', '{$fields['HISTORIA']}')";
	$db->sql_query($sql);

}

function actualizar_Ficha(array $fields){

	global $db, $user;

	$fields['HISTORIA'] = addslashes($fields['HISTORIA']);
	$fields['FISICO'] = addslashes($fields['FISICO']);
	$fields['CARACTER'] = addslashes($fields['CARACTER']);

	$sql = "UPDATE personajes SET ";
	$sql .= "nombre = '{$fields['NOMBRE']}', edad = '{$fields['EDAD']}', rango = '{$fields['RANGO']}', arquetipo_id = '{$fields['ARQUETIPO']}',";
	$sql .= "clan = '{$fields['PRINCIPAL']}', rama1 = '{$fields['RAMA1']}', rama2 = '{$fields['RAMA2']}', rama3 = '{$fields['RAMA3']}', rama4 = '{$fields['RAMA4']}', rama5 = '{$fields['RAMA5']}',";
	$sql .= 'tecnicas = "'.$fields['TEC_JUTSUS'].'",';
	$sql .= "fuerza = '{$fields['FUERZA']}', vitalidad = '{$fields['RESISTENCIA']}', agilidad = '{$fields['AGILIDAD']}', cck = '{$fields['ESPIRITU']}', concentracion = '{$fields['CONCENTRACION']}', voluntad = '{$fields['VOLUNTAD']}',";
	$sql .= "fisico = '{$fields['FISICO']}', psicologico = '{$fields['CARACTER']}', historia = '{$fields['HISTORIA']}'";
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
