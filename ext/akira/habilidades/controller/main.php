<?php

namespace akira\habilidades\controller;

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
    public function handle($ver_todo = false)
    {
		$caminos = array();
		
		// Llenar array con los caminos
		// TODO: Parametrizar en DB
		$caminos[0] = array(
			'camino_id'	=> 1,
			'nombre'	=> 'Primer Camino Shinobi — Cimiento',
			'nivel'		=> 2,
		);
		
		$caminos[1] = array(
			'camino_id'	=> 2,
			'nombre'	=> 'Segundo Camino Shinobi — Motivación',
			'nivel'		=> 10,
		);
		
		$caminos[2] = array(
			'camino_id'	=> 3,
			'nombre'	=> 'Tercer Camino Shinobi — Destino',
			'nivel'		=> 20,
		);
		
		foreach ($caminos as $camino) {
			$this->template->assign_block_vars('caminos', array(
				'ID'		=> $camino['camino_id'],
				'NOMBRE'	=> $camino['nombre'],
				'NIVEL'		=> $camino['nivel'],
			));
			
			// Por cada camino, obtener sus arquetipos
			$sql = 'SELECT * FROM '.ARQUETIPOS_TABLE.' WHERE activo = 1 AND nivel = ' . $camino['nivel'];
			$query_arq = $this->db->sql_query($sql);
			
			while ($row_arq = $this->db->sql_fetchrow($query_arq)) {
				$padre1 = $padre2 = '';
				
				// Obtener el primer arquetipo padre
				if ((int)$row_arq['arquetipo_id_padre1'] > 0) {
					$sql = 'SELECT * FROM '.ARQUETIPOS_TABLE.' WHERE activo = 1 AND arquetipo_id = ' . $row_arq['arquetipo_id_padre1'];
					$query = $this->db->sql_query($sql);
					
					if ($row = $this->db->sql_fetchrow($query)) {
						$padre1 = $row['nombre_jp'];
					}
					$this->db->sql_freeresult($query);
				}
				
				// Obtener el segundo arquetipo padre
				if ((int)$row_arq['arquetipo_id_padre2'] > 0) {
					$sql = 'SELECT * FROM '.ARQUETIPOS_TABLE.' WHERE activo = 1 AND arquetipo_id = ' . $row_arq['arquetipo_id_padre2'];
					$query = $this->db->sql_query($sql);
					
					if ($row = $this->db->sql_fetchrow($query)) {
						$padre2 = $row['nombre_jp'];
					}
					$this->db->sql_freeresult($query);
				}
				
				// Asignar variables del arquetipo
				$this->template->assign_block_vars('caminos.arquetipos', array(
					'ID'					=> $row_arq['arquetipo_id'],
					'NOMBRE_JP'				=> $row_arq['nombre_jp'],
					'NOMBRE_ES'				=> $row_arq['nombre_es'],
					'BONO_PV'				=> $row_arq['bono_pv'],
					'BONO_PC'				=> $row_arq['bono_pc'],
					'BONO_STA'				=> $row_arq['bono_sta'],
					'BONO_ES_PORCENTAJE'	=> $row_arq['bono_es_porcentaje'],
					'PADRE1_NOMBRE_JP'		=> $padre1,
					'PADRE2_NOMBRE_JP'		=> $padre2,
				));
				
				// Obtener habilidades visibles del arquetipo
				$sql = 'SELECT * FROM '.HABILIDADES_TABLE.' WHERE ' . ($ver_todo ? '' : ' visible = 1 AND ') . $row_arq['arquetipo_id'] . ' IN(arquetipo_id1, arquetipo_id2) ORDER BY visible DESC, coste ASC';
				$query_hab = $this->db->sql_query($sql);
				
				while ($row_hab = $this->db->sql_fetchrow($query_hab)) {
					$this->template->assign_block_vars('caminos.arquetipos.habilidades', array(
						'ID'			=> $row_hab['habilidad_id'],
						'NOMBRE'		=> $row_hab['nombre'],
						'EFECTO'		=> $row_hab['efecto'],
						'URL_IMAGEN'	=> $row_hab['url_imagen'],
						'COSTE'			=> $row_hab['coste'],
						'VISIBLE'		=> $row_hab['visible'],
					));
					
					$hab_requisitos = array();
					$requisitos = explode('|', $row_hab['requisitos']);
					for ($i = 0; $i < count($requisitos); $i++) {
						if (strlen($requisitos[$i]) > 0)
							$hab_requisitos[] = array('REQUISITO' => $requisitos[$i]);
					}

					if (count($hab_requisitos) > 0)
						$this->template->assign_block_vars_array('caminos.arquetipos.habilidades.requisitos', $hab_requisitos);
				}
				$this->db->sql_freeresult($query_hab);
			}
			$this->db->sql_freeresult($query_arq);
		}
		
		return $this->helper->render('habilidades/home.html', 'Habilidades de SL');
    }
	
	public function ver_todas() {
		return $this->handle(true);
	}
}
