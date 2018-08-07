<?php

/* message_body.html */
class __TwigTemplate_4ce3026afff81b54b9cc663d3b0699453e48bc2e5e4cf1e17b77dd1e72575781 extends Twig_Template
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
        if ((isset($context["S_SIMPLE_MESSAGE"]) ? $context["S_SIMPLE_MESSAGE"] : null)) {
            // line 2
            echo "\t";
            $location = "simple_header.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("simple_header.html", "message_body.html", 2)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
        } else {
            // line 4
            echo "\t";
            $location = "overall_header.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("overall_header.html", "message_body.html", 4)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
        }
        // line 6
        echo "
<div class=\"panel panel-default\">
\t<div class=\"panel-heading\">
\t\t<h3 class=\"panel-title\">";
        // line 9
        echo (isset($context["MESSAGE_TITLE"]) ? $context["MESSAGE_TITLE"] : null);
        echo "</h3>
\t</div>
\t<div class=\"panel-body\">
\t\t<p>";
        // line 12
        echo (isset($context["MESSAGE_TEXT"]) ? $context["MESSAGE_TEXT"] : null);
        echo "</p>
\t\t";
        // line 13
        if ((((((isset($context["SCRIPT_NAME"]) ? $context["SCRIPT_NAME"] : null) == "search") &&  !(isset($context["S_BOARD_DISABLED"]) ? $context["S_BOARD_DISABLED"] : null)) &&  !(isset($context["S_NO_SEARCH"]) ? $context["S_NO_SEARCH"] : null)) && (isset($context["L_RETURN_TO_SEARCH_ADV"]) ? $context["L_RETURN_TO_SEARCH_ADV"] : null))) {
            // line 14
            echo "\t\t\t<p>
\t\t\t\t<a href=\"";
            // line 15
            echo (isset($context["U_SEARCH"]) ? $context["U_SEARCH"] : null);
            echo "\" class=\"arrow-";
            echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
            echo "\" accesskey=\"r\">
\t\t\t\t<i class=\"fa fa-angle-";
            // line 16
            echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
            echo " fa-fw\" aria-hidden=\"true\"></i><span>";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("GO_TO_SEARCH_ADV");
            echo "</span>
\t\t\t</a>
\t\t\t</p>
\t\t";
        }
        // line 20
        echo "\t</div>
</div>

";
        // line 23
        if ((isset($context["S_SIMPLE_MESSAGE"]) ? $context["S_SIMPLE_MESSAGE"] : null)) {
            // line 24
            echo "\t";
            $location = "simple_footer.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("simple_footer.html", "message_body.html", 24)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
        } else {
            // line 26
            echo "\t";
            $location = "overall_footer.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("overall_footer.html", "message_body.html", 26)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
        }
    }

    public function getTemplateName()
    {
        return "message_body.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  105 => 26,  91 => 24,  89 => 23,  84 => 20,  75 => 16,  69 => 15,  66 => 14,  64 => 13,  60 => 12,  54 => 9,  49 => 6,  35 => 4,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "message_body.html", "");
    }
}
