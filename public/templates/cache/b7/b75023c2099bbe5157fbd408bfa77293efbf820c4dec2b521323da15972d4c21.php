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

/* _get_bib.html.twig */
class __TwigTemplate_22fa18c102f4fc0cd04bcdd262fcdfa829ddd911244ae3f9e8a7f1fa41c4915a extends Template
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
            <input type=\"hidden\" name=\"page\" value=\"get_bib\">
            <label for=\"bib_key\">Bib Key:</label><br>
            <input type=\"text\" id=\"bib_key\" name=\"bib_key\" value=\"";
        // line 6
        echo twig_escape_filter($this->env, ($context["bib_key"] ?? null), "html", null, true);
        echo "\" size=\"8\" maxlength=\"8\" required><br>
            <label for=\"field_list\">Field List (use 'marc' for MARC format):</label><br>
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
        return "_get_bib.html.twig";
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
        return new Source("", "_get_bib.html.twig", "/var/www/html-dev/public/templates/_get_bib.html.twig");
    }
}
