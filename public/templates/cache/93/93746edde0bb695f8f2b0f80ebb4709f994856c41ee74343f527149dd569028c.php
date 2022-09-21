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

/* _list.html.twig */
class __TwigTemplate_bdef865e549954ad58b05000d5d2f2bc4ac9be64b9fcd8673c0054e07137344b extends Template
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
        echo "    <tr>
        <td>";
        // line 2
        echo twig_escape_filter($this->env, ($context["currentLocation"] ?? null), "html", null, true);
        echo "</td>
        <td>";
        // line 3
        echo twig_escape_filter($this->env, ($context["locationDescription"] ?? null), "html", null, true);
        echo "</td>
        <td>";
        // line 4
        echo twig_escape_filter($this->env, ($context["itemType"] ?? null), "html", null, true);
        echo "</td>
        <td>";
        // line 5
        echo twig_escape_filter($this->env, ($context["callNumber"] ?? null), "html", null, true);
        echo "</td>
        <td><a href=\"";
        // line 6
        echo twig_escape_filter($this->env, ($context["base_URL"] ?? null), "html", null, true);
        echo "/index.php?page=search&index=AUTHOR&terms=";
        echo twig_escape_filter($this->env, ($context["author_search"] ?? null), "html", null, true);
        echo "\" alt=\"Search for author\">";
        echo twig_escape_filter($this->env, ($context["author"] ?? null), "html", null, true);
        echo "</a></td>
        <td><a href=\"";
        // line 7
        echo twig_escape_filter($this->env, ($context["base_URL"] ?? null), "html", null, true);
        echo "/index.php?page=search&index=TITLE&terms=";
        echo twig_escape_filter($this->env, ($context["title_search"] ?? null), "html", null, true);
        echo "\" alt=\"Search for title\">";
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</a></td>
        <td><a href=\"";
        // line 8
        echo twig_escape_filter($this->env, ($context["base_URL"] ?? null), "html", null, true);
        echo "/index.php?page=get_item&item_key=";
        echo twig_escape_filter($this->env, ($context["item"] ?? null), "html", null, true);
        echo "\" alt=\"Display item record\">";
        echo twig_escape_filter($this->env, ($context["barcode"] ?? null), "html", null, true);
        echo "</a></td>
    </tr>
";
    }

    public function getTemplateName()
    {
        return "_list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  72 => 8,  64 => 7,  56 => 6,  52 => 5,  48 => 4,  44 => 3,  40 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "_list.html.twig", "/var/www/html-dev/public/templates/_list.html.twig");
    }
}
