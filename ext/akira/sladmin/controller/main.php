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
	
	function aldeas_view() {
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
				'RYOS'	=> $row['ryos'],
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
	
	function aldeas_ins() {
		$this->validate_access();
		
		$sql_array = array(
			'nombre'	=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'group_id'	=> (int) request_var('group_id', 0),
			'cupo'		=> (int) request_var('cupo', 0),
			'rama_id_default'	=> (int) request_var('rama_id_default', 0),
			'nivel_inicial'	=> (int) request_var('nivel_inicial', 1),
			'ryos'		=> (int) request_var('ryos', 0),
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
	
	function aldeas_upd($aldea_id) {
		$this->validate_access();
		
		$sql_array = array(
			'nombre'	=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'group_id'	=> (int) request_var('group_id', 0),
			'cupo'		=> (int) request_var('cupo', 0),
			'rama_id_default'	=> (int) request_var('rama_id_default', 0),
			'nivel_inicial'	=> (int) request_var('nivel_inicial', 1),
			'ryos'		=> (int) request_var('ryos', 0),
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
	
	function aldeas_del($aldea_id) {
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
		$this->db->sql_freeresult($query);		
		
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
	
	function niveles_view() {
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
	
	function niveles_ins() {
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
	
	function niveles_upd($nivel) {
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
	
	function niveles_del($nivel) {
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
	--         RAMAS        --
	--------------------------*/
	
	function ramas_view() {
		$this->validate_access();
		
		$query = $this->db->sql_query("SELECT * FROM ". RAMAS_TABLE);
		while ($row = $this->db->sql_fetchrow($query)){
			$ramas[] = array(
				'RAMA_ID'	=> (int) $row['rama_id'],
				'NOMBRE'	=> $row['nombre'],
				'ALDEA'	=> $row['aldea'],
				'PRINCIPAL'	=> $row['principal'],
				'PRIMERO'	=> $row['primero'],
				'RAMA_ID_REQ1'	=> $row['rama_id_req1'],
				'RAMA1_NOMBRE'	=> $this->get_rama_nombre($row['rama_id_req1']),
				'RAMA1_OPTIONS'	=> $this->get_rama_options($row['rama_id_req1'], false),
				'RAMA_ID_REQ2'	=> $row['rama_id_req2'],
				'RAMA2_NOMBRE'	=> $this->get_rama_nombre($row['rama_id_req2']),
				'RAMA2_OPTIONS'	=> $this->get_rama_options($row['rama_id_req2'], false),
				'VISIBLE'	=> $row['visible'],
				'PREFIJO'	=> $row['prefijo'],
				'U_ACTION_UPD'	=> "/sladmin/ramas/upd/" . $row['rama_id'],
				'U_ACTION_DEL'	=> "/sladmin/ramas/del/" . $row['rama_id'],
			);
		}
		$this->db->sql_freeresult($query);
		
		$this->template->assign_block_vars_array('ramas', $ramas);
		$this->template->assign_vars(array(
			'RAMA_OPTIONS'	=> $this->get_rama_options(0, false),
			'U_ACTION_INS'	=> "/sladmin/ramas/ins",
		));
		
		return $this->helper->render('sladmin/ramas.html', 'Administrador de SL - Ramas');
	}
	
	function ramas_ins() {
		$this->validate_access();
		
		$sql_array = array(
			'nombre'	=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'aldea'		=> utf8_normalize_nfc(request_var('aldea', '', true)),
			'principal'	=> (bool) request_var('principal', false),
			'primero'	=> (bool) request_var('primero', false),
			'rama_id_req1'	=> (int) request_var('rama_id_req1', 0),
			'rama_id_req2'	=> (int) request_var('rama_id_req2', 0),
			'visible'	=> (bool) request_var('visible', false),
			'prefijo'	=> utf8_normalize_nfc(request_var('prefijo', '', true)),
		);
		
		$this->db->sql_query('INSERT INTO ' . RAMAS_TABLE . $this->db->sql_build_array('INSERT', $sql_array));
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error agregando la rama.' . $this->get_return_link('ramas'));
		}
		
		trigger_error('Rama agregada exitosamente.' . $this->get_return_link('ramas'));
	}
	
	function ramas_upd($rama_id) {
		$this->validate_access();
		
		$sql_array = array(
			'nombre'	=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'aldea'		=> utf8_normalize_nfc(request_var('aldea', '', true)),
			'principal'	=> (bool) request_var('principal', false),
			'primero'	=> (bool) request_var('primero', false),
			'rama_id_req1'	=> (int) request_var('rama_id_req1', 0),
			'rama_id_req2'	=> (int) request_var('rama_id_req2', 0),
			'visible'	=> (bool) request_var('visible', false),
			'prefijo'	=> utf8_normalize_nfc(request_var('prefijo', '', true)),
		);
		
		$this->db->sql_query('UPDATE ' . RAMAS_TABLE . ' SET ' .
					$this->db->sql_build_array('UPDATE', $sql_array) .
					" WHERE rama_id = $rama_id");
					
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error modificando la rama.' . $this->get_return_link('ramas'));
		}
		
		trigger_error('Rama actualizada exitosamente.' . $this->get_return_link('ramas'));
	}
	
	function ramas_del($rama_id) {
		$this->validate_access();
		
		$val_query = $this->db->sql_query("SELECT COUNT(0) AS cantidad FROM " . PERSONAJES_TABLE . " WHERE $rama_id IN (rama_id_pri, rama_id1, rama_id2, rama_id3, rama_id4, rama_id5)");
		if ($row = $this->db->sql_fetchrow($val_query)) {
			$pjs = (int) $row['cantidad'];
			if ($pjs > 0)
				trigger_error("No se puede eliminar la rama porque existen $pjs personajes que la poseen.");
		}
		$this->db->sql_freeresult($val_query);
		
		$query = $this->db->sql_query("SELECT nombre FROM " . RAMAS_TABLE . " WHERE rama_id = $rama_id");
		if ($row = $this->db->sql_fetchrow($query)) {
			$nombre = $row['nombre'];
		}
		$this->db->sql_freeresult($query);
		
		if (confirm_box(true))
		{
			$this->db->sql_query("DELETE FROM " . RAMAS_TABLE . " WHERE rama_id = $rama_id");
		
			if ((int) $this->db->sql_affectedrows() < 1) {
				trigger_error('Hubo un error eliminando la rama.' . $this->get_return_link('ramas'));
			}
		
			trigger_error('Rama eliminada exitosamente.' . $this->get_return_link('ramas'));
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array('submit' => true));
			confirm_box(false, "¿Desea borrar la rama '$nombre'?", $s_hidden_fields);
		}
		
		trigger_error('Acción cancelada.' . $this->get_return_link('ramas'));
	}
	
	/*------------------------
	--       TECNICAS       --
	--------------------------*/
	
	function tecnicas_view() {
		$this->validate_access();
		
		$rama_id = (int) request_var('rama_filtro', 0);
		$rama_nombre = $this->get_rama_nombre($rama_id);
		$rama_options = $this->get_rama_options($rama_id, true) . $this->get_rama_options($rama_id, false);
		
		$query = $this->db->sql_query("SELECT * FROM ". TECNICAS_TABLE . " WHERE rama_id = $rama_id");
		while ($row = $this->db->sql_fetchrow($query)){
			$tecnicas[] = array(
				'TECNICA_ID'	=> (int) $row['tecnica_id'],
				'NOMBRE'	=> $row['nombre'],
				'RANGO'	=> $row['rango'],
				'RAMA_ID'	=> $row['rama_id'],
				'RAMA_NOMBRE'	=> $rama_nombre,
				'RAMA_OPTIONS'	=> $rama_options,
				'PJ_ID_INVENCION'	=> (int) $row['pj_id_invencion'],
				'ETIQUETA'	=> $row['etiqueta'],
				'COSTE'	=> (int) $row['coste'],
				'ATTR_FIS'	=> (int) $row['attr_fis'],
				'ATTR_ESP'	=> (int) $row['attr_esp'],
				'FUERZA'	=> (int) $row['fuerza'],
				'AGILIDAD'	=> (int) $row['agilidad'],
				'VITALIDAD'	=> (int) $row['vitalidad'],
				'CCK'	=> (int) $row['cck'],
				'CONCENTRACION'	=> (int) $row['concentracion'],
				'VOLUNTAD'	=> (int) $row['voluntad'],
				'U_ACTION_UPD'	=> "/sladmin/tecnicas/upd/" . $row['tecnica_id'],
				'U_ACTION_DEL'	=> "/sladmin/tecnicas/del/" . $row['tecnica_id'],
			);
		}
		$this->db->sql_freeresult($query);
		
		if (isset($tecnicas))
			$this->template->assign_block_vars_array('tecnicas', $tecnicas);
	
		$this->template->assign_vars(array(
			'RAMA_ID'		=> $rama_id,
			'RAMA_NOMBRE'	=> $rama_nombre,
			'RAMA_OPTIONS'	=> $rama_options,
			'U_ACTION_INS'	=> "/sladmin/tecnicas/ins",
			'U_ACTION_SEL'	=> "/sladmin/tecnicas",
		));
		
		return $this->helper->render('sladmin/tecnicas.html', 'Administrador de SL - Técnicas');
	}
	
	function tecnicas_ins() {
		$this->validate_access();
		
		$sql_array = array(
			'nombre'	=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'rango'		=> utf8_normalize_nfc(request_var('rango', '', true)),
			'rama_id'	=> (int) request_var('rama_id', 0),
			'pj_id_invencion'	=> (int) request_var('pj_id_invencion', 0),
			'etiqueta'	=> utf8_normalize_nfc(request_var('etiqueta', '', true)),
			'coste'	=> (int) request_var('coste', 0),
			'attr_fis'	=> (int) request_var('attr_fis', 0),
			'attr_esp'	=> (int) request_var('attr_esp', 0),
			'fuerza'	=> (int) request_var('fuerza', 0),
			'agilidad'	=> (int) request_var('agilidad', 0),
			'vitalidad'	=> (int) request_var('vitalidad', 0),
			'cck'	=> (int) request_var('cck', 0),
			'concentracion'	=> (int) request_var('concentracion', 0),
			'voluntad'	=> (int) request_var('voluntad', 0),
		);
		
		$rama_id = $sql_array['rama_id'];
		
		$this->db->sql_query('INSERT INTO ' . TECNICAS_TABLE . $this->db->sql_build_array('INSERT', $sql_array));
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error agregando la técnica.' . $this->get_return_link('tecnicas'));
		}
		
		trigger_error('Técnica agregada exitosamente.' . $this->get_return_link("tecnicas?rama_filtro=$rama_id"));
	}
	
	function tecnicas_upd($tecnica_id) {
		$this->validate_access();
		
		$sql_array = array(
			'nombre'	=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'rango'		=> utf8_normalize_nfc(request_var('rango', '', true)),
			'rama_id'	=> (int) request_var('rama_id', 0),
			'pj_id_invencion'	=> (int) request_var('pj_id_invencion', 0),
			'etiqueta'	=> utf8_normalize_nfc(request_var('etiqueta', '', true)),
			'coste'	=> (int) request_var('coste', 0),
			'attr_fis'	=> (int) request_var('attr_fis', 0),
			'attr_esp'	=> (int) request_var('attr_esp', 0),
			'fuerza'	=> (int) request_var('fuerza', 0),
			'agilidad'	=> (int) request_var('agilidad', 0),
			'vitalidad'	=> (int) request_var('vitalidad', 0),
			'cck'	=> (int) request_var('cck', 0),
			'concentracion'	=> (int) request_var('concentracion', 0),
			'voluntad'	=> (int) request_var('voluntad', 0),
		);
		
		$rama_id = $sql_array['rama_id'];
		
		$this->db->sql_query('UPDATE ' . TECNICAS_TABLE . ' SET ' .
					$this->db->sql_build_array('UPDATE', $sql_array) .
					" WHERE tecnica_id = $tecnica_id");
					
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error modificando la técnica.' . $this->get_return_link("tecnicas?rama_filtro=$rama_id"));
		}
		
		trigger_error('Técnica actualizada exitosamente.' . $this->get_return_link("tecnicas?rama_filtro=$rama_id"));
	}
	
	function tecnicas_del($tecnica_id) {
		$this->validate_access();
		
		$rama_id = (int) request_var('rama_id', 0);
		
		$val_query = $this->db->sql_query("SELECT COUNT(0) AS cantidad FROM " . PERSONAJE_TECNICAS_TABLE . " WHERE tecnica_id = $tecnica_id");
		if ($row = $this->db->sql_fetchrow($val_query)) {
			$pjs = (int) $row['cantidad'];
			if ($pjs > 0)
				trigger_error("No se puede eliminar la técnica porque existen $pjs personajes que la poseen.");
		}
		$this->db->sql_freeresult($val_query);
		
		$query = $this->db->sql_query("SELECT nombre FROM " . TECNICAS_TABLE . " WHERE tecnica_id = $tecnica_id");
		if ($row = $this->db->sql_fetchrow($query)) {
			$nombre = $row['nombre'];
		}
		$this->db->sql_freeresult($query);
		
		if (confirm_box(true))
		{
			$this->db->sql_query("DELETE FROM " . TECNICAS_TABLE . " WHERE tecnica_id = $tecnica_id");
		
			if ((int) $this->db->sql_affectedrows() < 1) {
				trigger_error('Hubo un error eliminando la técnica.' . $this->get_return_link("tecnicas?rama_filtro=$rama_id"));
			}
		
			trigger_error('Técnica eliminada exitosamente.' . $this->get_return_link("tecnicas?rama_filtro=$rama_id"));
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array('submit' => true));
			confirm_box(false, "¿Desea borrar la técnica '$nombre'?", $s_hidden_fields);
		}
		
		trigger_error('Acción cancelada.' . $this->get_return_link("tecnicas?rama_filtro=$rama_id"));
	}
	
	/*------------------------
	--        TIENDAS       --
	--------------------------*/
	
	function tiendas_view() {
		$this->validate_access();
		
		$query = $this->db->sql_query("SELECT * FROM ". SHOPS_TABLE);
		while ($row = $this->db->sql_fetchrow($query)){
			$tiendas[] = array(
				'SHOP_ID'	=> (int) $row['shop_id'],
				'NOMBRE'	=> $row['nombre'],
				'FA_ICON'	=> $row['fa_icon'],
				'DESCRIPCION'	=> $row['descripcion'],
				'VISIBLE'	=> $row['visible'],
				'U_ACTION_UPD'	=> "/sladmin/tiendas/upd/" . $row['shop_id'],
				'U_ACTION_DEL'	=> "/sladmin/tiendas/del/" . $row['shop_id'],
			);
		}
		$this->db->sql_freeresult($query);
		
		$this->template->assign_block_vars_array('tiendas', $tiendas);
		$this->template->assign_vars(array(
			'U_ACTION_INS'	=> "/sladmin/tiendas/ins",
		));
		
		return $this->helper->render('sladmin/tiendas.html', 'Administrador de SL - Tiendas');
	}
	
	function tiendas_ins() {
		$this->validate_access();
		
		$sql_array = array(
			'nombre'	=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'fa_icon'	=> utf8_normalize_nfc(request_var('fa_icon', '', true)),
			'descripcion'	=> utf8_normalize_nfc(request_var('descripcion', '', true)),
			'visible'	=> (bool) request_var('visible', false),
		);
		
		$this->db->sql_query('INSERT INTO ' . SHOPS_TABLE . $this->db->sql_build_array('INSERT', $sql_array));
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error agregando la tienda.' . $this->get_return_link('tiendas'));
		}
		
		trigger_error('Tienda agregada exitosamente.' . $this->get_return_link('tiendas'));
	}
	
	function tiendas_upd($shop_id) {
		$this->validate_access();
		
		$sql_array = array(
			'nombre'	=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'fa_icon'	=> utf8_normalize_nfc(request_var('fa_icon', '', true)),
			'descripcion'	=> utf8_normalize_nfc(request_var('descripcion', '', true)),
			'visible'	=> (bool) request_var('visible', false),
		);
		
		$this->db->sql_query('UPDATE ' . SHOPS_TABLE . ' SET ' .
					$this->db->sql_build_array('UPDATE', $sql_array) .
					" WHERE shop_id = $shop_id");
					
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error modificando la tienda.' . $this->get_return_link('tiendas'));
		}
		
		trigger_error('Tienda actualizada exitosamente.' . $this->get_return_link('tienda'));
	}
	
	function tiendas_del($shop_id) {
		$this->validate_access();
		
		$val_query = $this->db->sql_query("SELECT COUNT(0) AS cantidad FROM " . ITEMS_TABLE . " WHERE shop_id = $shop_id");
		if ($row = $this->db->sql_fetchrow($val_query)) {
			$items = (int) $row['cantidad'];
			if ($items > 0)
				trigger_error("No se puede eliminar la tienda porque existen $items items en la misma.");
		}
		$this->db->sql_freeresult($val_query);
		
		$query = $this->db->sql_query("SELECT nombre FROM " . SHOPS_TABLE . " WHERE shop_id = $shop_id");
		if ($row = $this->db->sql_fetchrow($query)) {
			$nombre = $row['nombre'];
		}
		$this->db->sql_freeresult($query);
		
		if (confirm_box(true))
		{
			$this->db->sql_query("DELETE FROM " . SHOPS_TABLE . " WHERE shop_id = $shop_id");
		
			if ((int) $this->db->sql_affectedrows() < 1) {
				trigger_error('Hubo un error eliminando la tienda.' . $this->get_return_link('tienda'));
			}
		
			trigger_error('Tienda eliminada exitosamente.' . $this->get_return_link('tienda'));
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array('submit' => true));
			confirm_box(false, "¿Desea borrar la tienda '$nombre'?", $s_hidden_fields);
		}
		
		trigger_error('Acción cancelada.' . $this->get_return_link('tienda'));
	}
	
	/*------------------------
	--         ITEMS        --
	--------------------------*/
	
	function items_view() {		
		$this->validate_access();
		
		$shop_id = (int) request_var('tienda_filtro', 0);
		$shop_nombre = $this->get_tienda_nombre($shop_id);
		$shop_options = $this->get_tienda_options($shop_id);
		
		$query = $this->db->sql_query("SELECT * FROM ". ITEMS_TABLE . " WHERE shop_id = $shop_id");
		while ($row = $this->db->sql_fetchrow($query)) {			
			$items[] = array(
				'ITEM_ID'			=> (int) $row['item_id'],
				'SHOP_ID'			=> (int) $row['shop_id'],
				'SHOP_NOMBRE'		=> $shop_nombre,
				'SHOP_OPTIONS'		=> $shop_options,
				'NOMBRE'			=> $row['nombre'],
				'NOMBRE_BUSQUEDA'	=> $row['nombre_busqueda'],
				'TIPOS'				=> $row['tipos'],
				'REQUISITOS'		=> $row['requisitos'],
				'EFECTOS'			=> $row['efectos'],
				'DESCRIPCION'		=> $row['descripcion'],
				'URL_IMAGEN'		=> $row['url_imagen'],
				'PRECIO'			=> (int) $row['precio'],
				'CANTIDAD_MAX'		=> (int) $row['cantidad_max'],
				'COMPRABLE'			=> (bool) $row['comprable'],
				'PJ_ID_INVENCION'	=> (int) $row['pj_id_invencion'],
				'ORDEN'				=> (int) $row['orden'],
				'U_ACTION_UPD'		=> "/sladmin/items/upd/" . $row['item_id'],
				'U_ACTION_DEL'		=> "/sladmin/items/del/" . $row['item_id'],
			);
		}
		$this->db->sql_freeresult($query);
		
		if (isset($items))
			$this->template->assign_block_vars_array('items', $items);
	
		$this->template->assign_vars(array(
			'SHOP_ID'		=> $shop_id,
			'SHOP_NOMBRE'	=> $shop_nombre,
			'SHOP_OPTIONS'	=> $shop_options,
			'U_ACTION_INS'	=> "/sladmin/items/ins",
			'U_ACTION_SEL'	=> "/sladmin/items",
		));
		
		return $this->helper->render('sladmin/items.html', 'Administrador de SL - Items');
	}
	
	function items_ins() {
		$this->validate_access();
		
		$sql_array = array(
			'shop_id'			=> (int) request_var('shop_id', 0),
			'nombre'			=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'nombre_busqueda'	=> utf8_normalize_nfc(request_var('nombre_busqueda', '', true)),
			'tipos'				=> utf8_normalize_nfc(request_var('tipos', '', true)),
			'requisitos'		=> html_entity_decode(utf8_normalize_nfc(request_var('requisitos', '', true))),
			'efectos'			=> html_entity_decode(utf8_normalize_nfc(request_var('efectos', '', true))),
			'descripcion'		=> html_entity_decode(utf8_normalize_nfc(request_var('descripcion', '', true))),
			'url_imagen'		=> utf8_normalize_nfc(request_var('url_imagen', '', true)),
			'precio'			=> (int) request_var('precio', 0),
			'cantidad_max'		=> (int) request_var('cantidad_max', 0),
			'comprable'			=> (bool) request_var('comprable', false),
			'pj_id_invencion'	=> (int) request_var('pj_id_invencion', 0),
			'orden'				=> (int) request_var('orden', 0),
		);
		
		$shop_id = $sql_array['shop_id'];
		
		$this->db->sql_query('INSERT INTO ' . ITEMS_TABLE . $this->db->sql_build_array('INSERT', $sql_array));
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error agregando el item.' . $this->get_return_link("items?tienda_filtro=$shop_id"));
		}
		
		trigger_error('Técnica agregada exitosamente.' . $this->get_return_link("items?tienda_filtro=$shop_id"));
	}
	
	function items_upd($item_id) {
		$this->validate_access();
		
		$sql_array = array(
			'shop_id'			=> (int) request_var('shop_id', 0),
			'nombre'			=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'nombre_busqueda'	=> utf8_normalize_nfc(request_var('nombre_busqueda', '', true)),
			'tipos'				=> utf8_normalize_nfc(request_var('tipos', '', true)),
			'requisitos'		=> html_entity_decode(utf8_normalize_nfc(request_var('requisitos', '', true))),
			'efectos'			=> html_entity_decode(utf8_normalize_nfc(request_var('efectos', '', true))),
			'descripcion'		=> html_entity_decode(utf8_normalize_nfc(request_var('descripcion', '', true))),
			'url_imagen'		=> utf8_normalize_nfc(request_var('url_imagen', '', true)),
			'precio'			=> (int) request_var('precio', 0),
			'cantidad_max'		=> (int) request_var('cantidad_max', 0),
			'comprable'			=> (bool) request_var('comprable', false),
			'pj_id_invencion'	=> (int) request_var('pj_id_invencion', 0),
			'orden'				=> (int) request_var('orden', 0),
		);
		
		$shop_id = $sql_array['shop_id'];
		
		$this->db->sql_query('UPDATE ' . ITEMS_TABLE . ' SET ' .
					$this->db->sql_build_array('UPDATE', $sql_array) .
					" WHERE item_id = $item_id");
					
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error modificando el item.' . $this->get_return_link("items?tienda_filtro=$shop_id"));
		}
		
		trigger_error('Item actualizado exitosamente.' . $this->get_return_link("items?tienda_filtro=$shop_id"));
	}
	
	function items_del($item_id) {
		$this->validate_access();
		
		$shop_id = (int) request_var('shop_id', 0);
		
		$val_query = $this->db->sql_query("SELECT COUNT(0) AS cantidad FROM " . PERSONAJE_ITEMS_TABLE . " WHERE item_id = $item_id");
		if ($row = $this->db->sql_fetchrow($val_query)) {
			$pjs = (int) $row['cantidad'];
			if ($pjs > 0)
				trigger_error("No se puede eliminar el item porque existen $pjs personajes que lo poseen.");
		}
		$this->db->sql_freeresult($val_query);
		
		$query = $this->db->sql_query("SELECT nombre FROM " . ITEMS_TABLE . " WHERE item_id = $item_id");
		if ($row = $this->db->sql_fetchrow($query)) {
			$nombre = $row['nombre'];
		}
		$this->db->sql_freeresult($query);
		
		if (confirm_box(true))
		{
			$this->db->sql_query("DELETE FROM " . ITEMS_TABLE . " WHERE item_id = $item_id");
		
			if ((int) $this->db->sql_affectedrows() < 1) {
				trigger_error('Hubo un error eliminando el item.' . $this->get_return_link("items?tienda_filtro=$shop_id"));
			}
		
			trigger_error('Item eliminado exitosamente.' . $this->get_return_link("items?tienda_filtro=$shop_id"));
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array('submit' => true));
			confirm_box(false, "¿Desea borrar el item '$nombre'?", $s_hidden_fields);
		}
		
		trigger_error('Acción cancelada.' . $this->get_return_link("items?tienda_filtro=$shop_id"));
	}
	
	/*------------------------
	--      ARQUETIPOS      --
	--------------------------*/
	
	function arquetipos_view() {
		$this->validate_access();
		
		$query = $this->db->sql_query("SELECT * FROM ". ARQUETIPOS_TABLE);
		while ($row = $this->db->sql_fetchrow($query)){
			$arquetipos[] = array(
				'ARQUETIPO_ID'			=> (int) $row['arquetipo_id'],
				'NOMBRE_JP'				=> $row['nombre_jp'],
				'NOMBRE_ES'				=> $row['nombre_es'],
				'NIVEL'					=> (int) $row['nivel'],
				'ARQUETIPO_ID_PADRE1'	=> (int) $row['arquetipo_id_padre1'],
				'ARQUETIPO1_NOMBRE'		=> $this->get_arquetipo_nombre($row['arquetipo_id_padre1']),
				'ARQUETIPO1_OPTIONS'	=> $this->get_arquetipo_options($row['arquetipo_id_padre1']),
				'ARQUETIPO_ID_PADRE2'	=> (int) $row['arquetipo_id_padre2'],
				'ARQUETIPO2_NOMBRE'		=> $this->get_arquetipo_nombre($row['arquetipo_id_padre2']),
				'ARQUETIPO2_OPTIONS'	=> $this->get_arquetipo_options($row['arquetipo_id_padre2']),
				'BONO_PV'				=> (int) $row['bono_pv'],
				'BONO_STA'				=> (int) $row['bono_sta'],
				'BONO_PC'				=> (int) $row['bono_pc'],
				'BONO_ES_PORCENTAJE'	=> (bool) $row['bono_es_porcentaje'],
				'U_ACTION_UPD'			=> "/sladmin/arquetipos/upd/" . $row['arquetipo_id'],
				'U_ACTION_DEL'			=> "/sladmin/arquetipos/del/" . $row['arquetipo_id'],
			);
		}
		$this->db->sql_freeresult($query);
		
		$this->template->assign_block_vars_array('arquetipos', $arquetipos);
		$this->template->assign_vars(array(
			'ARQUETIPO_OPTIONS'	=> $this->get_arquetipo_options(),
			'U_ACTION_INS'	=> "/sladmin/arquetipos/ins",
		));
		
		return $this->helper->render('sladmin/arquetipos.html', 'Administrador de SL - Arquetipos');
	}
	
	function arquetipos_ins() {
		$this->validate_access();
		
		$sql_array = array(
			'nombre_jp'				=> utf8_normalize_nfc(request_var('nombre_jp', '', true)),
			'nombre_es'				=> utf8_normalize_nfc(request_var('nombre_es', '', true)),
			'nivel'					=> (int) request_var('nivel', 0),
			'arquetipo_id_padre1'	=> (int) request_var('arquetipo_id_padre1', null),
			'arquetipo_id_padre2'	=> (int) request_var('arquetipo_id_padre2', null),
			'bono_pv'				=> (int) request_var('bono_pv', 0),
			'bono_sta'				=> (int) request_var('bono_sta', 0),
			'bono_pc'				=> (int) request_var('bono_pc', 0),
			'bono_es_porcentaje'	=> (bool) request_var('bono_es_porcentaje', false),
		);
		
		$this->db->sql_query('INSERT INTO ' . ARQUETIPOS_TABLE . $this->db->sql_build_array('INSERT', $sql_array));
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error agregando el arquetipo.' . $this->get_return_link('arquetipos'));
		}
		
		trigger_error('Arquetipo agregado exitosamente.' . $this->get_return_link('arquetipos'));
	}
	
	function arquetipos_upd($arquetipo_id) {
		$this->validate_access();
		
		$sql_array = array(
			'nombre_jp'				=> utf8_normalize_nfc(request_var('nombre_jp', '', true)),
			'nombre_es'				=> utf8_normalize_nfc(request_var('nombre_es', '', true)),
			'nivel'					=> (int) request_var('nivel', 0),
			'arquetipo_id_padre1'	=> request_var('arquetipo_id_padre1', 0),
			'arquetipo_id_padre2'	=> request_var('arquetipo_id_padre2', 0),
			'bono_pv'				=> (int) request_var('bono_pv', 0),
			'bono_sta'				=> (int) request_var('bono_sta', 0),
			'bono_pc'				=> (int) request_var('bono_pc', 0),
			'bono_es_porcentaje'	=> (bool) request_var('bono_es_porcentaje', false),
		);
		
		$this->db->sql_query('UPDATE ' . ARQUETIPOS_TABLE . ' SET ' .
					$this->db->sql_build_array('UPDATE', $sql_array) .
					" WHERE arquetipo_id = $arquetipo_id");
					
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error modificando el arquetipo.' . $this->get_return_link('arquetipos'));
		}
		
		trigger_error('Arquetipo actualizado exitosamente.' . $this->get_return_link('arquetipos'));
	}
	
	function arquetipos_del($arquetipo_id) {
		$this->validate_access();
		
		$val_query = $this->db->sql_query("SELECT COUNT(0) AS cantidad FROM " . PERSONAJES_TABLE . " WHERE arquetipo_id = $arquetipo_id");
		if ($row = $this->db->sql_fetchrow($val_query)) {
			$pjs = (int) $row['cantidad'];
			if ($pjs > 0)
				trigger_error("No se puede eliminar el arquetipo porque existen $pjs personajes con el mismo.");
		}
		$this->db->sql_freeresult($val_query);
		
		$query = $this->db->sql_query("SELECT nombre_es FROM " . ARQUETIPOS_TABLE . " WHERE arquetipo_id = $arquetipo_id");
		if ($row = $this->db->sql_fetchrow($query)) {
			$nombre = $row['nombre_es'];
		}
		$this->db->sql_freeresult($query);
		
		if (confirm_box(true))
		{
			$this->db->sql_query("DELETE FROM " . ARQUETIPOS_TABLE . " WHERE arquetipo_id = $arquetipo_id");
		
			if ((int) $this->db->sql_affectedrows() < 1) {
				trigger_error('Hubo un error eliminando el arquetipo.' . $this->get_return_link('arquetipos'));
			}
		
			trigger_error('Arquetipo eliminado exitosamente.' . $this->get_return_link('arquetipos'));
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array('submit' => true));
			confirm_box(false, "¿Desea borrar el arquetipo '$nombre'?", $s_hidden_fields);
		}
		
		trigger_error('Acción cancelada.' . $this->get_return_link('arquetipos'));
	}

	/*------------------------
	--      HABILIDADES     --
	--------------------------*/
	
	function habilidades_view() {		
		$this->validate_access();
		
		$arquetipo_id = (int) request_var('arquetipo_filtro', 0);
		$arquetipo_nombre = $this->get_arquetipo_nombre($arquetipo_id);
		$arquetipo_options = $this->get_arquetipo_options($arquetipo_id);
		
		$query = $this->db->sql_query("SELECT * FROM ". HABILIDADES_TABLE . 
										" WHERE ($arquetipo_id > 0 AND $arquetipo_id IN (arquetipo_id1, arquetipo_id2))" .
											" OR ($arquetipo_id = 0 AND inventada = 1)");
		while ($row = $this->db->sql_fetchrow($query)) {
			$habilidades[] = array(
				'HABILIDAD_ID'		=> (int) $row['habilidad_id'],
				'NOMBRE'			=> $row['nombre'],
				'ARQUETIPO_ID1'		=> (int) $row['arquetipo_id1'],
				'ARQUETIPO1_NOMBRE'	=> $this->get_arquetipo_nombre($row['arquetipo_id1']),
				'ARQUETIPO1_OPTIONS'=> $this->get_arquetipo_options($row['arquetipo_id1']),
				'ARQUETIPO_ID2'		=> (int) $row['arquetipo_id2'],
				'ARQUETIPO2_NOMBRE'	=> $this->get_arquetipo_nombre($row['arquetipo_id2']),
				'ARQUETIPO2_OPTIONS'=> $this->get_arquetipo_options($row['arquetipo_id2']),
				'REQUISITOS'		=> $row['requisitos'],
				'EFECTO'			=> $row['efecto'],
				'COSTE'				=> (int) $row['coste'],
				'URL_IMAGEN'		=> $row['url_imagen'],
				'VISIBLE'			=> (bool) $row['visible'],
				'INVENTADA'			=> (bool) $row['inventada'],
				'U_ACTION_UPD'		=> "/sladmin/habilidades/upd/" . $row['habilidad_id'],
				'U_ACTION_DEL'		=> "/sladmin/habilidades/del/" . $row['habilidad_id'],
			);
		}
		$this->db->sql_freeresult($query);
		
		if (isset($habilidades))
			$this->template->assign_block_vars_array('habilidades', $habilidades);
	
		$this->template->assign_vars(array(
			'ARQUETIPO_ID'		=> $arquetipo_id,
			'ARQUETIPO_NOMBRE'	=> $arquetipo_nombre,
			'ARQUETIPO_OPTIONS'	=> $arquetipo_options,
			'U_ACTION_INS'	=> "/sladmin/habilidades/ins",
			'U_ACTION_SEL'	=> "/sladmin/habilidades",
		));
		
		return $this->helper->render('sladmin/habilidades.html', 'Administrador de SL - Habilidades');
	}
	
	function habilidades_ins() {
		$this->validate_access();
		
		$sql_array = array(
			'nombre'			=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'arquetipo_id1'		=> (int) request_var('arquetipo_id1', 0),
			'arquetipo_id2'		=> (int) request_var('arquetipo_id2', 0),
			'requisitos'		=> html_entity_decode(utf8_normalize_nfc(request_var('requisitos', '', true))),
			'efecto'			=> html_entity_decode(utf8_normalize_nfc(request_var('efecto', '', true))),
			'coste'				=> (int) request_var('coste', 0),
			'url_imagen'		=> utf8_normalize_nfc(request_var('url_imagen', '', true)),
			'visible'			=> (bool) request_var('visible', false),
			'inventada'			=> (bool) request_var('inventada', false),
		);
		
		$arquetipo_id = $sql_array['arquetipo_id1'];
		
		$this->db->sql_query('INSERT INTO ' . HABILIDADES_TABLE . $this->db->sql_build_array('INSERT', $sql_array));
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error agregando la habilidad.' . $this->get_return_link("habilidades?arquetipo_filtro=$arquetipo_id"));
		}
		
		trigger_error('Habilidad agregada exitosamente.' . $this->get_return_link("habilidades?arquetipo_filtro=$arquetipo_id"));
	}
	
	function habilidades_upd($habilidad_id) {
		$this->validate_access();
		
		$sql_array = array(
			'nombre'			=> utf8_normalize_nfc(request_var('nombre', '', true)),
			'arquetipo_id1'		=> (int) request_var('arquetipo_id1', 0),
			'arquetipo_id2'		=> (int) request_var('arquetipo_id2', 0),
			'requisitos'		=> html_entity_decode(utf8_normalize_nfc(request_var('requisitos', '', true))),
			'efecto'			=> html_entity_decode(utf8_normalize_nfc(request_var('efecto', '', true))),
			'coste'				=> (int) request_var('coste', 0),
			'url_imagen'		=> utf8_normalize_nfc(request_var('url_imagen', '', true)),
			'visible'			=> (bool) request_var('visible', false),
			'inventada'			=> (bool) request_var('inventada', false),
		);
		
		$arquetipo_id = $sql_array['arquetipo_id1'];
		
		$this->db->sql_query('UPDATE ' . HABILIDADES_TABLE . ' SET ' .
					$this->db->sql_build_array('UPDATE', $sql_array) .
					" WHERE habilidad_id = $habilidad_id");
					
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error modificando la habilidad.' . $this->get_return_link("habilidades?arquetipo_filtro=$arquetipo_id"));
		}
		
		trigger_error('Habilidad actualizada exitosamente.' . $this->get_return_link("habilidades?arquetipo_filtro=$arquetipo_id"));
	}
	
	function habilidades_del($habilidad_id) {
		$this->validate_access();
		
		$arquetipo_id = (int) request_var('arquetipo_id', 0);
		
		$val_query = $this->db->sql_query("SELECT COUNT(0) AS cantidad FROM " . PERSONAJE_HABILIDADES_TABLE . " WHERE habilidad_id = $habilidad_id");
		if ($row = $this->db->sql_fetchrow($val_query)) {
			$pjs = (int) $row['cantidad'];
			if ($pjs > 0)
				trigger_error("No se puede eliminar la habilidad porque existen $pjs personajes que la poseen.");
		}
		$this->db->sql_freeresult($val_query);
		
		$query = $this->db->sql_query("SELECT nombre FROM " . HABILIDADES_TABLE . " WHERE habilidad_id = $habilidad_id");
		if ($row = $this->db->sql_fetchrow($query)) {
			$nombre = $row['nombre'];
		}
		$this->db->sql_freeresult($query);
		
		if (confirm_box(true))
		{
			$this->db->sql_query("DELETE FROM " . HABILIDADES_TABLE . " WHERE habilidad_id = $habilidad_id");
		
			if ((int) $this->db->sql_affectedrows() < 1) {
				trigger_error('Hubo un error eliminando la habilidad.' . $this->get_return_link("habilidades?arquetipo_filtro=$arquetipo_id"));
			}
		
			trigger_error('Habilidad eliminada exitosamente.' . $this->get_return_link("habilidades?arquetipo_filtro=$arquetipo_id"));
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array('submit' => true));
			confirm_box(false, "¿Desea borrar la habilidad '$nombre'?", $s_hidden_fields);
		}
		
		trigger_error('Acción cancelada.' . $this->get_return_link("habilidades?arquetipo_filtro=$arquetipo_id"));
	}
	
	/*------------------------
	--     INVOCACIONES     --
	--------------------------*/
	
	function invocaciones_view() {
		$this->validate_access();
		
		$query = $this->db->sql_query("SELECT * FROM ". INVOCACIONES_TABLE);
		while ($row = $this->db->sql_fetchrow($query)){
			$invocaciones[] = array(
				'INVOCACION_ID'	=> (int) $row['invocacion_id'],
				'PACTO'			=> $row['pacto'],
				'ESPECIES'		=> $row['especies'],
				'ACTIVO'		=> $row['activo'],
				'U_ACTION_UPD'	=> "/sladmin/invocaciones/upd/" . $row['invocacion_id'],
				'U_ACTION_DEL'	=> "/sladmin/invocaciones/del/" . $row['invocacion_id'],
			);
		}
		$this->db->sql_freeresult($query);
		
		$this->template->assign_block_vars_array('invocaciones', $invocaciones);
		$this->template->assign_vars(array(
			'U_ACTION_INS'	=> "/sladmin/invocaciones/ins",
		));
		
		return $this->helper->render('sladmin/invocaciones.html', 'Administrador de SL - Invocaciones');
	}
	
	function invocaciones_ins() {
		$this->validate_access();
		
		$sql_array = array(
			'pacto'	=> utf8_normalize_nfc(request_var('pacto', '', true)),
			'especies'	=> utf8_normalize_nfc(request_var('especies', '', true)),
			'activo'	=> (bool) request_var('activo', false),
		);
		
		$this->db->sql_query('INSERT INTO ' . INVOCACIONES_TABLE . $this->db->sql_build_array('INSERT', $sql_array));
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error agregando la invocación.' . $this->get_return_link('invocaciones'));
		}
		
		trigger_error('Invocación agregada exitosamente.' . $this->get_return_link('invocaciones'));
	}
	
	function invocaciones_upd($shop_id) {
		$this->validate_access();
		
		$sql_array = array(
			'pacto'	=> utf8_normalize_nfc(request_var('pacto', '', true)),
			'especies'	=> utf8_normalize_nfc(request_var('especies', '', true)),
			'activo'	=> (bool) request_var('activo', false),
		);
		
		$this->db->sql_query('UPDATE ' . INVOCACIONES_TABLE . ' SET ' .
					$this->db->sql_build_array('UPDATE', $sql_array) .
					" WHERE invocacion_id = $invocacion_id");
					
		if ((int) $this->db->sql_affectedrows() < 1) {
			trigger_error('Hubo un error modificando la invocación.' . $this->get_return_link('invocaciones'));
		}
		
		trigger_error('Invocación actualizada exitosamente.' . $this->get_return_link('invocaciones'));
	}
	
	function invocaciones_del($shop_id) {
		$this->validate_access();
		
		$val_query = $this->db->sql_query("SELECT COUNT(0) AS cantidad FROM " . PERSONAJES_TABLE . " WHERE activo = 1 AND invocacion_id = $invocacion_id");
		if ($row = $this->db->sql_fetchrow($val_query)) {
			$pjs = (int) $row['cantidad'];
			if ($pjs > 0)
				trigger_error("No se puede eliminar la invocación porque existen $pjs personajes con la misma.");
		}
		$this->db->sql_freeresult($val_query);
		
		$query = $this->db->sql_query("SELECT pacto FROM " . INVOCACIONES_TABLE . " WHERE invocacion_id = $invocacion_id");
		if ($row = $this->db->sql_fetchrow($query)) {
			$pacto = $row['pacto'];
		}
		$this->db->sql_freeresult($query);
		
		if (confirm_box(true))
		{
			$this->db->sql_query("DELETE FROM " . INVOCACIONES_TABLE . " WHERE invocacion_id = $invocacion_id");
		
			if ((int) $this->db->sql_affectedrows() < 1) {
				trigger_error('Hubo un error eliminando la invocación.' . $this->get_return_link('invocaciones'));
			}
		
			trigger_error('Invocación eliminada exitosamente.' . $this->get_return_link('invocaciones'));
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array('submit' => true));
			confirm_box(false, "¿Desea borrar la invocación '$pacto'?", $s_hidden_fields);
		}
		
		trigger_error('Acción cancelada.' . $this->get_return_link('invocaciones'));
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
	
	function get_rama_options($rama_id = 0, $principal = true)
	{
		$options = "";
		
		$query = $this->db->sql_query("SELECT rama_id, nombre FROM " . RAMAS_TABLE . 
										" WHERE principal = " . ($principal ? "1" : "0" ) .
							($rama_id ? " AND rama_id <> $rama_id" : ""));
		
		while ($row = $this->db->sql_fetchrow($query)){
			$options .= "<option value='" . $row['rama_id'] . "'>" . $row['nombre'] . "</option>";
		}
		$this->db->sql_freeresult($query);
		
		return $options;
	}
	
	function get_arquetipo_nombre($arquetipo_id = 0)
	{
		if (!$arquetipo_id) return false;
		
		$query = $this->db->sql_query("SELECT nombre_es FROM " . ARQUETIPOS_TABLE . " WHERE arquetipo_id = $arquetipo_id");
		if ($row = $this->db->sql_fetchrow($query)) {
			return $row['nombre_es'];
		}
		$this->db->sql_freeresult($query);
		
		return false;
	}
	
	function get_arquetipo_options($arquetipo_id = 0)
	{
		$options = "";
		
		$query = $this->db->sql_query("SELECT arquetipo_id, nombre_es FROM " . ARQUETIPOS_TABLE . 
						($shop_id ? " WHERE arquetipo_id <> $arquetipo_id" : ""));
		
		while ($row = $this->db->sql_fetchrow($query)){
			$options .= "<option value='" . $row['arquetipo_id'] . "'>" . $row['nombre_es'] . "</option>";
		}
		$this->db->sql_freeresult($query);
		
		return $options;
	}
	
	function get_tienda_nombre($shop_id = 0)
	{
		if (!$shop_id) return false;
		
		$query = $this->db->sql_query("SELECT nombre FROM " . SHOPS_TABLE . " WHERE shop_id = $shop_id");
		if ($row = $this->db->sql_fetchrow($query)) {
			return $row['nombre'];
		}
		$this->db->sql_freeresult($query);
		
		return false;
	}
	
	function get_tienda_options($shop_id = 0)
	{
		$options = "";
		
		$query = $this->db->sql_query("SELECT shop_id, nombre FROM " . SHOPS_TABLE . 
						($shop_id ? " WHERE shop_id <> $shop_id" : "") .
						" ORDER BY shop_id");
		
		while ($row = $this->db->sql_fetchrow($query)){
			$options .= "<option value='" . $row['shop_id'] . "'>" . $row['nombre'] . "</option>";
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
