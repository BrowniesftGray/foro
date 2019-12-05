<?php

namespace akira\sladmin\controller;
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
		
		return $this->helper->render('sladmin/home.html', 'Administrador de SL');
    }
	
	/*------------------------
	--        ALDEAS        --
	--------------------------*/
	
	function aldeas_view() 
	{
		$this->validate_access();
		
		$query = $this->db->sql_query("SELECT * FROM ". ALDEAS_TABLE);
		while ($row = $this->db->sql_fetchrow($query)){
			$aldeas[] = array(
				'ALDEA_ID'	=> $row['aldea_id'],
				'NOMBRE'	=> $row['nombre'],
				'GROUP_ID'	=> $row['group_id'],
				'GROUP_NAME'	=> $this->get_group_name($row['group_id']),
				'GROUP_OPTIONS'	=> $this->get_group_options($row['group_id']),
				'CUPO'	=> $row['cupo'],
				'RAMA_ID_DEFAULT'	=> $row['rama_id_default'],
				'RAMA_NOMBRE'	=> $this->get_rama_nombre($row['rama_id_default']),
				'RAMA_OPTIONS'	=> $this->get_rama_options($row['rama_id_default']),
				'NIVEL_INICIAL'	=> $row['nivel_inicial'],
				'ORDEN'	=> $row['orden'],
				'VISIBLE'	=> $row['visible'],
				'ACTIVO'	=> $row['activo'],
				'U_ACTION_UPD'	=> "/sladmin/aldeas/upd/" . $row['aldea_id'],
				'U_ACTION_DEL'	=> "/sladmin/aldeas/del/" . $row['aldea_id'],
			);
		}
		$this->db->sql_freeresult($query);
		
		$this->template->assign_block_vars_array('aldeas', $aldeas);
		$this->template->assign_vars(array(
			'GROUP_OPTIONS'	=> $this->get_group_options(),
			'RAMA_OPTIONS'	=> $this->get_rama_options(),
			'U_ACTION_INS'	=> "/sladmin/aldeas/ins",
		));
		
		return $this->helper->render('sladmin/aldeas.html', 'Administrador de SL - Aldeas');
	}
	
	function aldeas_ins()
	{
		$this->validate_access();
		
		$sql_array = array(
			'nombre'	=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'group_id'	=> (int) request_var('group_id', 0),
			'cupo'		=> (int) request_var('cupo', 0),
			'rama_id_default'	=> (int) request_var('rama_id_default', 0),
			'nivel_inicial'	=> (int) request_var('nivel_inicial', 1),
			'orden'		=> (int) request_var('orden', 99),
			'visible'	=> (bool) request_var('visible', false),
			'activo'	=> (bool) request_var('activo', false),
		);
		
		$this->db->sql_query('INSERT INTO ' . ALDEAS_TABLE . $this->db->sql_build_array('INSERT', $sql_array));
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error agregando la aldea.' . $this->get_return_link('aldeas'));
		}
		
		trigger_error('Aldea agregada exitosamente.' . $this->get_return_link('aldeas'));
	}
	
	function aldeas_upd($aldea_id) 
	{
		$this->validate_access();
		
		$sql_array = array(
			'nombre'	=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'group_id'	=> (int) request_var('group_id', 0),
			'cupo'		=> (int) request_var('cupo', 0),
			'rama_id_default'	=> (int) request_var('rama_id_default', 0),
			'nivel_inicial'	=> (int) request_var('nivel_inicial', 1),
			'orden'		=> (int) request_var('orden', 99),
			'visible'	=> (bool) request_var('visible', false),
			'activo'	=> (bool) request_var('activo', false),
		);
		
		$this->db->sql_query('UPDATE ' . ALDEAS_TABLE . ' SET ' .
					$this->db->sql_build_array('UPDATE', $sql_array) .
					" WHERE aldea_id = $aldea_id");
					
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error modificando la aldea.' . $this->get_return_link('aldeas'));
		}
		
		trigger_error('Aldea actualizada exitosamente.' . $this->get_return_link('aldeas'));
	}
	
	function aldeas_del($aldea_id)
	{
		$this->validate_access();
		
		$val_query = $this->db->sql_query("SELECT COUNT(0) AS cantidad FROM " . PERSONAJES_TABLE . " WHERE aldea_id = $aldea_id");
		if ($row = $this->db->sql_fetchrow($val_query)) {
			$pjs = (int) $row['cantidad'];
			if ($pjs > 0)
				trigger_error("No se puede eliminar la aldea porque existen $pjs personajes en la misma.");
		}
		$this->db->sql_freeresult($val_query);
		
		$query = $this->db->sql_query("SELECT nombre FROM " . ALDEAS_TABLE . " WHERE aldea_id = $aldea_id");
		if ($row = $this->db->sql_fetchrow($query)) {
			$nombre = $row['nombre'];
		}
		
		if (confirm_box(true))
		{
			$this->db->sql_query("DELETE FROM " . ALDEAS_TABLE . " WHERE aldea_id = $aldea_id");
		
			if ((int) $this->db->sql_affectedrows() < 1) {
				trigger_error('Hubo un error eliminando la aldea.' . $this->get_return_link('aldeas'));
			}
		
			trigger_error('Aldea eliminada exitosamente.' . $this->get_return_link('aldeas'));
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array('submit' => true));
			confirm_box(false, "¿Desea borrar la aldea '$nombre'?", $s_hidden_fields);
		}
		
		trigger_error('Acción cancelada.' . $this->get_return_link('aldeas'));
	}
	
	/*------------------------
	--        NIVELES       --
	--------------------------*/
	
	function niveles_view() 
	{
		$this->validate_access();
		
		$query_max = $this->db->sql_query("SELECT 	max(nivel) AS nivel,
													max(experiencia) AS experiencia,
													max(atributos) AS atributos
												FROM " . NIVELES_TABLE);
		if ($row = $this->db->sql_fetchrow($query_max)) {
			$max_nivel	= (int) $row['nivel'];
			$max_exp	= (int) $row['experiencia'];
			$max_attr	= (int) $row['atributos'];
		}
		$this->db->sql_freeresult($query_max);
		
		$query = $this->db->sql_query("SELECT * FROM ". NIVELES_TABLE);
		while ($row = $this->db->sql_fetchrow($query)){
			$niveles[] = array(
				'NIVEL'	=> (int) $row['nivel'],
				'EXPERIENCIA'	=> (int) $row['experiencia'],
				'ATRIBUTOS'	=> (int) $row['atributos'],
				'ES_ULTIMO'	=> ($row['nivel'] == $max_nivel),
				'U_ACTION_UPD'	=> "/sladmin/niveles/upd/" . $row['nivel'],
				'U_ACTION_DEL'	=> "/sladmin/niveles/del/" . $row['nivel'],
			);
		}
		$this->db->sql_freeresult($query);
		
		$this->template->assign_block_vars_array('niveles', $niveles);
		$this->template->assign_vars(array(
			'PROXIMO_NIVEL'	=> $max_nivel + 1,
			'MIN_EXPERIENCIA'	=> $max_exp + 1,
			'MIN_ATRIBUTOS'	=> $max_attr + 1,
			'U_ACTION_INS'	=> "/sladmin/niveles/ins",
		));
		
		return $this->helper->render('sladmin/niveles.html', 'Administrador de SL - Niveles');
	}
	
	function niveles_ins()
	{
		$this->validate_access();
		
		$sql_array = array(
			'nivel'	=> request_var('nivel', 0),
			'experiencia'	=> (int) request_var('experiencia', 0),
			'atributos'		=> (int) request_var('atributos', 0),
		);
		
		$this->db->sql_query('INSERT INTO ' . NIVELES_TABLE . $this->db->sql_build_array('INSERT', $sql_array));
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error agregando el nivel.' . $this->get_return_link('niveles'));
		}
		
		trigger_error('Nivel agregado exitosamente.' . $this->get_return_link('niveles'));
	}
	
	function niveles_upd($nivel) 
	{
		$this->validate_access();
		
		$sql_array = array(
			'experiencia'	=> (int) request_var('experiencia', 0),
			'atributos'		=> (int) request_var('atributos', 0),
		);
		
		$this->db->sql_query('UPDATE ' . NIVELES_TABLE . ' SET ' .
					$this->db->sql_build_array('UPDATE', $sql_array) .
					" WHERE nivel = $nivel");
					
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error modificando el nivel.' . $this->get_return_link('niveles'));
		}
		
		trigger_error('Nivel actualizado exitosamente.' . $this->get_return_link('niveles'));
	}
	
	function niveles_del($nivel)
	{
		$this->validate_access();
		
		$val_query = $this->db->sql_query("SELECT COUNT(0) AS cantidad FROM " . PERSONAJES_TABLE . " WHERE nivel >= $nivel");
		if ($row = $this->db->sql_fetchrow($val_query)) {
			$pjs = (int) $row['cantidad'];
			if ($pjs > 0)
				trigger_error("No se puede eliminar el nivel porque existen $pjs personajes que lo han alcanzado.");
		}
		$this->db->sql_freeresult($val_query);
		
		if (confirm_box(true))
		{
			$this->db->sql_query("DELETE FROM " . NIVELES_TABLE . " WHERE nivel = $nivel");
		
			if ((int) $this->db->sql_affectedrows() < 1) {
				trigger_error('Hubo un error eliminando el nivel.' . $this->get_return_link('niveles'));
			}
		
			trigger_error('Nivel eliminado exitosamente.' . $this->get_return_link('niveles'));
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array('submit' => true));
			confirm_box(false, "¿Desea borrar el nivel $nivel?", $s_hidden_fields);
		}
		
		trigger_error('Acción cancelada.' . $this->get_return_link('niveles'));
	}
	
	/*------------------------
	--      FUNCIONES       --
	--------------------------*/
	
	function get_group_name($group_id = 0)
	{
		$query = $this->db->sql_query("SELECT group_name FROM " . GROUPS_TABLE . 
						($group_id ? " WHERE group_id = $group_id" : ""));
		if ($row = $this->db->sql_fetchrow($query)) {
			return $row['group_name'];
		}
		$this->db->sql_freeresult($query);
		
		return false;
	}
	
	function get_group_options($group_id = 0)
	{
		$options = "";
		
		$query = $this->db->sql_query("SELECT group_id, group_name FROM " . GROUPS_TABLE . 
										" WHERE group_type = 0" . 
							($group_id ? " AND group_id <> $group_id" : ""));
		
		while ($row = $this->db->sql_fetchrow($query)){
			$options .= "<option value='" . $row['group_id'] . "'>" . $row['group_name'] . "</option>";
		}
		$this->db->sql_freeresult($query);
		
		return $options;
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
		return "<br /><a href='/sladmin/$view'>Volver</a>.";
	}
}
