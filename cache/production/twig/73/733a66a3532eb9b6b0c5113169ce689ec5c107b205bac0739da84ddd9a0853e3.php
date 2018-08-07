<?php

/* forumlist_body.html */
class __TwigTemplate_df8de16ee1c2f6f0de410a64d6232f48d3edc4d44d3752367830c11d139028ec extends Twig_Template
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
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "forumrow", array()));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["forumrow"]) {
            // line 2
            echo "\t";
            if ((($this->getAttribute($context["forumrow"], "S_IS_CAT", array()) &&  !$this->getAttribute($context["forumrow"], "S_FIRST_ROW", array())) || $this->getAttribute($context["forumrow"], "S_NO_CAT", array()))) {
                // line 3
                echo "\t\t\t\t</tbody>
\t\t\t</table>
\t\t</div>
\t";
            }
            // line 7
            echo "
\t";
            // line 8
            // line 9
            echo "\t";
            if ((($this->getAttribute($context["forumrow"], "S_IS_CAT", array()) || $this->getAttribute($context["forumrow"], "S_FIRST_ROW", array())) || $this->getAttribute($context["forumrow"], "S_NO_CAT", array()))) {
                // line 10
                echo "\t\t<div class=\"table-responsive\">
\t\t\t<table class=\"table table-striped table-bordered table-hover\">
\t\t\t\t<thead class=\"topiclist\">
\t\t\t\t\t<tr>
\t\t\t\t\t\t";
                // line 14
                // line 15
                echo "\t\t\t\t\t\t<th class=\"forum-name\">";
                if ($this->getAttribute($context["forumrow"], "S_IS_CAT", array())) {
                    echo "<i class=\"fa fa-sitemap fa-fw\" aria-hidden=\"true\"></i> <a href=\"";
                    echo $this->getAttribute($context["forumrow"], "U_VIEWFORUM", array());
                    echo "\">";
                    echo $this->getAttribute($context["forumrow"], "FORUM_NAME", array());
                    echo "</a>";
                } else {
                    echo "<i class=\"fa fa-folder-open fa-fw\" aria-hidden=\"true\"></i> ";
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("FORUM");
                }
                echo "</th>
\t\t\t\t\t\t<th class=\"topics hidden-xs\"><i class=\"fa fa-comments-o fa-fw\" aria-hidden=\"true\"></i> ";
                // line 16
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("TOPICS");
                echo "</th>
\t\t\t\t\t\t<th class=\"posts hidden-xs\"><i class=\"fa fa-pencil-square-o fa-fw\" aria-hidden=\"true\"></i> ";
                // line 17
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("POSTS");
                echo "</th>
\t\t\t\t\t\t<th class=\"lastpost hidden-xs\"><i class=\"fa fa-history fa-fw\" aria-hidden=\"true\"></i> <span>";
                // line 18
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LAST_POST");
                echo "</span></th>
\t\t\t\t\t\t";
                // line 19
                // line 20
                echo "\t\t\t\t\t</tr>
\t\t\t\t</thead>
\t\t\t\t<tbody class=\"topiclist forums\">
\t";
            }
            // line 24
            echo "\t";
            // line 25
            echo "
\t";
            // line 26
            if ( !$this->getAttribute($context["forumrow"], "S_IS_CAT", array())) {
                // line 27
                echo "\t\t";
                // line 28
                echo "\t\t\t\t<tr class=\"icon clickable ";
                echo $this->getAttribute($context["forumrow"], "FORUM_IMG_STYLE", array());
                echo "\" onclick=\"location.href='";
                echo $this->getAttribute($context["forumrow"], "U_VIEWFORUM", array());
                echo "'\">
\t\t\t\t\t";
                // line 29
                // line 30
                echo "\t\t\t\t\t<td class=\"forum-name\" title=\"";
                echo $this->getAttribute($context["forumrow"], "FORUM_FOLDER_IMG_ALT", array());
                echo "\" ";
                if ($this->getAttribute($context["forumrow"], "S_IS_LINK", array())) {
                    echo "colspan=\"3\"";
                }
                echo ">
\t\t\t\t\t\t<div class=\"pull-left forum-icon\">
\t\t\t\t\t\t\t<a href=\"";
                // line 32
                echo $this->getAttribute($context["forumrow"], "U_VIEWFORUM", array());
                echo "\" class=\"btn ";
                if ( !$this->getAttribute($context["forumrow"], "FORUM_IMAGE", array())) {
                    echo "btn-lg";
                }
                echo " ";
                if ($this->getAttribute($context["forumrow"], "S_LOCKED_FORUM", array())) {
                    echo "btn-danger";
                } elseif ($this->getAttribute($context["forumrow"], "S_UNREAD_FORUM", array())) {
                    echo "btn-info";
                } else {
                    echo "btn-default";
                }
                echo " tooltip-link hidden-xs\" title=\"";
                echo $this->getAttribute($context["forumrow"], "FORUM_FOLDER_IMG_ALT", array());
                echo "\">
\t\t\t\t\t\t\t\t";
                // line 33
                if ($this->getAttribute($context["forumrow"], "FORUM_IMAGE", array())) {
                    // line 34
                    echo "\t\t\t\t\t\t\t\t\t<span class=\"forum-image\">";
                    echo $this->getAttribute($context["forumrow"], "FORUM_IMAGE", array());
                    echo "</span>
\t\t\t\t\t\t\t\t";
                } elseif ($this->getAttribute(                // line 35
$context["forumrow"], "S_IS_LINK", array())) {
                    // line 36
                    echo "\t\t\t\t\t\t\t\t\t<i class=\"fa fa-globe fa-fw\" aria-hidden=\"true\"></i>
\t\t\t\t\t\t\t\t";
                } elseif ($this->getAttribute(                // line 37
$context["forumrow"], "S_LOCKED_FORUM", array())) {
                    // line 38
                    echo "\t\t\t\t\t\t\t\t\t<i class=\"fa fa-lock fa-fw\" aria-hidden=\"true\"></i>
\t\t\t\t\t\t\t\t";
                } elseif ($this->getAttribute(                // line 39
$context["forumrow"], "FORUM_ICON", array())) {
                    // line 40
                    echo "\t\t\t\t\t\t\t\t\t<i class=\"";
                    echo $this->getAttribute($context["forumrow"], "FORUM_ICON", array());
                    echo "\"></i>
\t\t\t\t\t\t\t\t";
                } else {
                    // line 42
                    echo "\t\t\t\t\t\t\t\t\t<i class=\"fa fa-folder fa-fw\" aria-hidden=\"true\"></i>
\t\t\t\t\t\t\t\t";
                }
                // line 44
                echo "\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t<div class=\"visible-xs-inline\">
\t\t\t\t\t\t\t\t";
                // line 46
                if ($this->getAttribute($context["forumrow"], "S_IS_LINK", array())) {
                    // line 47
                    echo "\t\t\t\t\t\t\t\t\t<i class=\"fa fa-globe fa-fw\" aria-hidden=\"true\"></i>
\t\t\t\t\t\t\t\t";
                } elseif ($this->getAttribute(                // line 48
$context["forumrow"], "S_LOCKED_FORUM", array())) {
                    // line 49
                    echo "\t\t\t\t\t\t\t\t\t<i class=\"fa fa-lock fa-fw\" aria-hidden=\"true\"></i>
\t\t\t\t\t\t\t\t";
                } elseif ($this->getAttribute(                // line 50
$context["forumrow"], "FORUM_ICON", array())) {
                    // line 51
                    echo "\t\t\t\t\t\t\t\t\t<i class=\"";
                    echo $this->getAttribute($context["forumrow"], "FORUM_ICON", array());
                    echo "\"></i>
\t\t\t\t\t\t\t\t";
                } else {
                    // line 53
                    echo "\t\t\t\t\t\t\t\t\t<i class=\"fa fa-folder fa-fw\" aria-hidden=\"true\"></i>
\t\t\t\t\t\t\t\t";
                }
                // line 55
                echo "\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t";
                // line 57
                if (((isset($context["S_ENABLE_FEEDS"]) ? $context["S_ENABLE_FEEDS"] : null) && $this->getAttribute($context["forumrow"], "S_FEED_ENABLED", array()))) {
                    // line 58
                    echo "\t\t\t\t\t\t\t<!--
\t\t\t\t\t\t\t\t<a class=\"feed-icon-forum\" title=\"";
                    // line 59
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("FEED");
                    echo " - ";
                    echo $this->getAttribute($context["forumrow"], "FORUM_NAME", array());
                    echo "\" href=\"";
                    echo (isset($context["U_FEED"]) ? $context["U_FEED"] : null);
                    echo "?f=";
                    echo $this->getAttribute($context["forumrow"], "FORUM_ID", array());
                    echo "\">
\t\t\t\t\t\t\t\t\t<i class=\"fa fa-rss-square fa-fw\" aria-hidden=\"true\"></i><span class=\"sr-only\">";
                    // line 60
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("FEED");
                    echo " - ";
                    echo $this->getAttribute($context["forumrow"], "FORUM_NAME", array());
                    echo "</span>
\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t-->
\t\t\t\t\t\t";
                }
                // line 64
                echo "\t\t\t\t\t\t<a href=\"";
                echo $this->getAttribute($context["forumrow"], "U_VIEWFORUM", array());
                echo "\" class=\"forumtitle\">";
                echo $this->getAttribute($context["forumrow"], "FORUM_NAME", array());
                echo "</a><br />
\t\t\t\t\t\t<small class=\"forum-desc\">";
                // line 65
                echo $this->getAttribute($context["forumrow"], "FORUM_DESC", array());
                echo "</small>
\t\t\t\t\t\t";
                // line 66
                if ($this->getAttribute($context["forumrow"], "MODERATORS", array())) {
                    // line 67
                    echo "\t\t\t\t\t\t\t<br /><strong>";
                    echo $this->getAttribute($context["forumrow"], "L_MODERATOR_STR", array());
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
                    echo "</strong> ";
                    echo $this->getAttribute($context["forumrow"], "MODERATORS", array());
                    echo "
\t\t\t\t\t\t";
                }
                // line 69
                echo "\t\t\t\t\t\t";
                if (($this->getAttribute($context["forumrow"], "SUBFORUMS", array()) && $this->getAttribute($context["forumrow"], "S_LIST_SUBFORUMS", array()))) {
                    // line 70
                    echo "\t\t\t\t\t\t\t";
                    // line 71
                    echo "\t\t\t\t\t\t\t<br /><strong>";
                    echo $this->getAttribute($context["forumrow"], "L_SUBFORUM_STR", array());
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
                    echo "</strong>
\t\t\t\t\t\t\t";
                    // line 72
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["forumrow"], "subforum", array()));
                    foreach ($context['_seq'] as $context["_key"] => $context["subforum"]) {
                        // line 73
                        echo "\t\t\t\t\t\t\t\t";
                        echo "<a href=\"";
                        echo $this->getAttribute($context["subforum"], "U_SUBFORUM", array());
                        echo "\" class=\"subforum";
                        if ($this->getAttribute($context["subforum"], "S_UNREAD", array())) {
                            echo " unread";
                        } else {
                            echo " read";
                        }
                        echo "\" title=\"";
                        if ($this->getAttribute($context["subforum"], "UNREAD", array())) {
                            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("UNREAD_POSTS");
                        } else {
                            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NO_UNREAD_POSTS");
                        }
                        echo "\"><i class=\"fa ";
                        if ($this->getAttribute($context["subforum"], "IS_LINK", array())) {
                            echo "fa-external-link";
                        } else {
                            echo "fa-file-o";
                        }
                        echo " fa-fw ";
                        if ($this->getAttribute($context["subforum"], "S_UNREAD", array())) {
                            echo " text-danger";
                        } else {
                            echo " text-primary";
                        }
                        echo "\" aria-hidden=\"true\"></i>";
                        echo $this->getAttribute($context["subforum"], "SUBFORUM_NAME", array());
                        echo "</a>
\t\t\t\t\t\t\t\t";
                        // line 74
                        if ( !$this->getAttribute($context["subforum"], "S_LAST_ROW", array())) {
                            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COMMA_SEPARATOR");
                        }
                        // line 75
                        echo "\t\t\t\t\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['subforum'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 76
                    echo "\t\t\t\t\t\t\t";
                    // line 77
                    echo "\t\t\t\t\t\t";
                }
                // line 78
                echo "\t\t\t\t\t\t";
                if ( !(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)) {
                    // line 79
                    echo "\t\t\t\t\t\t\t<div class=\"visible-xs-block\">
\t\t\t\t\t\t\t\t";
                    // line 80
                    if ($this->getAttribute($context["forumrow"], "CLICKS", array())) {
                        // line 81
                        echo "\t\t\t\t\t\t\t\t\t";
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("REDIRECTS");
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
                        echo " <strong>";
                        echo $this->getAttribute($context["forumrow"], "CLICKS", array());
                        echo "</strong>
\t\t\t\t\t\t\t\t";
                    } elseif (( !$this->getAttribute(                    // line 82
$context["forumrow"], "S_IS_LINK", array()) && $this->getAttribute($context["forumrow"], "TOPICS", array()))) {
                        // line 83
                        echo "\t\t\t\t\t\t\t\t\t";
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("TOPICS");
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
                        echo " <strong>";
                        echo $this->getAttribute($context["forumrow"], "TOPICS", array());
                        echo "</strong>
\t\t\t\t\t\t\t\t";
                    }
                    // line 85
                    echo "\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t";
                }
                // line 87
                echo "\t\t\t\t\t</td>
\t\t\t\t\t";
                // line 88
                if ($this->getAttribute($context["forumrow"], "CLICKS", array())) {
                    // line 89
                    echo "\t\t\t\t\t\t<td class=\"redirect hidden-xs\"><span>";
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("REDIRECTS");
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
                    echo " ";
                    echo $this->getAttribute($context["forumrow"], "CLICKS", array());
                    echo "</span></td>
\t\t\t\t\t";
                } elseif ( !$this->getAttribute(                // line 90
$context["forumrow"], "S_IS_LINK", array())) {
                    // line 91
                    echo "\t\t\t\t\t\t<td class=\"topics hidden-xs\"><span class=\"badge\">";
                    echo $this->getAttribute($context["forumrow"], "TOPICS", array());
                    echo "</span></td>
\t\t\t\t\t\t<td class=\"posts hidden-xs\"><span class=\"badge\">";
                    // line 92
                    echo $this->getAttribute($context["forumrow"], "POSTS", array());
                    echo "</span></td>
\t\t\t\t\t\t<td class=\"lastpost hidden-xs\">
\t\t\t\t\t\t\t";
                    // line 94
                    if ($this->getAttribute($context["forumrow"], "U_UNAPPROVED_TOPICS", array())) {
                        // line 95
                        echo "\t\t\t\t\t\t\t\t<a href=\"";
                        echo $this->getAttribute($context["forumrow"], "U_UNAPPROVED_TOPICS", array());
                        echo "\" title=\"";
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("TOPICS_UNAPPROVED");
                        echo "\">
\t\t\t\t\t\t\t\t\t<i class=\"fa fa-question fa-fw\" aria-hidden=\"true\"></i><span class=\"sr-only\">";
                        // line 96
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("TOPICS_UNAPPROVED");
                        echo "</span>
\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t";
                    } elseif ($this->getAttribute(                    // line 98
$context["forumrow"], "U_UNAPPROVED_POSTS", array())) {
                        // line 99
                        echo "\t\t\t\t\t\t\t\t<a href=\"";
                        echo $this->getAttribute($context["forumrow"], "U_UNAPPROVED_POSTS", array());
                        echo "\" title=\"";
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("POSTS_UNAPPROVED_FORUM");
                        echo "\">
\t\t\t\t\t\t\t\t\t<i class=\"fa fa-question fa-fw\" aria-hidden=\"true\"></i><span class=\"sr-only\">";
                        // line 100
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("POSTS_UNAPPROVED_FORUM");
                        echo "</span>
\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t";
                    }
                    // line 103
                    echo "\t\t\t\t\t\t\t";
                    if ($this->getAttribute($context["forumrow"], "LAST_POST_TIME", array())) {
                        echo "<dfn>";
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LAST_POST");
                        echo "</dfn>
\t\t\t\t\t\t\t";
                        // line 104
                        if ($this->getAttribute($context["forumrow"], "S_DISPLAY_SUBJECT", array())) {
                            // line 105
                            echo "\t\t\t\t\t\t\t\t";
                            // line 106
                            echo "\t\t\t\t\t\t\t\t<a href=\"";
                            echo $this->getAttribute($context["forumrow"], "U_LAST_POST", array());
                            echo "\" title=\"";
                            echo $this->getAttribute($context["forumrow"], "LAST_POST_SUBJECT", array());
                            echo "\" class=\"lastsubject\">";
                            echo $this->getAttribute($context["forumrow"], "LAST_POST_SUBJECT_TRUNCATED", array());
                            echo "</a> <br />
\t\t\t\t\t\t\t";
                        }
                        // line 108
                        echo "\t\t\t\t\t\t\t<small>";
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("POST_BY_AUTHOR");
                        echo " ";
                        echo $this->getAttribute($context["forumrow"], "LAST_POSTER_FULL", array());
                        echo "
\t\t\t\t\t\t\t\t";
                        // line 109
                        if ( !(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)) {
                            // line 110
                            echo "\t\t\t\t\t\t\t\t\t<a href=\"";
                            echo $this->getAttribute($context["forumrow"], "U_LAST_POST", array());
                            echo "\"  title=\"";
                            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("VIEW_LATEST_POST");
                            echo "\">
\t\t\t\t\t\t\t\t\t\t<i class=\"fa fa-external-link fa-fw\" aria-hidden=\"true\"></i><span class=\"sr-only\">";
                            // line 111
                            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("VIEW_LATEST_POST");
                            echo "</span>
\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t";
                        }
                        // line 114
                        echo "\t\t\t\t\t\t\t\t<br />";
                        echo $this->getAttribute($context["forumrow"], "LAST_POST_TIME", array());
                    } else {
                        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NO_POSTS");
                        echo "<br />&nbsp;</small>";
                    }
                    // line 115
                    echo "\t\t\t\t\t\t\t</small>
\t\t\t\t\t\t</td>
\t\t\t\t\t";
                } else {
                    // line 118
                    echo "\t\t\t\t\t\t<td class=\"hidden-xs\">&nbsp;</td>
\t\t\t\t\t";
                }
                // line 120
                echo "\t\t\t\t\t";
                // line 121
                echo "\t\t\t\t</tr>
\t\t\t\t";
                // line 122
                // line 123
                echo "\t";
            }
            // line 124
            echo "\t";
            if ($this->getAttribute($context["forumrow"], "S_LAST_ROW", array())) {
                // line 125
                echo "\t\t\t\t</tbody>
\t\t\t</table>
\t\t</div>
\t";
                // line 128
                // line 129
                echo "\t";
            }
            // line 130
            echo "
";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 132
            echo "\t<div class=\"alert alert-danger\">
\t\t<strong>";
            // line 133
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NO_FORUMS");
            echo "</strong>
\t</div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['forumrow'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    public function getTemplateName()
    {
        return "forumlist_body.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  466 => 133,  463 => 132,  457 => 130,  454 => 129,  453 => 128,  448 => 125,  445 => 124,  442 => 123,  441 => 122,  438 => 121,  436 => 120,  432 => 118,  427 => 115,  420 => 114,  414 => 111,  407 => 110,  405 => 109,  398 => 108,  388 => 106,  386 => 105,  384 => 104,  377 => 103,  371 => 100,  364 => 99,  362 => 98,  357 => 96,  350 => 95,  348 => 94,  343 => 92,  338 => 91,  336 => 90,  328 => 89,  326 => 88,  323 => 87,  319 => 85,  310 => 83,  308 => 82,  300 => 81,  298 => 80,  295 => 79,  292 => 78,  289 => 77,  287 => 76,  281 => 75,  277 => 74,  245 => 73,  241 => 72,  235 => 71,  233 => 70,  230 => 69,  221 => 67,  219 => 66,  215 => 65,  208 => 64,  199 => 60,  189 => 59,  186 => 58,  184 => 57,  180 => 55,  176 => 53,  170 => 51,  168 => 50,  165 => 49,  163 => 48,  160 => 47,  158 => 46,  154 => 44,  150 => 42,  144 => 40,  142 => 39,  139 => 38,  137 => 37,  134 => 36,  132 => 35,  127 => 34,  125 => 33,  107 => 32,  97 => 30,  96 => 29,  89 => 28,  87 => 27,  85 => 26,  82 => 25,  80 => 24,  74 => 20,  73 => 19,  69 => 18,  65 => 17,  61 => 16,  47 => 15,  46 => 14,  40 => 10,  37 => 9,  36 => 8,  33 => 7,  27 => 3,  24 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "forumlist_body.html", "");
    }
}
