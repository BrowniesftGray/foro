<?php

/* online_panel.html */
class __TwigTemplate_8a92c6cddbb4cd11635de9f45546e83963ca124885b55ae50fbf6b9037b72992 extends Twig_Template
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
\t\t<h3 class=\"panel-title\"><i class=\"fa fa-globe\"></i> ";
        // line 3
        if ((isset($context["U_VIEWONLINE"]) ? $context["U_VIEWONLINE"] : null)) {
            echo "<a href=\"";
            echo (isset($context["U_VIEWONLINE"]) ? $context["U_VIEWONLINE"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("WHO_IS_ONLINE");
            echo "</a>";
        } else {
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("WHO_IS_ONLINE");
        }
        echo "</h3>
\t</div>
\t<div class=\"panel-body\">
\t\t";
        // line 6
        // line 7
        echo "\t\t";
        echo (isset($context["TOTAL_USERS_ONLINE"]) ? $context["TOTAL_USERS_ONLINE"] : null);
        echo " (";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ONLINE_EXPLAIN");
        echo ")<br />";
        echo (isset($context["RECORD_USERS"]) ? $context["RECORD_USERS"] : null);
        echo "<br />
\t\t\t";
        // line 8
        if ((isset($context["U_VIEWONLINE"]) ? $context["U_VIEWONLINE"] : null)) {
            // line 9
            echo "\t\t\t\t<hr />";
            echo (isset($context["LOGGED_IN_USER_LIST"]) ? $context["LOGGED_IN_USER_LIST"] : null);
            echo "
\t\t\t\t";
            // line 10
            if ((isset($context["LEGEND"]) ? $context["LEGEND"] : null)) {
                echo "<br /><em>";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LEGEND");
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
                echo " ";
                echo (isset($context["LEGEND"]) ? $context["LEGEND"] : null);
                echo "</em>";
            }
            // line 11
            echo "\t\t\t";
        }
        // line 12
        echo "\t\t";
        // line 13
        echo "\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "online_panel.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  68 => 13,  66 => 12,  63 => 11,  54 => 10,  49 => 9,  47 => 8,  38 => 7,  37 => 6,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "online_panel.html", "");
    }
}
