<?php

/* overall_footer.html */
class __TwigTemplate_386585ea99b3edddde3110f97c7e66306de1629064a6ce6832fd337671172e40 extends Twig_Template
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
        echo "\t\t\t\t</div>
\t\t\t";
        // line 2
        // line 3
        echo "
\t\t\t";
        // line 4
        // line 5
        echo "
\t\t\t<div id=\"page-footer\" class=\"container\">
\t\t\t\t<div class=\"row\">
\t\t\t\t\t";
        // line 8
        if (((isset($context["U_MCP"]) ? $context["U_MCP"] : null) || (isset($context["U_ACP"]) ? $context["U_ACP"] : null))) {
            // line 9
            echo "\t\t\t\t\t\t<div class=\"col-md-8 col-md-offset-2 text-center\">
\t\t\t\t\t\t\t<div class=\"btn-group\">
\t\t\t\t\t\t\t\t";
            // line 11
            if ((isset($context["U_MCP"]) ? $context["U_MCP"] : null)) {
                // line 12
                echo "\t\t\t\t\t\t\t\t\t<a class=\"btn btn-primary\" href=\"";
                echo (isset($context["U_MCP"]) ? $context["U_MCP"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MCP");
                echo "</a>
\t\t\t\t\t\t\t\t";
            }
            // line 14
            echo "\t\t\t\t\t\t\t\t";
            if ((isset($context["U_ACP"]) ? $context["U_ACP"] : null)) {
                // line 15
                echo "\t\t\t\t\t\t\t\t\t<a class=\"btn btn-primary\" href=\"";
                echo (isset($context["U_ACP"]) ? $context["U_ACP"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP");
                echo "</a>
\t\t\t\t\t\t\t\t";
            }
            // line 17
            echo "\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t";
        }
        // line 20
        echo "\t\t\t\t\t";
        if (((isset($context["S_DISPLAY_SEARCH"]) ? $context["S_DISPLAY_SEARCH"] : null) || ((isset($context["S_USER_LOGGED_IN"]) ? $context["S_USER_LOGGED_IN"] : null) &&  !(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)))) {
            // line 21
            echo "\t\t\t\t\t\t<div class=\"col-md-2 pull-right\">
\t\t\t\t\t\t\t<div class=\"btn-group dropup pull-right\">
\t\t\t\t\t\t\t\t<a class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\" style=\" display: block; \">
\t\t\t\t\t\t\t\t<i class=\"fa fa-wrench fa-fw\" aria-hidden=\"true\"></i>
\t\t\t\t\t\t\t\t<span class=\"caret\"></span>
\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t<ul class=\"dropdown-menu\">
\t\t\t\t\t\t\t\t\t";
            // line 28
            if ((isset($context["S_DISPLAY_SEARCH"]) ? $context["S_DISPLAY_SEARCH"] : null)) {
                // line 29
                echo "\t\t\t\t\t\t\t\t\t\t<li><a href=\"";
                echo (isset($context["U_SEARCH_UNANSWERED"]) ? $context["U_SEARCH_UNANSWERED"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_UNANSWERED");
                echo "</a></li>
\t\t\t\t\t\t\t\t\t";
                // line 30
                if ((isset($context["S_LOAD_UNREADS"]) ? $context["S_LOAD_UNREADS"] : null)) {
                    // line 31
                    echo "\t\t\t\t\t\t\t\t\t\t<li><a href=\"";
                    echo (isset($context["U_SEARCH_UNREAD"]) ? $context["U_SEARCH_UNREAD"] : null);
                    echo "\">";
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_UNREAD");
                    echo "</a></li>
\t\t\t\t\t\t\t\t\t";
                }
                // line 33
                echo "\t\t\t\t\t\t\t\t\t";
                if ((isset($context["S_USER_LOGGED_IN"]) ? $context["S_USER_LOGGED_IN"] : null)) {
                    // line 34
                    echo "\t\t\t\t\t\t\t\t\t\t<li><a href=\"";
                    echo (isset($context["U_SEARCH_NEW"]) ? $context["U_SEARCH_NEW"] : null);
                    echo "\">";
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_NEW");
                    echo "</a></li>
\t\t\t\t\t\t\t\t\t";
                }
                // line 36
                echo "\t\t\t\t\t\t\t\t\t\t<li><a href=\"";
                echo (isset($context["U_SEARCH_ACTIVE_TOPICS"]) ? $context["U_SEARCH_ACTIVE_TOPICS"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_ACTIVE_TOPICS");
                echo "</a></li>
\t\t\t\t\t\t\t\t\t";
            }
            // line 38
            echo "\t\t\t\t\t\t\t\t\t";
            if (( !(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null) && (isset($context["U_MARK_FORUMS"]) ? $context["U_MARK_FORUMS"] : null))) {
                // line 39
                echo "\t\t\t\t\t\t\t\t\t\t<li class=\"rightside\"><a href=\"";
                echo (isset($context["U_MARK_FORUMS"]) ? $context["U_MARK_FORUMS"] : null);
                echo "\" accesskey=\"m\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MARK_FORUMS_READ");
                echo "</a></li>
\t\t\t\t\t\t\t\t\t";
            }
            // line 41
            echo "\t\t\t\t\t\t\t\t\t";
            if ((isset($context["S_HAS_SUBFORUM"]) ? $context["S_HAS_SUBFORUM"] : null)) {
                // line 42
                echo "\t\t\t\t\t\t\t\t\t\t";
                if (( !(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null) && (isset($context["U_MARK_FORUMS"]) ? $context["U_MARK_FORUMS"] : null))) {
                    // line 43
                    echo "\t\t\t\t\t\t\t\t\t\t\t<li><a href=\"";
                    echo (isset($context["U_MARK_FORUMS"]) ? $context["U_MARK_FORUMS"] : null);
                    echo "\">";
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MARK_SUBFORUMS_READ");
                    echo "</a></li>
\t\t\t\t\t\t\t\t\t\t";
                }
                // line 45
                echo "\t\t\t\t\t\t\t\t\t";
            }
            // line 46
            echo "\t\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<br />
\t\t\t\t\t\t</div>
\t\t\t\t\t";
        }
        // line 51
        echo "\t\t\t\t</div>
\t\t\t\t<div class=\"copyright text-center\">
\t\t\t\t\t";
        // line 53
        // line 54
        echo "\t\t\t\t\t";
        echo (isset($context["CREDIT_LINE"]) ? $context["CREDIT_LINE"] : null);
        echo "
\t\t\t\t\t";
        // line 55
        if ((isset($context["TRANSLATION_INFO"]) ? $context["TRANSLATION_INFO"] : null)) {
            echo "<br />";
            echo (isset($context["TRANSLATION_INFO"]) ? $context["TRANSLATION_INFO"] : null);
        }
        // line 56
        echo "\t\t\t\t\t";
        // line 57
        echo "\t\t\t\t\t";
        if ((isset($context["DEBUG_OUTPUT"]) ? $context["DEBUG_OUTPUT"] : null)) {
            echo "<br />";
            echo (isset($context["DEBUG_OUTPUT"]) ? $context["DEBUG_OUTPUT"] : null);
        }
        // line 58
        echo "\t\t\t\t</div>
\t\t\t\t<div id=\"darkenwrapper\" data-ajax-error-title=\"";
        // line 59
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("AJAX_ERROR_TITLE");
        echo "\" data-ajax-error-text=\"";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("AJAX_ERROR_TEXT");
        echo "\" data-ajax-error-text-abort=\"";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("AJAX_ERROR_TEXT_ABORT");
        echo "\" data-ajax-error-text-timeout=\"";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("AJAX_ERROR_TEXT_TIMEOUT");
        echo "\" data-ajax-error-text-parsererror=\"";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("AJAX_ERROR_TEXT_PARSERERROR");
        echo "\">
\t\t\t\t\t<div id=\"darken\">&nbsp;</div>
\t\t\t\t</div>

\t\t\t\t<div id=\"phpbb_alert\" class=\"phpbb_alert alert alert-info\" data-l-err=\"";
        // line 63
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ERROR");
        echo "\" data-l-timeout-processing-req=\"";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("TIMEOUT_PROCESSING_REQ");
        echo "\">
\t\t\t\t\t<a href=\"#\" class=\"alert_close\"><i class=\"fa fa-times-circle fa-fw\" aria-hidden=\"true\"></i></a>
\t\t\t\t\t<strong class=\"alert_title\">&nbsp;</strong><p class=\"alert_text\"></p>
\t\t\t\t</div>
\t\t\t\t<div id=\"phpbb_confirm\" class=\"phpbb_alert\">
\t\t\t\t\t<a href=\"#\" class=\"alert_close\"><i class=\"fa fa-times-circle fa-fw\" aria-hidden=\"true\"></i></a>
\t\t\t\t\t<div class=\"alert_text\"></div>
\t\t\t\t</div>
\t\t\t\t";
        // line 71
        $location = "navbar_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("navbar_footer.html", "overall_footer.html", 71)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 72
        echo "\t\t\t\t<a id=\"bottom\" class=\"anchor\" accesskey=\"z\"></a>
\t\t\t\t";
        // line 73
        if ( !(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)) {
            echo (isset($context["RUN_CRON_TASK"]) ? $context["RUN_CRON_TASK"] : null);
        }
        // line 74
        echo "\t\t\t</div>
\t\t</div>
\t\t<script type=\"text/javascript\" src=\"";
        // line 76
        echo (isset($context["T_JQUERY_LINK"]) ? $context["T_JQUERY_LINK"] : null);
        echo "\"></script>
\t\t";
        // line 77
        if ((isset($context["S_ALLOW_CDN"]) ? $context["S_ALLOW_CDN"] : null)) {
            echo "<script type=\"text/javascript\">window.jQuery || document.write('\\x3Cscript src=\"";
            echo (isset($context["T_ASSETS_PATH"]) ? $context["T_ASSETS_PATH"] : null);
            echo "/javascript/jquery.min.js?assets_version=";
            echo (isset($context["T_ASSETS_VERSION"]) ? $context["T_ASSETS_VERSION"] : null);
            echo "\">\\x3C/script>');</script>";
        }
        // line 78
        echo "\t\t<script type=\"text/javascript\" src=\"";
        echo (isset($context["T_ASSETS_PATH"]) ? $context["T_ASSETS_PATH"] : null);
        echo "/javascript/core.js?assets_version=";
        echo (isset($context["T_ASSETS_VERSION"]) ? $context["T_ASSETS_VERSION"] : null);
        echo "\"></script>
\t\t";
        // line 79
        $asset_file = "forum_fn.js";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 80
        echo "\t\t";
        $asset_file = "ajax.js";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 81
        echo "
\t\t";
        // line 82
        if ((isset($context["S_ALLOW_CDN"]) ? $context["S_ALLOW_CDN"] : null)) {
            // line 83
            echo "\t\t\t<script type=\"text/javascript\">
\t\t\t\t(function(\$){
\t\t\t\t\tvar \$fa_cdn = \$('head').find('link[rel=\"stylesheet\"]').first(),
\t\t\t\t\t\t\$span = \$('<span class=\"fa\" style=\"display:none\"></span>').appendTo('body');
\t\t\t\t\tif (\$span.css('fontFamily') !== 'FontAwesome' ) {
\t\t\t\t\t\t\$fa_cdn.after('<link href=\"";
            // line 88
            echo (isset($context["T_ASSETS_PATH"]) ? $context["T_ASSETS_PATH"] : null);
            echo "/css/font-awesome.min.css\" rel=\"stylesheet\">');
\t\t\t\t\t\t\$fa_cdn.remove();
\t\t\t\t\t}
\t\t\t\t\t\$span.remove();
\t\t\t\t})(jQuery);
\t\t\t</script>
\t\t";
        }
        // line 95
        echo "
\t\t";
        // line 96
        if ((((isset($context["U_MCP"]) ? $context["U_MCP"] : null) || (isset($context["U_ACP"]) ? $context["U_ACP"] : null)) && ($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "NOTIFICATION", array()) == 1))) {
            // line 97
            echo "\t\t\t";
            $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/notification.js");
            $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
            if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
                $asset_path = $asset->get_path();                $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
                if (!file_exists($local_file)) {
                    $local_file = $this->getEnvironment()->findTemplate($asset_path);
                    $asset->set_path($local_file, true);
                }
                $asset->add_assets_version('285');
            }
            $this->getEnvironment()->get_assets_bag()->add_script($asset);            // line 98
            echo "\t\t";
        }
        // line 99
        echo "
\t\t";
        // line 100
        if ((isset($context["S_COOKIE_NOTICE"]) ? $context["S_COOKIE_NOTICE"] : null)) {
            // line 101
            echo "\t\t\t<script src=\"";
            echo (isset($context["T_ASSETS_PATH"]) ? $context["T_ASSETS_PATH"] : null);
            echo "/cookieconsent/cookieconsent.min.js?assets_version=";
            echo (isset($context["T_ASSETS_VERSION"]) ? $context["T_ASSETS_VERSION"] : null);
            echo "\"></script>
\t\t\t<script>
\t\t\t\twindow.addEventListener(\"load\", function(){
\t\t\t\t\twindow.cookieconsent.initialise({
\t\t\t\t\t\t\"palette\": {
\t\t\t\t\t\t\t\"popup\": {
\t\t\t\t\t\t\t\t\"background\": \"#0F538A\"
\t\t\t\t\t\t\t},
\t\t\t\t\t\t\t\"button\": {
\t\t\t\t\t\t\t\t\"background\": \"#E5E5E5\"
\t\t\t\t\t\t\t}
\t\t\t\t\t\t},
\t\t\t\t\t\t\"theme\": \"classic\",
\t\t\t\t\t\t\"content\": {
\t\t\t\t\t\t\t\"message\": \"";
            // line 115
            echo twig_escape_filter($this->env, $this->env->getExtension('phpbb\template\twig\extension')->lang("COOKIE_CONSENT_MSG"), "js");
            echo "\",
\t\t\t\t\t\t\t\"dismiss\": \"";
            // line 116
            echo twig_escape_filter($this->env, $this->env->getExtension('phpbb\template\twig\extension')->lang("COOKIE_CONSENT_OK"), "js");
            echo "\",
\t\t\t\t\t\t\t\"link\": \"";
            // line 117
            echo twig_escape_filter($this->env, $this->env->getExtension('phpbb\template\twig\extension')->lang("COOKIE_CONSENT_INFO"), "js");
            echo "\"
\t\t\t\t\t\t}
\t\t\t\t\t})});
\t\t\t</script>
\t\t";
        }
        // line 122
        echo "
\t\t";
        // line 123
        $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
        $this->env->setNamespaceLookUpOrder(array('phpbb_viglink', '__main__'));
        $this->env->loadTemplate('@phpbb_viglink/event/overall_footer_after.html')->display($context);
        $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        // line 124
        echo "
\t\t";
        // line 125
        if ((isset($context["S_PLUPLOAD"]) ? $context["S_PLUPLOAD"] : null)) {
            $location = "plupload.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("plupload.html", "overall_footer.html", 125)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
        }
        // line 126
        echo "\t\t";
        echo $this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "SCRIPTS", array());
        echo "

\t\t<script type=\"text/javascript\">
\t\t\t\$( document ).ready(function(\$){
\t\t\t\t";
        // line 130
        if ((isset($context["S_MERGE_VIEW"]) ? $context["S_MERGE_VIEW"] : null)) {
            // line 131
            echo "\t\t\t\t\t\$('#merge-panel-tab').tab('show');
\t\t\t\t";
        } elseif (        // line 132
(isset($context["S_SPLIT_VIEW"]) ? $context["S_SPLIT_VIEW"] : null)) {
            // line 133
            echo "\t\t\t\t\t\$('#split-panel-tab').tab('show');
\t\t\t\t";
        } else {
            // line 135
            echo "\t\t\t\t\t\$('#display-panel-tab').tab('show');
\t\t\t\t";
        }
        // line 137
        echo "\t\t\t});

\t\t\t";
        // line 139
        if (($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "AJAX_PAGELOAD", array()) == 1)) {
            // line 140
            echo "\t\t\t\t\t\$(document).pjax('a', '#wrap', {fragment: '#wrap'});
\t\t\t";
        }
        // line 142
        echo "\t\t\t";
        if (($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "PAGELOAD_INDICATOR", array()) == 1)) {
            // line 143
            echo "\t\t\t\t\$primaryColor = \$('.btn-primary').css('background-color');
\t\t\t\t\$('.pace .pace-progress').css('background-color', \$primaryColor);
\t\t\t";
        }
        // line 146
        echo "\t\t\t";
        if (($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "AUTOSAVE", array()) == 1)) {
            // line 147
            echo "\t\t\t\tif( \$('#message, #subject').val() ) {
\t\t\t\t\t\$('#message, #subject').phoenix('remove');
\t\t\t\t}
\t\t\t\t\$('#message, #subject').phoenix();
\t\t\t\t\$('#postform, #qr_postform').submit(function(e){
\t\t\t\t\t\$('#message, #subject').phoenix('remove');
\t\t\t\t});
\t\t\t";
        }
        // line 155
        echo "\t\t</script>

\t\t";
        // line 157
        // line 158
        echo "\t</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "overall_footer.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  435 => 158,  434 => 157,  430 => 155,  420 => 147,  417 => 146,  412 => 143,  409 => 142,  405 => 140,  403 => 139,  399 => 137,  395 => 135,  391 => 133,  389 => 132,  386 => 131,  384 => 130,  376 => 126,  362 => 125,  359 => 124,  354 => 123,  351 => 122,  343 => 117,  339 => 116,  335 => 115,  315 => 101,  313 => 100,  310 => 99,  307 => 98,  295 => 97,  293 => 96,  290 => 95,  280 => 88,  273 => 83,  271 => 82,  268 => 81,  256 => 80,  245 => 79,  238 => 78,  230 => 77,  226 => 76,  222 => 74,  218 => 73,  215 => 72,  203 => 71,  190 => 63,  175 => 59,  172 => 58,  166 => 57,  164 => 56,  159 => 55,  154 => 54,  153 => 53,  149 => 51,  142 => 46,  139 => 45,  131 => 43,  128 => 42,  125 => 41,  117 => 39,  114 => 38,  106 => 36,  98 => 34,  95 => 33,  87 => 31,  85 => 30,  78 => 29,  76 => 28,  67 => 21,  64 => 20,  59 => 17,  51 => 15,  48 => 14,  40 => 12,  38 => 11,  34 => 9,  32 => 8,  27 => 5,  26 => 4,  23 => 3,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "overall_footer.html", "");
    }
}
