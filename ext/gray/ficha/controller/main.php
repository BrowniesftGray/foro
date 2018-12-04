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
        if ($this->user->data['user_id'] == ANONYMOUS) {
            trigger_error('No puedes acceder aquí sin conectarte.');
        }
        else{
            $this->template->assign_var('DEMO_MESSAGE', $this->user->data['group_id']);
            return $this->helper->render('ficha_body.html', 'Creación de Ficha');
        }
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
            'NOMBRE'            => utf8_normalize_nfc(request_var('nombre', '', true)),
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
        return $this->helper->render('ficha_message.html');
    }
}