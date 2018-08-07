<?php

/* additional_files.html */
class __TwigTemplate_8bdaf07a2ee9e6a4d37f2ae4e35f19c0fe4a369bbc32e125795a06b2bca738dd extends Twig_Template
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
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/bootstrap.min.css");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_stylesheet($asset);        // line 2
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/colorpicker.min.css");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_stylesheet($asset);        // line 3
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/select.min.css");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_stylesheet($asset);        // line 4
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/highlight.min.css");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_stylesheet($asset);        // line 5
        if (($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "LIGHTBOX", array()) == 1)) {
            // line 6
            echo "\t";
            $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/lightbox.min.css");
            $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
            if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
                $asset_path = $asset->get_path();                $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
                if (!file_exists($local_file)) {
                    $local_file = $this->getEnvironment()->findTemplate($asset_path);
                    $asset->set_path($local_file, true);
                }
                $asset->add_assets_version('285');
            }
            $this->getEnvironment()->get_assets_bag()->add_stylesheet($asset);        }
        // line 8
        if (($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "VALUE_SLIDER", array()) == 1)) {
            // line 9
            echo "\t";
            $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/slider.min.css");
            $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
            if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
                $asset_path = $asset->get_path();                $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
                if (!file_exists($local_file)) {
                    $local_file = $this->getEnvironment()->findTemplate($asset_path);
                    $asset->set_path($local_file, true);
                }
                $asset->add_assets_version('285');
            }
            $this->getEnvironment()->get_assets_bag()->add_stylesheet($asset);        }
        // line 11
        if (($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "THEME_FILE", array()) == 1)) {
            // line 12
            echo "\t";
            $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/bootstrap-theme.min.css");
            $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
            if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
                $asset_path = $asset->get_path();                $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
                if (!file_exists($local_file)) {
                    $local_file = $this->getEnvironment()->findTemplate($asset_path);
                    $asset->set_path($local_file, true);
                }
                $asset->add_assets_version('285');
            }
            $this->getEnvironment()->get_assets_bag()->add_stylesheet($asset);        }
        // line 14
        echo "
";
        // line 15
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/progressbar.min.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 16
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/bootstrap.min.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 17
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/autosize.min.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 18
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/pace.min.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 19
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/jquery.pjax.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 20
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/js.cookie.min.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 21
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/bootbox.min.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 22
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/colorpicker.min.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 23
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/select.min.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 24
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/highlight.min.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);        // line 25
        if (($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "LIGHTBOX", array()) == 1)) {
            // line 26
            echo "\t";
            $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/lightbox.min.js");
            $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
            if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
                $asset_path = $asset->get_path();                $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
                if (!file_exists($local_file)) {
                    $local_file = $this->getEnvironment()->findTemplate($asset_path);
                    $asset->set_path($local_file, true);
                }
                $asset->add_assets_version('285');
            }
            $this->getEnvironment()->get_assets_bag()->add_script($asset);            // line 27
            echo "\t";
            $value = true;
            $context['definition']->set('INCLUDED_LIGHTBOXJS', $value);
        }
        // line 29
        if (($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "VALUE_SLIDER", array()) == 1)) {
            // line 30
            echo "\t";
            $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/slider.min.js");
            $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
            if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
                $asset_path = $asset->get_path();                $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
                if (!file_exists($local_file)) {
                    $local_file = $this->getEnvironment()->findTemplate($asset_path);
                    $asset->set_path($local_file, true);
                }
                $asset->add_assets_version('285');
            }
            $this->getEnvironment()->get_assets_bag()->add_script($asset);        }
        // line 32
        if (($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "AUTOSAVE", array()) == 1)) {
            // line 33
            echo "\t";
            $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/autosave.min.js");
            $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
            if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
                $asset_path = $asset->get_path();                $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
                if (!file_exists($local_file)) {
                    $local_file = $this->getEnvironment()->findTemplate($asset_path);
                    $asset->set_path($local_file, true);
                }
                $asset->add_assets_version('285');
            }
            $this->getEnvironment()->get_assets_bag()->add_script($asset);        }
        // line 35
        $asset_file = (("" . (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null)) . "/comboot/comboot.js");
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper(), $this->getEnvironment()->get_filesystem());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            }
            $asset->add_assets_version('285');
        }
        $this->getEnvironment()->get_assets_bag()->add_script($asset);    }

    public function getTemplateName()
    {
        return "additional_files.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  270 => 35,  257 => 33,  255 => 32,  242 => 30,  240 => 29,  235 => 27,  223 => 26,  221 => 25,  210 => 24,  199 => 23,  188 => 22,  177 => 21,  166 => 20,  155 => 19,  144 => 18,  133 => 17,  122 => 16,  111 => 15,  108 => 14,  95 => 12,  93 => 11,  80 => 9,  78 => 8,  65 => 6,  63 => 5,  52 => 4,  41 => 3,  30 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "additional_files.html", "");
    }
}
