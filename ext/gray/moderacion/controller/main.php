<?php

namespace gray\moderacion\controller;

use Symfony\Component\HttpFoundation\Response;

require_once('/home/shinobil/public_html/includes/functions_user.php');
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

    public function view_rev($rev_id){

      if($this->vista_staff() != "user"){

        $this->template->assign_var('rev_id', $rev_id);
        
        return $this->helper->render('/moderacion/viewRev.html', 'Recompensas');
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

    public function get_vista_recompensa($rev_id){

      $participante = request_var('id_participante', '0');
      if($this->vista_staff() != "user" || $participante == 0){

        $this->template->assign_var('id_revision', $rev_id);
        $this->template->assign_var('id_participante', $participante);
        
        return $this->helper->render('/moderacion/reward.html', 'Recompensas');
      }else{
        if ($participante == 0) {
          trigger_error('Se ha perdido el id del usuario, <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>. ');
        }
        else{
          trigger_error('No tienes acceso a esta característica');
        }
      }

    }

    public function get_vista_recompensa_combate($rev_id){

      $participante = request_var('id_participante', '0');
      if($this->vista_staff() != "user" || $participante == 0){

        $this->template->assign_var('id_revision', $rev_id);
        $this->template->assign_var('id_participante', $participante);
        
        return $this->helper->render('/moderacion/rewardCombate.html', 'Recompensas');
      }else{
        if ($participante == 0) {
          trigger_error('Se ha perdido el id del usuario, <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>. ');
        }
        else{
          trigger_error('No tienes acceso a esta característica');
        }
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
      
      $topic_id = request_var('topic_id', '0');

      $tema = $this->obtener_id_tema($topic_id);
      
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

    public function get_participantes_option($tema){

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
      
      return $options;
    }

    public function get_revisiones_user(){

      $user_id = request_var('user_id', '0');
      $query = $this->db->sql_query('SELECT * FROM revisiones WHERE id_usuario = '.$user_id);

      $options = "<table class='table table-striped'><thead class='thead-dark'><tr><th>Tipo</th><th>Moderador Asignado</th><th>Enlace</th><th>Fecha Creación</th><th>Estado</th></tr></thead><tbody>";

      while ($row = $this->db->sql_fetchrow($query)) {
        if ($row['estado'] == "registrada") {
          $options .= "<tr class='table-active'>";
        }
        if ($row['estado'] == "aceptada") {
          $options .= "<tr class='table-primary'>";
        }
        if ($row['estado'] == "completada") {
          $options .= "<tr class='table-success'>";
        }
        if ($row['estado'] == "rechazada") {
          $options .= "<tr class='table-warning'>";
        }
        $options .= "<td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['moderador_asignado'] . "</td>";
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

      $options = "<table class='table table-striped'><thead><tr><th>Tipo</th><th>Moderador Asignado</th><th>Revisarla</th><th>Información</th><th>Participantes</th><th>Fecha Creación</th><th>Estado</th></tr></thead><tbody>";

      while ($row = $this->db->sql_fetchrow($query)) {
        if ($row['estado'] == "registrada") {
          $options .= "<tr class='table-active'>";
        }
        if ($row['estado'] == "aceptada") {
          $options .= "<tr class='table-primary'>";
        }
        if ($row['estado'] == "completada") {
          $options .= "<tr class='table-success'>";
        }
        if ($row['estado'] == "rechazada") {
          $options .= "<tr class='table-warning'>";
        }
        $options .= "<td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['moderador_asignado'] . "</td>";
        $options .= "<td> <a href='/mod/viewRev/".$row['id_revision']."'>Ir a revisión</a></td>";
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

    public function get_revision_vista(){

      $rev_id = request_var('rev_id', '0');
      $query = $this->db->sql_query('SELECT * FROM revisiones WHERE id_revision = '.$rev_id);
      $row = $this->db->sql_fetchrow($query);
      $tema = $this->obtener_id_tema($row['enlace']);
      $participantes = $this->get_participantes_option($tema);
      $options = '<div class="card" id="vista_rev">
        <h3>Vista Revision</h3><br>';
        if ($row['tipo_revision'] == "Combate") {
          $options .= '<form method="POST" action="/mod/recompensa_combate/'.$rev_id.'">';
        }
        else{
          $options .= '<form method="POST" action="/mod/recompensa_usuario/'.$rev_id.'">';
        }
        $options .= '<div class="card-body">
          <div class="form-group row">
            <label for="longitud" class="col-3 col-form-label text-md-left">Moderador Asignado:</label> 
            <div class="col-6">
              '.$row['moderador_asignado'].' 
            </div>
          </div>
          <div class="form-group row">
            <label for="longitud" class="col-3 col-form-label text-md-left">Tipo de Tema:</label> 
            <div class="col-6">
              '.$row['tipo_revision'].' 
            </div>
          </div>
          <div class="form-group row">
            <label for="longitud" class="col-3 col-form-label text-md-left">Enlace al Tema:</label> 
            <div class="col-6">
             <a href="'.$row['enlace'].'">Ir al tema</a> 
            </div>
          </div>
          ';
          if($row['informacion'] != ""){
            $options .=  '<div class="form-group row">
              <label for="longitud" class="col-3 col-form-label text-md-left">Información:</label> 
              <div class="col-6">
              '.$row['informacion'].' 
              </div>
              </div>';
          }

          $options .= '<div class="form-group row">
            <label for="longitud" class="col-3 col-form-label text-md-left">Participantes:</label>
              <select id="id_participante" name="id_participante" class="col-6 form-control">
                '.$participantes.'
              </select>
            </div>
            <div class="form-group row">
              <div class="offset-4 col-5">
                <button name="submit" type="submit" class="btn btn-primary">Enviar</button>
              </div>
            </div>
          </form>
        </div>';

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

      $options = "<table class='table table-striped'><thead><tr><th>Tipo</th><th>Usuario</th><th>Información</th><th>Participantes</th><th>Fecha Creación</th><th>Estado</th><th>Asignar moderador</th><th></th></tr></thead><tbody>";

      $select = $this->get_moderadores();

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<tr><td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['id_usuario'] . "</td>";
        $options .= "<td>" . $row['información'] . "</td>";
        $options .= "<td>" . $row['participantes'] . "</td>";
        $options .= "<td>" . $row['fecha_creacion'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td>";
        $options .= "<td><form method='POST' action='/admin/asignar/".$row['id_revision']."' <select class='moderadores' name='moderadores'>".$select."</select></td>";
        $options .= "<td><button type='submit' class='btn btn-primary'>Submit</button></td></tr>";
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

      trigger_error('Petición de revision creada correctamente.<br><a href="/mod">Volver a crear una petición de revisión.</a>.');
    }

    public function insert_recompensa(){
      
      $revision = request_var('id_revision', '0');
      $user_id  = request_var('id_participante', '0');
      $entorno  = $this->asignar_puntuacion(request_var('entorno', 'No'));
      $acciones = $this->asignar_puntuacion(request_var('acciones', 'No'));
      $interes  = $this->asignar_puntuacion(request_var('interes', 'No'));
      $longitud = $this->asignar_puntuacion(request_var('longitud', 'No'));
      $gamemaster = $this->asignar_puntuacion(request_var('gamemaster', 'No'));

      //Estas hay que obtenerlas de otra función.
      $info_rev = $this->obtener_info_rev($revision);
      $topic_id = $this->obtener_id_tema($info_rev['enlace']);
      $tipo_tema = $info_rev['tipo_tema'];
      $compas   = $info_rev['compas'];
      
      //Calculamos cosas para la experiencia y tal.
      $bono = $this->calcular_bono($tipo_tema);
      $total = $entorno+$acciones+$interes+$longitud;
      
      //Obtenemos el número de post del usuario.
      $sql = "SELECT	p.poster_id as user_id,
                COUNT(0) as cantidad
              FROM phpbby1_posts p
              WHERE p.topic_id = $topic_id
                  AND p.poster_id = $user_id
              GROUP BY p.poster_id";
      $this->db->sql_query($sql);
      $row = $this->db->sql_fetchrow($query);
      $numero_post = $row['cantidad'];


      if ($bono['experiencia'] == 0) {
        $experiencia = (($numero_post * $total)+20);
        $ryos = 750;
        $puntos_apen = 2;
      }
      else{
        $experiencia = ((($numero_post * $total)*$bono['experiencia'])*$bono['porcentaje']);
        if ($bono['experiencia'] == 3 || $bono['experiencia'] == 5 || $bono['experiencia'] == 7 || $bono['experiencia'] == 12) {
          if ($gamemaster == "Si") {
            $puntos_apen = ceil($experiencia/25);
            $ryos = 0;
          }else{
            $puntos_apen = ceil($experiencia/30);
            $ryos = $bono['ryos'];
          }
        }else{
          if ($gamemaster == "Si") {
            $puntos_apen = ceil($experiencia/15);
            $ryos = 0;
          }else{
            $puntos_apen = ceil($experiencia/20);
            $ryos = $bono['ryos'];
          }
        }
        ($puntos_apen > $bono['limite']) ? $puntos_apen = $bono['limite'] : $puntos_apen = $puntos_apen;

      }

      $array = array();
      $array['ADD_PUNTOS_EXPERIENCIA'] = $experiencia;
      $array['ADD_PUNTOS_APRENDIZAJE'] = $puntos_apen;
      $array['ADD_RYOS'] = $ryos;
      $array['PUNTOS_APRENDIZAJE'] = 0;
      $array['RAZON'] = "Revisión de tema";

      registrar_moderacion($array, $user_id);

      //Insert en la tabla revisiones_recompensas
      $sql_array = array();
      $sql_array['id_pj'] = $user_id;
      $sql_array['id_revision'] = $revision;
      $sql_array['experiencia'] = $experiencia;
      $sql_array['pa'] = $puntos_apen;
      $sql_array['ryos'] = $ryos;

      //Insert en la tabla revisiones_recompensas
      $sql = "INSERT INTO revisiones_recompensas " . $this->db->sql_build_array('INSERT', $sql_array);
      $this->db->sql_query($sql);

      trigger_error('Se ha insertado la recompensa correctamente, <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>. ');
    }

    public function insert_recompensa_combate(){
      
      $revision = request_var('id_revision', '0');
      $user_id  = request_var('id_participante', '0');
      $nivel = request_var('nivel_adversario', '1');
      $metarol  = $this->asignar_puntuacion_combate(request_var('metarol', 'No'));
      $estrategia = $this->asignar_puntuacion_combate(request_var('estrategia', 'No'));
      $longitud_combate  = $this->asignar_puntuacion_combate(request_var('longitud', 'No'));
      $victoria = $this->asignar_puntuacion_combate(request_var('victoria', 'No'));
      $entorno  = $this->asignar_puntuacion(request_var('entorno', 'No'));
      $acciones = $this->asignar_puntuacion(request_var('acciones', 'No'));
      $interes  = $this->asignar_puntuacion(request_var('interes', 'No'));
      $longitud = $this->asignar_puntuacion(request_var('longitud', 'No'));
      $info_rev = $this->obtener_info_rev($revision);
      $topic_id = $this->obtener_id_tema($info_rev['enlace']);
      
      //Calculamos cosas para la experiencia y tal.
      $total = $entorno+$acciones+$interes+$longitud;
      $total_combate = $metarol+$estrategia+$longitud_combate+$victoria;
      
      //Obtenemos el número de post del usuario.
      $sql = "SELECT	p.poster_id as user_id,
                COUNT(0) as cantidad
              FROM phpbby1_posts p
              WHERE p.topic_id = $topic_id
                  AND p.poster_id = $user_id
              GROUP BY p.poster_id";
      $this->db->sql_query($sql);
      $row = $this->db->sql_fetchrow($query);
      $numero_post = $row['cantidad'];

      $experiencia = (($numero_post * $total)+(($nivel*10)*$total_combate));
      $puntos_apen = (4/$total_combate);
      $ryos = 0;

      $array = array();
      $array['ADD_PUNTOS_EXPERIENCIA'] = $experiencia;
      $array['ADD_PUNTOS_APRENDIZAJE'] = $puntos_apen;
      $array['ADD_RYOS'] = $ryos;
      $array['PUNTOS_APRENDIZAJE'] = 0;
      $array['RAZON'] = "Revisión de combate";

      registrar_moderacion($array, $user_id);

      $sql_array = array();
      $sql_array['id_pj'] = $user_id;
      $sql_array['id_revision'] = $revision;
      $sql_array['experiencia'] = $experiencia;
      $sql_array['pa'] = $puntos_apen;
      $sql_array['ryos'] = $ryos;

      //Insert en la tabla revisiones_recompensas
      $sql = "INSERT INTO revisiones_recompensas " . $this->db->sql_build_array('INSERT', $sql_array);
      $this->db->sql_query($sql);

      trigger_error('Se ha insertado la recompensa correctamente, <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>. ');
    }

    public function asignar_puntuacion($criterio){

      switch ($criterio) {
        case 'Si':
            $criterio = 1;
          break;
        case 'A veces':
            $criterio = 0.60;
          break;
        case 'No':
            $criterio = 0.15;
          break;        
      }
      return $criterio;
    }

    public function asignar_puntuacion_combate($criterio){

      switch ($criterio) {
        case 'Si':
            $criterio = 0.25;
          break;
        case 'No':
            $criterio = 0;
          break;        
      }
      return $criterio;
    }

    public function obtener_id_tema($topic_id){

      $es_post = false;

      if ($topic_id == 0) {
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

      return $tema;
    }

    public function obtener_info_rev($revision){

      $sql = "SELECT enlace, 
                tipo_revision AS tipo_tema, 
                participantes AS compas 
              FROM revisiones
              WHERE id_revision = $revision";
        
      $query = $this->db->sql_query($sql);
      $row = $this->db->sql_fetchrow($query);
      return $row;
    }

    public function calcular_bono($tipo_tema){
      $bono = array(
        'experiencia' => 1,
        'limite' => 10,
        'ryos' => 1,
        'porcentaje' => 1
        );

      switch ($tipo_tema) {
        case 'Mision D':
           $bono['experiencia'] = 0;
           $bono['limite'] = 0;
           $bono['ryos'] = 0;
           $bono['porcentaje'] = 0;
          break;

        case 'Mision C':
          $bono['experiencia'] = 1.5;
          $bono['limite'] = 4;
          $bono['ryos'] = 1500;
          $bono['porcentaje'] = 20;
          break;

        case 'Mision B':
          $bono['experiencia'] = 4;
          $bono['limite'] = 10;
          $bono['ryos'] = 3000;
          $bono['porcentaje'] = 30;
          break;

        case 'Mision A':
          $bono['experiencia'] = 6;
          $bono['limite'] = 15;
          $bono['ryos'] = 10000;
          $bono['porcentaje'] = 30;
          break;

        case 'Mision S':
          $bono['experiencia'] = 8;
          $bono['limite'] = 25;
          $bono['ryos'] = 30000;
          $bono['porcentaje'] = 30;
          break;

        case 'Encargo D':
          $bono['experiencia'] = 0;
          $bono['limite'] = 0;
          $bono['ryos'] = 0;
          $bono['porcentaje'] = 0;
          break;

        case 'Encargo C':
          $bono['experiencia'] = 1.5;
          $bono['limite'] = 4;
          $bono['ryos'] = 1500;
          $bono['porcentaje'] = 20;
          break;

        case 'Encargo B':
          $bono['experiencia'] = 4;
          $bono['limite'] = 10;
          $bono['ryos'] = 3000;
          $bono['porcentaje'] = 30;
          break;

        case 'Encargo A':
          $bono['experiencia'] = 6;
          $bono['limite'] = 15;
          $bono['ryos'] = 10000;
          $bono['porcentaje'] = 30;
          break;

        case 'Encargo S':
          $bono['experiencia'] = 8;
          $bono['limite'] = 25;
          $bono['ryos'] = 30000;
          $bono['porcentaje'] = 30;
          break;

        case 'Trama C':
          $bono['experiencia'] = 3;
          $bono['limite'] = 8;
          $bono['ryos'] = 3000;
          $bono['porcentaje'] = 20;
        break;

        case 'Trama B':
          $bono['experiencia'] = 5;
          $bono['limite'] = 30;
          $bono['ryos'] = 3000;
          $bono['porcentaje'] = 30;
          break;

        case 'Trama A':
          $bono['experiencia'] = 7;
          $bono['limite'] = 45;
          $bono['ryos'] = 10000;
          $bono['porcentaje'] = 30;
          break;

        case 'Trama S':
          $bono['experiencia'] = 9;
          $bono['limite'] = 75;
          $bono['ryos'] = 30000;
          $bono['porcentaje'] = 30;
          break;
      }
    }
}
