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

    function store()
    {
        $this->validate_access();

        $tecnica = array(
            'ETIQUETA'       => (int) request_var('etiqueta', 1),
            'NOMBRE'            => (int) request_var('name', 1),
            'TIPO'          => (int) request_var('tipo', 1),
            'RANGO'          => (int) request_var('rango', 1),
            'SELLOS'     => (int) request_var('sellos', 1),
            'REQUISITOS'          => (int) request_var('requisitos', 1),
            'EFECTOS'          => (int) request_var('efectos', 1),
            'DAMAGE_PV'          => (int) request_var('pv_damage', 1),
            'DAMAGE_PC'          => (int) request_var('pc_damage', 1),
            'DAMAGE_STA'          => (int) request_var('sta_damage', 1),
            'DAMAGE_TURNO'          => (int) request_var('por_turno_damage', 1),
            'COSTE_PV'          => (int) request_var('pv', 1),
            'COSTE_PC'          => (int) request_var('pc', 1),
            'COSTE_STA'          => (int) request_var('sta', 1),
            'COSTE_TURNO'          => (int) request_var('por_turno', 1),
            'DESCRIPCION'          => (int) request_var('descripcion', 1),
        );

        $etiqueta = $tecnica['ETIQUETA'];

        $nombre = "<nombre>".$tecnica['NOMBRE']."</nombre>";
        $rango = "<rango>".$tecnica['rango']."</rango>";
        $sellos = $tecnica['SELLOS'];
        $descripcion = $tecnica['DESCRIPCION'];

        //Daño
        $damage = "";
        $contador = 0;
        if ($tecnica['DAMAGE_PV'] != "") {
          $damage .= $tecnica['DAMAGE_PV'];
          $contador++;
        }
        if ($tecnica['DAMAGE_STA'] != "" ) {
          if ($contador != 0) {
            $damage .= $damage.", ".$tecnica['DAMAGE_STA'];
            $contador++;
          }
          else{
            $damage .= $tecnica['DAMAGE_STA'];
            $contador++;
          }
        }
        if ($tecnica['DAMAGE_PC'] != "" ) {
          if ($contador != 0) {
            $damage .= $damage.", ".$tecnica['DAMAGE_PC'];
            $contador++;
          }
          else{
            $damage .= $tecnica['DAMAGE_PC'];
            $contador++;
          }
        }
      if (isset($tecnica['DAMAGE_TURNO'])) {
        $damage .= $damage." por turno."
      }


      //Coste
      $coste = "";
      $contador = 0;
      if ($tecnica['COSTE_PC '] != "") {
        $coste .= $tecnica['COSTE_PC'];
        $contador++;
      }
      if ($tecnica['COSTE_STA'] != "" ) {
        if ($contador != 0) {
          $coste .= $coste.", ".$tecnica['COSTE_STA'];
          $contador++;
        }
        else{
          $coste .= $tecnica['DAMAGE_STA'];
          $contador++;
        }
      }
      if ($tecnica['COSTE_PV'] != "" ) {
        if ($contador != 0) {
          $coste .= $coste.", ".$tecnica['COSTE_PV'];
          $contador++;
        }
        else{
          $coste .= $tecnica['COSTE_PV'];
          $contador++;
        }
      }
    if (isset($tecnica['COSTE_TURNO'])) {
      $coste .= $coste." por turno."
    }

//         <jutsu>
// <nombre>Nombre Japonés (Nombre español)</nombre>
// <tipo nin>Ninjutsu </tipo><tipo tai>Taijutsu </tipo> <tipo fuin>Fuinjutsu </tipo> <tipo gen>Genjutsu</tipo>
// <rango>Rango [E, D, C, B, A, S]</rango>
// <datos>
// <b>Requisitos:</b>
// <ul>
// <li>X puntos en atributos Fisicos.</li>
// <li>X puntos en atributos Espirituales.</li>
// <li>Técnica aprendida.</li>
// </ul>
// <b>Sellos:</b> → → <br/>
// <b>Efectos:</b>
// <ul>
// <li>EfectoA.</li>
// <li>EfectoB.</li>
// </ul>
// <b>Daño:</b> <pv>X PV</pv>.<br/>
// <b>Coste:</b> <pc>X PC</pc> <sta>X STA</sta>.
// </datos>
// <desc>
// Descripción.
// </desc>
// <hr/>
// <c><b onclick="selectCode(this)">Código:</b><code>[jutsu][/jutsu]</code></c>
// </jutsu>
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
