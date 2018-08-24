<?php

/* ficha_ver.html */
class __TwigTemplate_81e889b4b8a15249a22884af0b6c842fcb67b4a82804b845a40138b6233f04a4 extends Twig_Template
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
        $this->loadTemplate("overall_header.html", "ficha_ver.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "<!-- Latest compiled and minified CSS -->

<div class=\"row container-fluid justify-content-center\">
<span class=\"corners-top\"><span></span></span>
<p class=\"error\">";
        // line 6
        echo (isset($context["ERRORES"]) ? $context["ERRORES"] : null);
        echo "
<ul class=\"nav nav-tabs\" id=\"myTab\" role=\"tablist\">
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link active\" id=\"perso-tab\" data-toggle=\"tab\" href=\"#perso\" >Info</a>
\t</li>
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link\" id=\"home-tab\" data-toggle=\"tab\" href=\"#accordion\">Técnicas</a>
\t</li>
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link\" id=\"profile-tab\" data-toggle=\"tab\" href=\"#profile\">Atributos</a>
\t</li>
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link\" id=\"contact-tab\" data-toggle=\"tab\" href=\"#contact\">Personaje</a>
\t</li>
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link\" id=\"botones-tab\" data-toggle=\"tab\" href=\"#botones\">Moderaciones</a>
\t</li>
\t<li class=\"nav-item\">
\t\t<a class=\"nav-link\" id=\"borrar-tab\" data-toggle=\"tab\" href=\"#borrar\">Borrar Personaje</a>
\t</li>
</ul>
<div class=\"tab-content\" id=\"myTabContent\">
\t<div class=\"col-xs-12\" style=\"height:25px;\"></div>
\t<div class=\"tab-pane fade in active\" id=\"perso\" >
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"nombre\">Nombre:</label>
\t\t ";
        // line 32
        echo (isset($context["FICHA_NOMBRE"]) ? $context["FICHA_NOMBRE"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"edad\">Edad:</label>
\t\t ";
        // line 36
        echo (isset($context["FICHA_EDAD"]) ? $context["FICHA_EDAD"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"rango\">Rango:</label>
\t\t ";
        // line 40
        echo (isset($context["FICHA_RANGO"]) ? $context["FICHA_RANGO"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t\t<label class=\" control-label\" for=\"selectAldea\">Aldea:</label>
\t\t\t";
        // line 44
        echo (isset($context["FICHA_ALDEA"]) ? $context["FICHA_ALDEA"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"selectOjos\">Ojos:</label>
\t\t ";
        // line 48
        echo (isset($context["FICHA_OJOS"]) ? $context["FICHA_OJOS"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"selectPelo\">Pelo:</label>
\t\t ";
        // line 52
        echo (isset($context["FICHA_PELOS"]) ? $context["FICHA_PELOS"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"complexion\">Complexion:</label>
\t\t ";
        // line 56
        echo (isset($context["FICHA_COMPLEXION"]) ? $context["FICHA_COMPLEXION"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"altura\">Altura:</label>
\t\t ";
        // line 60
        echo (isset($context["FICHA_ALTURA"]) ? $context["FICHA_ALTURA"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t <label class=\" control-label\" for=\"peso\">Peso:</label>
\t\t ";
        // line 64
        echo (isset($context["FICHA_PESO"]) ? $context["FICHA_PESO"] : null);
        echo "
\t\t</div>
\t</div>
<div class=\"tab-pane fade panel-group\" id=\"accordion\">
\t    <dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 68
        echo (isset($context["FICHA_CLAN"]) ? $context["FICHA_CLAN"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_CLAN"]) ? $context["FICHA_CLAN"] : null);
        echo "</a></dt><dd style=\"display: none;\">";
        echo (isset($context["TECNICAS_CLAN"]) ? $context["TECNICAS_CLAN"] : null);
        echo "</dd></dl>
\t\t\t<dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 69
        echo (isset($context["FICHA_RAMA1"]) ? $context["FICHA_RAMA1"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_RAMA1"]) ? $context["FICHA_RAMA1"] : null);
        echo "</a></dt><dd style=\"display: none;\">";
        echo (isset($context["TECNICAS_RAMA1"]) ? $context["TECNICAS_RAMA1"] : null);
        echo "</dd></dl>
\t\t\t<dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 70
        echo (isset($context["FICHA_RAMA3"]) ? $context["FICHA_RAMA3"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_RAMA3"]) ? $context["FICHA_RAMA3"] : null);
        echo "</a></dt><dd style=\"display: none;\">";
        echo (isset($context["TECNICAS_RAMA3"]) ? $context["TECNICAS_RAMA3"] : null);
        echo "</dd></dl>
\t\t\t<dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 71
        echo (isset($context["FICHA_RAMA2"]) ? $context["FICHA_RAMA2"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_RAMA2"]) ? $context["FICHA_RAMA2"] : null);
        echo "</a></dt><dd style=\"display: none;\">";
        echo (isset($context["TECNICAS_RAMA2"]) ? $context["TECNICAS_RAMA2"] : null);
        echo "</dd></dl>
\t\t\t<dl class=\"codebox\"><dt><a href=\"javascript:void(0);\" onclick=\"var el = this.parentNode.parentNode.getElementsByTagName('dd')[0]; var v = el.style.display != 'none'; el.style.display = v ? 'none' : 'block'; this.innerHTML = (v ? '[+]' : '[−]') + ' ";
        // line 72
        echo (isset($context["FICHA_RAMA4"]) ? $context["FICHA_RAMA4"] : null);
        echo "'; \">[+] ";
        echo (isset($context["FICHA_RAMA4"]) ? $context["FICHA_RAMA4"] : null);
        echo "</a></dt><dd style=\"display: none;\">";
        echo (isset($context["TECNICAS_RAMA4"]) ? $context["TECNICAS_RAMA4"] : null);
        echo "</dd></dl>
</div>
 <div class=\"tab-pane fade\" id=\"profile\">
\t<div class=\"form-group\">
\t\t<label class=\"col control-label\" for=\"atrFuerza\">Fuerza:</label>
\t\t";
        // line 77
        echo (isset($context["FICHA_FUERZA"]) ? $context["FICHA_FUERZA"] : null);
        echo "
\t</div>
\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"atrRes\">Resistencia:</label>
\t\t\t";
        // line 81
        echo (isset($context["FICHA_AGI"]) ? $context["FICHA_AGI"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"artAg\">Agilidad:</label>
\t\t\t";
        // line 85
        echo (isset($context["FICHA_RES"]) ? $context["FICHA_RES"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"atrEsp\">Espíritu:</label>
\t\t\t";
        // line 89
        echo (isset($context["FICHA_ESP"]) ? $context["FICHA_ESP"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"atrCon\">Concentración:</label>
\t\t\t";
        // line 93
        echo (isset($context["FICHA_CON"]) ? $context["FICHA_CON"] : null);
        echo "
\t\t</div>
\t\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"atrVol\">Voluntad:</label>
\t\t\t";
        // line 97
        echo (isset($context["FICHA_VOL"]) ? $context["FICHA_VOL"] : null);
        echo "
\t\t</div>
\t\t<hr>
\t\t<div class=\"form-group\">
\t\t\t<label class=\"col control-label\" for=\"chakra\">Chakra:</label>
\t\t\t";
        // line 102
        echo (isset($context["FICHA_PC"]) ? $context["FICHA_PC"] : null);
        echo "
\t\t</div>
</div>
<div class=\"tab-pane fade\" id=\"contact\">
 <div class=\"form-group\">
\t <div class=\"\">
\t\t <label for=\"descFis\">Descripción Física:</label>
\t\t  <textarea class=\"form-control\" id=\"descFis\" name=\"descHis\" rows=\"12\" readonly>";
        // line 109
        echo (isset($context["FICHA_FISICO"]) ? $context["FICHA_FISICO"] : null);
        echo "</textarea>
\t </div>
 </div>
 <div class=\"form-group\">
\t <div class=\"\">
\t\t <label for=\"descPsic\">Descripción Psicológica:</label>
\t\t <textarea class=\"form-control\" id=\"descPsic\" name=\"descHis\" rows=\"12\" readonly>";
        // line 115
        echo (isset($context["FICHA_PSICOLOGICO"]) ? $context["FICHA_PSICOLOGICO"] : null);
        echo "</textarea>
\t </div>
 </div>
 <div class=\"form-group\">
\t <div class=\"\">
\t\t <label for=\"descHis\">Historia:</label>
\t\t <textarea class=\"form-control\" id=\"descHis\" name=\"descHis\" rows=\"12\" readonly>";
        // line 121
        echo (isset($context["FICHA_HISTORIA"]) ? $context["FICHA_HISTORIA"] : null);
        echo "</textarea>
\t </div>
 </div>
</div>
<div class=\"tab-pane fade\" id=\"botones\">
\t<div class=\"form-group\">
\t\t<a href=\"";
        // line 127
        echo (isset($context["FICHA_MODERACIONES"]) ? $context["FICHA_MODERACIONES"] : null);
        echo "\" class=\"btn btn-success\">Moderar Personaje</a>
\t</div>
\t<div class=\"form-group\" >
\t\t<div class=\"\">
\t\t\t<label for=\"razon\">Historial moderaciones:</label></br>
\t\t\t<textarea name=\"razones\" rows=\"8\" cols=\"80\">
\t\t\t\t";
        // line 133
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "loopname", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["loopname"]) {
            // line 134
            echo "\t\t\t\t";
            echo $this->getAttribute($context["loopname"], "RAZON_MODERACION", array());
            echo " ";
            echo $this->getAttribute($context["loopname"], "USER_MODERACION", array());
            echo " ";
            echo $this->getAttribute($context["loopname"], "FECHA_MODERACION", array());
            echo "</br>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['loopname'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 136
        echo "\t\t\t</textarea>
\t\t</div>
\t</div>
</div>
<div class=\"tab-pane fade\" id=\"borrar\">
\t<form class=\"\" action=\"";
        // line 141
        echo (isset($context["U_ACTION"]) ? $context["U_ACTION"] : null);
        echo "\" method=\"POST\">
\t\t<button type=\"submit\" class=\"btn btn-warning\" name=\"submit\" id=\"submit\">Borrar Personaje</button>
\t</form>
</div>
</div>
</div>
";
        // line 147
        $location = "overall_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_footer.html", "ficha_ver.html", 147)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "ficha_ver.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  295 => 147,  286 => 141,  279 => 136,  266 => 134,  262 => 133,  253 => 127,  244 => 121,  235 => 115,  226 => 109,  216 => 102,  208 => 97,  201 => 93,  194 => 89,  187 => 85,  180 => 81,  173 => 77,  161 => 72,  153 => 71,  145 => 70,  137 => 69,  129 => 68,  122 => 64,  115 => 60,  108 => 56,  101 => 52,  94 => 48,  87 => 44,  80 => 40,  73 => 36,  66 => 32,  37 => 6,  31 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "ficha_ver.html", "");
    }
}
