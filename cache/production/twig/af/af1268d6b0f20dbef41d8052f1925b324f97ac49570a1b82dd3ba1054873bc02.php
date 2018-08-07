<?php

/* login_panel.html */
class __TwigTemplate_1fcf03c2061c5beed48c4e241ae1c24fb483a83e31ae6d43961e5160290140b5 extends Twig_Template
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
        echo "<div class=\"panel panel-info\">
\t<div class=\"panel-heading\">
\t\t<h3 class=\"panel-title\"><i class=\"fa fa-user fa-fw\" aria-hidden=\"true\"></i> ";
        // line 3
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN_LOGOUT");
        echo "</h3>
\t</div>
\t<div class=\"panel-body\">
\t\t<form method=\"post\" action=\"";
        // line 6
        echo (isset($context["S_LOGIN_ACTION"]) ? $context["S_LOGIN_ACTION"] : null);
        echo "\" class=\"form-inline\" name=\"loginform\">
\t\t\t<div class=\"row mobile-fix\">
\t\t\t\t<div class=\"col-xs-6\">
\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t<input type=\"text\" placeholder=\"";
        // line 10
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("USERNAME");
        echo "\" tabindex=\"1\" name=\"username\" size=\"10\" class=\"form-control\" title=\"";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("USERNAME");
        echo "\"/>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t<input type=\"password\" placeholder=\"";
        // line 13
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PASSWORD");
        echo "\" tabindex=\"2\" name=\"password\" size=\"10\" class=\"form-control\" title=\"";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PASSWORD");
        echo "\" autocomplete=\"off\"/>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t";
        // line 16
        echo (isset($context["S_LOGIN_REDIRECT"]) ? $context["S_LOGIN_REDIRECT"] : null);
        echo "
\t\t\t\t\t\t<input type=\"submit\" tabindex=\"5\" name=\"login\" value=\"";
        // line 17
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN");
        echo "\" class=\"btn btn-primary btn-block\" />
\t\t\t\t\t</div>
\t\t\t\t\t";
        // line 19
        if ((isset($context["U_SEND_PASSWORD"]) ? $context["U_SEND_PASSWORD"] : null)) {
            // line 20
            echo "\t\t\t\t\t\t<br/> <a href=\"";
            echo (isset($context["U_SEND_PASSWORD"]) ? $context["U_SEND_PASSWORD"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("FORGOT_PASS");
            echo "</a>
\t\t\t\t\t";
        }
        // line 22
        echo "\t\t\t\t\t";
        if ((isset($context["S_AUTOLOGIN_ENABLED"]) ? $context["S_AUTOLOGIN_ENABLED"] : null)) {
            // line 23
            echo "\t\t\t\t\t\t<span class=\"responsive-hide\">|</span>
\t\t\t\t\t\t<div class=\"checkbox\">
\t\t\t\t\t\t\t<label for=\"autologin\"><input type=\"checkbox\" tabindex=\"4\" name=\"autologin\" id=\"autologin\"/> ";
            // line 25
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOG_ME_IN");
            echo "</label>
\t\t\t\t\t\t</div>
\t\t\t\t\t";
        }
        // line 28
        echo "\t\t\t\t</div>
\t\t\t\t<div class=\"col-xs-6\">
\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t";
        // line 31
        if ((isset($context["S_REGISTER_ENABLED"]) ? $context["S_REGISTER_ENABLED"] : null)) {
            // line 32
            echo "\t\t\t\t\t\t\t<a href=\"";
            echo (isset($context["U_REGISTER"]) ? $context["U_REGISTER"] : null);
            echo "\" class=\"btn btn-primary btn-labeled\"><span class=\"btn-label\"><i class=\"fa fa-user fa-fw\" aria-hidden=\"true\"></i></span>";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("REGISTER");
            echo "</a>
\t\t\t\t\t\t";
        } else {
            // line 34
            echo "\t\t\t\t\t\t\t<a class=\"btn btn-primary btn-labeled disabled\"><span class=\"btn-label\"><i class=\"fa fa-user fa-fw\" aria-hidden=\"true\"></i></span>";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("REGISTER");
            echo "</a>
\t\t\t\t\t\t";
        }
        // line 36
        echo "\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t\t";
        // line 39
        echo (isset($context["S_FORM_TOKEN"]) ? $context["S_FORM_TOKEN"] : null);
        echo "
\t\t</form>
\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "login_panel.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  110 => 39,  105 => 36,  99 => 34,  91 => 32,  89 => 31,  84 => 28,  78 => 25,  74 => 23,  71 => 22,  63 => 20,  61 => 19,  56 => 17,  52 => 16,  44 => 13,  36 => 10,  29 => 6,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "login_panel.html", "");
    }
}
