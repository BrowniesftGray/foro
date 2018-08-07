<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
?>
<?php
/*
echo $user->data['user_id'];
echo "<br>";
if ($user->data['user_id'] == ANONYMOUS)
{
   echo 'Please login!';
}

else
{
   echo 'Thanks for logging in, ' . $user->data['username_clean'];
}
^*/
//your PHP and/or HTML code goes here
$user_id = $user->data['user_id'];
if ($user->data['user_id'] == ANONYMOUS) {
  echo '<div class="alert alert-danger fade show">No está conectado, vuelve a la página principal y <a href="index.php">conéctese</a></div>';
}
else{
    $servidor  = "localhost";
    $basedatos = "foro";
    $usuario   = "root";
    $password  = "";

    // Creamos la conexión al servidor.
    $conexion = mysql_connect($servidor, $usuario, $password) or die(mysql_error());
    mysql_query("SET NAMES 'utf8'", $conexion);

    // Seleccionar la base de datos en esa conexion.
    mysql_select_db($basedatos, $conexion) or die(mysql_error());

    // Consulta SQL para obtener los datos de los videojuegos
    $sql = "SELECT * FROM personajes WHERE user_id=$user_id";

    $resultados = mysql_query($sql, $conexion) or die(mysql_error());
    while ($fila = mysql_fetch_array($resultados, MYSQL_ASSOC)) {
        // Almacenamos en un array cada una de las filas que vamos leyendo del recordset.
        $datos[] = $fila;
    }

    $request->enable_super_globals();
    if (count($datos) != 0) {
      $sid = $_REQUEST['sid'];
    echo '<div class="alert alert-danger fade show">Ya tiene una ficha, no puede crear otra, vuelva al <a href="index.php?sid='.$sid.'">inicio</a></div>';
  }
  else{
?>

    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
     <link rel="stylesheet" type="text/css" href="bootstrap.css">
    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title></title>
<script type="text/javascript">
$( document ).ready(function() {
  $('#botonComprobar').click(function() {
    procesoFicha();
  });
});

function procesoFicha(){
  if (validacionFicha()) {
    tratarRespuestaFicha();
  }
}

function validacionFicha(){
  $errores = "";
  $error = false;

  var user_id = document.getElementById("user_id").value;
  var nombre = document.getElementById("nombre").value;
  var edad = document.getElementById("edad").value;
  var rango = document.getElementById("rango").value;
  var selectAldea = document.getElementById("selectAldea").value;
  var ojos = document.getElementById("selectOjos").value;
  var pelo = document.getElementById("selectPelo").value;
  var altura = document.getElementById("altura").value;
  var peso = document.getElementById("peso").value;
  var selectComplexion = document.getElementById("selectComplexion").value;
  var clan = document.getElementById("clan").value;
  var selectElemento = document.getElementById("selectElemento").value;
  var selectEspecialidad = document.getElementById("selectEspecialidad").value;
  var selectElemento2 = document.getElementById("selectElemento2").value;
  var selectEspecialidad2 = document.getElementById("selectEspecialidad2").value;
  var invocacion = document.getElementById("invocacion").value;
  var atrFuerza = document.getElementById("atrFuerza").value;
  var atrRes = document.getElementById("atrRes").value;
  var artAg = document.getElementById("artAg").value;
  var atrEsp = document.getElementById("atrEsp").value;
  var atrCon = document.getElementById("atrCon").value;
  var atrVol = document.getElementById("atrVol").value;
  var descFis = document.getElementById("descFis").value;
  var descPsic = document.getElementById("descPsic").value;
  var descHis = document.getElementById("descHis").value;

  var total = parseInt(atrRes) + parseInt(atrFuerza) + parseInt(artAg) + parseInt(atrEsp) + parseInt(atrCon) + parseInt(atrVol);

  if (nombre.length < 5) {
    $error = true;
    $errores += '<div class="alert alert-danger alert-dismissible fade show">El nombre debe tener cinco carácteres o más. <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
  }

  if (edad < 10 && edad > 20) {
    $error = true;
    $errores += '<div class="alert alert-danger alert-dismissible fade show">La edad debe tener entre 10 y 20 años. <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
  }

  if (altura < 120) {
    $error = true;
    $errores += '<div class="alert alert-danger alert-dismissible fade show">La altura debe ser 120 centímetros o más. <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
  }

  if (peso < 40 && peso > 200) {
    $error = true;
    $errores += '<div class="alert alert-danger alert-dismissible fade show">El peso debe estar entre 40 y 200 kilos. <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
  }

  if (total != 30) {
    $error = true;
    $errores += '<div class="alert alert-danger alert-dismissible fade show">La suma total de los atributos debe ser 30. <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
  }

  if (descFis.length < 200) {
    $error = true;
    $errores += '<div class="alert alert-danger alert-dismissible fade show">La descripción física debe tener al menos 200 carácteres. <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
  }

  if (descPsic.length < 600) {
    $error = true;
    $errores += '<div class="alert alert-danger alert-dismissible fade show">La descripción psicológica debe tener al menos 600 carácteres. <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
  }

  if (descHis.length < 800) {
    $error = true;
    $errores += '<div class="alert alert-danger alert-dismissible fade show">La historia debe tener al menos 800 carácteres. <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
  }

  if ($error == true) {
    $("#alertas").empty();
    $('#alertas').append($errores);
    return false;
  }
  else{
    return true;
  }
}

function tratarRespuestaFicha(){

  var user_id = document.getElementById("user_id").value;
  var nombre = document.getElementById("nombre").value;
  var edad = document.getElementById("edad").value;
  var rango = document.getElementById("rango").value;
  var selectAldea = document.getElementById("selectAldea").value;
  var ojos = document.getElementById("selectOjos").value;
  var pelo = document.getElementById("selectPelo").value;
  var altura = document.getElementById("altura").value;
  var peso = document.getElementById("peso").value;
  var selectComplexion = document.getElementById("selectComplexion").value;
  var clan = document.getElementById("clan").value;
  var selectElemento = document.getElementById("selectElemento").value;
  var selectEspecialidad = document.getElementById("selectEspecialidad").value;
  var selectElemento2 = document.getElementById("selectElemento2").value;
  var selectEspecialidad2 = document.getElementById("selectEspecialidad2").value;
  var invocacion = document.getElementById("invocacion").value;
  var atrFuerza = document.getElementById("atrFuerza").value;
  var atrRes = document.getElementById("atrRes").value;
  var artAg = document.getElementById("artAg").value;
  var atrEsp = document.getElementById("atrEsp").value;
  var atrCon = document.getElementById("atrCon").value;
  var atrVol = document.getElementById("atrVol").value;
  var descFis = document.getElementById("descFis").value;
  var descPsic = document.getElementById("descPsic").value;
  var descHis = document.getElementById("descHis").value;


  var oPersonaje = {
    user_id: user_id,
    nombre: nombre,
    edad: edad,
    rango: rango,
    selectAldea: selectAldea,
    ojos: ojos,
    pelo: pelo,
    altura: altura,
    peso: peso,
    selectComplexion: selectComplexion,
    clan: clan,
    selectElemento: selectElemento,
    selectEspecialidad: selectEspecialidad,
    selectElemento2: selectElemento2,
    selectEspecialidad2: selectEspecialidad2,
    invocacion: invocacion,
    atrFuerza: atrFuerza,
    atrRes: atrRes,
    artAg: artAg,
    atrEsp: atrEsp,
    atrCon: atrCon,
    atrVol: atrVol,
    descFis: descFis,
    descPsic: descPsic,
    descHis: descHis
  };

// Formateo de parametro POST
var sParametroPOST = "datos=" + JSON.stringify(oPersonaje);

// Script de envio
var sURL = encodeURI("guardarFicha.php");

AjaxFicha(sURL,sParametroPOST);
}


/* LLAMADAS AJAX */
function AjaxFicha(sURL,sParametroPOST){

oAjaxAltaFicha = objetoXHR();

oAjaxAltaFicha.open("POST",sURL,true);

// Para peticiones con metodo POST
  oAjaxAltaFicha.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

oAjaxAltaFicha.onreadystatechange = respuestaAltaFicha;
//	oAjaxAltaProp.addEventListener("readystatechange",respuestaAltaProp,false);

oAjaxAltaFicha.send(sParametroPOST);

}

function respuestaAltaFicha(){

  if(oAjaxAltaFicha.readyState == 4 && oAjaxAltaFicha.status ==200)	{
    var oArrayRespuesta = JSON.parse(oAjaxAltaFicha.responseText);
    if (oArrayRespuesta[0] == true){
        alert(oArrayRespuesta[1]);
    } else {
        alert(oArrayRespuesta[1]);
    }
  }
}

function objetoXHR() {
        if (window.XMLHttpRequest) {
            return new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            var versionesIE = new Array('Msxml2.XMLHTTP.5.0', 'Msxml2.XMLHTTP.4.0', 'Msxml2.XMLHTTP.3.0', 'Msxml2.XMLHTTP', 'Microsoft.XMLHTTP');
            for (var i = 0; i < versionesIE.length; i++) {
                try {
                    return new ActiveXObject(versionesIE[i]);
                } catch (errorControlado) {} //Capturamos el error,
            }
        }
        throw new Error("No se pudo crear el objeto XMLHttpRequest");
}
</script>
<?php

## Function para rellenar el select para seleccionar Clan, Kekkei Genkai —  o Arte —
 function rellenarSelectIncial(){
    $opciones = Array('Sin Clan',
    'Clan — Kamizuru',
    'Clan — Jyugo',
    'Clan — Nendo',
    'Clan — Orochi',
    'Clan — Uzumaki',
    'Clan — Sabaku',
    'Clan — Aburame',
    'Clan — Senju',
    'Clan — Hyuga',
    'Clan — Kaguya',
    'Clan — Inuzuka',
    'Clan — Yuki',
    'Clan — Yotsuki',
    'Clan — Uchiha',
    'Clan — Yamanaka',
    'Clan — Akimichi',
    'Clan — Hozuki',
    'Clan — Nara',
    'Arte — Gotokuji',
    'Arte — Yūrei',
    'Arte — Tenkasai',
    'Arte — Inku',
    'Arte — Tessen',
    'Arte — Origami',
    'Kekkei Genkai — Shakuton',
    'Kekkei Genkai — Yōton',
    'Kekkei Genkai — Jiton',
    'Kekkei Genkai — Shōton',
    'Kekkei Genkai — Futton',
    'Kekkei Genkai — Ranton');

    foreach ($opciones as $valor => $valor2){
        echo "<option>";
        echo $valor2;
        echo "</option>";
    }
 }

##Conexión a base de datos
if (!isset($_POST['btnInicialAceptar'])) {
  $sid = $_REQUEST['sid'];
  ?>
  <div class="row container-fluid justify-content-center">

    <form class="col-6"action="personaje.php?sid=<?php echo $sid; ?>" method="post">
      <div class="form-group">
   <label class=" control-label" for="selectbasic">Seleccione su Clan, Kekkei Genkai o Arte</label>
   <div class="">
     <select id="selectClan" name="selectClan" class="form-control">
       <?php
       rellenarSelectIncial();
       ?>
     </select>
   </div>
 </div>
<?php

 ?>
 <div class="form-group">
  <label class=" control-label" for="button1id"></label>
  <div class="col-md-8">
    <button type="submit" name="btnInicialAceptar" class="btn btn-primary">Aceptar</button>
    <button id="button2id" name="button2id" class="btn btn-danger">Cancelar</button>
  </div>
</div>

   </form>
 </div>
<?php
}
else{
 ## Function para rellenar el select de primer elemento
 function rellenarSelectElemento(){
    $elementos = Array('Katon (火遁, Elemento Fuego)',
        'Suiton (水遁, Elemento Agua)',
        'Raiton (雷遁, Elemento Rayo)',
        'Fuuton (風遁, Elemento Viento)',
        'Doton (土遁, Elemento Tierra)'
    );

    foreach ($elementos as $valor => $valor2){
        echo "<option>";
        echo $valor2;
        echo "</option>";
    }
 }

 ## Function para rellenar el select de primera especialidad
 function rellenarSelectEspecialidad(){
    $especialidades = Array('Genjutsu (幻術, Ilusiones)',
        'Taijutsu (体術, Técnicas de Cuerpo a Cuerpo)',
        'Bukijutsu (武器术, Técnicas de armas)',
        'Fūinjutsu (封印術, Sellado)',
        'Iryō Ninjutsu (医療忍術, Médico)',
        'Kanchi Taipu (感知タイプ - Sensorial)'
    );

    foreach ($especialidades as $valor => $valor2){
        echo "<option>";
        echo $valor2;
        echo "</option>";
    }
 }

 switch ($_POST['selectClan']) {

   case 'Sin Clan':
   $invocacion = "";
   $especialidad1 = "";
   $elemento1 = "";
   $especialidad2 = "";
   $elemento2 = "";
   break;

   case 'Clan — Kamizuru':
    $invocacion = "Abejas";
    $especialidad1 = "";
    $elemento1 = "";
    $especialidad2 = "";
    $elemento2 = "";
   break;

   case 'Clan — Jyugo':
     $invocacion = "";
     $especialidad1 = "Taijutsu (体術, Técnicas de Cuerpo a Cuerpo)";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Nendo':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Doton (土遁, Elemento Tierra)";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Orochi':
     $invocacion = "Serpientes";
     $especialidad1 = "";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Uzumaki':
     $invocacion = "";
     $especialidad1 = "Fūinjutsu (封印術, Sellado)";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Sabaku':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Doton (土遁, Elemento Tierra)";
     $especialidad2 = "";
     $elemento2 = "Fuuton (風遁, Elemento Viento)";
   break;

   case 'Clan — Aburame':
     $invocacion = "Insectos";
     $especialidad1 = "";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Senju':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Doton (土遁, Elemento Tierra)";
     $especialidad2 = "";
     $elemento2 = "Suiton (水遁, Elemento Agua)";
   break;

   case 'Clan — Hyuga':
     $invocacion = "";
     $especialidad1 = "Taijutsu (体術, Técnicas de Cuerpo a Cuerpo)";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Kaguya':
     $invocacion = "";
     $especialidad1 = "kaguya";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Inuzuka':
     $invocacion = "Perros";
     $especialidad1 = "Taijutsu (体術, Técnicas de Cuerpo a Cuerpo)";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Yuki':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Suiton (水遁, Elemento Agua)";
     $especialidad2 = "";
     $elemento2 = "Fuuton (風遁, Elemento Viento)";
   break;

   case 'Clan — Yotsuki':
     $invocacion = "";
     $especialidad1 = "Taijutsu (体術, Técnicas de Cuerpo a Cuerpo)";
     $elemento1 = "Raiton (雷遁, Elemento Rayo)";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Uchiha':
     $invocacion = "";
     $especialidad1 = "Genjutsu";
     $elemento1 = "Katon (火遁, Elemento Fuego)";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Yamanaka':
     $invocacion = "";
     $especialidad1 = "Kanchi Taipu";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Akimichi':
     $invocacion = "";
     $especialidad1 = "Taijutsu (体術, Técnicas de Cuerpo a Cuerpo)";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Hozuki':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Suiton (水遁, Elemento Agua)";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Clan — Nara':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "";
     $especialidad2 = "Ninjutsu";
     $elemento2 = "";
   break;

   case 'Arte —  Gotokuji':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Suiton (水遁, Elemento Agua)";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Arte — Yūrei':
     $invocacion = "Dokis";
     $especialidad1 = "Genjutsus";
     $elemento1 = "";
     $especialidad2 = "Ninjutsus";
     $elemento2 = "";
   break;

   case 'Arte — Tenkasai':
     $invocacion = "";
     $especialidad1 = "Bukijutsu (武器术, Técnicas de armas)";
     $elemento1 = "";
     $especialidad2 = "Fūinjutsu (封印術, Sellado)";
     $elemento2 = "";
   break;

   case 'Arte — Inku':
     $invocacion = "";
     $especialidad1 = "Fūinjutsu (封印術, Sellado)";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Arte — Tessen':
     $invocacion = "Comadrejas";
     $especialidad1 = "";
     $elemento1 = "Fuuton (風遁, Elemento Viento)";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Arte — Origami':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Kekkei Genkai — Shakuton':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Fuuton (風遁, Elemento Viento)";
     $especialidad2 = "";
     $elemento2 = "Katon (火遁, Elemento Fuego)";
   break;

   case 'Kekkei Genkai — Yōton':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Doton (土遁, Elemento Tierra)";
     $especialidad2 = "";
     $elemento2 = "Yoton";
   break;

   case 'Kekkei Genkai — Jiton':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Raiton (雷遁, Elemento Rayo)";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Kekkei Genkai — Shōton':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Doton (土遁, Elemento Tierra)";
     $especialidad2 = "";
     $elemento2 = "";
   break;

   case 'Kekkei Genkai — Futton':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Suiton (水遁, Elemento Agua)";
     $especialidad2 = "";
     $elemento2 = "Katon (火遁, Elemento Fuego)";
   break;

   case 'Kekkei Genkai — Ranton':
     $invocacion = "";
     $especialidad1 = "";
     $elemento1 = "Raiton (雷遁, Elemento Rayo)";
     $especialidad2 = "";
     $elemento2 = "Suiton (水遁, Elemento Agua)";
   break;
 }

/*echo $_POST['selectClan'];
 echo $invocacion;
 echo $especialidad1;
 echo $elemento1;
 echo $especialidad2;
 echo $elemento2 ;*/

 ?>
 <div class="row container-fluid justify-content-center">
   <form class="col-6" action="personaje.php?sid=<?php echo $sid; ?>" method="post">
     <?php
     $sid = $_REQUEST['sid'];
     echo '<input type="hidden" id="user_id" name="user_id" value="'.$user->data['user_id'].'">';

     ?>
       <ul class="nav nav-tabs" id="myTab" role="tablist">
         <li class="nav-item">
           <a class="nav-link active" id="perso-tab" data-toggle="tab" href="#perso" role="tab" aria-controls="perso" aria-selected="true">Info</a>
         </li>
         <li class="nav-item">
           <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">Técnicas</a>
         </li>
         <li class="nav-item">
           <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Atributos</a>
         </li>
         <li class="nav-item">
           <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Personaje</a>
         </li>
         <li class="nav-item">
           <a class="nav-link" id="botones-tab" data-toggle="tab" href="#botones" role="tab" aria-controls="botones" aria-selected="false">Finalizar</a>
         </li>
       </ul>
       <div id="alertas">

       </div>
       <div class="tab-content" id="myTabContent">
         <div class="col-xs-12" style="height:25px;"></div>
         <div class="tab-pane fade show active" id="perso" role="tabpanel" aria-labelledby="perso-tab">
           <div class="form-group">
            <label class=" control-label" for="nombre">Nombre de Personaje</label>
            <div class="">
                <input type='text' class='form-control' id='nombre' name='nombre'> </input>
            </div>
           </div>
           <div class="form-group">
            <label class=" control-label" for="edad">Edad</label>
            <div class="">
                <input type='number' class='form-control' id='edad' name='edad' max="20" min="10" value="10"> </input>
            </div>
           </div>
           <div class="form-group">
            <label class=" control-label" for="rango">Rango</label>
            <div class="">
                <input type='text' class='form-control' id='rango' name='rango' value="Genin" readonly></input>
            </div>
           </div>
           <div class="form-group">
            <label class=" control-label" for="selectAldea">Aldea</label>
            <div class="">
                <select id="selectAldea" name="selectAldea" class="form-control">
                  <option value="konoha">Konohagakure no Sato</option>
                  <option value="kiri">Kirigakure no Sato</option>
                  <option value="getsu">Getsugakure no Sato</option>
                  <option value="yuki">Yukigakure no Sato</option>
                  <option value="kusa">Kusagakure no Sato</option>
                </select>
            </div>
           </div>
           <div class="form-group">
            <label class=" control-label" for="selectOjos">Color de ojos</label>
            <div class="">
                <select id="selectOjos" name="selectOjos" class="form-control">
                  <option value="Azules">Azules</option>
                  <option value="Marrones">Marrones</option>
                  <option value="Verdes">Verdes</option>
                  <option value="Ámbar">Ámbar</option>
                  <option value="Violeta">Violeta</option>
                  <option value="Rojos">Rojos</option>
                  <option value="Negros">Negros</option>
                </select>
            </div>
           </div>
           <div class="form-group">
            <label class=" control-label" for="selectPelo">Color de pelo</label>
            <div class="">
                <select id="selectPelo" name="selectPelo" class="form-control">
                  <option value="Castaño">Castaño</option>
                  <option value="Verde">Verde</option>
                  <option value="Negro">Negro</option>
                  <option value="Rubio">Rubio</option>
                  <option value="Rojo">Rojo</option>
                  <option value="Azul">Azul</option>
                  <option value="Morado">Morado</option>
                </select>
            </div>
           </div>
           <div class="form-group">
            <label class=" control-label" for="pelo">Altura</label>
            <div class="">
                <input type='number' class='form-control' id='altura' name='altura' min="120" max="230" value="150"></input>
            </div>
           </div>
           <div class="form-group">
            <label class=" control-label" for="pelo">Peso</label>
            <div class="">
                <input type='number' class='form-control' id='peso' name='peso' min="40" max="200" value="50"></input>
            </div>
           </div>
           <div class="form-group">
            <label class=" control-label" for="selectComplexion">Complexión</label>
            <div class="">
                <select id="selectComplexion" name="selectComplexion" class="form-control">
                  <option value="Delgado">Pequeña</option>
                  <option value="Normal">Mediana</option>
                  <option value="Definido">Grande</option>
                </select>
            </div>
           </div>
         </div>
         <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
         <div class="form-group">
          <label class=" control-label" for="selectbasic">Clan / Kekkei Genkai / Arte</label>
          <div class="">
              <?php
              echo "<input type='text' class='form-control' id='clan' name='clan' readonly value='".$_POST['selectClan']."'> </input>";
              ?>
          </div>
         </div>
         <div class="form-group">
          <label class=" control-label" for="selectbasic">Primer elemento</label>
          <div class="">
            <select id="selectElemento" name="selectElemento" class="form-control">
              <?php
              if ($elemento1 == "") {
                rellenarSelectElemento();
              }
              else{
                echo "<option>".$elemento1."</option>";
              }
              ?>
            </select>
          </div>
         </div>
         <div class="form-group">
          <label class=" control-label" for="selectbasic">Primera especialidad</label>
          <div class="">
            <select id="selectEspecialidad" name="selectEspecialidad" class="form-control">
              <?php
              if ($especialidad1 == "") {
                rellenarSelectEspecialidad();
              }
              else{
                if ($especialidad1 == "kaguya") {
                  echo "<option>Taijutsu</option>";
                  echo "<option>Bukijutsu</option>";
                }
                else{
                  echo "<option>".$especialidad1."</option>";
                }
              }
              ?>
            </select>
          </div>
         </div>
         <div class="form-group">
          <label class=" control-label" for="selectbasic">Segundo elemento</label>
          <div class="">
              <?php
              if ($elemento2 == "") {
                echo "<input type='text' class='form-control' id='selectElemento2' name='selectElemento2' readonly value='Segundo elemento disponible con PN'> </input>";
              }
              else{
                if ($elemento2 == "Yoton") {
                  echo '<select id="selectElemento2" name="selectElemento2" class="form-control">';
                  echo "<option>Katon</option>";
                  echo "<option>Suiton</option>";
                  echo '</select>';
                }
                else{

                  echo "<input type='text' class='form-control' id='clan' name='clan' readonly value='".$elemento2."'> </input>";
                }
              }
              ?>
          </div>
         </div>
         <div class="form-group">
          <label class=" control-label" for="selectbasic">Segunda especialidad</label>
          <div class="">
              <?php
              if ($especialidad2 == "") {
                echo "<input type='text' class='form-control' id='selectEspecialidad2' name='selectEspecialidad2' readonly value='Segunda especialidad disponible con PN'> </input>";
              }
              else{
                echo "<input type='text' class='form-control' id='clan' name='clan' readonly value='".$especialidad2."'> </input>";
              }
              ?>
          </div>
         </div>
         <div class="form-group">
          <label class=" control-label" for="invocacion">Invocacion</label>
          <div class="">
              <?php
              if ($invocacion == "") {
                echo "<input type='text' class='form-control' id='invocacion' name='invocacion' readonly value='Invocación por determinar'> </input>";
              }
              else{
                echo "<input type='text' class='form-control' id='invocacion' name='invocacion' readonly value='".$invocacion."'> </input>";
              }
              ?>
          </div>
         </div>
       </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
         <div class="form-group">
           <div class="row">

           </div>
          <div class="">
            <div class="form-group row">
              <label class="col-md-3 control-label" for="atrFuerza">Fuerza</label>
              <input class="col-md-3 form-control" type="number" name="atrFuerza" id="atrFuerza" min="1" max="10" value="1">
              <label class="col-md-3 control-label" for="atrRes">Resistencia</label>
              <input class="col-md-3 form-control" type="number" name="atrRes" id="atrRes" min="1" max="10" value="1">
            </div>
            <div class="form-group row">
              <label class="col-md-3 control-label" for="artAg">Agilidad</label>
              <input class="col-md-3 form-control" type="number" name="artAg" id="artAg" min="1" max="10" value="1">
              <label class="col-md-3 control-label" for="atrEsp">Espíritu</label>
              <input class="col-md-3 form-control" type="number" name="atrEsp" id="atrEsp" min="1" max="10" value="1">
            </div>
            <div class="form-group row">
              <label class="col-md-3 control-label" for="atrCon">Concentración</label>
              <input class="col-md-3 form-control" type="number" name="atrCon" id="atrCon" min="1" max="10" value="1">
              <label class="col-md-3 control-label" for="atrVol">Voluntad</label>
              <input class="col-md-3 form-control" type="number" name="atrVol" id="atrVol" min="1" max="10" value="1">
            </div>
          </div>
         </div>
       </div>
       <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <div class="form-group">
          <div class="">
            <label for="descFis">Descripción Física</label>
            <textarea class="form-control" id="descFis" name="descFis" rows="5"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="">
            <label for="descPsic">Descripción Psicológica</label>
            <textarea class="form-control" id="descPsic" name="descPsic" rows="12"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="">
            <label for="descHis">Historia</label>
            <textarea class="form-control" id="descHis" name="descHis" rows="12"></textarea>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="botones" role="tabpanel" aria-labelledby="botones-tab">
        <div class="form-group">
          <div class="col-md-8">
            <button type="button" class="btn btn-success" name="botonComprobar" id="botonComprobar">Guardar Personaje</button>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-8">
            <button id="button2id" name="button2id" class="btn btn-danger" formaction="personaje.php?sid=<?php echo $sid; ?>">Volver</button>
          </div>
        </div>
    </div>
</div>
  </form>
 <?php
}
}
}
$request->disable_super_globals();
?>
 </div>
  </div>
