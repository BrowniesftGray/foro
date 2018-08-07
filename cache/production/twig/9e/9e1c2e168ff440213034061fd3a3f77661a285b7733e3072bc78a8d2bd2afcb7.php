<?php

/* navbar_header.html */
class __TwigTemplate_162945464f3a0cf6e6d0b5cad04dba8023a512aa5bae7986ecb214123a2026ef extends Twig_Template
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
        echo "<nav class=\"navbar navbar-default navbar-fixed-top navbar-fix\" id=\"header-nav\">
\t<!-- Mobile dropdown buttons -->
\t<div class=\"container-fluid\">
\t\t<div class=\"navbar-header\">
\t\t\t<button type=\"button\" class=\"navbar-toggle pull-left-mobile\" id=\"main-menu-btn\" data-toggle=\"collapse\" data-target=\"#main-menu\">
\t\t\t\t<i class=\"fa fa-bars fa-fw\" aria-hidden=\"true\"></i>
\t\t\t</button>
\t\t\t<a class=\"navbar-brand\" href=\"";
        // line 8
        echo (isset($context["U_INDEX"]) ? $context["U_INDEX"] : null);
        echo "\">";
        echo (isset($context["SITENAME"]) ? $context["SITENAME"] : null);
        echo "</a>
\t\t\t<button type=\"button\" class=\"navbar-toggle pull-right-mobile\" id=\"user-menu-btn\" data-toggle=\"collapse\" data-target=\"#user-menu\">
\t\t\t\t";
        // line 10
        if ((isset($context["CURRENT_USER_AVATAR"]) ? $context["CURRENT_USER_AVATAR"] : null)) {
            // line 11
            echo "\t\t\t\t\t<span class=\"nav-avatar-mobile\">";
            echo (isset($context["CURRENT_USER_AVATAR"]) ? $context["CURRENT_USER_AVATAR"] : null);
            echo "</span>
\t\t\t\t";
        } elseif (        // line 12
(isset($context["S_REGISTERED_USER"]) ? $context["S_REGISTERED_USER"] : null)) {
            // line 13
            echo "\t\t\t\t\t<span class=\"nav-avatar-mobile\"><img src=\"";
            echo (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null);
            echo "/images/default_avatar.jpg\" alt=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("AVATAR");
            echo "\"></span>
\t\t\t\t";
        } else {
            // line 15
            echo "\t\t\t\t\t<i class=\"fa fa-user fa-fw\" aria-hidden=\"true\"></i>
\t\t\t\t";
        }
        // line 17
        echo "\t\t\t</button>
\t\t\t";
        // line 18
        if (((isset($context["S_REGISTERED_USER"]) ? $context["S_REGISTERED_USER"] : null) && (isset($context["S_NOTIFICATIONS_DISPLAY"]) ? $context["S_NOTIFICATIONS_DISPLAY"] : null))) {
            // line 19
            echo "\t\t\t\t<button type=\"button\" class=\"navbar-toggle pull-right-mobile\" id=\"notification-menu-btn\" data-toggle=\"collapse\" data-target=\"#notification-menu\">
\t\t\t\t\t<i class=\"fa fa-bell fa-fw\" aria-hidden=\"true\"></i>
\t\t\t\t</button>
\t\t\t";
        }
        // line 23
        echo "\t\t\t";
        if (((isset($context["S_DISPLAY_SEARCH"]) ? $context["S_DISPLAY_SEARCH"] : null) &&  !(isset($context["S_IN_SEARCH"]) ? $context["S_IN_SEARCH"] : null))) {
            // line 24
            echo "\t\t\t\t<button type=\"button\" class=\"navbar-toggle pull-right-mobile\" id=\"search-menu-btn\" data-toggle=\"collapse\" data-target=\"#search-menu\">
\t\t\t\t\t<i class=\"fa fa-search fa-fw\" aria-hidden=\"true\"></i>
\t\t\t\t</button>
\t\t\t";
        }
        // line 28
        echo "\t\t</div>
\t\t<div class=\"collapse navbar-collapse pull-left-desktop\" id=\"main-menu\">
\t\t\t<ul class=\"nav navbar-nav\">
\t\t\t\t<li id=\"quick-links\" class=\"dropdown ";
        // line 31
        if (( !(isset($context["S_DISPLAY_QUICK_LINKS"]) ? $context["S_DISPLAY_QUICK_LINKS"] : null) &&  !(isset($context["S_DISPLAY_SEARCH"]) ? $context["S_DISPLAY_SEARCH"] : null))) {
            echo " hidden";
        }
        echo "\" data-skip-responsive=\"true\">
\t\t\t\t\t<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-bars fa-fw\" aria-hidden=\"true\"></i> ";
        // line 32
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("QUICK_LINKS");
        echo "</a>
\t\t\t\t\t<ul class=\"dropdown-menu\" role=\"menu\">
\t\t\t\t\t\t";
        // line 34
        // line 35
        echo "\t\t\t\t\t\t";
        // line 36
        echo "\t\t\t\t\t\t";
        if ((isset($context["S_DISPLAY_SEARCH"]) ? $context["S_DISPLAY_SEARCH"] : null)) {
            // line 37
            echo "\t\t\t\t\t\t\t";
            if ((isset($context["S_REGISTERED_USER"]) ? $context["S_REGISTERED_USER"] : null)) {
                // line 38
                echo "\t\t\t\t\t\t\t\t<li><a href=\"";
                echo (isset($context["U_SEARCH_SELF"]) ? $context["U_SEARCH_SELF"] : null);
                echo "\" role=\"menuitem\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_SELF");
                echo "</a></li>
\t\t\t\t\t\t\t";
            }
            // line 40
            echo "\t\t\t\t\t\t\t";
            if ((isset($context["S_USER_LOGGED_IN"]) ? $context["S_USER_LOGGED_IN"] : null)) {
                // line 41
                echo "\t\t\t\t\t\t\t\t<li><a href=\"";
                echo (isset($context["U_SEARCH_NEW"]) ? $context["U_SEARCH_NEW"] : null);
                echo "\" role=\"menuitem\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_NEW");
                echo "</a></li>
\t\t\t\t\t\t\t";
            }
            // line 43
            echo "\t\t\t\t\t\t\t";
            if ((isset($context["S_LOAD_UNREADS"]) ? $context["S_LOAD_UNREADS"] : null)) {
                // line 44
                echo "\t\t\t\t\t\t\t\t<li><a href=\"";
                echo (isset($context["U_SEARCH_UNREAD"]) ? $context["U_SEARCH_UNREAD"] : null);
                echo "\" role=\"menuitem\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_UNREAD");
                echo "</a></li>
\t\t\t\t\t\t\t";
            }
            // line 46
            echo "\t\t\t\t\t\t\t<li><a href=\"";
            echo (isset($context["U_SEARCH_UNANSWERED"]) ? $context["U_SEARCH_UNANSWERED"] : null);
            echo "\" role=\"menuitem\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_UNANSWERED");
            echo "</a></li>
\t\t\t\t\t\t\t<li><a href=\"";
            // line 47
            echo (isset($context["U_SEARCH_ACTIVE_TOPICS"]) ? $context["U_SEARCH_ACTIVE_TOPICS"] : null);
            echo "\" role=\"menuitem\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_ACTIVE_TOPICS");
            echo "</a></li>
\t\t\t\t\t\t\t<li class=\"divider\"></li>
\t\t\t\t\t\t\t<li><a href=\"";
            // line 49
            echo (isset($context["FICHA_CREAR"]) ? $context["FICHA_CREAR"] : null);
            echo "\" title=\"Crear ficha\" class=\"btn_announce\">Crear personaje</a></li>
\t\t\t\t\t\t\t<li><a href=\"";
            // line 50
            echo (isset($context["FICHA_URL"]) ? $context["FICHA_URL"] : null);
            echo "\" title=\"Ver ficha\" class=\"btn_announce\">Ir a ficha de personaje</a></li>
\t\t\t\t\t\t\t<li class=\"divider\"></li>
\t\t\t\t\t\t\t<li><a href=\"";
            // line 52
            echo (isset($context["U_SEARCH"]) ? $context["U_SEARCH"] : null);
            echo "\" role=\"menuitem\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH");
            echo "</a></li>
\t\t\t\t\t\t";
        }
        // line 54
        echo "
\t\t\t\t\t\t";
        // line 55
        if (( !(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null) && ((isset($context["S_DISPLAY_MEMBERLIST"]) ? $context["S_DISPLAY_MEMBERLIST"] : null) || (isset($context["U_TEAM"]) ? $context["U_TEAM"] : null)))) {
            // line 56
            echo "\t\t\t\t\t\t\t<li class=\"divider\"></li>
\t\t\t\t\t\t\t";
            // line 57
            if ((isset($context["S_DISPLAY_MEMBERLIST"]) ? $context["S_DISPLAY_MEMBERLIST"] : null)) {
                echo "<li><a href=\"";
                echo (isset($context["U_MEMBERLIST"]) ? $context["U_MEMBERLIST"] : null);
                echo "\" role=\"menuitem\"><i class=\"fa fa-group fa-fw\" aria-hidden=\"true\"></i> ";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MEMBERLIST");
                echo "</a></li>";
            }
            // line 58
            echo "\t\t\t\t\t\t\t";
            if ((isset($context["U_TEAM"]) ? $context["U_TEAM"] : null)) {
                echo "<li><a href=\"";
                echo (isset($context["U_TEAM"]) ? $context["U_TEAM"] : null);
                echo "\" role=\"menuitem\"><i class=\"fa fa-shield fa-fw\" aria-hidden=\"true\"></i> ";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("THE_TEAM");
                echo "</a></li>";
            }
            // line 59
            echo "\t\t\t\t\t\t";
        }
        // line 60
        echo "
\t\t\t\t\t\t";
        // line 61
        // line 62
        echo "\t\t\t\t\t</ul>
\t\t\t\t</li>
\t\t\t\t";
        // line 64
        // line 65
        echo "\t\t\t\t<li><a href=\"";
        echo (isset($context["U_FAQ"]) ? $context["U_FAQ"] : null);
        echo "\" title=\"";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("FAQ_EXPLAIN");
        echo "\"><i class=\"fa fa-question fa-fw\" aria-hidden=\"true\"></i> ";
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("FAQ");
        echo "</a></li>
\t\t\t\t";
        // line 66
        if ((isset($context["U_ACP"]) ? $context["U_ACP"] : null)) {
            // line 67
            echo "\t\t\t\t\t<li><a href=\"";
            echo (isset($context["U_ACP"]) ? $context["U_ACP"] : null);
            echo "\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP");
            echo "\"><i class=\"fa fa-cogs fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ACP_SHORT");
            echo "</a></li>
\t\t\t\t";
        }
        // line 69
        echo "\t\t\t\t";
        if ((isset($context["U_MCP"]) ? $context["U_MCP"] : null)) {
            // line 70
            echo "\t\t\t\t\t<li><a href=\"";
            echo (isset($context["U_MCP"]) ? $context["U_MCP"] : null);
            echo "\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MCP");
            echo "\"><i class=\"fa fa-gavel fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MCP_SHORT");
            echo "</a></li>
\t\t\t\t";
        }
        // line 72
        echo "\t\t\t\t";
        // line 73
        echo "\t\t\t</ul>
\t\t</div>
\t\t";
        // line 75
        if (((isset($context["S_DISPLAY_SEARCH"]) ? $context["S_DISPLAY_SEARCH"] : null) &&  !(isset($context["S_IN_SEARCH"]) ? $context["S_IN_SEARCH"] : null))) {
            // line 76
            echo "\t\t\t<div class=\"collapse navbar-collapse pull-left-desktop\" id=\"search-menu\">
\t\t\t\t<form class=\"navbar-form navbar-left\" method=\"get\" id=\"search\" action=\"";
            // line 77
            echo (isset($context["U_SEARCH"]) ? $context["U_SEARCH"] : null);
            echo "\">
\t\t\t\t\t<div class=\"input-group\">
\t\t\t\t\t\t<input class=\"input-medium search form-control\" maxlength=\"128\" type=\"text\" name=\"keywords\" id=\"search_keywords\" size=\"20\" title=\"";
            // line 79
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_KEYWORDS");
            echo "\" value=\"";
            echo (isset($context["SEARCH_WORDS"]) ? $context["SEARCH_WORDS"] : null);
            echo "\" placeholder=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_MINI");
            echo "\" />
\t\t\t\t\t\t<div class=\"input-group-btn\">
\t\t\t\t\t\t\t";
            // line 81
            echo (isset($context["S_SEARCH_LOCAL_HIDDEN_FIELDS"]) ? $context["S_SEARCH_LOCAL_HIDDEN_FIELDS"] : null);
            echo "
\t\t\t\t\t\t\t<button type=\"submit\" class=\"btn btn-default\" title=\"";
            // line 82
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH");
            echo "\" data-placement=\"bottom\"><i class=\"fa fa-search fa-fw\" aria-hidden=\"true\"></i></button>
\t\t\t\t\t\t\t<a href=\"";
            // line 83
            echo (isset($context["U_SEARCH"]) ? $context["U_SEARCH"] : null);
            echo "\" class=\"btn btn-default\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("SEARCH_ADV");
            echo "\" data-placement=\"bottom\"><i class=\"fa fa-cog fa-fw\" aria-hidden=\"true\"></i></a>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</form>
\t\t\t</div>
\t\t";
        }
        // line 89
        echo "\t\t";
        // line 90
        echo "\t\t<div class=\"collapse navbar-collapse pull-right-desktop\" id=\"user-menu\">
\t\t\t<!-- Desktop user menu -->
\t\t\t<ul class=\"nav navbar-nav navbar-right hidden-xs fix-right-nav\">
\t\t\t\t";
        // line 93
        if ((isset($context["S_DISPLAY_PM"]) ? $context["S_DISPLAY_PM"] : null)) {
            // line 94
            echo "\t\t\t\t\t<li>
\t\t\t\t\t\t<a href=\"";
            // line 95
            echo (isset($context["U_PRIVATEMSGS"]) ? $context["U_PRIVATEMSGS"] : null);
            echo "\">
\t\t\t\t\t\t\t<i class=\"fa fa-envelope fa-fw\" aria-hidden=\"true\"></i> ";
            // line 96
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PRIVATE_MESSAGES");
            echo " <span class=\"label ";
            if ((isset($context["PRIVATE_MESSAGE_INFO_UNREAD"]) ? $context["PRIVATE_MESSAGE_INFO_UNREAD"] : null)) {
                echo "label-danger";
            } else {
                echo "label-primary";
            }
            echo "\">";
            echo (isset($context["PRIVATE_MESSAGE_COUNT"]) ? $context["PRIVATE_MESSAGE_COUNT"] : null);
            echo "</span>
\t\t\t\t\t\t</a>
\t\t\t\t\t</li>
\t\t\t\t";
        }
        // line 100
        echo "\t\t\t\t<li class=\"dropdown\">
\t\t\t\t\t";
        // line 101
        // line 102
        echo "\t\t\t\t\t<button type=\"button\" class=\"btn btn-primary btn-labeled navbar-btn dropdown-toggle\" data-toggle=\"dropdown\">
\t\t\t\t\t\t";
        // line 103
        if ((isset($context["CURRENT_USER_AVATAR"]) ? $context["CURRENT_USER_AVATAR"] : null)) {
            // line 104
            echo "\t\t\t\t\t\t\t<span class=\"btn-label nav-avatar\">";
            echo (isset($context["CURRENT_USER_AVATAR"]) ? $context["CURRENT_USER_AVATAR"] : null);
            echo "</span>
\t\t\t\t\t\t";
        } elseif (        // line 105
(isset($context["S_REGISTERED_USER"]) ? $context["S_REGISTERED_USER"] : null)) {
            // line 106
            echo "\t\t\t\t\t\t\t<span class=\"btn-label nav-avatar\"><img src=\"";
            echo (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null);
            echo "/images/default_avatar.jpg\" alt=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("AVATAR");
            echo "\"></span>
\t\t\t\t\t\t";
        } else {
            // line 108
            echo "\t\t\t\t\t\t\t<span class=\"btn-label\"><i class=\"fa fa-user fa-fw\" aria-hidden=\"true\"></i></span>
\t\t\t\t\t\t";
        }
        // line 110
        echo "\t\t\t\t\t\t";
        if ((isset($context["S_REGISTERED_USER"]) ? $context["S_REGISTERED_USER"] : null)) {
            echo (isset($context["S_USERNAME"]) ? $context["S_USERNAME"] : null);
            echo " ";
        }
        // line 111
        echo "\t\t\t\t\t\t<span class=\"caret\"></span>
\t\t\t\t\t</button>
\t\t\t\t\t<ul class=\"dropdown-menu\">
\t\t\t\t\t\t";
        // line 114
        if ((isset($context["S_REGISTERED_USER"]) ? $context["S_REGISTERED_USER"] : null)) {
            // line 115
            echo "\t\t\t\t\t\t\t";
            // line 116
            echo "\t\t\t\t\t\t\t";
            if ((isset($context["U_RESTORE_PERMISSIONS"]) ? $context["U_RESTORE_PERMISSIONS"] : null)) {
                // line 117
                echo "\t\t\t\t\t\t\t\t<li><a href=\"";
                echo (isset($context["U_RESTORE_PERMISSIONS"]) ? $context["U_RESTORE_PERMISSIONS"] : null);
                echo "\"><i class=\"fa fa-refresh fa-fw\" aria-hidden=\"true\"></i> ";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("RESTORE_PERMISSIONS");
                echo "</a></li>
\t\t\t\t\t\t\t";
            }
            // line 119
            echo "\t\t\t\t\t\t\t";
            // line 120
            echo "\t\t\t\t\t\t\t<li><a href=\"";
            echo (isset($context["U_PROFILE"]) ? $context["U_PROFILE"] : null);
            echo "\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PROFILE");
            echo "\" accesskey=\"e\"><i class=\"fa fa-wrench fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PROFILE");
            echo "</a></li>
\t\t\t\t\t\t\t<li><a href=\"";
            // line 121
            echo (isset($context["U_USER_PROFILE"]) ? $context["U_USER_PROFILE"] : null);
            echo "\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("READ_PROFILE");
            echo "\"><i class=\"fa fa-sliders fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("READ_PROFILE");
            echo "</a></li>
\t\t\t\t\t\t\t";
            // line 122
            // line 123
            echo "\t\t\t\t\t\t\t<li class=\"divider\"></li>
\t\t\t\t\t\t\t<li><button class=\"btn btn-danger btn-block\" type=\"button\" title=\"";
            // line 124
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN_LOGOUT");
            echo "\" onclick=\"window.location.href='";
            echo (isset($context["U_LOGIN_LOGOUT"]) ? $context["U_LOGIN_LOGOUT"] : null);
            echo "'\" accesskey=\"x\"><i class=\"fa fa-sign-out fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN_LOGOUT");
            echo "</button></li>
\t\t\t\t\t\t\t";
            // line 125
            // line 126
            echo "\t\t\t\t\t\t";
        } else {
            // line 127
            echo "\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t<form action=\"";
            // line 128
            echo (isset($context["S_LOGIN_ACTION"]) ? $context["S_LOGIN_ACTION"] : null);
            echo "\" method=\"post\" id=\"navloginform\" name=\"loginform\">
\t\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t\t<input type=\"text\" placeholder=\"";
            // line 130
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("USERNAME");
            echo "\" name=\"username\" size=\"10\" class=\"form-control\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("USERNAME");
            echo "\"/>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t\t<input type=\"password\" placeholder=\"";
            // line 133
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PASSWORD");
            echo "\" name=\"password\" size=\"10\" class=\"form-control\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PASSWORD");
            echo "\"/>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t";
            // line 135
            if ((isset($context["S_AUTOLOGIN_ENABLED"]) ? $context["S_AUTOLOGIN_ENABLED"] : null)) {
                // line 136
                echo "\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t\t\t<div class=\"checkbox\">
\t\t\t\t\t\t\t\t\t\t\t\t<label for=\"autologin-navbar\"><input type=\"checkbox\" name=\"autologin\" id=\"autologin-navbar\" tabindex=\"4\" /> ";
                // line 138
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOG_ME_IN");
                echo "</label>
\t\t\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t";
            }
            // line 142
            echo "\t\t\t\t\t\t\t\t\t";
            echo (isset($context["S_LOGIN_REDIRECT"]) ? $context["S_LOGIN_REDIRECT"] : null);
            echo "
\t\t\t\t\t\t\t\t\t<button type=\"submit\" name=\"login\" class=\"btn btn-primary btn-block\"><i class=\"fa fa-sign-in fa-fw\" aria-hidden=\"true\"></i> ";
            // line 143
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN");
            echo "</button>
\t\t\t\t\t\t\t\t</form>
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t";
            // line 146
            if ((isset($context["S_REGISTER_ENABLED"]) ? $context["S_REGISTER_ENABLED"] : null)) {
                // line 147
                echo "\t\t\t\t\t\t\t\t<li class=\"divider\"></li>
\t\t\t\t\t\t\t\t<li><button type=\"button\" class=\"btn btn-primary btn-block\" onclick=\"window.location.href='";
                // line 148
                echo (isset($context["U_REGISTER"]) ? $context["U_REGISTER"] : null);
                echo "'\"><i class=\"fa fa-user fa-fw\" aria-hidden=\"true\"></i> ";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("REGISTER");
                echo "</button></li>
\t\t\t\t\t\t\t";
            } else {
                // line 150
                echo "\t\t\t\t\t\t\t\t<li class=\"divider\"></li>
\t\t\t\t\t\t\t\t<li><button type=\"button\" class=\"btn btn-primary btn-block\" disabled=\"disabled\"><i class=\"fa fa-user fa-fw\" aria-hidden=\"true\"></i> ";
                // line 151
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("REGISTER");
                echo "</button></li>
\t\t\t\t\t\t\t";
            }
            // line 153
            echo "\t\t\t\t\t\t\t";
            // line 154
            echo "\t\t\t\t\t\t";
        }
        // line 155
        echo "\t\t\t\t\t</ul>
\t\t\t\t</li>
\t\t\t\t";
        // line 157
        // line 158
        echo "\t\t\t</ul>
\t\t\t<!-- Mobile user menu -->
\t\t\t<ul class=\"nav navbar-nav navbar-right visible-xs fix-right-nav\">
\t\t\t\t";
        // line 161
        if ((isset($context["S_REGISTERED_USER"]) ? $context["S_REGISTERED_USER"] : null)) {
            // line 162
            echo "\t\t\t\t\t";
            // line 163
            echo "\t\t\t\t\t";
            if ((isset($context["U_RESTORE_PERMISSIONS"]) ? $context["U_RESTORE_PERMISSIONS"] : null)) {
                // line 164
                echo "\t\t\t\t\t\t<li><a href=\"";
                echo (isset($context["U_RESTORE_PERMISSIONS"]) ? $context["U_RESTORE_PERMISSIONS"] : null);
                echo "\"><i class=\"fa fa-refresh fa-fw\" aria-hidden=\"true\"></i> ";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("RESTORE_PERMISSIONS");
                echo "</a></li>
\t\t\t\t\t";
            }
            // line 166
            echo "\t\t\t\t\t";
            // line 167
            echo "\t\t\t\t\t<li><a href=\"";
            echo (isset($context["U_PROFILE"]) ? $context["U_PROFILE"] : null);
            echo "\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PROFILE");
            echo "\" accesskey=\"e\"><i class=\"fa fa-wrench fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PROFILE");
            echo "</a></li>
\t\t\t\t\t<li><a href=\"";
            // line 168
            echo (isset($context["U_USER_PROFILE"]) ? $context["U_USER_PROFILE"] : null);
            echo "\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("READ_PROFILE");
            echo "\"><i class=\"fa fa-sliders fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("READ_PROFILE");
            echo "</a></li>
\t\t\t\t\t";
            // line 169
            // line 170
            echo "\t\t\t\t\t<li class=\"divider\"></li>
\t\t\t\t\t<li><a href=\"";
            // line 171
            echo (isset($context["U_LOGIN_LOGOUT"]) ? $context["U_LOGIN_LOGOUT"] : null);
            echo "\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN_LOGOUT");
            echo "\" accesskey=\"x\"><i class=\"fa fa-sign-out fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN_LOGOUT");
            echo "</a></li>
\t\t\t\t\t";
            // line 172
            // line 173
            echo "\t\t\t\t\t";
            // line 174
            echo "\t\t\t\t";
        } else {
            // line 175
            echo "\t\t\t\t\t<li>
\t\t\t\t\t\t<div class=\"row no-margin-bottom\">
\t\t\t\t\t\t\t<div class=\"col-xs-12\">
\t\t\t\t\t\t\t\t<button class=\"btn btn-danger btn-block\" type=\"button\" title=\"";
            // line 178
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN_LOGOUT");
            echo "\" onclick=\"window.location.href='";
            echo (isset($context["U_LOGIN_LOGOUT"]) ? $context["U_LOGIN_LOGOUT"] : null);
            echo "'\" accesskey=\"x\"><i class=\"fa fa-sign-out fa-fw\" aria-hidden=\"true\"></i> ";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN_LOGOUT");
            echo "</button>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<br class=\"col-xs-12\"/>
\t\t\t\t\t\t\t<br class=\"col-xs-12\"/>
\t\t\t\t\t\t\t<div class=\"col-xs-12\">
\t\t\t\t\t\t\t\t";
            // line 183
            if ((isset($context["S_REGISTER_ENABLED"]) ? $context["S_REGISTER_ENABLED"] : null)) {
                // line 184
                echo "\t\t\t\t\t\t\t\t\t<button class=\"btn btn-primary btn-block\" type=\"button\" onclick=\"window.location.href='";
                echo (isset($context["U_REGISTER"]) ? $context["U_REGISTER"] : null);
                echo "'\"><i class=\"fa fa-user fa-fw\" aria-hidden=\"true\"></i> ";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("REGISTER");
                echo "</button>
\t\t\t\t\t\t\t\t";
            } else {
                // line 186
                echo "\t\t\t\t\t\t\t\t\t<button class=\"btn btn-primary btn-block\" disabled=\"disabled\" type=\"button\"><i class=\"fa fa-user fa-fw\" aria-hidden=\"true\"></i> ";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("REGISTER");
                echo "</button>
\t\t\t\t\t\t\t\t";
            }
            // line 188
            echo "\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t</li>
\t\t\t\t";
        }
        // line 192
        echo "\t\t\t</ul>
\t\t</div>
\t\t";
        // line 194
        if (((isset($context["S_REGISTERED_USER"]) ? $context["S_REGISTERED_USER"] : null) && (isset($context["S_NOTIFICATIONS_DISPLAY"]) ? $context["S_NOTIFICATIONS_DISPLAY"] : null))) {
            // line 195
            echo "\t\t\t<div class=\"collapse navbar-collapse pull-right-desktop\" id=\"notification-menu\">
\t\t\t\t<ul class=\"nav navbar-nav navbar-right hidden-xs\">
\t\t\t\t\t<li class=\"dropdown\">
\t\t\t\t\t\t<a href=\"#\" class=\"dropdown-toggle hidden-xs\" id=\"notification-button\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-bell fa-fw\" aria-hidden=\"true\"></i> ";
            // line 198
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NOTIFICATIONS");
            echo " <span class=\"label label-primary\">";
            echo (isset($context["NOTIFICATIONS_COUNT"]) ? $context["NOTIFICATIONS_COUNT"] : null);
            echo "</span></a>
\t\t\t\t\t\t<ul class=\"dropdown-menu notification-panel\" id=\"notification-panel-desktop\" role=\"menu\">
\t\t\t\t\t\t\t<li><div id=\"notification_list\">";
            // line 200
            $location = "notification_dropdown.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("notification_dropdown.html", "navbar_header.html", 200)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
            echo "</div></li>
\t\t\t\t\t\t</ul>
\t\t\t\t\t</li>
\t\t\t\t</ul>
\t\t\t\t<ul class=\"nav navbar-nav navbar-right visible-xs notification-panel\" id=\"notification-panel-mobile\">
\t\t\t\t\t<li><div id=\"notification_list_mobile\">";
            // line 205
            $location = "notification_dropdown.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("notification_dropdown.html", "navbar_header.html", 205)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
            echo "</div></li>
\t\t\t\t</ul>
\t\t\t</div>
\t\t";
        }
        // line 209
        echo "\t</div>
</nav>
";
    }

    public function getTemplateName()
    {
        return "navbar_header.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  622 => 209,  605 => 205,  587 => 200,  580 => 198,  575 => 195,  573 => 194,  569 => 192,  563 => 188,  557 => 186,  549 => 184,  547 => 183,  535 => 178,  530 => 175,  527 => 174,  525 => 173,  524 => 172,  516 => 171,  513 => 170,  512 => 169,  504 => 168,  495 => 167,  493 => 166,  485 => 164,  482 => 163,  480 => 162,  478 => 161,  473 => 158,  472 => 157,  468 => 155,  465 => 154,  463 => 153,  458 => 151,  455 => 150,  448 => 148,  445 => 147,  443 => 146,  437 => 143,  432 => 142,  425 => 138,  421 => 136,  419 => 135,  412 => 133,  404 => 130,  399 => 128,  396 => 127,  393 => 126,  392 => 125,  384 => 124,  381 => 123,  380 => 122,  372 => 121,  363 => 120,  361 => 119,  353 => 117,  350 => 116,  348 => 115,  346 => 114,  341 => 111,  335 => 110,  331 => 108,  323 => 106,  321 => 105,  316 => 104,  314 => 103,  311 => 102,  310 => 101,  307 => 100,  292 => 96,  288 => 95,  285 => 94,  283 => 93,  278 => 90,  276 => 89,  265 => 83,  261 => 82,  257 => 81,  248 => 79,  243 => 77,  240 => 76,  238 => 75,  234 => 73,  232 => 72,  222 => 70,  219 => 69,  209 => 67,  207 => 66,  198 => 65,  197 => 64,  193 => 62,  192 => 61,  189 => 60,  186 => 59,  177 => 58,  169 => 57,  166 => 56,  164 => 55,  161 => 54,  154 => 52,  149 => 50,  145 => 49,  138 => 47,  131 => 46,  123 => 44,  120 => 43,  112 => 41,  109 => 40,  101 => 38,  98 => 37,  95 => 36,  93 => 35,  92 => 34,  87 => 32,  81 => 31,  76 => 28,  70 => 24,  67 => 23,  61 => 19,  59 => 18,  56 => 17,  52 => 15,  44 => 13,  42 => 12,  37 => 11,  35 => 10,  28 => 8,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "navbar_header.html", "");
    }
}
