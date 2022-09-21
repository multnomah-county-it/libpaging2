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

/* _index_list.html.twig */
class __TwigTemplate_04f9a5b2ced1740b3e499001ed23a9a563c223fec447d8eeddb2d69d898bd66f extends Template
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
        echo "<h1 class=\"center-text\">Multnomah County Library Paging Lists for ";
        echo twig_escape_filter($this->env, ($context["today"] ?? null), "html", null, true);
        echo "</h1>
<p class=\"list list-narrow\">
";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["branches"] ?? null));
        foreach ($context['_seq'] as $context["code"] => $context["name"]) {
            // line 4
            echo "    <a href=\"/index.php?page=library&code=";
            echo twig_escape_filter($this->env, $context["code"], "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, $context["name"], "html", null, true);
            echo " Paging Lists\">";
            echo twig_escape_filter($this->env, $context["name"], "html", null, true);
            echo "</a><br>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['code'], $context['name'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 6
        echo "</p>
<div class=\"center-text\">
<form id=\"update_paging\" method=\"get\" action=\"/index.php\">
<input name=\"page\" type=\"hidden\" value=\"index_list\">
<input name=\"update\" type=\"hidden\" value=\"1\">
<input value=\"Update Paging Lists\" type=\"submit\">
</form>
</div>
<p class=\"center-text small\">";
        // line 14
        echo twig_escape_filter($this->env, ($context["update"] ?? null), "html", null, true);
        echo "</p>
";
    }

    public function getTemplateName()
    {
        return "_index_list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 14,  60 => 6,  47 => 4,  43 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "_index_list.html.twig", "/var/www/html-dev/public/templates/_index_list.html.twig");
    }
}
