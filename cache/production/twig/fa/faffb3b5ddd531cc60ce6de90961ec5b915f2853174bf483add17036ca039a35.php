<?php

/* navbar_footer.html */
class __TwigTemplate_0b9f6ad1d0648fb905401e8d356bd368d6c52b1abd07e8bce827124c683ee7dc extends Twig_Template
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
        echo "<nav class=\"navbar navbar-default navbar-fixed-bottom hidden-sm hidden-xs\" id=\"footer-nav\">
\t<div class=\"container-fluid\">
\t\t<div class=\"collapse navbar-collapse\">
\t\t\t<ul class=\"nav navbar-nav\">
\t\t\t\t";
        // line 5
        if ((isset($context["U_SITE_HOME"]) ? $context["U_SITE_HOME"] : null)) {
            // line 6
            echo "\t\t\t\t\t";
            ob_start();
            // line 7
            echo "\t\t\t\t\t\t<li><a href=\"";
            echo (isset($context["U_SITE_HOME"]) ? $context["U_SITE_HOME"] : null);
            echo "\" data-navbar-reference=\"home\"><i class=\"fa fa-home fa-fw\" aria-hidden=\"true\"></i><span>";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SITE_HOME");
            echo "</span></a>
\t\t\t\t\t";
            echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
            // line 9
            echo "\t\t\t\t";
        }
        // line 10
        echo "\t\t\t\t";
        // line 11
        echo "\t\t\t\t";
        ob_start();
        // line 12
        echo "\t\t\t\t<li><a href=\"";
        echo (isset($context["U_INDEX"]) ? $context["U_INDEX"] : null);
        echo "\" data-navbar-reference=\"index\">";
        if ( !(isset($context["U_SITE_HOME"]) ? $context["U_SITE_HOME"] : null)) {
            echo "<i class=\"fa fa-home fa-fw\" aria-hidden=\"true\"></i>";
        }
        echo "<span>";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("INDEX");
        echo "</span></a></li>
\t\t\t\t";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
        // line 14
        echo "\t\t\t\t";
        // line 15
        echo "\t\t\t\t";
        if ((isset($context["U_CONTACT_US"]) ? $context["U_CONTACT_US"] : null)) {
            echo "<li><a href=\"";
            echo (isset($context["U_CONTACT_US"]) ? $context["U_CONTACT_US"] : null);
            echo "\"><i class=\"fa fa-envelope fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("CONTACT_US");
            echo "</a></li>";
        }
        // line 16
        echo "\t\t\t\t";
        // line 17
        echo "\t\t\t\t";
        if ((isset($context["U_TEAM"]) ? $context["U_TEAM"] : null)) {
            echo "<li><a href=\"";
            echo (isset($context["U_TEAM"]) ? $context["U_TEAM"] : null);
            echo "\"><i class=\"fa fa-shield fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("THE_TEAM");
            echo "</a></li>";
        }
        // line 18
        echo "\t\t\t\t";
        // line 19
        echo "\t\t\t\t";
        if ((isset($context["S_DISPLAY_MEMBERLIST"]) ? $context["S_DISPLAY_MEMBERLIST"] : null)) {
            // line 20
            echo "\t\t\t\t\t<li><a href=\"";
            echo (isset($context["U_MEMBERLIST"]) ? $context["U_MEMBERLIST"] : null);
            echo "\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MEMBERLIST_EXPLAIN");
            echo "\"><i class=\"fa fa-group fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MEMBERLIST");
            echo "</a></li>
\t\t\t\t";
        }
        // line 22
        echo "\t\t\t\t";
        if ( !(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)) {
            // line 23
            echo "\t\t\t\t\t<li><a href=\"";
            echo (isset($context["U_DELETE_COOKIES"]) ? $context["U_DELETE_COOKIES"] : null);
            echo "\"><i class=\"fa fa-trash-o fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("DELETE_COOKIES");
            echo "</a></li>
\t\t\t\t";
        }
        // line 25
        echo "\t\t\t\t";
        // line 26
        echo "\t\t\t\t<li><p class=\"navbar-text\">";
        echo (isset($context["S_TIMEZONE"]) ? $context["S_TIMEZONE"] : null);
        echo "</p></li>
\t\t\t\t";
        // line 27
        // line 28
        echo "\t\t\t</ul>
\t\t\t<ul class=\"nav navbar-nav navbar-right\"><li><p class=\"navbar-text\" id=\"copyright\">Design";
        // line 29
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
        echo " <a href=\"http://zoker.me/go/comboot\" target=\"_blank\">ComBoot</a> by <a href=\"http://www.florian-gareis.de\" target=\"_blank\" id=\"author-name\">Florian Gareis</a></p></li></ul>
\t\t</div>
\t</div>
</nav>
<a id=\"back-to-top\" href=\"#\" class=\"btn btn-primary\" role=\"button\" title=\"";
        // line 33
        echo $this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "BACK_TO_TOP_TOOLTIP", array());
        echo "\" data-toggle=\"tooltip\" data-placement=\"left\"><i class=\"fa fa-chevron-up fa-fw\" aria-hidden=\"true\"></i></a>
";
    }

    public function getTemplateName()
    {
        return "navbar_footer.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  124 => 33,  117 => 29,  114 => 28,  113 => 27,  108 => 26,  106 => 25,  98 => 23,  95 => 22,  85 => 20,  82 => 19,  80 => 18,  71 => 17,  69 => 16,  60 => 15,  58 => 14,  46 => 12,  43 => 11,  41 => 10,  38 => 9,  30 => 7,  27 => 6,  25 => 5,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "navbar_footer.html", "");
    }
}
