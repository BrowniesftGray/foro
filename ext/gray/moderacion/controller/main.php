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

    public function home()
    {
      $this->validate_access();
      
      $user_id = $this->user->data['user_id'];
      $vista_rev = "/mod/view/".$user_id;
      $vista_mod = "/mod/viewMod/".$user_id;

      $this->template->assign_var('vista_rev', $vista_rev);
      $this->template->assign_var('vista_mod', $vista_mod);

		  return $this->helper->render('moderacion/index.html', 'Revisiones');
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

        $user_id = $this->user->data['user_id'];
        if (($this->obtener_mod_asignado($rev_id, $user_id)) == false || $this->vista_staff() == "admin") {

          $this->template->assign_var('rev_id', $rev_id);
          return $this->helper->render('/moderacion/viewRev.html', 'Recompensas');
        }
        else{
          trigger_error('No tienes asignada esta revisión.');
        }
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

    public function get_vista_recompensa_tema($rev_id){

      $participante = request_var('id_participante', '0');
      if($this->vista_staff() != "user" || $participante == 0){

        $this->template->assign_var('id_revision', $rev_id);
        $this->template->assign_var('id_participante', $participante);
        
        return $this->helper->render('/moderacion/rewardTema.html', 'Recompensas');
      }else{
        if ($participante == 0) {
          trigger_error('Se ha perdido el id del usuario, <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>. ');
        }
        else{
          trigger_error('No tienes acceso a esta característica');
        }
      }

    }

    public function get_vista_recompensa_mision($rev_id){

      $participante = request_var('id_participante', '0');
      if($this->vista_staff() != "user" || $participante == 0){

        $this->template->assign_var('id_revision', $rev_id);
        $this->template->assign_var('id_participante', $participante);
        
        return $this->helper->render('/moderacion/rewardMision.html', 'Recompensas');
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
      if($this->vista_staff() != "user" || $participante != 0){

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

    public function obtener_mod_asignado($revision, $mod){
      $sql = "SELECT id_revision
              FROM revisiones
              WHERE id_revision = $revision
                AND moderador_asignado = $mod";

      $query = $this->db->sql_query($sql);
      $row = $this->db->sql_fetchrow($query);
      if(!$row){
          $row = false;
        }
      return $row;
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
        if ($row['estado'] == "revisando") {
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

      $options = "<table class='table table-striped'><thead><tr><th>Tipo</th><th>Moderador Asignado</th><th>Revisarla</th><th>Fecha Creación</th><th>Estado</th></tr></thead><tbody>";

      while ($row = $this->db->sql_fetchrow($query)) {
        if ($row['estado'] == "registrada") {
          $options .= "<tr class='table-active'>";
        }
        if ($row['estado'] == "aceptada") {
          $options .= "<tr class='table-primary'>";
        }
        if ($row['estado'] == "revisando") {
          $options .= "<tr class='table-primary'>";
        }
        if ($row['estado'] == "completada") {
          $options .= "<tr class='table-success'>";
        }
        if ($row['estado'] == "rechazada") {
          $options .= "<tr class='table-warning'>";
        }
        if ($row['estado'] == "cerrada") {
          $options .= "<tr class='table-warning'>";
        }
        $options .= "<td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['moderador_asignado'] . "</td>";
        if ($row['estado'] != "cerrada" && $row['estado'] != "completada") {
          $options .= "<td> <a href='/mod/viewRev/".$row['id_revision']."'>Ir a revisión</a></td>";
        }
        // $options .= "<td>" . $row['información'] . "</td>";
        // $options .= "<td>" . $row['participantes'] . "</td>";
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

      $compis = false;
      $rev_id = request_var('rev_id', '0');
      $query = $this->db->sql_query('SELECT * FROM revisiones WHERE id_revision = '.$rev_id);
      $row = $this->db->sql_fetchrow($query);
      $tema = $this->obtener_id_tema($row['enlace']);
      $options = '<div class="card" id="vista_rev">
      <h3>Vista Revision</h3><br>';
      if ($row['tipo_revision'] == "Combate") {
        $participantes = $this->get_participantes_option($tema);
        $options .= '<form method="POST" action="/mod/recompensa_combate/'.$rev_id.'" target="_blank">';
          $compis = true;
        }
        else if(preg_match('/\bMision\b/', $row['tipo_revision']) OR preg_match('/\bEncargo\b/', $row['tipo_revision']) OR preg_match('/\bTrama\b/', $row['tipo_revision'])){
          $participantes = $this->get_participantes_option($tema);
          $options .= '<form method="POST" action="/mod/recompensa_mision/'.$rev_id.'" target="_blank">';
          $compis = true;
        }
        else{
          $participantes = $this->get_participantes_option($tema);
          $options .= '<form method="POST" action="/mod/recompensa_tema/'.$rev_id.'" target="_blank">';
          $compis = true;
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
             <a target="_blank" href="'.$row['enlace'].'">Ir al tema</a> 
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

          if ($compis == true) {
            # code...
            $options .= '<div class="form-group row">
            <label for="longitud" class="col-3 col-form-label text-md-left">Participantes:</label>
            <select id="id_participante" name="id_participante" class="col-6 form-control">
            '.$participantes.'
            </select>
            </div>';
          }
          else{
            $options .= '<div class="form-group row">
            <label for="longitud" class="col-3 col-form-label text-md-left">Ficha Usuario:</label>
            <div class="col-6">
              <a target="_blank" href="/ficha/'.$row['id_usuario'].'"> Ficha del Usuario</a>
            </div>
            </div>';
          }
          if ($row['estado'] != "cerrada" || $compis == false) {
            $options.='<div class="form-group row">
                <div class="offset-4 col-5">
                <button name="submit" type="submit" class="btn btn-primary">Enviar</button>
                </div>
                </div>
                </form>
                </div>';
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

      $options = "<table class='table table-striped'><thead><tr><th>Tipo</th><th>Usuario</th><th>Fecha Creación</th><th>Estado</th><th>Ver Revisión</th></tr></thead><tbody>";

      $select = $this->get_moderadores();

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<tr><td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['id_usuario'] . "</td>";
        $options .= "<td>" . $row['fecha_creacion'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td>";
        $options .= "<td> <a href='/mod/viewRev/".$row['id_revision']."'>Ir a revisión</a></td></tr>";
      }

      $response = new Response();
      
      $response->setContent($options);
      $response->setStatusCode(Response::HTTP_OK);
      
      // sets a HTTP response header
      $response->headers->set('Content-Type', 'text/html');
      
      // prints the HTTP headers followed by the content
      return $response;
    }

    public function get_revisiones_asignadas(){

      $query = $this->db->sql_query('SELECT * FROM revisiones WHERE moderador_asignado <> 0 ORDER BY fecha_creacion');

      $options = "<table class='table table-striped'><thead><tr><th>Tipo</th><th>Usuario</th><th>Fecha Creación</th><th>Estado</th><th>Asignar moderador</th></tr></thead><tbody>";

      $select = $this->get_moderadores();

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<tr><td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['id_usuario'] . "</td>";
        $options .= "<td>" . $row['fecha_creacion'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td>";
        $options .= "<td><form method='POST' class='form-inline' action='/admin/asignar/".$row['id_revision']."'> <select class='moderadores form-control col-7' name='moderadores'>".$select."</select>";
        $options .= "<button type='submit' class='col-5 btn btn-primary'>Asignar</button></td></form></tr>";
      }

      $response = new Response();
      
      $response->setContent($options);
      $response->setStatusCode(Response::HTTP_OK);
      
      // sets a HTTP response header
      $response->headers->set('Content-Type', 'text/html');
      
      // prints the HTTP headers followed by the content
      return $response;
    }

    public function get_revisiones_cerradas(){

      $query = $this->db->sql_query('SELECT * FROM revisiones WHERE estado = "cerrada"');

      $options = "<table class='table table-striped'><thead><tr><th>Tipo</th><th>Usuario</th><th>Fecha Creación</th><th>Estado</th><th>Asignar moderador</th></tr></thead><tbody>";

      $select = $this->get_moderadores();

      while ($row = $this->db->sql_fetchrow($query)) {
        $options .= "<tr><td>" . $row['tipo_revision'] . "</td>";
        $options .= "<td>" . $row['id_usuario'] . "</td>";
        $options .= "<td>" . $row['fecha_creacion'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td>";
        $options .= "<td><form method='POST' class='form-inline' action='/admin/asignar/".$row['id_revision']."'> <select class='moderadores form-control col-7' name='moderadores'>".$select."</select>";
        $options .= "<button type='submit' class='col-5 btn btn-primary'>Asignar</button></td></form></tr>";
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
      $fecha = date("Y-m-d");
      $sql_array = array(
        'id_usuario'	=> $user_id,
        'estado'		=> "registrada",
        'moderador_asignado'		=> 0,
        'fecha_creacion' => $fecha,
      );

      switch ($tipo_revision) {

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

    public function dar_recompensa(){
      
      $revision = request_var('id_revision', '0');
      $user_id  = request_var('id_participante', '0');
      $alt_id   = request_var('id_alternativo', '0');
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
      $bono_temporada = "1";
      $total = $entorno+$acciones+$interes+$longitud;
      
      //Obtenemos el número de post del usuario.
      $sql = "SELECT	p.poster_id as user_id,
                COUNT(0) as cantidad
              FROM phpbby1_posts p
              WHERE p.topic_id = $topic_id
                  AND p.poster_id = $user_id
              GROUP BY p.poster_id";
              // echo $sql;
      $query = $this->db->sql_query($sql);
      $row = $this->db->sql_fetchrow($query);
      $numero_post = $row['cantidad'];

      $experiencia = round(($numero_post * $total)*$bono_temporada);
      // echo "experiencia =".$experiencia;
      // echo "post =".$numero_post;
      // echo "total =".$total;
      if ($gamemaster == "Si") {
        $puntos_apen = ceil($experiencia/15);
      }else{
        $puntos_apen = ceil($experiencia/20);
      }

      if($alt_id != 0){

        $pj_id = get_pj_id($alt_id);
        $check = $this->comprobar_recompensa($revision, $pj_id);
      }else{
        $pj_id = get_pj_id($user_id);
        $check = $this->comprobar_recompensa($revision, $pj_id);
      }
      echo $check;
      if($check != false){
        trigger_error('Este usuario ya ha recibido su recompensa, <a href="/mod/viewRev/'.$revision.'">Volver a la revision.</a>. ');
      }
      else{
          $array = array();
          $array['ADD_PUNTOS_EXPERIENCIA'] = $experiencia;
          $array['ADD_PUNTOS_APRENDIZAJE'] = $puntos_apen;
          $array['ADD_RYOS'] = 0;
          $array['PJ_ID'] = $pj_id;
          $array['PUNTOS_APRENDIZAJE'] = 0;
          $array['RAZON'] = "Revisión de tema";

          registrar_moderacion($array, $user_id);

          //Insert en la tabla revisiones_recompensas
          $sql_array = array();
          $sql_array['id_pj'] = $pj_id;
          $sql_array['id_revision'] = $revision;
          $sql_array['experiencia'] = $experiencia;
          $sql_array['pa'] = $puntos_apen;
          $sql_array['ryos'] = 0;

          //Insert en la tabla revisiones_recompensas
          $sql = "INSERT INTO revisiones_recompensas " . $this->db->sql_build_array('INSERT', $sql_array);
          $this->db->sql_query($sql);

          trigger_error('Se ha insertado la recompensa correctamente, <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>. ');
      }
    }

    public function dar_recompensa_mision(){
      
      $revision = request_var('id_revision', '0');
      $user_id  = request_var('id_participante', '0');
      $alt_id   = request_var('id_alternativo', '0');

      //Campos criterios de rol
      $entorno  = $this->asignar_puntuacion(request_var('entorno', 'No'));
      $acciones = $this->asignar_puntuacion(request_var('acciones', 'No'));
      $interes  = $this->asignar_puntuacion(request_var('interes', 'No'));
      $longitud = $this->asignar_puntuacion(request_var('longitud', 'No'));
      $gamemaster = $this->asignar_puntuacion(request_var('gamemaster', 'No'));

      //Campos bonos
      $bono_tipo  = $this->asignar_puntuacion(request_var('tipo_tema', '0'));
      $bono_rev  = $this->asignar_puntuacion(request_var('bono_mision', '0'));
      $ryos_rev = $this->asignar_puntuacion(request_var('ryos_mision', '0'));
      $compa_rev  = $this->asignar_puntuacion(request_var('bono_por_compa', '0'));

      //Criterios bonos
      $bono_base = 0.25;
      $bono_utilidad  = request_var('bono_utilidad', 'No');
      $bono_coherencia = request_var('bono_coherencia', 'No');
      $bono_sobrevivir  = request_var('bono_sobrevivir', 'No');
      
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
      $query = $this->db->sql_query($sql);
      $row = $this->db->sql_fetchrow($query);
      $numero_post = $row['cantidad'];

      if($bono_utilidad == "Si"){ $bono_utilidad = 0.25;}else{$bono_utilidad = 0;}
      if($bono_coherencia == "Si"){ $bono_coherencia = 0.25;}else{$bono_coherencia = 0;}
      if($bono_sobrevivir == "Si"){ $bono_sobrevivir = 0.25;}else{$bono_sobrevivir = 0;}

      $bono_total = $bono_base + $bono_utilidad + $bono_coherencia + $bono_sobrevivir;
      $bono['experiencia'] = $bono_total * $bono_rev;

      // echo "numero post: ".$numero_post;
      // echo "bono total: ".$bono_total;
      // echo "bono experiencia: ".$bono['experiencia'];
      // echo "bono porcentaje: ".$bono['porcentaje'];
      // echo "total: ".$total;

      if ($bono['experiencia'] == 0) {
        $experiencia = (($numero_post * $total)+20);
        $ryos = $ryos_rev;
        $puntos_apen = 2;
      }
      else{
        $experiencia = round((($numero_post * $total)*$bono['experiencia'])*$bono['porcentaje']);
        if ($bono_tipo == 'Trama C' || $bono_tipo == 'Trama B' || $bono_tipo == 'Trama A' || $bono_tipo == 'Trama S') {
          if ($gamemaster == "Si") {
            $puntos_apen = ceil($experiencia/25);
            $ryos = 0;
          }else{
            $puntos_apen = ceil($experiencia/30);
            $ryos = $ryos_rev;
          }
        }else{
          if ($gamemaster == "Si") {
            $puntos_apen = ceil($experiencia/15);
            $ryos = 0;
          }else{
            $puntos_apen = ceil($experiencia/20);
            $ryos = $ryos_rev;
          }
        }
        ($puntos_apen > $bono['limite']) ? $puntos_apen = $bono['limite'] : $puntos_apen = $puntos_apen;

      }
      // echo "experiencia: ".$experiencia;
      if($alt_id != 0){

        $pj_id = get_pj_id($alt_id);
        $check = $this->comprobar_recompensa($revision, $pj_id);
      }else{
        $pj_id = get_pj_id($user_id);
        $check = $this->comprobar_recompensa($revision, $pj_id);
      }

      echo $check;
      if($check != false){
        trigger_error('Este usuario ya ha recibido su recompensa, <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>. ');
      }
      else{
          $array = array();
          $array['ADD_PUNTOS_EXPERIENCIA'] = $experiencia;
          $array['ADD_PUNTOS_APRENDIZAJE'] = $puntos_apen;
          $array['ADD_RYOS'] = $ryos;
          $array['PJ_ID'] = $pj_id;
          $array['PUNTOS_APRENDIZAJE'] = 0;
          $array['RAZON'] = "Revisión de tema";

          registrar_moderacion($array, $user_id);

          //Insert en la tabla revisiones_recompensas
          $sql_array = array();
          $sql_array['id_pj'] = $pj_id;
          $sql_array['id_revision'] = $revision;
          $sql_array['experiencia'] = $experiencia;
          $sql_array['pa'] = $puntos_apen;
          $sql_array['ryos'] = $ryos;

          //Insert en la tabla revisiones_recompensas
          $sql = "INSERT INTO revisiones_recompensas " . $this->db->sql_build_array('INSERT', $sql_array);
          $this->db->sql_query($sql);

          trigger_error('Se ha insertado la recompensa correctamente, <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>. ');
      }
    }

    public function dar_recompensa_combate(){
      
      $revision = request_var('id_revision', '0');
      $user_id  = request_var('id_participante', '0');
      $nivel = request_var('nivel_adversario', '1');
      $alt_id   = request_var('id_alternativo', '0');
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
      
      // echo "metarol: ".$metarol;
      // echo "<br>estrategia: ".$estrategia;
      // echo "<br>longitud_combate: ".$longitud_combate;
      // echo "<br>victoria: ".$victoria;
      //Calculamos cosas para la experiencia y tal.
      $total = $entorno+$acciones+$interes+$longitud;
      $total_combate = $metarol+$estrategia+$longitud_combate+$victoria;
      // echo "<br>total_combate: ".$total_combate;
      
      //Obtenemos el número de post del usuario.
      $sql = "SELECT	p.poster_id as user_id,
                COUNT(0) as cantidad
              FROM phpbby1_posts p
              WHERE p.topic_id = $topic_id
                  AND p.poster_id = $user_id
              GROUP BY p.poster_id";
      $query = $this->db->sql_query($sql);
      $row = $this->db->sql_fetchrow($query);
      $numero_post = $row['cantidad'];

      $experiencia = round((($numero_post * $total)+(($nivel*10)*$total_combate)));
      // echo "<br>experiencia: ".$experiencia;
      $puntos_apen = (4*$total_combate);
      // echo "<br>puntos_apen: ".$puntos_apen;
      $ryos = 0;
      
      if($alt_id != 0){

        $pj_id = get_pj_id($alt_id);
        $check = $this->comprobar_recompensa($revision, $pj_id);
      }else{
        $pj_id = get_pj_id($user_id);
        $check = $this->comprobar_recompensa($revision, $pj_id);
      }

      if($check != false){
        trigger_error('Este usuario ya ha recibido su recompensa, <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>. ');
      }
      else{
        $array = array();
        $array['ADD_PUNTOS_EXPERIENCIA'] = $experiencia;
        $array['ADD_PUNTOS_APRENDIZAJE'] = $puntos_apen;
        $array['ADD_RYOS'] = $ryos;
        $array['PJ_ID'] = $pj_id;
        $array['PUNTOS_APRENDIZAJE'] = 0;
        $array['RAZON'] = "Revisión de combate";

        registrar_moderacion($array, $user_id);
        
        $sql_array = array();
        $sql_array['id_pj'] = $pj_id;
        $sql_array['id_revision'] = $revision;
        $sql_array['experiencia'] = $experiencia;
        $sql_array['pa'] = $puntos_apen;
        $sql_array['ryos'] = $ryos;
        
        //Insert en la tabla revisiones_recompensas
        $sql = "INSERT INTO revisiones_recompensas " . $this->db->sql_build_array('INSERT', $sql_array);
        $this->db->sql_query($sql);
        
        trigger_error('Se ha insertado la recompensa correctamente, <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>. ');
      }
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
           $bono['porcentaje'] = 1;
          break;

        case 'Mision C':
          $bono['experiencia'] = 1.5;
          $bono['limite'] = 4;
          $bono['ryos'] = 1500;
          $bono['porcentaje'] = 0.20;
          break;

        case 'Mision B':
          $bono['experiencia'] = 4;
          $bono['limite'] = 10;
          $bono['ryos'] = 3000;
          $bono['porcentaje'] = 0.30;
          break;

        case 'Mision A':
          $bono['experiencia'] = 6;
          $bono['limite'] = 15;
          $bono['ryos'] = 10000;
          $bono['porcentaje'] = 0.30;
          break;

        case 'Mision S':
          $bono['experiencia'] = 8;
          $bono['limite'] = 25;
          $bono['ryos'] = 30000;
          $bono['porcentaje'] = 0.30;
          break;

        case 'Encargo D':
          $bono['experiencia'] = 0;
          $bono['limite'] = 0;
          $bono['ryos'] = 0;
          $bono['porcentaje'] = 1;
          break;

        case 'Encargo C':
          $bono['experiencia'] = 1.5;
          $bono['limite'] = 4;
          $bono['ryos'] = 1500;
          $bono['porcentaje'] = 0.20;
          break;

        case 'Encargo B':
          $bono['experiencia'] = 4;
          $bono['limite'] = 10;
          $bono['ryos'] = 3000;
          $bono['porcentaje'] = 0.30;
          break;

        case 'Encargo A':
          $bono['experiencia'] = 6;
          $bono['limite'] = 15;
          $bono['ryos'] = 10000;
          $bono['porcentaje'] = 0.30;
          break;

        case 'Encargo S':
          $bono['experiencia'] = 8;
          $bono['limite'] = 25;
          $bono['ryos'] = 30000;
          $bono['porcentaje'] = 0.30;
          break;

        case 'Trama C':
          $bono['experiencia'] = 3;
          $bono['limite'] = 8;
          $bono['ryos'] = 3000;
          $bono['porcentaje'] = 0.20;
        break;

        case 'Trama B':
          $bono['experiencia'] = 5;
          $bono['limite'] = 30;
          $bono['ryos'] = 3000;
          $bono['porcentaje'] = 0.30;
          break;

        case 'Trama A':
          $bono['experiencia'] = 7;
          $bono['limite'] = 45;
          $bono['ryos'] = 10000;
          $bono['porcentaje'] = 0.30;
          break;

        case 'Trama S':
          $bono['experiencia'] = 9;
          $bono['limite'] = 75;
          $bono['ryos'] = 30000;
          $bono['porcentaje'] = 0.30;
          break;
      }
    return $bono;
     }

    function update_revision(){
      
      global $user;
      $revision = request_var('id_revision', '0');
      $estado  = request_var('estado', '0');

      $sql = "  UPDATE revisiones
                SET
                  estado = '".$estado."'
                WHERE id_revision = $revision";

      $query = $this->db->sql_query($sql);

      if($estado == "cerrada"){

        $tema = $this->obtener_info_rev($revision);
        $tipo_tema = $tema['tipo_tema'];
        $enlace = $tema['enlace'];
        $topic_id = $this->obtener_id_tema($enlace);
        // echo "topic id: ".$enlace;

        $sql = "SELECT COUNT(0) as cantidad
              FROM phpbby1_posts p
              WHERE p.topic_id = $topic_id";
        // echo $sql;
        $query = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($query);
        $numero_post = $row['cantidad'];
        
        $mod = $this->calcular_puntos_mod($tipo_tema);
        $puntos_totales = "";
        // print_r($row);

        // echo $numero_post;
        if ($mod['post'] == 1) {
          $puntos_totales = $mod['puntos'] * $numero_post;
        }else if($mod['post'] == 0){
          $puntos_totales = $mod['puntos'];
        }
        // echo $puntos_totales;
        $mod_id = $this->user->data['user_id'];
        //Hacer un select antes

        $user->get_profile_fields($mod_id);

        if (!array_key_exists('pf_puntos_mod', $user->profile_fields)) {
          $puntos_moderacion = 0;
        }
        else{
          $puntos_moderacion = $user->profile_fields['pf_puntos_mod'];
        }


        $this->db->sql_query('UPDATE '.PROFILE_FIELDS_DATA_TABLE."
										SET pf_puntos_mod = $puntos_moderacion + $puntos_totales
                    WHERE user_id = $mod_id");
                    
                
        $sql_array = array();
        $sql_array['mod_id'] = $mod_id;
        $sql_array['puntos_mod'] = $puntos_totales;
        $sql_array['rev_id'] = $revision;
        
        //Insert en la tabla revisiones_recompensas
        $sql = "INSERT INTO revisiones_puntos_mod " . $this->db->sql_build_array('INSERT', $sql_array);
        $this->db->sql_query($sql);
      }
      
      trigger_error('Revisión modificada correctamente al estado '.$estado.'. <a href="/mod/viewRev/'.$rev_id.'">Volver a la revision.</a>.');
    }

    function update_revision_mod($revision_id){
      
      $revision = $revision_id;
      $moderadores  = request_var('moderadores', '0');

      $sql = "  UPDATE revisiones
                SET
                  moderador_asignado = '".$moderadores."'
                WHERE id_revision = $revision";

      $query = $this->db->sql_query($sql);
      
      trigger_error('Revisión asignada correctamente al moderador/a: '.$moderadores.'. <a href="/mod/viewAdmin">Volver a la vista</a>.');
    }

    function comprobar_recompensa($revision, $id_pj){

       $query = $this->db->sql_query("SELECT id_recompensa
                FROM revisiones_recompensas
                WHERE id_revision = $revision
                  AND id_pj = $id_pj");
        if ($row = $this->db->sql_fetchrow($query)) {
          $this->db->sql_freeresult($query);
          return $row;
        }
        else {
          return false;
        }
    }

    public function calcular_puntos_mod($tipo_tema){

      $bono = array(
        'puntos' => 0,
        'post' => 0,
        );

      switch ($tipo_tema) {

        case 'Crónica':
          $bono['puntos'] = 6;
          $bono['post'] = 0;
        break;
        
        case 'Activacion Ficha':
          $bono['puntos'] = 6;
          $bono['post'] = 0;
        break;
        
        case 'Revision Ficha':
          $bono['puntos'] = 3;
          $bono['post'] = 0;
        break;
        
        case 'Solicitud Encargo':
          $bono['puntos'] = 2;
          $bono['post'] = 0;
        break;
        
        case 'Moderacion Combate':
          $bono['puntos'] = 30;
          $bono['post'] = 0;
        break;
        
        case 'Patreon':
          $bono['puntos'] = 5;
          $bono['post'] = 0;
        break;
        
        default:
           $bono['puntos'] = 1;
           $bono['post'] = 1;
          break;
      }
      
      return $bono;
    }
}
