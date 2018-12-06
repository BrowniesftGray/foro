<?php

namespace gray\ficha\controller;

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

        $this->template->assign_var('DEMO_MESSAGE', $this->user->data['group_id']);
        return $this->helper->render('ficha_body.html', 'Creación de Ficha');
    }
	
	public function store()
    {   
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
            'PRINCIPAL'         => utf8_normalize_nfc(request_var('ramaPrincipal', '', true)),
            'RAMA1'             => utf8_normalize_nfc(request_var('ramaSec1', '', true)),
            'RAMA2'             => utf8_normalize_nfc(request_var('ramaSec2', '', true)),
            'FISICO'            => utf8_normalize_nfc(request_var('descFis', '', true)),
            'CARACTER'          => utf8_normalize_nfc(request_var('descPsic', '', true)),
            'HISTORIA'          => utf8_normalize_nfc(request_var('descHis', '', true)),
        ), $atrs);

        $fields['HISTORIA'] = addslashes($fields['HISTORIA']);
        $fields['FISICO'] = addslashes($fields['FISICO']);
        $fields['CARACTER'] = addslashes($fields['CARACTER']);
        $idUsuario = $this->user->data['user_id'];

        $sql = "INSERT INTO personajes (user_id, nivel, rango, arquetipo_id, nombre, edad, clan, rama1, rama2, rama3, rama4, rama5, tecnicas, fuerza, vitalidad, agilidad, cck, concentracion, voluntad, fisico, psicologico, historia)";
        $sql .= "values (   $idUsuario, '1', 'Estudiante', '0', '{$fields['NOMBRE']}', '{$fields['EDAD']}',";
        $sql .="'{$fields['PRINCIPAL']}', '{$fields['RAMA1']}', '{$fields['RAMA2']}', 'No seleccionada', 'No seleccionada', 'No seleccionada', '', '{$fields['FUERZA']}', '{$fields['RESISTENCIA']}', '{$fields['AGILIDAD']}', '{$fields['ESPIRITU']}', '{$fields['CONCENTRACION']}', '{$fields['VOLUNTAD']}', '{$fields['FISICO']}', '{$fields['CARACTER']}', '{$fields['HISTORIA']}')";
        $this->db->sql_query($sql);

        $this->template->assign_var('DEMO_MESSAGE', request_var('name', '', true));
        trigger_error("Personaje creado correctamente.");
    }

    function ficha_exists($user_id)
    {
        $query = $this->db->sql_query('SELECT pj_id FROM personajes WHERE user_id='.$user_id.'');
        if ($row = $this->db->sql_fetchrow($query)) {
            $this->db->sql_freeresult($query);
            return true;
        } else {
            return false;
        }
    }

    function view($user_id, $return = false, $ver = false)
    {
            $query = $this->db->sql_query("SELECT * FROM personajes WHERE user_id=".$user_id."");
            if ($row = $this->db->sql_fetchrow($query)) {
            $this->db->sql_freeresult($query);
            $pj_id = $row['pj_id'];
            //$puede_ver = ($auth->acl_get('m_modera_ficha') || $this->user->data['user_id'] == $pj) ? true : false;

            $queryTec = $this->db->sql_query("SELECT * FROM tecnicas WHERE pj_id=".$pj_id."");
            $row2 = $this->db->sql_fetchrow($queryTec);
            $this->db->sql_freeresult($queryTec);

            $queryModeraciones = $this->db->sql_query("SELECT * FROM moderaciones WHERE pj_moderado=".$pj_id."");

            while ($row3 = $this->db->sql_fetchrow($queryModeraciones))
            {
                $this->template->assign_block_vars('moderaciones', array(
                        'RAZON_MODERACION' => $row3['razon'],
                        'USER_MODERACION' => $row3['moderador'],
                        'FECHA_MODERACION' => $row3['fecha'],
                ));
            }
            $this->db->sql_freeresult($queryModeraciones);
            
            $queryHab = $this->db->sql_query("SELECT h.* 
                                            FROM ".HABILIDADES_TABLE." h
                                                INNER JOIN ".PERSONAJE_HABILIDADES_TABLE." ph
                                                    ON ph.habilidad_id = h.habilidad_id
                                            WHERE ph.pj_id = '$pj_id'");
            while ($row4 = $this->db->sql_fetchrow($queryHab)) {
                $this->template->assign_block_vars('habilidades', array(
                        'ID'            => $row4['habilidad_id'],
                        'NOMBRE'        => $row4['nombre'],
                        'EFECTO'        => $row4['efecto'],
                        'URL_IMAGEN'    => $row4['url_imagen'],
                ));
                
                if ($row4['requisitos']) {
                    $requisitos = explode(';', $row4['requisitos']);
                    for ($i = 0; $i < count($requisitos); $i++) {
                        $hab_requisitos[] = array('REQUISITO' => $requisitos[$i]);
                    }
                    $this->template->assign_block_vars_array('habilidades.requisitos', $hab_requisitos);  
                }           
            }
            $this->db->sql_freeresult($queryHab);
            
            $grupo = $this->user->data['group_id'];
            $borrar = $this->user->data['user_id'];
				
			$moderador = ($grupo == 5 || $grupo == 4);
			$borrarPersonaje = ($borrar == $user_id);

            $this->user->get_profile_fields($user_id);
            if (!array_key_exists('pf_experiencia', $this->user->profile_fields)) {
                $experiencia = 0;
            }
            else{
                $experiencia = $this->user->profile_fields['pf_experiencia'];
            }
            

			//Guarda el texto de tal forma que al usar generate_text_for_display muestre correctamente los bbcodes
			$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
			$allow_bbcode = $allow_urls = $allow_smilies = true;
			generate_text_for_storage($row['tecnicas'], $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
			$jutsus = generate_text_for_display($row['tecnicas'], $uid, $bitfield, $options);

            $this->template->assign_vars(array(
                'NIVEL' => $row['nivel'],
                'PUEDE_BORRAR' => $borrarPersonaje,
                'PUEDE_MODERAR' => $moderador,
                'FICHA_RANGO' => $row['rango'],
                'VISTA_ARQUETIPO' => vista_arquetipo ($row['arquetipo_id']),
                'ID_ARQUETIPO' => $row['arquetipo_id'],
                'FICHA_NOMBRE' => stripslashes($row['nombre']),
                'FICHA_ID' => $pj_id,
                'FICHA_EDAD' => $row['edad'],
                'FICHA_CLAN' => $row['clan'],
                'TECNICAS_CLAN' => $row2['clan'],
                'FICHA_RAMA1' => stripslashes($row['rama1']),
                'FICHA_RAMA2' => stripslashes($row['rama2']),
                'FICHA_RAMA3' => stripslashes($row['rama3']),
                'FICHA_RAMA4' => stripslashes($row['rama4']),
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
                'FICHA_JUTSUS'          => $jutsus,
                'FICHA_PC'              => calcula_pc($row),
                'FICHA_PV'              => calcula_pv($row),
                'FICHA_STA'             => calcula_sta($row),
                'FICHA_URL'             => append_sid("/ficha/". $user_id),
                'FICHA_MODERACIONES'    => append_sid("/ficha.php", 'mode=moderar&pj=' . $user_id),
                'FICHA_BORRAR_2'    => append_sid("/ficha/delete/". $user_id),
            ));            

        } else {
            if ($return) {
                return false;
            }

            $this->template->assign_vars(array(
                'FICHA_EXISTS'          => false,
            ));
           
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
                trigger_error('Personaje borrado correctamente.');
            }
            else
            {
                $s_hidden_fields = build_hidden_fields(array('submit' => true));
                confirm_box(false, '¿Estás seguro de que quieres borrar tu personaje? Él no lo haría.', $s_hidden_fields);
            }
        }
        else{
            trigger_error("No puede borrar un personaje de otro usuario.");
        }
        return $this->helper->render('ficha_delete.html');
    }

    function vista_arquetipo ($arquetipo){
        if ($arquetipo != 0) {
            $query = $this->db->sql_query("SELECT * FROM arquetipos WHERE arquetipo_id=".$arquetipo."");
            $row = $this->db->sql_fetchrow($query);
            $this->db->sql_freeresult($query);
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

    function borrar_personaje($pj) {

    global $db;

    $db->sql_query("DELETE FROM personajes WHERE user_id = '$pj'");
    $db->sql_query("DELETE FROM tecnicas WHERE pj_id = '$pj'");
    $db->sql_query("DELETE FROM moderaciones WHERE pj_moderado = '$pj'");
    }
}