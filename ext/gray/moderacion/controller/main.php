<?php

namespace gray\moderacion\controller;

use Symfony\Component\HttpFoundation\Response;

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

		  return $this->helper->render('moderacion/home.html', 'Revisiones');
    }

    public function validate_access(){
        $grupo = $this->user->data['group_id'];
        if ($grupo != 5 AND $grupo != 4 AND $grupo != 18) {
            trigger_error('No tienes acceso a esta característica.');
        }
    }

    public function view_user($user_id){

      $this->template->assign_var('user_id', $user_id);
      
      return $this->helper->render('/moderacion/view.html', 'Vista Revisiones');

    }

    public function view_mod($user_id){

      if($this->vista_staff() != "user"){

        $this->template->assign_var('user_id', $user_id);
        
        return $this->helper->render('/moderacion/viewMod.html', 'Vista Revisiones');
      }else{
        trigger_error('No tienes acceso a esta característica.');
      }

    }

    public function view_admin(){

      $vista = $this->vista_staff(); 
      if($vista == "admin"){   
        return $this->helper->render('/moderacion/viewAdmin.html', 'Vista Revisiones');
      }else{
        trigger_error('No tienes acceso a esta característica.');
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
      return "<br /><a href='/mod'>Volver</a>.";
    }

    public function get_participantes($topic_id = 0){
      
      $es_post = false;

      if ($topic_id == 0) {
        $topic_id = request_var('topic_id', '0');
        $topic_id = str_replace("#", "-", $topic_id);

        //Comprobaciones, tema o post
        $comprobacion = explode("-p", $topic_id);
        if (count($comprobacion) > 1) {
          $es_post = true;
        }else{
          $es_post = false;
        }

        if ($es_post == true) {
          $topic_id = explode("-p", $topic_id);
          $cuenta_topic = count($topic_id)-1;
          $tema = $topic_id[$cuenta_topic];
        }
        else{
          $topic_id = explode("-t", $topic_id);
          $cuenta_topic = count($topic_id)-1;
          $comprobacion = explode("/", $topic_id[$cuenta_topic]);
          if (count($comprobacion) > 1) {
            $tema = $comprobacion[0];
          }else{
            $tema = $topic_id[$cuenta_topic];
          }
        }
      }

      // echo "post id:".$tema."<br>";
      if ($es_post == true) {
        $sql = "SELECT topic_id 
                FROM phpbby1_posts
                WHERE post_id = $tema
                LIMIT 1";

        $query = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($query);
        $tema = $row['topic_id'];
        
      }

      $sql = "SELECT DISTINCT
              u.user_id,
              u.username
            FROM phpbby1_posts AS p
            INNER JOIN phpbby1_users AS u
              ON u.user_id = p.poster_id
            WHERE p.topic_id = $tema";
      $query = $this->db->sql_query($sql);

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<option value='" . $row['user_id'] . "'>" . $row['username'] . "</option>";
      }

      $response = new Response();
      
      $response->setContent($options);
      $response->setStatusCode(Response::HTTP_OK);
      
      // sets a HTTP response header
      $response->headers->set('Content-Type', 'text/html');
      
      // prints the HTTP headers followed by the content
      return $response;

    }

    public function get_revisiones_user(){

      $user_id = request_var('user_id', '0');
      $query = $this->db->sql_query('SELECT * FROM revisiones WHERE id_usuario = '.$user_id);

      $options = "<table class='table table-striped'><thead><tr><th>Tipo</th><th>Moderador Asignado</th><th>Información</th><th>Enlace</th><th>Fecha Creación</th><th>Estado</th></tr></thead><tbody>";

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<tr><td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['moderador_asignado'] . "</td>";
        $options .= "<td>" . $row['informacion'] . "</td>";
        $options .= "<td> <a href='" . $row['enlace'] . "'>Enlace</a></td>";
        $options .= "<td>" . $row['fecha_creacion'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td></tr>";
      }
      $options .= "</tbody></table>";

      $response = new Response();
      
      $response->setContent($options);
      $response->setStatusCode(Response::HTTP_OK);
      
      // sets a HTTP response header
      $response->headers->set('Content-Type', 'text/html');
      
      // prints the HTTP headers followed by the content
      return $response;
    }

    public function get_revisiones_mod(){
      $user_id = request_var('user_id', '0');

      $query = $this->db->sql_query('SELECT * FROM revisiones WHERE moderador_asignado = '.$user_id);

      $options = "<table class='table table-striped'><thead><tr><th>Tipo</th><th>Moderador Asignado</th><th>Información</th><th>Participantes</th><th>Fecha Creación</th><th>Estado</th></tr></thead><tbody>";

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<tr><td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['moderador_asignado'] . "</td>";
        $options .= "<td>" . $row['información'] . "</td>";
        $options .= "<td>" . $row['participantes'] . "</td>";
        $options .= "<td>" . $row['fecha_creacion'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td></tr>";
      }

      $response = new Response();
      
      $response->setContent($options);
      $response->setStatusCode(Response::HTTP_OK);
      
      // sets a HTTP response header
      $response->headers->set('Content-Type', 'text/html');
      
      // prints the HTTP headers followed by the content
      return $response;
    }

    public function get_revisiones_no_asignadas(){

      $query = $this->db->sql_query('SELECT * FROM revisiones WHERE moderador_asignado = 0');

      $options = "<table class='table table-striped'><thead><tr><th>Tipo</th><th>Usuario</th><th>Información</th><th>Participantes</th><th>Fecha Creación</th><th>Estado</th><th>Asignar moderador</th></tr></thead><tbody>";

      $select = $this->get_moderadores();

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<tr><td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['id_usuario'] . "</td>";
        $options .= "<td>" . $row['información'] . "</td>";
        $options .= "<td>" . $row['participantes'] . "</td>";
        $options .= "<td>" . $row['fecha_creacion'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td>";
        $options .= "<td><select class='moderadores'>".$select."</select></td></tr>";
      }

      $response = new Response();
      
      $response->setContent($options);
      $response->setStatusCode(Response::HTTP_OK);
      
      // sets a HTTP response header
      $response->headers->set('Content-Type', 'text/html');
      
      // prints the HTTP headers followed by the content
      return $response;
    }
    
    public function get_moderadores(){
      $query = $this->db->sql_query('SELECT user_id, username FROM phpbby1_users WHERE group_id IN (4, 5, 18) ORDER BY group_id');

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<option value='".$row['user_id']."'>" . $row['username'] . "</option>";
      }

      return $options;
    }

    public function insert_revision($tipo_revision){

      $user_id = $this->user->data['user_id'];

      $sql_array = array(
        'id_usuario'	=> $user_id,
        'estado'		=> "registrada",
        'moderador_asignado'		=> 0,
        'fecha_creacion' => "now()",
      );

      switch ($tipo_revision) {

        case 'activacion_ficha':
          $sql_array['tipo_revision'] = 'Activacion Ficha';
          $sql_array['informacion'] = request_var('activacion_asunto', '0');
          break;

        case 'revision_ficha':
          $sql_array['tipo_revision'] = 'Revision Ficha';
          $sql_array['informacion'] = request_var('asunto_rev_ficha', '0');
          $sql_array['enlace'] = request_var('enlace_rev_ficha', '0');
          break;
          
        case 'revision_mision':
          $sql_array['tipo_revision'] = request_var('rev_mision_tipo', '0');
          $sql_array['enlace'] = request_var('rev_mision_enlace', '0');
          $participantes = request_var('rev_mision_participantes', array(0));
          $sql_array['participantes'] = "";
          foreach ($participantes as $key => $value) {
            $sql_array['participantes'] .= $value."#";
          }
          break;
          
        case 'solicitud_encargo':
          $sql_array['tipo_revision'] = 'Solicitud Encargo';
          $nombre_encargo = request_var('solicitud_nombre', '0');
          $info_encargo = request_var('solicitud_informacion', '0');
          $sql_array['informacion'] = "Nombre: ".$nombre_encargo." \n ".$info_encargo;
          break;

        case 'moderacion_combate':
          $sql_array['tipo_revision'] = 'Moderacion Combate';
          $sql_array['informacion'] = request_var('mod_combate_asunto', '0');
          $sql_array['enlace'] = request_var('mod_combate_enlace', '0');
          break;

        case 'patreon':
          $sql_array['tipo_revision'] = 'Patreon';
          $sql_array['informacion'] = request_var('patreon_asunto', '0');
          $sql_array['enlace'] = request_var('patreon_enlace', '0');
          break;

        case 'revision_tema':
          $sql_array['tipo_revision'] = request_var('rev_tema_tipo', '0');
          $sql_array['enlace'] = request_var('rev_tema_enlace', '0');
          $participantes = request_var('rev_tema_participantes', array(0));
          $sql_array['participantes'] = "";
          foreach ($participantes as $key => $value) {
            $sql_array['participantes'] .= $value."#";
          }
          break;
      }

      $sql = "INSERT INTO revisiones " . $this->db->sql_build_array('INSERT', $sql_array);
      $this->db->sql_query($sql);

      trigger_error('Petción de revision creada correctamente.<br><a href="/mod">Volver a crear una petición de revisión.</a>.');
    }

}
