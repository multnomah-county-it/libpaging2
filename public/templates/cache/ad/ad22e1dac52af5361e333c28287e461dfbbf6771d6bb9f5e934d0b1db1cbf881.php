<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* _header.html.twig */
class __TwigTemplate_de1585ccc1a7a5c837ba03160f0aed9754cc8ff456efa80918549cba04043412 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
        <meta name=\"robots\" content=\"noindex\" />
        <meta http-equiv=\"Cache-Control\" content=\"no-cache, no-store, must-revalidate\" />
        <meta http-equiv=\"Pragma\" content=\"no-cache\" />
        <meta http-equiv=\"Expires\" content=\"0\" />
        <meta name=\"Author\" content=\"Multnomah County IT\" />
        <meta name=\"Language\" content=\"en\" />
        <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, ($context["styles_path"] ?? null), "html", null, true);
        echo "\">
        <link rel=\"icon\" type=\"image/x-icon\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, ($context["favicon_path"] ?? null), "html", null, true);
        echo "\">
        <title>";
        // line 13
        echo twig_escape_filter($this->env, ($context["page_title"] ?? null), "html", null, true);
        echo "</title>
    </head>
    <body>
        <div class=\"header\">
            <a class=\"logo center-flex\" href=\"";
        // line 17
        echo twig_escape_filter($this->env, ($context["logo_link_path"] ?? null), "html", null, true);
        echo "\" alt=\"Link to main website\">
                <img class=\"logo\" src=\"";
        // line 18
        echo twig_escape_filter($this->env, ($context["logo_path"] ?? null), "html", null, true);
        echo "\" alt=\"Logo\"></a>
            <div class=\"header-right center-flex\">
                ";
        // line 20
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["menu_items"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["menu_item"]) {
            // line 21
            echo "                    &nbsp;&nbsp;<a class=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["menu_item"], "class", [], "any", false, false, false, 21), "html", null, true);
            echo "\" href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["menu_item"], "link", [], "any", false, false, false, 21), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["menu_item"], "name", [], "any", false, false, false, 21), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["menu_item"], "name", [], "any", false, false, false, 21), "html", null, true);
            echo "</a>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['menu_item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        echo "            </div>
        </div>
";
    }

    public function getTemplateName()
    {
        return "_header.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  92 => 23,  77 => 21,  73 => 20,  68 => 18,  64 => 17,  57 => 13,  53 => 12,  49 => 11,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "_header.html.twig", "/var/www/html-dev/public/templates/_header.html.twig");
    }
}
