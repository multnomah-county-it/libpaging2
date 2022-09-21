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

/* _get_item.html.twig */
class __TwigTemplate_9344126ab5cfd843d9aa08699c9b39f031a7680358c21877794f289d8ecd80f5 extends Template
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
        echo "<div class=\"center-block\">
    <form action=\"/index.php\">
        <p class=\"list list-wide\">
            <input type=\"hidden\" name=\"page\" value=\"get_item\">
            <label for=\"item_key\">Item Key:</label><br>
            <input type=\"text\" id=\"item_key\" name=\"item_key\" value=\"";
        // line 6
        echo twig_escape_filter($this->env, ($context["item_key"] ?? null), "html", null, true);
        echo "\" size=\"14\" maxlength=\"14\" required><br>
            <label for=\"field_list\">Field List (use * for all fields):</label><br>
            <textarea class=\"field_list\" id=\"field_list\" name=\"field_list\" wrap=\"off\" rows=\"2\" cols=\"70\" maxlength=\"256\" required>";
        // line 8
        echo twig_escape_filter($this->env, ($context["field_list"] ?? null), "html", null, true);
        echo "</textarea><br><br>
            <input type=\"submit\" value=\"Get Record\">
        </p>
    </form>
</div>
<hr>
";
    }

    public function getTemplateName()
    {
        return "_get_item.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 8,  44 => 6,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "_get_item.html.twig", "/var/www/html-dev/public/templates/_get_item.html.twig");
    }
}
