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
        trigger_error("Personaje creado correctamente.");
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
	
    function borrar_personaje($pj) {

    global $db;

    $db->sql_query("DELETE FROM personajes WHERE user_id = '$pj'");
    $db->sql_query("DELETE FROM tecnicas WHERE pj_id = '$pj'");
    $db->sql_query("DELETE FROM moderaciones WHERE pj_moderado = '$pj'");
    }
}