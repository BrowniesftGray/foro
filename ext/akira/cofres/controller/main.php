<?php

namespace akira\cofres\controller;
require_once('/home/shinobil/public_html/includes/functions_user.php');
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
		if (!$this->inicializar($pj_id))
			return $this->info();
		
		// Obtener los cofres sin abrir del personaje
		$query = $this->db->sql_query("SELECT c.*, t.topic_title
										FROM " . COFRES_TABLE . " c
											LEFT JOIN " . TOPICS_TABLE . " t
												ON t.topic_id = c.topic_id
										WHERE c.pj_id = '$pj_id' 
											AND c.fecha_abierto IS NULL
										ORDER BY fecha_recibido DESC");
						
		// Recorrer cofres
		while ($row = $this->db->sql_fetchrow($query))
		{
			// Generar URL de imágenes del cofre
			$img_cerrado = strtolower(sprintf(COFRES_CERRADO_IMG, $row['rango']));
			$img_abierto = strtolower(sprintf(COFRES_ABIERTO_IMG, $row['rango']));
			
			// Obtener origen del cofre
			if ((int) $row['topic_id'] > 0)
			{
				$topic_title = "<i>'" . $row['topic_title'] . "'</i>";
			} else {
				$topic_title = 'Premio Diario';
			}
			
			// Armar array de cofres
			$cofres[] = array(
				'COFRE_ID'			=> (int) $row['cofre_id'],
				'RANGO'				=> $row['rango'],
				'PJ_ID'				=> (int) $row['pj_id'],
				'TOPIC_ID'			=> (int) $row['topic_id'],
				'TOPIC_TITLE'		=> $topic_title,
				'ITEMS_EXTRA'		=> (int) $row['items_extra'],
				'ESTADO'			=> $row['estado'],
				'FECHA_RECIBIDO'	=> $row['fecha_recibido'],
				'IMG_CERRADO_URL'	=> $img_cerrado,
				'IMG_ABIERTO_URL'	=> $img_abierto,
			);
		}
		$this->db->sql_freeresult($query);
		
		// Asignar variables al template
		
		if (isset($cofres))
			$this->template->assign_block_vars_array('cofres', $cofres);
		
		$this->template->assign_vars(array(
			'COFRES_PREMIO_CADENA'				=> COFRES_PREMIO_CADENA,
			'COFRES_PREMIO_ACUMULADO_RANGO'		=> COFRES_PREMIO_ACUMULADO_RANGO,
			'COFRES_PREMIO_ACUMULADO_DIAS'		=> COFRES_PREMIO_ACUMULADO_DIAS,
			'PREMIO_CADENA_COMPLETA_CANTIDAD'	=> PREMIO_CADENA_COMPLETA_CANTIDAD,
			'COFRES_CANTIDAD_ITEMS'				=> COFRES_CANTIDAD_ITEMS,
			'COFRES_HISTORIAL'					=> ($pj_id == 765),
		));
		
		return $this->helper->render('cofres/home.html', 'Cofres de Tesoro');
    }
	
	public function abrir() 
	{
		if (!$this->inicializar($pj_id))
			return $this->info();
		
		// Obtener el cofre_id abierto
		$cofre_id = (int) request_var('cofre_id', 0);
		
		// Si no hay es porque se llegó por URL, redirigir
		if (!$cofre_id)
			return $this->handle();
		
		// Obtener los datos del cofre
		$query = $this->db->sql_query("SELECT c.*
										FROM " . COFRES_TABLE . " c
										WHERE c.cofre_id = '$cofre_id'");
		$row = $this->db->sql_fetchrow($query);
		$this->db->sql_freeresult($query);
										
		// Comprobar que se haya encontrado
		if (!$this->db->sql_affectedrows())
			trigger_error('No se pudo abrir el cofre. ¿Quizá tienes la llave incorrecta?' . $this->get_return_link());
		
		// Comprobar que no esté abierto
		if ($row['fecha_abierto'])
			trigger_error('Este cofre ya ha sido abierto.' . $this->get_return_link());
	
		// Comprobar que pertenezca al personaje
		if ($pj_id != (int) $row['pj_id'])
			trigger_error('No puedes abrir el cofre de otro personaje, puerco.' . $this->get_return_link());
	
		$rango = $row['rango'];
	
		// Obtener las posibles recompensas acorde al Rango
		$query = $this->db->sql_query("SELECT item_id, chance
										FROM " . COFRES_RECOMPENSAS_TABLE . " 
										WHERE rango = '$rango'");
		while ($row_items = $this->db->sql_fetchrow($query))
		{
			// Armar array de item_id posibles
			for ($i = 0; $i < (int) $row_items['chance']; $i++)
				$items[] = $row_items['item_id'];
		}
		$this->db->sql_freeresult($query);
		
		// Comprobar que hubiera recompensas cargadas en sladmin
		if (!isset($items))
			trigger_error('¡El cofre está vacío! Por favor, avísale a la Administración.' . $this->get_return_link());
		
		// Mezclar array al azar
		shuffle($items);
		
		// Definir la cantidad de items a otorgar
		$cantidad_default = COFRES_CANTIDAD_ITEMS;
		$cantidad_items = (int) $cantidad_default + (int) $row['items_extra'];
		
		// Recorrer los primeros items del array para otorgarlos
		for ($i = 0; $i < $cantidad_items; $i++)
		{
			$item_id = (int) $items[$i];
			
			if (!$item_id)
				trigger_error('¡El cofre está embrujado! Por favor, avísale a la Administración.' . $this->get_return_link());
		
			// Obtener cantidad actual poseída por el personaje
			$query = $this->db->sql_query("SELECT cantidad 
											FROM " . PERSONAJE_ITEMS_TABLE . "
											WHERE pj_id = '$pj_id' 
												AND item_id = '$item_id'");
			$cantidad = (int) $this->db->sql_fetchfield('cantidad');
			$this->db->sql_freeresult($query);
			
			// Obtener datos del item y chance de ganarlo
			$query = $this->db->sql_query("SELECT i.nombre, i.url_imagen, cr.chance
											FROM " . ITEMS_TABLE . " i
												INNER JOIN " . COFRES_RECOMPENSAS_TABLE . " cr
													ON cr.item_id = i.item_id
													AND cr.rango = '$rango'
											WHERE i.item_id = '$item_id'");
											
			if ($item_row = $this->db->sql_fetchrow($query))
			{
				$item_nombre = $item_row['nombre'];
				
				// Definir rareza según chance				
				$color = COFRES_COLOR_NORMAL;
				$tipo = 'Normal';
				$tag = 'generico';
				
				if ((int) $item_row['chance'] <= COFRES_CHANCE_RARO) {
					$color = COFRES_COLOR_RARO;
					$tipo = 'Raro';
					$tag = 'fuin';
				}
				
				if ((int) $item_row['chance'] <= COFRES_CHANCE_EPICO) {
					$color = COFRES_COLOR_EPICO;
					$tipo = 'Épico';
					$tag = 'gen';
				}
				
				if ((int) $item_row['chance'] <= COFRES_CHANCE_LEGENDARIO) {
					$color = COFRES_COLOR_LEGENDARIO;
					$tipo = 'Legendario';
					$tag = 'tai';
				}
				
				// Armar array de premios para mostrar al final
				$premios[] = array(
					'NOMBRE'		=> $item_row['nombre'],
					'URL_IMAGEN'	=> '/images/shop_icons/' . $item_row['url_imagen'],
					'COLOR'			=> $color,
					'TIPO'			=> $tipo,
					'TAG'			=> $tag,
				);
			}
			$this->db->sql_freeresult($query);
			
			// Array para personajes_items
			$sql_ary = array(
				'pj_id'		=> $pj_id,
				'item_id'	=> $item_id,
				'cantidad'	=> ($cantidad + 1),
			);
			
			// Actualizar inventario del personaje
			$sql = 'UPDATE ' . PERSONAJE_ITEMS_TABLE . '
					SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . "
					WHERE pj_id = '$pj_id' 
						AND item_id = '$item_id'";
			$this->db->sql_query($sql);
			
			if(!$this->db->sql_affectedrows()) {
				$sql = 'INSERT INTO ' . PERSONAJE_ITEMS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
				$this->db->sql_query($sql);
			}
			
			// Array para cofres_items
			$sql_ary = array(
				'cofre_id'	=> $cofre_id,
				'item_id'	=> $item_id,
			);
			
			// Registrar premio
			$sql = 'INSERT INTO ' . COFRES_ITEMS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
			$this->db->sql_query($sql);
			
			// Registrar moderación
			$moderacion = array(
				'PJ_ID'	=> $pj_id,
				'RAZON'	=> "Obtenido 1x '$item_nombre' de Cofre Rango $rango.",
			);
			registrar_moderacion($moderacion);
		}
		
		// Fecha y hora actual
		$fecha = date('Y-m-d H:i:s');
		
		// Array para actualizar cofre
		$sql_ary = array(
			'fecha_abierto'	=> $fecha,
			'estado'	=> 'Abierto',
		);
		
		// Actualizar estado del cofre
		$sql = 'UPDATE ' . COFRES_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . "
				WHERE cofre_id = '$cofre_id'";
		$this->db->sql_query($sql);
		
		// Asignar variables al template
		
		if (isset($premios))
			$this->template->assign_block_vars_array('premios', $premios);
		
		$this->template->assign_vars(array(
			'RANGO'				=> $rango,
			'ITEMS_EXTRA'		=> $row['items_extra'],
			'IMG_ABIERTO_URL'	=> strtolower(sprintf(COFRES_ABIERTO_IMG, $rango)),
		));
		
		return $this->helper->render('cofres/abrir.html', 'Abrir Cofre de Tesoro');
	}
	
	public function fusion() 
	{
		if (!$this->inicializar($pj_id))
			return $this->info();
		
		trigger_error('Fusión cancelada.' . $this->get_return_link());
	}
	
	public function historial($ver_todo = false)
	{
		if (!$this->inicializar($pj_id) && !$ver_todo)
			return $this->info();
		
		$last_cofre_id = 0;
		
		$query = $this->db->sql_query("SELECT	c.*,
												COALESCE(cr.chance, 100) as chance,
												i.nombre,
												i.url_imagen,
												tp.topic_title,
												p.nombre as nombre_pj
										FROM " . COFRES_TABLE . " c
											INNER JOIN " . COFRES_ITEMS_TABLE . " ci
												ON ci.cofre_id = c.cofre_id
											INNER JOIN " . ITEMS_TABLE . " i
												ON i.item_id = ci.item_id
											LEFT JOIN " . PERSONAJES_TABLE . " p
												ON p.pj_id = c.pj_id
											LEFT JOIN " . COFRES_RECOMPENSAS_TABLE ." cr
												ON cr.rango = c.rango
												AND cr.item_id = i.item_id
											LEFT JOIN " . TOPICS_TABLE . " tp
												ON tp.topic_id = c.topic_id
										WHERE c.fecha_abierto IS NOT NULL " .
						($ver_todo ? "" : " AND c.pj_id = '$pj_id' ") . "
										ORDER BY c.fecha_abierto DESC");
		while ($row = $this->db->sql_fetchrow($query)) {
			if ($row['cofre_id'] != $last_cofre_id) {
				$last_cofre_id = $row['cofre_id'];
				
				// Obtener origen del cofre
				if ((int) $row['topic_id'] > 0) {
					$topic_title = "<i>'" . $row['topic_title'] . "'</i>";
				} else {
					$topic_title = 'Premio Diario';
				}
				
				$this->template->assign_block_vars('cofres', array(
					'RANGO'				=> $row['rango'],
					'TOPIC_TITLE'		=> $topic_title,
					'ITEMS_EXTRA'		=> $row['items_extra'],
					'ESTADO'			=> $row['estado'],
					'FECHA_RECIBIDO'	=> $row['fecha_recibido'],
					'FECHA_ABIERTO'		=> $row['fecha_abierto'],
					'NOMBRE_PJ'			=> ($ver_todo ? $row['nombre_pj'] : ""),
					'URL_IMAGEN'		=> strtolower(sprintf(COFRES_ABIERTO_IMG, $row['rango'])),
				));
			}
			
			$color = COFRES_COLOR_NORMAL;
			$tipo = 'Normal';
			$tag = 'generico';
			
			if ((int) $row['chance'] <= COFRES_CHANCE_RARO) {
				$color = COFRES_COLOR_RARO;
				$tipo = 'Raro';
				$tag = 'fuin';
			}
			
			if ((int) $row['chance'] <= COFRES_CHANCE_EPICO) {
				$color = COFRES_COLOR_EPICO;
				$tipo = 'Épico';
				$tag = 'gen';
			}
			
			if ((int) $row['chance'] <= COFRES_CHANCE_LEGENDARIO) {
				$color = COFRES_COLOR_LEGENDARIO;
				$tipo = 'Legendario';
				$tag = 'tai';
			}
			
			$this->template->assign_block_vars('cofres.premios', array(
				'NOMBRE' 		=> $row['nombre'],
				'URL_IMAGEN'	=> '/images/shop_icons/' . $row['url_imagen'],
				'COLOR'			=> $color,
				'TIPO'			=> $tipo,
				'TAG'			=> $tag,
			));
		}
		$this->db->sql_freeresult($query);
		
		return $this->helper->render('cofres/historial.html', ' Historial de Cofres Abiertos');
	}
	
	public function historial_todos() 
	{
		$this->validate_access();
		
		return $this->historial(true);
	}
	
	public function info() 
	{
		// Obtener Rangos de misiones
		$query_rango = $this->db->sql_query("SELECT letra FROM " . RANGOS_TABLE . " WHERE misiones = 1 ORDER BY rango_id");
		while ($row_rango = $this->db->sql_fetchrow($query_rango)) {
			// Agregar rango al template
			$this->template->assign_block_vars('cofres', array(
				'RANGO' 		=> $row_rango['letra'],
				'URL_IMAGEN'	=> strtolower(sprintf(COFRES_CERRADO_IMG, $row_rango['letra'])),
			));
			
			// Obtener los premios posibles
			$query_premios = $this->db->sql_query("SELECT i.nombre, i.url_imagen, cr.chance
													FROM " . COFRES_RECOMPENSAS_TABLE . " cr
														INNER JOIN " . ITEMS_TABLE . " i
															ON i.item_id = cr.item_id
													WHERE rango = '".$row_rango['letra']."'
													ORDER BY cr.chance ASC");
													
			while ($row_premio = $this->db->sql_fetchrow($query_premios)) {
				// Definir rareza según chance				
				$color = COFRES_COLOR_NORMAL;
				$tipo = 'Normal';
				$tag = 'generico';
				
				if ((int) $row_premio['chance'] <= COFRES_CHANCE_RARO) {
					$color = COFRES_COLOR_RARO;
					$tipo = 'Raro';
					$tag = 'fuin';
				}
				
				if ((int) $row_premio['chance'] <= COFRES_CHANCE_EPICO) {
					$color = COFRES_COLOR_EPICO;
					$tipo = 'Épico';
					$tag = 'gen';
				}
				
				if ((int) $row_premio['chance'] <= COFRES_CHANCE_LEGENDARIO) {
					$color = COFRES_COLOR_LEGENDARIO;
					$tipo = 'Legendario';
					$tag = 'tai';
				}
				
				$this->template->assign_block_vars('cofres.premios', array(
					'NOMBRE' 		=> $row_premio['nombre'],
					'URL_IMAGEN'	=> '/images/shop_icons/' . $row_premio['url_imagen'],
					'COLOR'			=> $color,
					'TIPO'			=> $tipo,
					'TAG'			=> $tag,
				));
			}
			$this->db->sql_freeresult($query_premios);
		}
		$this->db->sql_freeresult($query_rango);
		
		$this->template->assign_vars(array(
			'COFRES_PREMIO_CADENA'				=> COFRES_PREMIO_CADENA,
			'COFRES_PREMIO_ACUMULADO_RANGO'		=> COFRES_PREMIO_ACUMULADO_RANGO,
			'COFRES_PREMIO_ACUMULADO_DIAS'		=> COFRES_PREMIO_ACUMULADO_DIAS,
			'PREMIO_CADENA_COMPLETA_CANTIDAD'	=> PREMIO_CADENA_COMPLETA_CANTIDAD,
			'COFRES_CANTIDAD_ITEMS'				=> COFRES_CANTIDAD_ITEMS,
		));
		
		return $this->helper->render('cofres/info.html', 'Cofres de Tesoro - Información');
	}
	
	function inicializar(&$pj_id)
	{
		$result = true;
		$user_id = $this->user->data['user_id'];
		$pj_id = get_pj_id($user_id);
		
		if ($user_id == ANONYMOUS)
			$result = false;
		
		if (!$pj_id)
			$result = false;
		
		return $result;
	}
	
	function validate_access()
	{
		$grupo = $this->user->data['group_id'];
        if ($grupo != 5 && $grupo != 18) {
            trigger_error("No puedes acceder a esta sección.");
        }
	}
	
	function get_return_link()
	{
		return "<br /><a href='/cofres'>Volver</a>.";
	}
}
