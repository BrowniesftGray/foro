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

    public function createTecnicas()
    {
        $this->validate_access();

        $this->template->assign_vars(array(
                'RAMA_OPTIONS'	=> $this->get_rama_options(),
            ));

        return $this->helper->render('tecnicas/create.html', 'Administrador de SL');
    }

    public function store()
    {
        $this->validate_access();

        $tipo_array = request_var('tipo', array(0));

        //Tipos
        $tipos = $tipo_array;
        $tipos_texto = "";

          for ($i=0; $i < count($tipos); $i++) {
              switch ($tipos[$i]) {
              case 1:
                $tipos_texto .= "<tipo nin>Ninjutsu</tipo>";
                break;
              case 2:
                $tipos_texto .= "<tipo tai>Taijutsu</tipo>";
                break;
              case 3:
                $tipos_texto .= "<tipo gen>Genjutsu</tipo>";
                break;
              case 4:
                $tipos_texto .= "<tipo fuin>Fuinjutsu </tipo>";
                break;
              case 5:
                $tipos_texto .= "<tipo nin>Senjutsu</tipo>";
                break;
              case 6:
                $tipos_texto .= "<tipo biju>Bijūjutsu</tipo>";
                break;
              case 7:
                $tipos_texto .= "<rango>Kinjutsu</rango>";
                break;
              }
          }

        $tecnica = array(
            'ETIQUETA'          => utf8_normalize_nfc(request_var('etiqueta', '', true)),
            'NOMBRE'            => utf8_normalize_nfc(request_var('name', '', true)),
            'TIPO'              => $tipos_texto,
            'RANGO'             => request_var('rango', '', true),
            'SELLOS'            => utf8_normalize_nfc(request_var('sellos', '', true)),
            'REQUISITOS'        => utf8_normalize_nfc(request_var('requisitos', '', true)),
            'EFECTOS'           => utf8_normalize_nfc(request_var('efectos', '', true)),
            'DAMAGE'            => utf8_normalize_nfc(request_var('damage', '', true)),
            'COSTE'             => utf8_normalize_nfc(request_var('coste', '', true)),
            'DESCRIPCION'       => utf8_normalize_nfc(request_var('descripcion', '', true)),
        );

        $rama = (int) request_var('rama', 1);
        $etiqueta = $tecnica['ETIQUETA'];
        $etiqueta = "[".$etiqueta."][/".$etiqueta."]";
        $etiqueta_envio = $tecnica['ETIQUETA'];

        $nombre = "<nombre>".$tecnica['NOMBRE']."</nombre>";
        $rango = "<rango>".$tecnica['RANGO']."</rango>";
        $sellos = $tecnica['SELLOS'];
        $descripcion = $tecnica['DESCRIPCION'];

        //Damage
        $damage = $tecnica['DAMAGE'];
        $damage = str_replace("/!","</pv>", $damage);
        $damage = str_replace("/@","</pc>", $damage);
        $damage = str_replace("/|","</sta>", $damage);
        $damage = str_replace("!","<pv>", $damage);
        $damage = str_replace("@","<pc>", $damage);
        $damage = str_replace("|","<sta>", $damage);

        //Coste
        $coste = $tecnica['COSTE'];
        $coste = str_replace("/!","</pv>", $coste);
        $coste = str_replace("/@","</pc>", $coste);
        $coste = str_replace("/|","</sta>", $coste);
        $coste = str_replace("!","<pv>", $coste);
        $coste = str_replace("@","<pc>", $coste);
        $coste = str_replace("|","<sta>", $coste);

        //Requisitos
        $requisitos = $tecnica['REQUISITOS'];
        $requisitos = str_replace("/#", "</ul>", $requisitos);
        $requisitos = str_replace("/$","</li>", $requisitos);
        $requisitos = str_replace("/!","</pv>", $requisitos);
        $requisitos = str_replace("/@","</pc>", $requisitos);
        $requisitos = str_replace("/|","</sta>", $requisitos);
        $requisitos = str_replace("#","<ul>", $requisitos);
        $requisitos = str_replace("$","<li>", $requisitos);
        $requisitos = str_replace("!","<pv>", $requisitos);
        $requisitos = str_replace("@","<pc>", $requisitos);
        $requisitos = str_replace("|","<sta>", $requisitos);

        //Efectos
        $efectos = $tecnica['EFECTOS'];
        $efectos = str_replace("/#", "</ul>", $efectos);
        $efectos = str_replace("/$","</li>", $efectos);
        $efectos = str_replace("/!","</pv>", $efectos);
        $efectos = str_replace("/@","</pc>", $efectos);
        $efectos = str_replace("/|","</sta>", $efectos);
        $efectos = str_replace("#","<ul>", $efectos);
        $efectos = str_replace("$","<li>", $efectos);
        $efectos = str_replace("!","<pv>", $efectos);
        $efectos = str_replace("@","<pc>", $efectos);
        $efectos = str_replace("|","<sta>", $efectos);

        $texto_tecnica = "<jutsu>";
        $texto_tecnica .= $nombre.$tipos_texto.$rango."<datos><b>Requisitos:</b>".$requisitos." </br>";
        if ($sellos != "") {
          $texto_tecnica .= "<b>Sellos: </b>".$sellos."<br/>";
        }
        $texto_tecnica .= "<b>Efectos:</b>".$efectos;
        if ($damage != "") {
            $texto_tecnica .= " <b>Daño:</b>&nbsp;".$damage."<br/>";
        }
        $texto_tecnica .= "<b>Coste:</b>&nbsp;".$coste."</datos><desc>".$descripcion."</desc><hr/><c><b onclick='selectCode(this)'>Código:</b><code>".$etiqueta."</code></c>";
        $texto_tecnica .= "</jutsu>";

        $this->crearBbcode($texto_tecnica, $etiqueta_envio, $tecnica, $rama);

        redirect('tecnicas/create');
        //return $this->helper->render('tecnicas/create.html', 'Administrador de SL');
    }

    public function crearBbcode($codigo, $etiqueta, $tecnica, $ramaTec)
    {

      $sql = 'SELECT MAX(bbcode_id) as last_id
  			FROM ' . BBCODES_TABLE;
  		$result = $this->db->sql_query($sql);
  		$bbcode_id = (int) $this->db->sql_fetchfield('last_id');
  		$this->db->sql_freeresult($result);
  		$bbcode_id += 1;

      $coste = $this->get_coste_tecnica($tecnica['RANGO']);

      $rama = array(
          'nombre'    => $tecnica['NOMBRE'],
          'rango'     => $tecnica['RANGO'],
          'etiqueta'  => $tecnica['ETIQUETA'],
          'coste'     => $coste,
          'rama_id'   => $ramaTec,
      );

        $sql_array_bbcode = array(
        'bbcode_id'           => $bbcode_id,
        'bbcode_tag'          => $etiqueta,
        'bbcode_match'        => "[".$etiqueta."][/".$etiqueta."]",
        'bbcode_tpl'          => $codigo,
        'first_pass_match'    => "!\[".$etiqueta."\]\[/".$etiqueta."\]!i",
        'first_pass_replace'  => "[".$etiqueta.":\$uid][/".$etiqueta.":\$uid]",
        'second_pass_match'   => "[".$etiqueta.":\$uid][/".$etiqueta.":\$uid]",
        'second_pass_replace' => "",
      );

    		$this->db->sql_freeresult($query_max);
        $sql = "INSERT INTO ". BBCODES_TABLE . $this->db->sql_build_array('INSERT', $sql_array_bbcode);
        $this->db->sql_query($sql);

        $sql = "INSERT INTO ". TECNICAS_INFO . $this->db->sql_build_array('INSERT', $tecnica);
        $this->db->sql_query($sql);

        // if ($rama['rama_id'] != 0) {
          $sql = "INSERT INTO ". TECNICAS_TABLE . $this->db->sql_build_array('INSERT', $rama);
          $this->db->sql_query($sql);
        // }
    }

    public function get_coste_tecnica($rango)
    {
      switch ($rango) {
        case 'Rango E':
          $coste_tecnica = 0;
          return $coste_tecnica;
        break;
        case 'Rango D':
          $coste_tecnica = 1;
          return $coste_tecnica;
        break;
        case 'Rango C':
          $coste_tecnica = 2;
          return $coste_tecnica;
        break;
        case 'Rango B':
          $coste_tecnica = 3;
          return $coste_tecnica;
        break;
        case 'Rango A':
          $coste_tecnica = 5;
          return $coste_tecnica;
        break;
        case 'Rango S':
          $coste_tecnica = 10;
          return $coste_tecnica;
        break;
      }

    }
    public function get_rama_options()
    {
        $options = "";

        $query = $this->db->sql_query("SELECT rama_id, nombre FROM " . RAMAS_TABLE);

        while ($row = $this->db->sql_fetchrow($query)) {
            $options .= "<option value='" . $row['rama_id'] . "'>" . $row['nombre'] . "</option>";
        }
        $this->db->sql_freeresult($query);

        return $options;
    }

    public function validate_access()
    {
        $grupo = $this->user->data['group_id'];
        if ($grupo != 5) {
            trigger_error("No puedes acceder a esta sección.");
        }
    }

    public function get_return_link($view)
    {
        return "<br /><a href='/tecnicas/$view'>Volver</a>.";
    }
}
