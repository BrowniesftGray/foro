<?php

namespace gray\tecnicas\controller;
require_once('/home/shinobil/public_html/includes/functions_user.php');

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
        $this->validate_access();

		      return $this->helper->render('tecnicas/home.html', 'Administrador de SL');
    }

    function createTecnicas()
    {
        $this->validate_access();

		      return $this->helper->render('tecnicas/create.html', 'Administrador de SL');
    }

    function crearBbcode($codigo, $etiqueta)
    {
      /*
        Campos de la tabla
          'bbcode_tag': $etiqueta
          'bbcode_match': [$etiqueta][/$etiqueta]
          'bbcode_tpl': $codigo
          'first_pass_match': !\[$etiqueta\]\[/$etiqueta\]!i
          'first_pass_replace': [$etiqueta:$uid][/$etiqueta:$uid]
          'second_pass_replace': [$etiqueta:$uid][/$etiqueta:$uid]
      */

      $sql_array = array(
        'bbcode_tag': $etiqueta
        'bbcode_match': "[".$etiqueta."][/".$etiqueta"]",
        'bbcode_tpl': $codigo,
        'first_pass_match': "!\[".$etiqueta."\]\[/".$etiqueta."\]!i",
        'first_pass_replace': "[".$etiqueta.":$uid][/".$etiqueta.":$uid]",
        'second_pass_replace': "[".$etiqueta.":$uid][/".$etiqueta.":$uid]"
      );

      $sql = "INSERT INTO ". BBCODE_TECNICAS . $this->db->sql_build_array('INSERT', $sql_array);
      $this->db->sql_query($sql);

    }

  	function validate_access()
  	{
  		$grupo = $this->user->data['group_id'];
          if ($grupo != 5) {
              trigger_error("No puedes acceder a esta sección.");
          }
  	}

  	function get_return_link($view)
  	{
  		return "<br /><a href='/tecnicas/$view'>Volver</a>.";
  	}
}
