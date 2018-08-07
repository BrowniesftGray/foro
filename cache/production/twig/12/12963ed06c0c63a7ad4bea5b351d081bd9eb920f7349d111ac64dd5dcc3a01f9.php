<?php

/* notification_dropdown.html */
class __TwigTemplate_c298980e4334a9af0ab2897df36b35ec3b7570d0244db0871467f629f11fc936 extends Twig_Template
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
        echo "<div class=\"panel panel-default\">
\t<div class=\"panel-heading\">";
        // line 2
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NOTIFICATIONS");
        echo " <span class=\"pull-right\"><a href=\"";
        echo (isset($context["U_NOTIFICATION_SETTINGS"]) ? $context["U_NOTIFICATION_SETTINGS"] : null);
        echo "\">";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SETTINGS");
        echo "</a></span></div>
\t";
        // line 3
        if ((isset($context["NOTIFICATIONS_COUNT"]) ? $context["NOTIFICATIONS_COUNT"] : null)) {
            // line 4
            echo "\t\t<div class=\"panel-body\">
\t\t\t<span id=\"mark_all_notifications\"><button class=\"btn btn-warning btn-block\" type=\"button\" onclick=\"window.location.href='";
            // line 5
            echo (isset($context["U_MARK_ALL_NOTIFICATIONS"]) ? $context["U_MARK_ALL_NOTIFICATIONS"] : null);
            echo "'\" data-ajax=\"notification.mark_all_read\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MARK_ALL_READ");
            echo "</button></span>
\t\t</div>
\t";
        }
        // line 8
        echo "\t<ul class=\"list-group\">
\t\t";
        // line 9
        if ( !twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notifications", array()))) {
            // line 10
            echo "\t\t\t<li class=\"list-group-item no_notifications\">
\t\t\t\t";
            // line 11
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NO_NOTIFICATIONS");
            echo "
\t\t\t</li>
\t\t";
        }
        // line 14
        echo "\t\t";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "notifications", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["notifications"]) {
            // line 15
            echo "\t\t\t<li class=\"list-group-item ";
            if ($this->getAttribute($context["notifications"], "UNREAD", array())) {
                echo " list-group-item-info";
            }
            if ($this->getAttribute($context["notifications"], "STYLING", array())) {
                echo " ";
                echo $this->getAttribute($context["notifications"], "STYLING", array());
            }
            if ( !$this->getAttribute($context["notifications"], "URL", array())) {
                echo " no-url";
            }
            echo "\">
\t\t\t\t";
            // line 16
            if ($this->getAttribute($context["notifications"], "URL", array())) {
                // line 17
                echo "\t\t\t\t\t<a href=\"";
                if ($this->getAttribute($context["notifications"], "UNREAD", array())) {
                    echo $this->getAttribute($context["notifications"], "U_MARK_READ", array());
                    echo "\" data-real-url=\"";
                    echo $this->getAttribute($context["notifications"], "URL", array());
                } else {
                    echo $this->getAttribute($context["notifications"], "URL", array());
                }
                echo "\">
\t\t\t\t";
            }
            // line 19
            echo "\t\t\t\t<div class=\"row notification\">
\t\t\t\t\t<div class=\"col-xs-2 notification-avatar\">
\t\t\t\t\t\t";
            // line 21
            if ($this->getAttribute($context["notifications"], "AVATAR", array())) {
                echo $this->getAttribute($context["notifications"], "AVATAR", array());
            } else {
                echo "<img class=\"avatar notification-avatar\" src=\"";
                echo (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null);
                echo "/images/no_avatar.gif\" alt=\"\" />";
            }
            // line 22
            echo "\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-xs-10\">
\t\t\t\t\t\t<div class=\"notification_text\">
\t\t\t\t\t\t\t<p class=\"notification-title\">";
            // line 25
            echo $this->getAttribute($context["notifications"], "FORMATTED_TITLE", array());
            echo "</p>
\t\t\t\t\t\t\t";
            // line 26
            if ($this->getAttribute($context["notifications"], "REFERENCE", array())) {
                echo "<p class=\"notification-reference\">";
                echo $this->getAttribute($context["notifications"], "REFERENCE", array());
                echo "</p>";
            }
            // line 27
            echo "\t\t\t\t\t\t\t";
            if ($this->getAttribute($context["notifications"], "FORUM", array())) {
                echo "<p class=\"notification-forum\">";
                echo $this->getAttribute($context["notifications"], "FORUM", array());
                echo "</p>";
            }
            // line 28
            echo "\t\t\t\t\t\t\t";
            if ($this->getAttribute($context["notifications"], "REASON", array())) {
                echo "<p class=\"notification-reason\">";
                echo $this->getAttribute($context["notifications"], "REASON", array());
                echo "</p>";
            }
            // line 29
            echo "\t\t\t\t\t\t\t<p class=\"notification-time\">";
            echo $this->getAttribute($context["notifications"], "TIME", array());
            echo "</p>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t";
            // line 33
            if ($this->getAttribute($context["notifications"], "URL", array())) {
                echo "</a>";
            }
            // line 34
            echo "\t\t\t\t";
            if ($this->getAttribute($context["notifications"], "UNREAD", array())) {
                // line 35
                echo "\t\t\t\t\t<a href=\"";
                echo $this->getAttribute($context["notifications"], "U_MARK_READ", array());
                echo "\" class=\"mark_read\" data-ajax=\"notification.mark_read\" title=\"";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MARK_READ");
                echo "\"><i class=\"fa fa-check-circle-o fa-fw\" aria-hidden=\"true\"></i></a>
\t\t\t\t";
            }
            // line 37
            echo "\t\t\t</li>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notifications'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "\t</ul>
\t<div class=\"panel-footer\">
\t\t<button class=\"btn btn-primary btn-block\" type=\"button\" onclick=\"window.location.href='";
        // line 41
        echo (isset($context["U_VIEW_ALL_NOTIFICATIONS"]) ? $context["U_VIEW_ALL_NOTIFICATIONS"] : null);
        echo "'\">";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEE_ALL");
        echo "</button>
\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "notification_dropdown.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  165 => 41,  161 => 39,  154 => 37,  146 => 35,  143 => 34,  139 => 33,  131 => 29,  124 => 28,  117 => 27,  111 => 26,  107 => 25,  102 => 22,  94 => 21,  90 => 19,  78 => 17,  76 => 16,  62 => 15,  57 => 14,  51 => 11,  48 => 10,  46 => 9,  43 => 8,  35 => 5,  32 => 4,  30 => 3,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "notification_dropdown.html", "");
    }
}
