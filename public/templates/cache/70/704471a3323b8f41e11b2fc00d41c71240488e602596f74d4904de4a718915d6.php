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

/* _list_start.html.twig */
class __TwigTemplate_0ab49e193e6dd6c07e691994021584f4a797ba57cd1c0eedbcd9e3fc370bd2f3 extends Template
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
        echo "<h1 class=\"center-text\">";
        echo twig_escape_filter($this->env, ($context["name"] ?? null), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, ($context["type"] ?? null), "html", null, true);
        echo " Paging List for ";
        echo twig_escape_filter($this->env, ($context["today"] ?? null), "html", null, true);
        echo "</h1>

<table class=\"center-block\">
    <tr>
        <th>Location</th>
        <th>Location Description</th>
        <th>Item Type</th>
        <th>Call Number</th>
        <th>Author</th>
        <th>Title</th>
        <th>Barcode</th>
    </tr>
";
    }

    public function getTemplateName()
    {
        return "_list_start.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "_list_start.html.twig", "/var/www/html-dev/public/templates/_list_start.html.twig");
    }
}
