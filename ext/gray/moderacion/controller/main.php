<?php

namespace gray\moderacion\controller;

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

		  return $this->helper->render('moderacion/home.html', 'Administrador de SL');
    }

    public function validate_access(){
        $grupo = $this->user->data['group_id'];
        if ($grupo != 5 AND $grupo != 4 AND $grupo != 18) {
            trigger_error($grupo);
        }
    }

    public function vista_staff(){
      $grupo = $this->user->data['group_id'];

      switch ($grupo) {
        case '4':
          //Moderadores globales
          return "mod";
        break;

        case '5':
          //Administradores
          return "admin";
        break;

        case '18':
          //Líder de mod
          return "admin";
        break;

        default:
          //Usuarios normales
            return "user";
        break;
      }
    }

    public function get_return_link()
    {
        return "<br /><a href='/moderacion'>Volver</a>.";
    }

    public function get_participantes($topic_id){
      
      $sql = "SELECT DISTINCT
                u.user_id,
                u.username
              FROM phpbby1_posts AS p
              INNER JOIN phpbby1_users AS u
                ON u.user_id = p.poster_id
              WHERE p.topic_id = $topic_id";

      $query = $this->db->sql_query($sql);

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<option value='" . $row['user_id'] . "'>" . $row['username'] . "</option>";
      }

    }

    public function get_revisiones_user($user_id){

      $query = $this->db->sql_query('SELECT * FROM '.REVISIONES.' WHERE id_usuario = '.$user_id);

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<tr><td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['moderador_asignado'] . "</td>";
        $options .= "<td>" . $row['información'] . "</td>";
        $options .= "<td>" . $row['enlace'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td></tr>";
      }

    }

    public function get_revisiones_mod($user_id){

      $query = $this->db->sql_query('SELECT * FROM '.REVISIONES.' WHERE mod_asignado = '.$user_id);

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<tr><td>" . $row['moderador_asignado'] . "</td>";
        $options .= "<td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['id_usuario'] . "</td>";
        $options .= "<td>" . $row['información'] . "</td>";
        $options .= "<td>" . $row['participantes'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td></tr>";
      }

    }

    public function insert_revision($array){

      $user_id = $this->user->data['user_id'];

      $sql_array = array(
        'user_id'	=> $user_id,
        'tipo_revision'		=> $array["tipo_revision"],
        'estado'		=> "registrada",
      );

      switch ($array["tipo_revision"]) {
        case 'activacion_ficha':
          $sql_array['informacion'] = $array['informacion'];
          break;
        case 'revision_ficha':
          $sql_array['informacion'] = $array['asunto'];
          $sql_array['enlace'] = $array['enlace'];
          $sql_array['participantes'] = $array['participantes'];
          break;
          
        case 'revision_mision':
          $sql_array['informacion'] = $array['informacion'];
          $sql_array['enlace'] = $array['enlace'];
          $sql_array['participantes'] = $array['participantes'];
          break;
          
        case 'solicitud_encargo':
          $sql_array['informacion'] = $array['informacion'];
          $sql_array['enlace'] = $array['enlace'];
          break;

        case 'moderacion_combate':
          $sql_array['informacion'] = $array['informacion'];
          $sql_array['enlace'] = $array['enlace'];
          break;

        case 'patreon':
          $sql_array['informacion'] = $array['informacion'];
          $sql_array['enlace'] = $array['enlace'];
          break;

        case 'revision_tema':
          $sql_array['enlace'] = $array['enlace'];
          $sql_array['participantes'] = $array['participantes'];
          break;
      }

      $sql = "INSERT INTO revisiones " . $this->db->sql_build_array('INSERT', $sql_array);
      $this->db->sql_query($sql);
    }
}
