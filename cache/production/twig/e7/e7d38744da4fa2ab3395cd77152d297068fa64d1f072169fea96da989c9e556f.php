<?php

/* ficha_mod.html */
class __TwigTemplate_3e7f6b122611f26cab3a5279e22b6317d295a488519923aebf3d3a77380b23c6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $location = "overall_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_header.html", "ficha_mod.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<div class=\"row container-fluid justify-content-center\">
<form name=\"crear_ficha\" action=\"";
        // line 4
        echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
        echo "\" method=\"POST\">
<span class=\"corners-top\"><span></span></span>
<p class=\"error\">";
        // line 6
        echo (isset($context["ERRORES"]) ? $context["ERRORES"] : null);
        echo "</p>
<ul class=\"nav nav-tabs\" id=\"myTab\" role=\"tablist\">
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link active\" id=\"perso-tab\" data-toggle=\"tab\" href=\"#perso\" >Info</a>
\t</li>
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link\" id=\"home-tab\" data-toggle=\"tab\" href=\"#home\">Técnicas</a>
\t</li>
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link\" id=\"profile-tab\" data-toggle=\"tab\" href=\"#profile\">Atributos</a>
\t</li>
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link\" id=\"contact-tab\" data-toggle=\"tab\" href=\"#contact\">Personaje</a>
\t</li>
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link\" id=\"botones-tab\" data-toggle=\"tab\" href=\"#botones\">Finalizar</a>
\t</li>
</ul>
<div class=\"tab-content\" id=\"myTabContent\">
\t<div class=\"col-xs-12\" style=\"height:25px;\"></div>
\t<div class=\"tab-pane fade in active\" id=\"perso\" >
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"nombre\">Nombre:</label>
\t\t <input type=\"hidden\" id=\"pj_id\" name=\"pj_id\" value=\"";
        // line 29
        echo (isset($context["FICHA_ID"]) ? $context["FICHA_ID"] : null);
        echo "\" readonly>
\t\t <input type='text' class='form-control' id='nombre' name='nombre' value=\"";
        // line 30
        echo (isset($context["FICHA_NOMBRE"]) ? $context["FICHA_NOMBRE"] : null);
        echo "\"></input>
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"edad\">Edad:</label>
\t\t <input type='number' class='form-control' id='edad' name='edad' value=\"";
        // line 34
        echo (isset($context["FICHA_EDAD"]) ? $context["FICHA_EDAD"] : null);
        echo "\"></input>
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"rango\">Rango:</label>
\t\t <input type='text' class='form-control' id='rango' name='rango' value=\"";
        // line 38
        echo (isset($context["FICHA_RANGO"]) ? $context["FICHA_RANGO"] : null);
        echo "\"></input>
\t\t</div>
\t\t<div class=\"form-group\">
\t\t\t<label class=\" control-label\" for=\"selectAldea\">Aldea:</label>
\t\t\t<select id=\"selectAldea\" name=\"selectAldea\" class=\"form-control\">
\t\t\t\t<option value=\"";
        // line 43
        echo (isset($context["FICHA_ALDEA"]) ? $context["FICHA_ALDEA"] : null);
        echo "\">";
        echo (isset($context["FICHA_ALDEA"]) ? $context["FICHA_ALDEA"] : null);
        echo "</option>
\t\t\t\t<option value=\"Konohagakure no Sato\">Konohagakure no Sato</option>
\t\t\t\t<option value=\"Kirigakure no Sato\">Kirigakure no Sato</option>
\t\t\t\t<option value=\"Getsugakure no Sato\">Getsugakure no Sato</option>
\t\t\t\t<option value=\"Yukigakure no Sato\">Yukigakure no Sato</option>
\t\t\t\t<option value=\"Kusagakure no Sato\">Kusagakure no Sato</option>
\t\t\t </select>
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"selectOjos\">Ojos:</label>
\t\t <input type='text' class='form-control' id='selectOjos' name='selectOjos' value=\"";
        // line 53
        echo (isset($context["FICHA_OJOS"]) ? $context["FICHA_OJOS"] : null);
        echo "\"></input>
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"selectPelo\">Pelo:</label>
\t\t <input type='text' class='form-control' id='selectPelo' name='selectPelo' value=\"";
        // line 57
        echo (isset($context["FICHA_PELOS"]) ? $context["FICHA_PELOS"] : null);
        echo "\"></input>
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\"control-label\" for=\"complexion\">Complexion:</label>
\t\t <input type='text' class='form-control' id='complexion' name='complexion' value=\"";
        // line 61
        echo (isset($context["FICHA_COMPLEXION"]) ? $context["FICHA_COMPLEXION"] : null);
        echo "\"></input>
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\"control-label\" for=\"altura\">Altura:</label>
\t\t <input type='text' class='form-control' id='altura' name='altura' value=\"";
        // line 65
        echo (isset($context["FICHA_ALTURA"]) ? $context["FICHA_ALTURA"] : null);
        echo "\"></input>
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\"control-label\" for=\"peso\">Peso:</label>
\t\t <input type='text' class='form-control' id='peso' name='peso' value=\"";
        // line 69
        echo (isset($context["FICHA_PESO"]) ? $context["FICHA_PESO"] : null);
        echo "\"></input>
\t\t</div>
\t</div>
\t<div class=\"tab-pane fade\" id=\"home\">
\t\t<select size=\"6\" id=\"clan\" name=\"clan\" data-size=\"10\" class=\"form-control\">
\t\t<option value=\"";
        // line 74
        echo (isset($context["FICHA_CLAN"]) ? $context["FICHA_CLAN"] : null);
        echo "\">";
        echo (isset($context["FICHA_CLAN"]) ? $context["FICHA_CLAN"] : null);
        echo "</option>
\t\t <option value=\"Sin Clan\">Sin Clan</option>
\t\t <option value=\"Clan Kamizuru\">Clan Kamizuru</option>
\t\t <option value=\"Clan Jyugo\">Clan Jyugo</option>
\t\t <option value=\"Clan Nendo\">Clan Nendo</option>
\t\t <option value=\"Clan Orochi\">Clan Orochi</option>
\t\t <option value=\"Clan Uzumaki\">Clan Uzumaki</option>
\t\t <option value=\"Clan Sabaku\">Clan Sabaku</option>
\t\t <option value=\"Clan Aburame\">Clan Aburame</option>
\t\t <option value=\"Clan Senju\">Clan Senju</option>
\t\t <option value=\"Clan Hyuga\">Clan Hyuga</option>
\t\t <option value=\"Clan Kaguya\">Clan Kaguya</option>
\t\t <option value=\"Clan Inuzuka\">Clan Inuzuka</option>
\t\t <option value=\"Clan Yuki\">Clan Yuki</option>
\t\t <option value=\"Clan Yotsuki\">Clan Yotsuki</option>
\t\t <option value=\"Clan Uchiha\">Clan Uchiha</option>
\t\t <option value=\"Clan Yamanaka\">Clan Yamanaka</option>
\t\t <option value=\"Clan Akimichi\">Clan Akimichi</option>
\t\t <option value=\"Clan Hozuki\">Clan Hozuki</option>
\t\t <option value=\"Clan Nara\">Clan Nara</option>
\t\t <option value=\"Arte Gotokuji\">Arte Gotokuji</option>
\t\t <option value=\"Arte Yūrei\">Arte Yūrei</option>
\t\t <option value=\"Arte Tenkasai\">Arte Tenkasai</option>
\t\t <option value=\"Arte Inku\">Arte Inku</option>
\t\t <option value=\"Arte Tessen\">Arte Tessen</option>
\t\t <option value=\"Arte Origami\">Arte Origami</option>
\t\t <option value=\"Kekkei Genkai Shakuton\">Kekkei Genkai Shakuton</option>
\t\t <option value=\"Kekkei Genkai Yōton\">Kekkei Genkai Yōton</option>
\t\t <option value=\"Kekkei Genkai Jiton\">Kekkei Genkai Jiton</option>
\t\t <option value=\"Kekkei Genkai Shōton\">Kekkei Genkai Shōton</option>
\t\t <option value=\"Kekkei Genkai Futton\">Kekkei Genkai Futton</option>
\t\t <option value=\"Kekkei Genkai Ranton\">Kekkei Genkai Ranton</option>
\t </select>
\t\t<dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 107
        echo (isset($context["FICHA_CLAN"]) ? $context["FICHA_CLAN"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_CLAN"]) ? $context["FICHA_CLAN"] : null);
        echo "</a></dt><dd style=\"display: none;\"><textarea class=\"form-control\" id=\"tecsClan\" name=\"tecsClan\" rows=\"5\"> ";
        echo (isset($context["TECNICAS_CLAN"]) ? $context["TECNICAS_CLAN"] : null);
        echo "</textarea></dd></dl>
\t\t<hr>
\t\t<select id=\"selectElemento\" name=\"selectElemento\" class=\"form-control\" data-size=\"5\">
\t\t\t<option value=\"";
        // line 110
        echo (isset($context["FICHA_ELEMENTO1"]) ? $context["FICHA_ELEMENTO1"] : null);
        echo "\">";
        echo (isset($context["FICHA_ELEMENTO1"]) ? $context["FICHA_ELEMENTO1"] : null);
        echo "</option>
\t\t\t <option value=\"Katon (火遁 Elemento Fuego)\">Katon (火遁 Elemento Fuego)</option>
\t\t\t <option value=\"Suiton (水遁 Elemento Agua)\">Suiton (水遁 Elemento Agua)</option>
\t\t\t <option value=\"Raiton (雷遁 Elemento Rayo)\">Raiton (雷遁 Elemento Rayo)</option>
\t\t\t <option value=\"Fuuton (風遁 Elemento Viento)\">Fuuton (風遁 Elemento Viento)</option>
\t\t\t <option value=\"Doton (土遁 Elemento Tierra)\">Doton (土遁 Elemento Tierra)</option>
\t\t</select>
\t\t<dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 117
        echo (isset($context["FICHA_ELEMENTO1"]) ? $context["FICHA_ELEMENTO1"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_ELEMENTO1"]) ? $context["FICHA_ELEMENTO1"] : null);
        echo "</a></dt><dd style=\"display: none;\"><textarea class=\"form-control\" id=\"tecsSelectElemento\" name=\"tecsSelectElemento\" rows=\"5\">";
        echo (isset($context["TECNICAS_ELEMENTO1"]) ? $context["TECNICAS_ELEMENTO1"] : null);
        echo "</textarea></dd></dl>
\t\t<hr>
\t\t<select id=\"selectEspecialidad\" name=\"selectEspecialidad\" class=\"form-control\">
\t\t\t<option value=\"";
        // line 120
        echo (isset($context["FICHA_ESPECIALIDAD1"]) ? $context["FICHA_ESPECIALIDAD1"] : null);
        echo "\">";
        echo (isset($context["FICHA_ESPECIALIDAD1"]) ? $context["FICHA_ESPECIALIDAD1"] : null);
        echo "</option>
\t\t\t <option value=\"Genjutsu (幻術 Ilusiones)\">Genjutsu (幻術 Ilusiones)</option>
\t\t\t <option value=\"Taijutsu (体術 Técnicas de Cuerpo a Cuerpo)\">Taijutsu (体術 Técnicas de Cuerpo a Cuerpo)</option>
\t\t\t <option value=\"Bukijutsu (武器术 Técnicas de armas)\">Bukijutsu (武器术 Técnicas de armas)</option>
\t\t\t <option value=\"Fūinjutsu (封印術 Sellado)\">Fūinjutsu (封印術 Sellado)</option>
\t\t\t <option value=\"Iryō Ninjutsu (医療忍術 Médico)\">Iryō Ninjutsu (医療忍術 Médico)</option>
\t\t\t <option value=\"Kanchi Taipu (感知タイプ Sensorial)\">Kanchi Taipu (感知タイプ Sensorial)</option>
\t\t</select>
\t\t<dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 128
        echo (isset($context["FICHA_ESPECIALIDAD1"]) ? $context["FICHA_ESPECIALIDAD1"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_ESPECIALIDAD1"]) ? $context["FICHA_ESPECIALIDAD1"] : null);
        echo "</a></dt><dd style=\"display: none;\"><textarea class=\"form-control\" id=\"tecsSelectEspecialidad\" name=\"tecsSelectEspecialidad\" rows=\"5\">";
        echo (isset($context["TECNICAS_ESPECIALIDAD1"]) ? $context["TECNICAS_ESPECIALIDAD1"] : null);
        echo "</textarea></dd></dl>
\t\t<hr>
\t\t<select id=\"selectElemento2\" name=\"selectElemento2\" class=\"form-control\" data-size=\"5\">
\t\t\t<option value=\"";
        // line 131
        echo (isset($context["FICHA_ELEMENTO2"]) ? $context["FICHA_ELEMENTO2"] : null);
        echo "\">";
        echo (isset($context["FICHA_ELEMENTO2"]) ? $context["FICHA_ELEMENTO2"] : null);
        echo "</option>
\t\t\t<option value=\"Segundo elemento disponible en compra con PN\">Segundo elemento disponible en compra con PN</option>
\t\t\t<option value=\"Katon (火遁 Elemento Fuego)\">Katon (火遁 Elemento Fuego)</option>
\t\t\t <option value=\"Suiton (水遁 Elemento Agua)\">Suiton (水遁 Elemento Agua)</option>
\t\t\t <option value=\"Raiton (雷遁 Elemento Rayo)\">Raiton (雷遁 Elemento Rayo)</option>
\t\t\t <option value=\"Fuuton (風遁 Elemento Viento)\">Fuuton (風遁 Elemento Viento)</option>
\t\t\t <option value=\"Doton (土遁 Elemento Tierra)\">Doton (土遁 Elemento Tierra)</option>
\t\t</select>
\t\t<dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 139
        echo (isset($context["FICHA_ELEMENTO2"]) ? $context["FICHA_ELEMENTO2"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_ELEMENTO2"]) ? $context["FICHA_ELEMENTO2"] : null);
        echo "</a></dt><dd style=\"display: none;\"><textarea class=\"form-control\" id=\"tecsSelectElemento2\" name=\"tecsSelectElemento2\" rows=\"5\">";
        echo (isset($context["TECNICAS_ELEMENTO2"]) ? $context["TECNICAS_ELEMENTO2"] : null);
        echo "</textarea></dd></dl>
\t\t<hr>
\t\t<select id=\"selectEspecialidad2\" name=\"selectEspecialidad2\" class=\"form-control\">
\t\t\t<option value=\"";
        // line 142
        echo (isset($context["FICHA_ESPECIALIDAD2"]) ? $context["FICHA_ESPECIALIDAD2"] : null);
        echo "\">";
        echo (isset($context["FICHA_ESPECIALIDAD2"]) ? $context["FICHA_ESPECIALIDAD2"] : null);
        echo "</option>
\t\t\t<option value=\"Segunda especialidad disponible en compra con PN\">Segunda especialidad disponible en compra con PN</option>
\t\t\t <option value=\"Genjutsu (幻術 Ilusiones)\">Genjutsu (幻術 Ilusiones)</option>
\t\t\t <option value=\"Taijutsu (体術 Técnicas de Cuerpo a Cuerpo)\">Taijutsu (体術 Técnicas de Cuerpo a Cuerpo)</option>
\t\t\t <option value=\"Bukijutsu (武器术 Técnicas de armas)\">Bukijutsu (武器术 Técnicas de armas)</option>
\t\t\t <option value=\"Fūinjutsu (封印術 Sellado)\">Fūinjutsu (封印術 Sellado)</option>
\t\t\t <option value=\"Iryō Ninjutsu (医療忍術 Médico)\">Iryō Ninjutsu (医療忍術 Médico)</option>
\t\t\t <option value=\"Kanchi Taipu (感知タイプ Sensorial)\">Kanchi Taipu (感知タイプ Sensorial)</option>
\t\t</select>
\t\t<dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 151
        echo (isset($context["FICHA_ESPECIALIDAD2"]) ? $context["FICHA_ESPECIALIDAD2"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_ESPECIALIDAD2"]) ? $context["FICHA_ESPECIALIDAD2"] : null);
        echo "</a></dt><dd style=\"display: none;\"><textarea class=\"form-control\" id=\"tecsSelectEspecialidad2\" name=\"tecsSelectEspecialidad2\" rows=\"5\">";
        echo (isset($context["TECNICAS_ESPECIALIDAD2"]) ? $context["TECNICAS_ESPECIALIDAD2"] : null);
        echo "</textarea></dd></dl>
\t\t<input class=\"form-control\" type=\"text\" name=\"invocacion\" value=\"";
        // line 152
        echo (isset($context["FICHA_INVOCACION"]) ? $context["FICHA_INVOCACION"] : null);
        echo "\">
\t\t<dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 153
        echo (isset($context["FICHA_INVOCACION"]) ? $context["FICHA_INVOCACION"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_INVOCACION"]) ? $context["FICHA_INVOCACION"] : null);
        echo "</a></dt><dd style=\"display: none;\"><textarea class=\"form-control\" id=\"tecsInvocacion\" name=\"tecsInvocacion\" rows=\"5\">";
        echo (isset($context["TECNICAS_INVOCACION"]) ? $context["TECNICAS_INVOCACION"] : null);
        echo "</textarea></dd></dl>
</div>
 <div class=\"tab-pane fade\" id=\"profile\">
\t<dl>
\t<div class=\"form-group\">
\t\t<label class=\"col control-label\" for=\"atrFuerza\">Fuerza:</label>
\t\t<input class=\"col form-control\" type=\"number\" name=\"atrFuerza\" id=\"atrFuerza\" value=\"";
        // line 159
        echo (isset($context["FICHA_FUERZA"]) ? $context["FICHA_FUERZA"] : null);
        echo "\">
\t</div>
\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"atrRes\">Resistencia:</label>
\t\t\t<input class=\"col form-control\" type=\"number\" name=\"atrRes\" id=\"atrRes\" value=\"";
        // line 163
        echo (isset($context["FICHA_AGI"]) ? $context["FICHA_AGI"] : null);
        echo "\">
\t\t</div>
\t\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"artAg\">Agilidad:</label>
\t\t\t<input class=\"col form-control\" type=\"number\" name=\"artAg\" id=\"artAg\" value=\"";
        // line 167
        echo (isset($context["FICHA_RES"]) ? $context["FICHA_RES"] : null);
        echo "\">
\t\t</div>
\t\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"atrEsp\">Espíritu:</label>
\t\t\t<input class=\"col form-control\" type=\"number\" name=\"atrEsp\" id=\"atrEsp\" value=\"";
        // line 171
        echo (isset($context["FICHA_ESP"]) ? $context["FICHA_ESP"] : null);
        echo "\">
\t\t</div>
\t\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"atrCon\">Concentración:</label>
\t\t\t<input class=\"col form-control\" type=\"number\" name=\"atrCon\" id=\"atrCon\" value=\"";
        // line 175
        echo (isset($context["FICHA_CON"]) ? $context["FICHA_CON"] : null);
        echo "\">
\t\t</div>
\t\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"atrVol\">Voluntad:</label>
\t\t\t<input class=\"col form-control\" type=\"number\" name=\"atrVol\" id=\"atrVol\" value=\"";
        // line 179
        echo (isset($context["FICHA_VOL"]) ? $context["FICHA_VOL"] : null);
        echo "\">
\t\t</div>
\t<!-- <div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"chakra\">Chakra:</label>
\t\t\t<p>";
        // line 183
        echo (isset($context["FICHA_PC"]) ? $context["FICHA_PC"] : null);
        echo "</p>
\t\t</div> -->
</div>
<div class=\"tab-pane fade\" id=\"contact\">
 <div class=\"form-group\">
\t <div class=\"\">
\t\t <label for=\"descFis\">Descripción Física:</label>
\t\t  <textarea class=\"form-control\" id=\"descFis\" name=\"descFis\" rows=\"12\" onblur=\"checkText(this, 600);\">";
        // line 190
        echo (isset($context["FICHA_FISICO"]) ? $context["FICHA_FISICO"] : null);
        echo "</textarea>
\t </div>
 </div>
 <div class=\"form-group\">
\t <div class=\"\">
\t\t <label for=\"descPsic\">Descripción Psicológica:</label>
\t\t <textarea class=\"form-control\" id=\"descPsic\" name=\"descPsic\" rows=\"12\" onblur=\"checkText(this, 600);\">";
        // line 196
        echo (isset($context["FICHA_PSICOLOGICO"]) ? $context["FICHA_PSICOLOGICO"] : null);
        echo "</textarea>
\t </div>
 </div>
 <div class=\"form-group\">
\t <div class=\"\">
\t\t <label for=\"descHis\">Historia:</label>
\t\t <textarea class=\"form-control\" id=\"descHis\" name=\"descHis\" rows=\"12\" onblur=\"checkText(this, 600);\">";
        // line 202
        echo (isset($context["FICHA_HISTORIA"]) ? $context["FICHA_HISTORIA"] : null);
        echo "</textarea>
\t </div>
 </div>
</div>
<div class=\"tab-pane fade\" id=\"botones\">
\t<div class=\"form-group\">
\t\t<div class=\"\">
 \t\t <label for=\"razon\">Razón moderacion:</label>
 \t\t <textarea class=\"form-control\" id=\"razon\" name=\"razon\" rows=\"3\" onblur=\"checkText(this, 5);\"></textarea>
 \t </div>
 </div>
 <div class=\"form-group\">
\t\t\t<button type=\"submit\" class=\"btn btn-success\" name=\"submit\" id=\"submit\">Moderar Personaje</button>
\t</div>
</div>
</form>
</div>
<script type=\"text/javascript\">
function checkText(elem, num) {
\tif (elem.length < num) {
\t\talert('Este campo debe tener más de ' + num + ' caracteres.');
\t\treturn false;
\t}
\treturn true;
}
</script>
";
        // line 228
        $location = "overall_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_footer.html", "ficha_mod.html", 228)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "ficha_mod.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  397 => 228,  368 => 202,  359 => 196,  350 => 190,  340 => 183,  333 => 179,  326 => 175,  319 => 171,  312 => 167,  305 => 163,  298 => 159,  285 => 153,  281 => 152,  273 => 151,  259 => 142,  249 => 139,  236 => 131,  226 => 128,  213 => 120,  203 => 117,  191 => 110,  181 => 107,  143 => 74,  135 => 69,  128 => 65,  121 => 61,  114 => 57,  107 => 53,  92 => 43,  84 => 38,  77 => 34,  70 => 30,  66 => 29,  40 => 6,  35 => 4,  31 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "ficha_mod.html", "");
    }
}
