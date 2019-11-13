<?php

namespace gray\ficha\controller;
require_once('/home/shinobil/public_html/includes/functions_ficha.php');
require_once('/home/shinobil/public_html/includes/functions_beneficios.php');

class main
{
    /* @var \phpbb\config\config */
    protected $config;

    /* @var \phpbb\controller\helper */
    protected $helper;

    /* @var \phpbb\template\template */
    protected $template;

    /* @var \phpbb\user */
    protected $user;

    protected $db;
    protected $auth;

    /**
     * Constructor
     *
     * @param \phpbb\config\config      $config
     * @param \phpbb\controller\helper  $helper
     * @param \phpbb\template\template  $template
     * @param \phpbb\user               $user
     */
    public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth)
    {
        $this->config   = $config;
        $this->helper   = $helper;
        $this->template = $template;
        $this->user     = $user;
        $this->db       = $db;
        $this->auth     = $auth;
    }

    /**
     * Demo controller for route /demo/{name}
     *
     * @param string $name
     * @throws \phpbb\exception\http_exception
     * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
     */
    public function handle()
    {
        $user_id = $this->user->data['user_id'];

        if (ficha_exists($user_id) == true) {
            trigger_error('Ya tienes ficha creada.');
        }
        if ($this->user->data['user_id'] == ANONYMOUS ) {
            trigger_error('No puedes acceder aquí sin conectarte');
        }

        $this->template->assign_var('RAMAS_PRINCIPALES', get_ramas_select(1, false, null, false));
		$this->template->assign_var('FICHA_ALDEAS', obtener_aldeas_select(false, false));

        return $this->helper->render('ficha_body.html', 'Creación de Ficha');
    }

	public function store()
    {
		$group_id = $user_rank = false;

        $user_id = $this->user->data['user_id'];
		$pj_id = get_pj_id($user_id);

		if($pj_id)
			trigger_error('Ya posees un personaje; no puedes crear otro.' . $this->get_return_link($user_id));

        $atrs = array(
            'FUERZA'            => (int) request_var('atrFuerza', 1),
            'RESISTENCIA'       => (int) request_var('atrVit', 1),
            'AGILIDAD'          => (int) request_var('artAg', 1),
            'ESPIRITU'          => (int) request_var('atrCCK', 1),
            'CONCENTRACION'     => (int) request_var('atrCon', 1),
            'VOLUNTAD'          => (int) request_var('atrVol', 1),
        );
//profile_fields_data -> Tabla donde se encuentra la experiencia
        $fields = array_merge(array(
            'NOMBRE'            => utf8_normalize_nfc(request_var('name', '', true)),
            'EDAD'              => utf8_normalize_nfc(request_var('edad', '', true)),
            'PRINCIPAL'         => request_var('ramaPrincipal', 0, true),
			'ALDEA'				=> request_var('aldea', 0, true),
            'RAMA1'             => request_var('ramaSec1', 0, true),
            'RAMA2'             => request_var('ramaSec2', 0, true),
            'FISICO'            => utf8_normalize_nfc(request_var('descFis', '', true)),
            'CARACTER'          => utf8_normalize_nfc(request_var('descPsic', '', true)),
            'HISTORIA'          => utf8_normalize_nfc(request_var('descHis', '', true)),
        ), $atrs);

        $fields['HISTORIA'] = addslashes($fields['HISTORIA']);
        $fields['FISICO'] = addslashes($fields['FISICO']);
        $fields['CARACTER'] = addslashes($fields['CARACTER']);

		$pj_id = get_max_pj_id() + 1;

		$sql_array = array(
			'pj_id'		=> $pj_id,
			'user_id'	=> $user_id,
			'nivel'		=> 1,
			'rango'		=> 'Estudiante',
			'arquetipo_id'	=> 0,
			'nombre'	=> $fields['NOMBRE'],
			'aldea_id'	=> $fields['ALDEA'],
			'edad'		=> $fields['EDAD'],
			'edad_inicial'	=> $fields['EDAD'],
			'rama_id_pri'	=> $fields['PRINCIPAL'],
			'rama_id1'	=> $fields['RAMA1'],
			'rama_id2'	=> $fields['RAMA2'],
			'rama_id3'	=> 0,
			'rama_id4'	=> 0,
			'rama_id5'	=> 0,
			'tecnicas'	=> '',
			'fuerza'	=> $fields['FUERZA'],
			'vitalidad'	=> $fields['RESISTENCIA'],
			'agilidad'	=> $fields['AGILIDAD'],
			'cck'		=> $fields['ESPIRITU'],
			'concentracion'	=> $fields['CONCENTRACION'],
			'voluntad'	=> $fields['VOLUNTAD'],
			'fisico'	=> $fields['FISICO'],
			'psicologico'	=> $fields['CARACTER'],
			'historia'	=> $fields['HISTORIA'],
			'activo'	=> 0,
		);

		if ((int)$fields['ALDEA'] > 0) {
			$query = $this->db->sql_query("SELECT	a.group_id, 
													a.nivel_inicial, 
													a.rama_id_default,
													g.group_colour,
													g.group_rank
											FROM ".ALDEAS_TABLE." a
												INNER JOIN ".GROUPS_TABLE." g
													ON g.group_id = a.group_id
											WHERE aldea_id = ".$fields['ALDEA']);

			if ($row = $this->db->sql_fetchrow($query)) {
				$group_id = (int)$row['group_id'];
				$user_colour = $row['group_colour'];
				
				if ((int)$row['group_rank'] > 0) {
					$user_rank = (int)$row['group_rank'];
				}

				if ((int)$row['nivel_inicial'] > 1) {
					$sql_array['nivel_inicial'] = $row['nivel_inicial'];
				}

				if ((int)$row['rama_id_default'] > 0) {
					$sql_array['rama_id_pri'] = $row['rama_id_default'];
				}
			}
			$this->db->sql_freeresult($query);
		}

        $sql = "INSERT INTO personajes " . $this->db->sql_build_array('INSERT', $sql_array);
        $this->db->sql_query($sql);

		$sql_ary = array(
			'pf_ryos'			=> '1500',
			'pf_experiencia'	=> '0',
			'pf_puntos_apren'	=> '10',
		);
		$sql = 'UPDATE '.PROFILE_FIELDS_DATA_TABLE.' SET ' .
					$this->db->sql_build_array('UPDATE', $sql_ary)
					." WHERE user_id = $user_id";
		$this->db->sql_query($sql);

		if ((int) $this->db->sql_affectedrows() < 1) {
			$sql_ary['user_id'] = $user_id;
			$sql = 'INSERT INTO ' . PROFILE_FIELDS_DATA_TABLE . $this->db->sql_build_array('INSERT', $sql_ary);
			$this->db->sql_query($sql);
		}

		if ($group_id && (int)$fields['ALDEA'] != $aldea_id_old) {
			$sql_ary = array(
				'user_id'		=> $user_id,
				'group_id'		=> $group_id,
				'group_leader'	=> 0,
				'user_pending'	=> 0,
			);

			$sql = "DELETE FROM ".USER_GROUP_TABLE."
					WHERE user_id = '$user_id'
						AND group_id IN(SELECT group_id
										   FROM ".ALDEAS_TABLE.")";
			$this->db->sql_query($sql);

			$sql = 'INSERT INTO ' . USER_GROUP_TABLE . $this->db->sql_build_array('INSERT', $sql_ary);
			$this->db->sql_query($sql);
			
			$sql = "UPDATE ".USERS_TABLE." 
						SET group_id = $group_id,
							user_colour = '$user_colour' " .
			 ($user_rank ? ", user_rank = $user_rank" : "") .
					" WHERE user_id = $user_id";
			$this->db->sql_query($sql);
		}

        $this->template->assign_var('DEMO_MESSAGE', request_var('name', '', true));
        trigger_error("<div class='no-character' style='text-align:center'><span class='mensaje'>El personaje ha sido creado.</span><br/><a href='/reglas-y-guias-f4/01-primeros-pasos-t135'><img src='/images/comienzo.png' /><br/><span class='character-btn' style='float:none'>¿Cómo empezar?</span></a></div>");
    }

    function view($user_id)
    {
		$b_avatar_ficha = $b_ficha_premium = $b_ubicacion_items = false;

		$pj_id = get_pj_id($user_id);
        get_ficha($user_id,$return = false, $ver = true);

		$beneficios = get_beneficios($user_id);
		if ($beneficios) {
			foreach ($beneficios as $key => $val) {
				if ($val['nombre_php'] == BENEFICIO_AVATAR_FICHA) {
					$b_avatar_ficha = true;
				}

				if ($val['nombre_php'] == BENEFICIO_BANNER_FICHA) {
					$b_ficha_premium = true;
				}

				if ($val['nombre_php'] == BENEFICIO_UBICACION_ITEMS) {
					$b_ubicacion_items = true;
				}
			}
		}

		if ($b_avatar_ficha) {
			$query = $this->db->sql_query('SELECT * FROM '.USERS_TABLE.' WHERE user_id = ' . $user_id);
			if ($row = $this->db->sql_fetchrow($query)) {
				$avatar = phpbb_get_user_avatar($row);
			}
		}

		$this->template->assign_vars(array(
			'B_AVATAR_FICHA'	=> $b_avatar_ficha,
			'AVATAR_FICHA'		=> $avatar,
			'B_FICHA_PREMIUM'	=> $b_ficha_premium,
			'B_UBICACION_ITEMS'	=> $b_ubicacion_items,
		));

		$categorias = get_full_shops();
		foreach($categorias as $cat) {
			$this->template->assign_block_vars('categoria_item', $cat);
			$items = get_pj_inventory($pj_id, 0, $cat['ID']);
			if($items)
				foreach($items as $item) {
					$this->template->assign_block_vars('categoria_item.items', $item);
					$this->template->assign_block_vars_array('categoria_item.items.tipos', $item['tags']);
				}
		}

		$user_beneficios_historico = get_user_beneficios_historico($user_id);
		if ($user_beneficios_historico) {
			foreach ($user_beneficios_historico as $beneficio) {
				$this->template->assign_block_vars('beneficios_historico', array(
					'NOMBRE'		=> $beneficio['nombre'],
					'DESCRIPCION'	=> $beneficio['descripcion'],
					'FECHA_INICIO'	=> $beneficio['fecha_inicio'],
					'FECHA_FIN'		=> $beneficio['fecha_fin'],
					'MODERADOR'		=> $beneficio['moderador_add'],
					'ACTIVO'		=> ($beneficio['fecha_fin'] ? $beneficio['activo'] : true),
				));
			}

			$user_tier = get_user_tier($user_id);
			if ($user_tier) {
				$tier_actual = get_user_tier_string($user_tier);
				$this->template->assign_vars(array(
					'PATREON_TIER'	=> $tier_actual
				));
			}
		}

        return $this->helper->render('ficha_view.html');
    }

    function delete($user_id)
    {
        $borrar = $this->user->data['user_id'];

        if ($borrar == $user_id) {
            // check mode
            if (confirm_box(true))
            {
                borrar_personaje($user_id);
                trigger_error('Personaje borrado correctamente.<br><a href="/ficha/new">Crear nuevo personaje</a>.');
            }
            else
            {
                $s_hidden_fields = build_hidden_fields(array('submit' => true));
                confirm_box(false, '¿Estás seguro de que quieres borrar tu personaje? Él no lo haría.', $s_hidden_fields);
            }
        }
        else{
            trigger_error("No puede borrar un personaje de otro usuario." . $this->get_return_link($user_id));
        }

		return $this->view($user_id);
    }

    function borrar_personaje($pj) {

        global $db;

        $db->sql_query("DELETE FROM personajes WHERE user_id = '$pj'");
        $db->sql_query("DELETE FROM tecnicas WHERE pj_id = '$pj'");
        $db->sql_query("DELETE FROM moderaciones WHERE pj_moderado = '$pj'");
    }


    public function viewMod($user_id)
    {
        $grupo = $this->user->data['group_id'];
        if ($grupo == 5 || $grupo == 4 || $grupo == 18) {
            get_ficha($user_id,$return = false, $ver = false);
            $this->template->assign_vars(array(
                'U_ACTION'	=> append_sid('/ficha/storeMod/' . $user_id),
            ));
            return $this->helper->render('ficha_edit.html');
        }
        else
        {
            trigger_error("No puedes acceder a esta sección.". $this->get_return_link($user_id));
        }
    }

    public function storeMod($user_id)
    {
		$group_id = $aldea_id_old = $user_rank = false;

        $grupo = $this->user->data['group_id'];

        if ($grupo == 5 || $grupo == 4 || $grupo == 18) {
            $atrs = array(
                'FUERZA'            => (int) request_var('atrFuerza', 1),
                'RESISTENCIA'       => (int) request_var('atrVit', 1),
                'AGILIDAD'          => (int) request_var('artAg', 1),
                'ESPIRITU'          => (int) request_var('atrCCK', 1),
                'CONCENTRACION'     => (int) request_var('atrCon', 1),
                'VOLUNTAD'          => (int) request_var('atrVol', 1),
            );

            //profile_fields_data -> Tabla donde se encuentra la experiencia
            $fields = array_merge(array(
                    'NOMBRE'		=> utf8_normalize_nfc(request_var('name', '', true)),
                    'PJ_ID'			=> utf8_normalize_nfc(request_var('pj_id', '', true)),
                    'EDAD'			=> utf8_normalize_nfc(request_var('edad', '', true)),
                    'RANGO'			=> utf8_normalize_nfc(request_var('rango', '', true)),
                    'ARQUETIPO'		=> utf8_normalize_nfc(request_var('arquetipo', '', true)),
					'ALDEA'			=> request_var('aldea', 0, true),
                    'NIVEL_INICIAL'	=> request_var('nivel_inicial', 0, true),
                    'ES_BIJUU'		=> request_var('es_bijuu', -1, true),
                    'PRINCIPAL'		=> request_var('ramaPrincipal', 0, true),
                    'RAMA1'			=> request_var('ramaSec1', 0, true),
                    'RAMA2'			=> request_var('ramaSec2', 0, true),
                    'RAMA3'			=> request_var('ramaSec3', 0, true),
                    'RAMA4'			=> request_var('ramaSec4', 0, true),
                    'RAMA5'			=> request_var('ramaSec5', 0, true),
                    'FISICO'		=> utf8_normalize_nfc(request_var('descFis', '', true)),
                    'CARACTER'		=> utf8_normalize_nfc(request_var('descPsic', '', true)),
                    'HISTORIA'		=> utf8_normalize_nfc(request_var('descHis', '', true)),
                    'TEC_JUTSUS'	=> utf8_normalize_nfc(request_var('tecnicas', '', true)),
                    'RAZON'			=> utf8_normalize_nfc(request_var('razon', '', true)),
                    'PUNTOS_APRENDIZAJE' => utf8_normalize_nfc(request_var('puntos_aprendizaje', '', true)),
                    'ADD_PUNTOS_EXPERIENCIA' => utf8_normalize_nfc(request_var('add_puntos_experiencia', '', true)),
                    'ADD_PUNTOS_APRENDIZAJE' => utf8_normalize_nfc(request_var('add_puntos_aprendizaje', '', true)),
                    'ADD_RYOS' => utf8_normalize_nfc(request_var('add_ryos', '', true)),
                ), $atrs);

            $fields['HISTORIA'] = addslashes($fields['HISTORIA']);
            $fields['FISICO'] = addslashes($fields['FISICO']);
            $fields['CARACTER'] = addslashes($fields['CARACTER']);
            $idUsuario = $this->user->data['user_id'];
			$pj_id = $fields['PJ_ID'];

			$sql_array = array(
				'rango'		=> $fields['RANGO'],
				'nombre'	=> $fields['NOMBRE'],
				'aldea_id'	=> $fields['ALDEA'],
				'edad'		=> $fields['EDAD'],
				'rama_id_pri'	=> $fields['PRINCIPAL'],
				'rama_id1'	=> $fields['RAMA1'],
				'rama_id2'	=> $fields['RAMA2'],
				'rama_id3'	=> $fields['RAMA3'],
				'rama_id4'	=> $fields['RAMA4'],
				'rama_id5'	=> $fields['RAMA5'],
				'tecnicas'	=> $fields['TEC_JUTSUS'],
				'fuerza'	=> $fields['FUERZA'],
				'vitalidad'	=> $fields['RESISTENCIA'],
				'agilidad'	=> $fields['AGILIDAD'],
				'cck'		=> $fields['ESPIRITU'],
				'concentracion'	=> $fields['CONCENTRACION'],
				'voluntad'	=> $fields['VOLUNTAD'],
				'fisico'	=> $fields['FISICO'],
				'psicologico'	=> $fields['CARACTER'],
				'historia'	=> $fields['HISTORIA'],
			);

			if ($fields['ARQUETIPO'] != '') {
                $sql_array['arquetipo_id'] = $fields['ARQUETIPO'];
            }

			if ((int)$fields['NIVEL_INICIAL'] > 0)
				$sql_array['nivel_inicial'] = $fields['NIVEL_INICIAL'];

			if ((int)$fields['ES_BIJUU'] > -1)
				$sql_array['es_bijuu'] = $fields['ES_BIJUU'];

			if ((int)$fields['ALDEA'] > 0) {
				
			$query = $this->db->sql_query("SELECT	a.group_id, 
													g.group_colour,
													g.group_rank
											FROM ".ALDEAS_TABLE." a
												INNER JOIN ".GROUPS_TABLE." g
													ON g.group_id = a.group_id
											WHERE aldea_id = ".$fields['ALDEA']);

				if ($row = $this->db->sql_fetchrow($query)) {
					$group_id = (int)$row['group_id'];
					$user_colour = $row['group_colour'];
					
					if ((int)$row['group_rank'] > 0) {
						$user_rank = (int)$row['group_rank'];
					}
				}
				$this->db->sql_freeresult($query);

				$query = $this->db->sql_query("SELECT aldea_id FROM ".PERSONAJES_TABLE." WHERE pj_id = $pj_id");
				if ($row = $this->db->sql_fetchrow($query)) {
					$aldea_id_old = (int)$row['aldea_id'];
				}
				$this->db->sql_freeresult($query);
			}

			$nueva_edad = calcular_edad_personaje($pj_id);
			if ($nueva_edad) $sql_array['edad'] = $nueva_edad;

            $sql = "UPDATE ".PERSONAJES_TABLE." SET "
						. $this->db->sql_build_array('UPDATE', $sql_array) .
						" WHERE user_id = $user_id";
            $this->db->sql_query($sql);

			if ($group_id && (int)$fields['ALDEA'] != $aldea_id_old) {
				$sql_ary = array(
					'user_id'		=> $user_id,
					'group_id'		=> $group_id,
					'group_leader'	=> 0,
					'user_pending'	=> 0,
				);

			$sql = "DELETE FROM ".USER_GROUP_TABLE."
					WHERE user_id = '$user_id'
						AND group_id IN(SELECT group_id
										   FROM ".ALDEAS_TABLE.")";
				$this->db->sql_query($sql);

				$sql = 'INSERT INTO ' . USER_GROUP_TABLE . $this->db->sql_build_array('INSERT', $sql_ary);
				$this->db->sql_query($sql);

				$sql = "UPDATE ".USERS_TABLE." 
						SET group_id = $group_id,
							user_colour = '$user_colour' " .
			 ($user_rank ? ",user_rank = $user_rank" : "") .
					" WHERE user_id = $user_id";
					
				$this->db->sql_query($sql);
			}

            registrar_moderacion($fields, $user_id);

            trigger_error("Personaje moderado correctamente." . $this->get_return_link($user_id));
        }
        else{
            trigger_error("No eres moderador o administrador." . $this->get_return_link($user_id));
        }

		return $this->view($user_id);
    }

	public function buyHab($user_id)
	{
		if ($user_id != $this->user->data['user_id']) {
			trigger_error('No puedes comprar habilidades para un personaje que no te pertenece.' . $this->get_return_link($user_id));
		}

		$hab_id = (int) request_var('habilidad_id', 0);

		if ($hab_id > 0) {
			$sql = "SELECT nombre, coste FROM habilidades WHERE habilidad_id = '$hab_id'";
			$query = $this->db->sql_query($sql);
			if ($row = $this->db->sql_fetchrow($query)) {
				$hab_nombre = $row['nombre'];
				$hab_coste = (int) $row['coste'];
			}
			else {
				trigger_error('No se ha encontró la habilidad.' . $this->get_return_link($user_id));
			}

			if (confirm_box(true)){
				if (comprar_habilidad($user_id, $hab_id, $hab_nombre, $hab_coste, $msg_error)) {
					trigger_error("Habilidad aprendida exitosamente." . $this->get_return_link($user_id));
				}
				else {
					trigger_error($msg_error . $this->get_return_link($user_id));
				}
			}
			else {
				$s_hidden_fields = build_hidden_fields(array(
					'submit' 		=> true,
					'habilidad_id'	=> $hab_id,
				));

				confirm_box(false, "¿Deseas aprender la habilidad '$hab_nombre' por $hab_coste Puntos de Aprendizaje?", $s_hidden_fields);
			}
		}
		else {
			trigger_error('No se ha seleccionado una habilidad.' . $this->get_return_link($user_id));
		}

		return $this->view($user_id);
	}

	function lvlUp($user_id)
	{
		$sql_array = array();
		$lvlup_data = array(
			'RAMA1'			=> (request_var('ramaSec1', 0, true)),
			'RAMA2'			=> (request_var('ramaSec2', 0, true)),
			'RAMA3'			=> (request_var('ramaSec3', 0, true)),
			'RAMA4'			=> (request_var('ramaSec4', 0, true)),
			'RAMA5'			=> (request_var('ramaSec5', 0, true)),
			'ARQUETIPO'		=> (int) request_var('arquetipo', 0),
			'FUERZA'		=> (int) request_var('atrFuerza', 0),
			'AGILIDAD'		=> (int) request_var('artAg', 0),
			'VITALIDAD'		=> (int) request_var('atrVit', 0),
			'CCK'			=> (int) request_var('atrCCK', 0),
			'CONCENTRACION'	=> (int) request_var('atrCon', 0),
			'VOLUNTAD'		=> (int) request_var('atrVol', 0),
			'ATTR_DISP'		=> (int) request_var('attrdisp', 0),
		);

		if ($user_id != $this->user->data['user_id']) {
			trigger_error('No puedes modificar un personaje que no te pertenece.' . $this->get_return_link($user_id));
		}

		$pj_id = get_pj_id($user_id);
		if (!$pj_id) trigger_error('No se encontró tu personaje.' . $this->get_return_link($user_id));

		$pj_data = get_pj_data($pj_id, 0);
		if (!$pj_data) trigger_error('Hubo un error obteniendo los datos de tu personaje.' . $this->get_return_link($user_id));

		if ($lvlup_data['ATTR_DISP'] > 0) {
			$diff = ($lvlup_data['FUERZA']+$lvlup_data['AGILIDAD']+$lvlup_data['VITALIDAD']+$lvlup_data['CCK']+$lvlup_data['CONCENTRACION']+$lvlup_data['VOLUNTAD'])
					- ($pj_data['PJ_FUE']+$pj_data['PJ_AGI']+$pj_data['PJ_VIT']+$pj_data['PJ_CCK']+$pj_data['PJ_CON']+$pj_data['PJ_VOL']);

			$attr_max = 10 + ((int)$pj_data['PJ_NIVEL'] * 5);

			$attr_disp = (int) $pj_data['PJ_ATTR_DISP'];
			if ($diff > $attr_disp) {
				trigger_error("No puedes repartir más de $attr_disp puntos." . $this->get_return_link($user_id));
			}

			if ($lvlup_data['FUERZA'] < $pj_data['PJ_FUE'] || $lvlup_data['FUERZA'] > $attr_max)
				trigger_error("La Fuerza ingresada es incorrecta." . $this->get_return_link($user_id));

			if ($lvlup_data['AGILIDAD'] < $pj_data['PJ_AGI'] || $lvlup_data['AGILIDAD'] > $attr_max)
				trigger_error("La Agilidad ingresada es incorrecta." . $this->get_return_link($user_id));

			if ($lvlup_data['VITALIDAD'] < $pj_data['PJ_VIT'] || $lvlup_data['VITALIDAD'] > $attr_max)
				trigger_error("La Vitalidad ingresada es incorrecta." . $this->get_return_link($user_id));

			if ($lvlup_data['CCK'] < $pj_data['PJ_CCK'] || $lvlup_data['CCK'] > $attr_max)
				trigger_error("El Control de Chakra ingresado es incorrecto." . $this->get_return_link($user_id));

			if ($lvlup_data['CONCENTRACION'] < $pj_data['PJ_CON'] || $lvlup_data['CONCENTRACION'] > $attr_max)
				trigger_error("La Concentración ingresada es incorrecta." . $this->get_return_link($user_id));

			if ($lvlup_data['VOLUNTAD'] < $pj_data['PJ_VOL'] || $lvlup_data['VOLUNTAD'] > $attr_max)
				trigger_error("La Voluntad ingresada es incorrecta." . $this->get_return_link($user_id));

			$sql_array = array_merge(array(
				'fuerza'		=> $lvlup_data['FUERZA'],
				'agilidad'		=> $lvlup_data['AGILIDAD'],
				'vitalidad'		=> $lvlup_data['VITALIDAD'],
				'cck'			=> $lvlup_data['CCK'],
				'concentracion'	=> $lvlup_data['CONCENTRACION'],
				'voluntad'		=> $lvlup_data['VOLUNTAD'],
			), $sql_array);
		}

		if ($lvlup_data['ARQUETIPO'] > 0)
			$sql_array['arquetipo_id'] = $lvlup_data['ARQUETIPO'];

		if ($lvlup_data['RAMA1'] > 0)
			$sql_array['rama_id1'] = $lvlup_data['RAMA1'];

		if ($lvlup_data['RAMA2'] > 0)
			$sql_array['rama_id2'] = $lvlup_data['RAMA2'];

		if ($lvlup_data['RAMA3'] > 0)
			$sql_array['rama_id3'] = $lvlup_data['RAMA3'];

		if ($lvlup_data['RAMA4'] > 0)
			$sql_array['rama_id4'] = $lvlup_data['RAMA4'];

		if ($lvlup_data['RAMA5'] > 0)
			$sql_array['rama_id5'] = $lvlup_data['RAMA5'];

		$query = $this->db->sql_query('SELECT nivel, arquetipo_id, cambio_arquetipo
										FROM '.PERSONAJES_TABLE."
										WHERE user_id = $user_id");
		if($row = $this->db->sql_fetchrow($query)) {
			if ((int)$row['cambio_arquetipo'] == -1 && (int)$row['nivel'] >= 5) {
				$no_cambia_arq = ((int)$lvlup_data['ARQUETIPO'] == (int)$row['arquetipo_id']);
				$sql_array['cambio_arquetipo'] = $no_cambia_arq ? 0 : 1;
			}
		}
		$this->db->sql_freeresult($query);

		$nueva_edad = calcular_edad_personaje($pj_id);
		if ($nueva_edad) $sql_array['edad'] = $nueva_edad;

		try {
			$this->db->sql_query('UPDATE '.PERSONAJES_TABLE.' SET '
									. $this->db->sql_build_array('UPDATE', $sql_array)
									. " WHERE user_id = $user_id");

			$moderacion = array(
				'PJ_ID'	=> $pj_id,
				'RAZON'	=> 'Modificación por Usuario',
			);
			registrar_moderacion($moderacion);

			if ($no_cambia_arq) {
				$this->db->sql_query('UPDATE '.PROFILE_FIELDS_DATA_TABLE."
										SET pf_puntos_apren = pf_puntos_apren + 10
										WHERE user_id = $user_id");

				$moderacion = array(
					'PJ_ID'	=> $pj_id,
					'RAZON'	=> '+10 PA por mantener Arquetipo.',
				);
				registrar_moderacion($moderacion);
			}

			trigger_error("Ficha actualizada exitosamente." . $this->get_return_link($user_id));
		} catch (Exception $e) {
			trigger_error("Ocurrió un error al actualizar la ficha; contacta a Administración.<br>"
							. $e->getMessage()
							. $this->get_return_link($user_id));
		}

	}

	function nextLvl($user_id)
	{
		if ($user_id != $this->user->data['user_id']) {
			trigger_error('No puedes modificar un personaje que no te pertenece, puerco.' . $this->get_return_link($user_id));
		}

		$pj_id = get_pj_id($user_id);
		if (!$pj_id) trigger_error('No se encontró tu personaje.' . $this->get_return_link($user_id));

		$pj_data = get_pj_data($pj_id, 0);
		if (!$pj_data) trigger_error('Hubo un error obteniendo los datos de tu personaje.' . $this->get_return_link($user_id));

		$nivel = $pj_data['PJ_NIVEL'];
		$nivel_inicial = $pj_data['PJ_NIVEL_INICIAL'];
		$nivel_sig = $nivel + 1;
		$exp_subir = $pj_data['PJ_EXPERIENCIA_SIG'] - $pj_data['PJ_EXPERIENCIA'];
		$pa_subir = ($nivel_sig > 10 ? 5 : 3); // Si el próximo nivel es mayor a 10, gana 5 PA. Si no, 3 PA.
		$ryos_subir = 1000;

		if ($nivel >= $nivel_inicial)
			trigger_error('El personaje ya ha alcanzado su nivel inicial.' . $this->get_return_link($user_id));

		if (confirm_box(true)){
			try {
				$this->db->sql_query('UPDATE '.PROFILE_FIELDS_DATA_TABLE."
										SET pf_experiencia = pf_experiencia + $exp_subir,
											pf_puntos_apren = pf_puntos_apren + $pa_subir,
											pf_ryos = pf_ryos + $ryos_subir
										WHERE user_id = '$user_id'");

				$moderacion = array(
						'PJ_ID'	=> $pj_id,
						'RAZON'	=> "Subir de Nivel $nivel a Nivel $nivel_sig.",
					);
				registrar_moderacion($moderacion);
			} catch (Exception $e) {
				trigger_error("Ocurrió un error al actualizar la ficha; contacta a Administración.<br>"
								. $e->getMessage()
								. $this->get_return_link($user_id));
			}
		}
		else {
			$s_hidden_fields = build_hidden_fields(array(
				'submit'	=> true
			));

			confirm_box(false, "¿Deseas subir de Nivel $nivel a Nivel $nivel_sig?<br/>
								Tu personaje podrá seguir subiendo hasta Nivel $nivel_inicial.", $s_hidden_fields);
		}

		return $this->view($user_id);
	}

	function sellItem($user_id) {
		$item_id = (int) request_var('item_id', 0);
		$cantidad_venta = (int) request_var('cantidad_venta', 1);

		if ($user_id != $this->user->data['user_id']) {
			trigger_error('No puedes modificar un personaje que no te pertenece, puerco.' . $this->get_return_link($user_id));
		}

		$pj_id = get_pj_id($user_id);
		if (!$pj_id) trigger_error('No se encontró tu personaje.' . $this->get_return_link($user_id));

		$sql = "SELECT i.nombre,
					i.precio,
					pi.cantidad
				FROM " . ITEMS_TABLE . " i
					INNER JOIN " . PERSONAJE_ITEMS_TABLE . " pi
						ON pi.item_id = i.item_id
				WHERE pi.pj_id = '$pj_id'
					AND i.item_id = '$item_id'
					AND pi.cantidad >= $cantidad_venta";

		$query = $this->db->sql_query($sql);

		if ($row = $this->db->sql_fetchrow($query)) {
			$item_nombre = $row['nombre'];
			$precio_venta = round((int)$row['precio'] / 2) * $cantidad_venta;
		}
		else {
			trigger_error("No posees el item en tu inventario." . $this->get_return_link($user_id));
		}
		$this->db->sql_freeresult($query);

		if (confirm_box(true)) {
			if (vender_item($user_id, $pj_id, $item_id, $cantidad_venta, $msg_error)) {
				$moderacion = array(
					'PJ_ID'	=> $pj_id,
					'RAZON'	=> "Vende $cantidad_venta x '$item_nombre' por $precio_venta Ryos.",
				);
				registrar_moderacion($moderacion);

				trigger_error("Item vendido exitosamente." . $this->get_return_link($user_id));
			}
			else {
				trigger_error($msg_error . $this->get_return_link($user_id));
			}
		}
		else {
			$s_hidden_fields = build_hidden_fields(array(
				'submit' 	=> true,
				'item_id'	=> $item_id,
				'cantidad_venta'	=> $cantidad_venta
			));

			confirm_box(false, "¿Deseas vender $cantidad_venta x '$item_nombre' por $precio_venta Ryos? Esta acción no puede revertirse.", $s_hidden_fields);
		}

		return $this->view($user_id);
	}

	function saveItem($user_id) {
		$item_id = (int) request_var('item_id', 0);
		$ubicacion = utf8_normalize_nfc(request_var('ubicacion', '', true));
		$b_ubicacion_items = false;

		if ($user_id != $this->user->data['user_id']) {
			trigger_error('No puedes modificar un personaje que no te pertenece, puerco.' . $this->get_return_link($user_id));
		}

		$pj_id = get_pj_id($user_id);
		if (!$pj_id) trigger_error('No se encontró tu personaje.' . $this->get_return_link($user_id));

		$beneficios = get_beneficios($user_id);
		if ($beneficios) {
			foreach ($beneficios as $key => $val) {
				if ($val['nombre_php'] == BENEFICIO_UBICACION_ITEMS) {
					$b_ubicacion_items = true;
				}
			}
		}

		if (!$b_ubicacion_items) {
			trigger_error('No tienes habilitada la ubicación de items en el inventario.' . $this->get_return_link($user_id));
		}

		$sql = "SELECT nombre FROM " . ITEMS_TABLE . " WHERE item_id = '$item_id'";
		$query = $this->db->sql_query($sql);
		if ($row = $this->db->sql_fetchrow($query)) {
			$item_nombre = $row['nombre'];
		} else {
			trigger_error("No se encontró el item." . $this->get_return_link($user_id));
		}
		$this->db->sql_freeresult($query);

		if (confirm_box(true)) {
			if (actualizar_item($user_id, $pj_id, $item_id, $ubicacion, $msg_error)) {
				$moderacion = array(
					'PJ_ID'	=> $pj_id,
					'RAZON'	=> "Actualizado '$item_nombre' a la ubicación '$ubicacion'.",
				);
				registrar_moderacion($moderacion);

				trigger_error("Item actualizado exitosamente." . $this->get_return_link($user_id));
			}
			else {
				trigger_error($msg_error . $this->get_return_link($user_id));
			}
		} else {
			$s_hidden_fields = build_hidden_fields(array(
				'submit' 	=> true,
				'item_id'	=> $item_id,
				'ubicacion'	=> $ubicacion
			));

			confirm_box(false, "¿Actualizar la ubicación de '$item_nombre'?", $s_hidden_fields);
		}

		return $this->view($user_id);
	}

	public function buyTec($user_id)
	{
		if ($user_id != $this->user->data['user_id']) {
			trigger_error('No puedes aprender técnicas para un personaje que no te pertenece.' . $this->get_return_link($user_id));
		}

		$tec_id = (int) request_var('tecnica_id', 0);

		if ($tec_id > 0) {
			$sql = "SELECT nombre, coste FROM tecnicas WHERE tecnica_id = '$tec_id'";
			$query = $this->db->sql_query($sql);
			if ($row = $this->db->sql_fetchrow($query)) {
				$tec_nombre = $row['nombre'];
				$tec_coste = (int) $row['coste'];
			}
			else {
				trigger_error('No se ha encontró la técnica.' . $this->get_return_link($user_id));
			}

			if (confirm_box(true)){
				if (comprar_tecnica($user_id, $tec_id, $tec_nombre, $tec_coste, $msg_error)) {
					trigger_error("Técnica aprendida exitosamente." . $this->get_return_link($user_id));
				}
				else {
					trigger_error($msg_error . $this->get_return_link($user_id));
				}
			}
			else {
				$s_hidden_fields = build_hidden_fields(array(
					'submit' 		=> true,
					'tecnica_id'	=> $tec_id,
				));

				confirm_box(false, "¿Deseas aprender la técnica '$tec_nombre' por $tec_coste Puntos de Aprendizaje?", $s_hidden_fields);
			}
		}
		else {
			trigger_error('No se ha seleccionado una técnica.' . $this->get_return_link($user_id));
		}

		return $this->view($user_id);
	}

	function removeTecMod($user_id) {
		$tec_id = (int) request_var('tecnica_id', 0);

		$grupo = $this->user->data['group_id'];

        if ($grupo != 5 && $grupo != 4 && $grupo != 18) {
            trigger_error("No eres moderador o administrador." . $this->get_return_link($user_id));
		}

		$pj_id = get_pj_id($user_id);
		if (!$pj_id) trigger_error('No se encontró el personaje.' . $this->get_return_link($user_id));

		$sql = "SELECT nombre, coste
				FROM " . TECNICAS_TABLE . " t
					INNER JOIN " . PERSONAJE_TECNICAS_TABLE . " pt
						ON pt.tecnica_id = t.tecnica_id
				WHERE pt.pj_id = '$pj_id'
					AND t.tecnica_id = '$tec_id'";

		$query = $this->db->sql_query($sql);

		if ($row = $this->db->sql_fetchrow($query)) {
			$tec_nombre = $row['nombre'];
			$tec_coste = (int)$row['coste'];
		}
		else {
			trigger_error("El personaje no posee la técnica seleccionada." . $this->get_return_link($user_id));
		}
		$this->db->sql_freeresult($query);

		if (confirm_box(true)) {
			if (quitar_tecnica($user_id, $pj_id, $tec_id, $tec_coste, $msg_error)) {
				$moderacion = array(
					'PJ_ID'	=> $pj_id,
					'RAZON'	=> "Quitar '$tec_nombre' y devolver $tec_coste PA.",
				);
				registrar_moderacion($moderacion);

				trigger_error("Técnica quitada exitosamente." . $this->get_return_link($user_id));
			}
			else {
				trigger_error($msg_error . $this->get_return_link($user_id));
			}
		}
		else {
			$s_hidden_fields = build_hidden_fields(array(
				'submit' 	=> true,
				'tecnica_id'	=> $tec_id
			));

			confirm_box(false, "¿Deseas quitar la técnica '$tec_nombre' y devolver $tec_coste PA a su personaje?", $s_hidden_fields);
		}

		return $this->view($user_id);
	}

	function enable ($user_id) {
		$grupo = $this->user->data['group_id'];

        if ($grupo != 5 && $grupo != 4 && $grupo != 18) {
            trigger_error("No eres moderador o administrador." . $this->get_return_link($user_id));
		}

		$pj_id = get_pj_id($user_id);
		if (!$pj_id) trigger_error('No se encontró el personaje.' . $this->get_return_link($user_id));

		$this->db->sql_query("UPDATE ".PERSONAJES_TABLE." SET activo = 1 WHERE pj_id = $pj_id");

		$moderacion = array(
			'PJ_ID'	=> $pj_id,
			'RAZON'	=> "Personaje Activo.",
		);
		registrar_moderacion($moderacion);

		return $this->view($user_id);
	}

	function disable ($user_id) {
		$grupo = $this->user->data['group_id'];

        if ($grupo != 5 && $grupo != 4 && $grupo != 18) {
            trigger_error("No eres moderador o administrador." . $this->get_return_link($user_id));
		}

		$pj_id = get_pj_id($user_id);
		if (!$pj_id) trigger_error('No se encontró el personaje.' . $this->get_return_link($user_id));

		$this->db->sql_query("UPDATE ".PERSONAJES_TABLE." SET activo = 0 WHERE pj_id = $pj_id");

		$moderacion = array(
			'PJ_ID'	=> $pj_id,
			'RAZON'	=> "Personaje Inactivo.",
		);
		registrar_moderacion($moderacion);

		return $this->view($user_id);
	}

	function get_return_link($user_id) {
		return "<br /><a href='/ficha/$user_id'>Volver a la ficha</a>.";
	}
}
