<?php

namespace gray\ficha\controller;
require_once('/home/shinobil/public_html/includes/functions_ficha.php');

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

        $this->template->assign_var('RAMAS_PRINCIPALES', get_ramas_select(1, false, null));
        $this->template->assign_var('RAMAS_SECUNDARIAS', get_ramas_select(0, false, null));
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
		
		$sql_array = array(
			'user_id'	=> $idUsuario,
			'nivel'		=> 1,
			'rango'		=> 'Estudiante',
			'arquetipo_id'	=> 0,
			'nombre'	=> $fields['NOMBRE'],
			'edad'		=> $fields['EDAD'],
			'clan'		=> $fields['PRINCIPAL'],
			'rama1'		=> $fields['RAMA1'],
			'rama2'		=> $fields['RAMA2'],
			'rama3'		=> '',
			'rama4'		=> '',
			'rama5'		=> '',
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
		);

        $sql = "INSERT INTO personajes " . $this->db->sql_build_array('INSERT', $sql_array);
        $this->db->sql_query($sql);

        $this->template->assign_var('DEMO_MESSAGE', request_var('name', '', true));
        trigger_error("Personaje creado correctamente." . $this->get_return_link($idUsuario));
    }
	
    function view($user_id)
    {   
        get_ficha($user_id,$return = false, $ver = true);
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
        if ($grupo == 5 || $grupo == 4) {
            get_ficha($user_id,$return = false, $ver = false);
            $this->template->assign_vars(array(
                'U_ACTION'              => append_sid('/ficha/storeMod/' . $user_id),
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
        $grupo = $this->user->data['group_id'];

        if ($grupo == 5 || $grupo == 4) {
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
                    'NOMBRE'                => utf8_normalize_nfc(request_var('name', '', true)),
                    'PJ_ID'                 => utf8_normalize_nfc(request_var('pj_id', '', true)),
                    'EDAD'                  => utf8_normalize_nfc(request_var('edad', '', true)),
                    'RANGO'                 => utf8_normalize_nfc(request_var('rango', '', true)),
                    'ARQUETIPO'         => utf8_normalize_nfc(request_var('arquetipo', '', true)),
                    'PRINCIPAL'         => utf8_normalize_nfc(request_var('ramaPrincipal', '', true)),
                    'RAMA1'                 => utf8_normalize_nfc(request_var('ramaSec1', '', true)),
                    'RAMA2'                 => utf8_normalize_nfc(request_var('ramaSec2', '', true)),
                    'RAMA3'                 => utf8_normalize_nfc(request_var('ramaSec3', '', true)),
                    'RAMA4'                 => utf8_normalize_nfc(request_var('ramaSec4', '', true)),
                    'RAMA5'                 => utf8_normalize_nfc(request_var('ramaSec5', '', true)),
                    'FISICO'                => utf8_normalize_nfc(request_var('descFis', '', true)),
                    'CARACTER'          => utf8_normalize_nfc(request_var('descPsic', '', true)),
                    'HISTORIA'          => utf8_normalize_nfc(request_var('descHis', '', true)),
                    'TEC_JUTSUS'        => utf8_normalize_nfc(request_var('tecnicas', '', true)),
                    'RAZON'                 => utf8_normalize_nfc(request_var('razon', '', true)),
                ), $atrs);

            $fields['HISTORIA'] = addslashes($fields['HISTORIA']);
            $fields['FISICO'] = addslashes($fields['FISICO']);
            $fields['CARACTER'] = addslashes($fields['CARACTER']);
            $idUsuario = $this->user->data['user_id'];
			
			$sql_array = array(
				'rango'		=> $fields['RANGO'],
				'nombre'	=> $fields['NOMBRE'],
				'edad'		=> $fields['EDAD'],
				'clan'		=> $fields['PRINCIPAL'],
				'rama1'		=> $fields['RAMA1'],
				'rama2'		=> $fields['RAMA2'],
				'rama3'		=> $fields['RAMA3'],
				'rama4'		=> $fields['RAMA4'],
				'rama5'		=> $fields['RAMA5'],
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

            $sql = "UPDATE personajes SET "
						. $this->db->sql_build_array('UPDATE', $sql_array) .
						" WHERE user_id = $user_id";
            $this->db->sql_query($sql);
			
            registrar_moderacion($fields);

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
				if (comprar_habilidad($user_id, $hab_id, $hab_coste, $msg_error)) {
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
			'RAMA1'			=> utf8_normalize_nfc(request_var('ramaSec1', '', true)),
			'RAMA2'			=> utf8_normalize_nfc(request_var('ramaSec2', '', true)),
			'RAMA3'			=> utf8_normalize_nfc(request_var('ramaSec3', '', true)),
			'RAMA4'			=> utf8_normalize_nfc(request_var('ramaSec4', '', true)),
			'RAMA5'			=> utf8_normalize_nfc(request_var('ramaSec5', '', true)),
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
					
			$attr_disp = (int) $pj_data['PJ_ATTR_DISP'];
			if ($diff > $attr_disp) {
				trigger_error("No puedes repartir más de $attr_disp puntos." . $this->get_return_link($user_id));
			}
			
			if ($lvlup_data['FUERZA'] < $pj_data['PJ_FUE']) 
				trigger_error("La Fuerza ingresada es incorrecta." . $this->get_return_link($user_id));
			
			if ($lvlup_data['AGILIDAD'] < $pj_data['PJ_AGI']) 
				trigger_error("La Agilidad ingresada es incorrecta." . $this->get_return_link($user_id));
			
			if ($lvlup_data['VITALIDAD'] < $pj_data['PJ_VIT']) 
				trigger_error("La Vitalidad ingresada es incorrecta." . $this->get_return_link($user_id));
			
			if ($lvlup_data['CCK'] < $pj_data['PJ_CCK']) 
				trigger_error("El Control de Chakra ingresada es incorrecto." . $this->get_return_link($user_id));
			
			if ($lvlup_data['CONCENTRACION'] < $pj_data['PJ_CON']) 
				trigger_error("La Concentración ingresada es incorrecta." . $this->get_return_link($user_id));
			
			if ($lvlup_data['VOLUNTAD'] < $pj_data['PJ_VOL']) 
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
		
		if ($lvlup_data['RAMA1'] != '')
			$sql_array['rama1'] = $lvlup_data['RAMA1'];
		
		if ($lvlup_data['RAMA2'] != '')
			$sql_array['rama2'] = $lvlup_data['RAMA2'];
		
		if ($lvlup_data['RAMA3'] != '')
			$sql_array['rama3'] = $lvlup_data['RAMA3'];
		
		if ($lvlup_data['RAMA4'] != '')
			$sql_array['rama4'] = $lvlup_data['RAMA4'];
		
		if ($lvlup_data['RAMA5'] != '')
			$sql_array['rama5'] = $lvlup_data['RAMA5'];
		
		try {
			$this->db->sql_query("UPDATE personajes SET " 
									. $this->db->sql_build_array('UPDATE', $sql_array)
									. " WHERE user_id = $user_id");
			
			$moderacion = array(
				'PJ_ID'	=> $pj_id,
				'RAZON'	=> 'Modificación por Usuario',
			);
			registrar_moderacion($moderacion);
			
			trigger_error("Ficha actualizada exitosamente." . $this->get_return_link($user_id));
		} catch (Exception $e) {
			trigger_error("Ocurrió un error al actualizar la ficha; contacta a Administración.<br>" 
							. $e->getMessage()
							. $this->get_return_link($user_id));
		}
		
	}
	
	function get_return_link($user_id) {
		return "<br /><a href='/ficha/$user_id'>Volver a la ficha</a>.";
	}
}