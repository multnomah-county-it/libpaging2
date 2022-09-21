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

/* _search.html.twig */
class __TwigTemplate_12838cea786769e1623d9b6cba0ee00ae70bbe3b3786577b2a05c9ccfbfd169f extends Template
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
            <input type=\"hidden\" name=\"page\" value=\"search\">
            <label for=\"index\">Index:</label><br>
            ";
        // line 6
        echo ($context["index_select"] ?? null);
        echo "<br>
            <label for=\"terms\">Terms:</label><br>
            <input type=\"text\" id=\"terms\" name=\"terms\" value=\"";
        // line 8
        echo twig_escape_filter($this->env, ($context["terms"] ?? null), "html", null, true);
        echo "\" size=\"40\" maxlength=\"60\" required><br>
            <label for=\"field_list\">Field List:</label><br>
            <textarea class=\"field_list\" id=\"field_list\" name=\"field_list\" wrap=\"off\" rows=\"2\" cols=\"70\" maxlength=\"256\" required>";
        // line 10
        echo twig_escape_filter($this->env, ($context["field_list"] ?? null), "html", null, true);
        echo "</textarea><br><br>
            <input type=\"radio\" id=\"j\" name=\"j\" value=\"AND\"";
        // line 11
        echo twig_escape_filter($this->env, ($context["j_AND"] ?? null), "html", null, true);
        echo "><label for=\"j\">AND</label>
            <input type=\"radio\" id=\"j\" name=\"j\" value=\"OR\"";
        // line 12
        echo twig_escape_filter($this->env, ($context["j_OR"] ?? null), "html", null, true);
        echo "><label for=\"j\">OR</label>
            <label for=\"ct\">&nbsp;&nbsp;&nbsp;&nbsp;Max Rows to Return: </label><input type=\"text\" id=\"ct\" name=\"ct\" value=\"";
        // line 13
        echo twig_escape_filter($this->env, ($context["ct"] ?? null), "html", null, true);
        echo "\" size=\"4\" maxlength=\"4\" required><br><br>
            <input type=\"submit\" value=\"Search\">
        </p>
    </form>
</div>
<hr>
";
    }

    public function getTemplateName()
    {
        return "_search.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 13,  62 => 12,  58 => 11,  54 => 10,  49 => 8,  44 => 6,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "_search.html.twig", "/var/www/html-dev/public/templates/_search.html.twig");
    }
}
