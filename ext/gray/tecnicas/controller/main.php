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

	function get_rama_nombre($rama_id = 0)
	{
		$query = $this->db->sql_query("SELECT nombre FROM " . RAMAS_TABLE .
							($rama_id ? " WHERE rama_id = $rama_id" : ""));
		if ($row = $this->db->sql_fetchrow($query)) {
			return $row['nombre'];
		}
		$this->db->sql_freeresult($query);

		return false;
	}

	function get_rama_options($rama_id = 0)
	{
		$options = "";

		$query = $this->db->sql_query("SELECT rama_id, nombre FROM " . RAMAS_TABLE .
										" WHERE principal = 1 " .
							($rama_id ? " AND rama_id <> $rama_id" : ""));

		while ($row = $this->db->sql_fetchrow($query)){
			$options .= "<option value='" . $row['rama_id'] . "'>" . $row['nombre'] . "</option>";
		}
		$this->db->sql_freeresult($query);

		return $options;
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
