<?php

/* config.html */
class __TwigTemplate_97d932844cf34597ae8dbcccb0cfd98312b0f1303d0e5259c090623b29375fd7 extends Twig_Template
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
        $value = 1;
        $context['definition']->set('BETA', $value);
        // line 2
        $value = 0;
        $context['definition']->set('THEME_FILE', $value);
        // line 3
        $value = 1;
        $context['definition']->set('NOTIFICATION', $value);
        // line 4
        $value = 1;
        $context['definition']->set('PAGELOAD_INDICATOR', $value);
        // line 5
        $value = 0;
        $context['definition']->set('AJAX_PAGELOAD', $value);
        // line 6
        $value = 1;
        $context['definition']->set('AUTOSAVE', $value);
        // line 7
        $value = 1;
        $context['definition']->set('LIGHTBOX', $value);
        // line 8
        $value = 1;
        $context['definition']->set('VALUE_SLIDER', $value);
        // line 9
        echo "
";
        // line 10
        $value = "ONLINE";
        $context['definition']->set('ONLINE_BADGE', $value);
        // line 11
        $value = "OFFLINE";
        $context['definition']->set('OFFLINE_BADGE', $value);
    }

    public function getTemplateName()
    {
        return "config.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 11,  46 => 10,  43 => 9,  40 => 8,  37 => 7,  34 => 6,  31 => 5,  28 => 4,  25 => 3,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "config.html", "");
    }
}
