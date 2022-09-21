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

/* _library.html.twig */
class __TwigTemplate_e9872510c0133bfcd7696a4006c3c97ddbf3f66bb51605a6c1c686b755ac4aa4 extends Template
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
        echo " Paging Lists for ";
        echo twig_escape_filter($this->env, ($context["today"] ?? null), "html", null, true);
        echo "</h1>
<p class=\"center-text list list-narrow\">
    <a href=\"/index.php?page=list&code=";
        // line 3
        echo twig_escape_filter($this->env, ($context["code"] ?? null), "html", null, true);
        echo "&type=title\" alt=\"Title List\">Title List</a><br>
    <a href=\"/index.php?page=list&code=";
        // line 4
        echo twig_escape_filter($this->env, ($context["code"] ?? null), "html", null, true);
        echo "&type=item\" alt=\"Item List\">Item List</a>
</p>
";
    }

    public function getTemplateName()
    {
        return "_library.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 4,  45 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "_library.html.twig", "/var/www/html-dev/public/templates/_library.html.twig");
    }
}
