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

/* _search_result_list.html.twig */
class __TwigTemplate_8aa7ba9b06854fed7d523da1e2daa2b9f1bfff7c4be8b6b355607a3ce886ca3b extends Template
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
        echo "<div id=\"record\" class=\"center-block\">
    <table class=\"center-block\">
        ";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["record"] ?? null));
        foreach ($context['_seq'] as $context["key"] => $context["field"]) {
            // line 4
            echo "            ";
            if ((0 === twig_compare($context["key"], "key"))) {
                // line 5
                echo "                <tr>
                    <th class=\"c1\">";
                // line 6
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                echo "</th>
                    <td class=\"c2\" colspan=\"2\"><a href=\"";
                // line 7
                echo twig_escape_filter($this->env, ($context["base_URL"] ?? null), "html", null, true);
                echo "/index.php?page=get_bib&bib_key=";
                echo twig_escape_filter($this->env, $context["field"], "html", null, true);
                echo "\" alt=\"Display bib record\">";
                echo twig_escape_filter($this->env, $context["field"], "html", null, true);
                echo "</a></td>
                </tr>
            ";
            } elseif ((0 === twig_compare(            // line 9
$context["key"], "author"))) {
                // line 10
                echo "                <tr>
                    <th class=\"c1\">";
                // line 11
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                echo "</th>
                    <td class=\"c2\" colspan=\"2\"><a href=\"";
                // line 12
                echo twig_escape_filter($this->env, ($context["base_URL"] ?? null), "html", null, true);
                echo "/index.php?page=search&terms=";
                echo twig_escape_filter($this->env, ($context["author_search"] ?? null), "html", null, true);
                echo "\" alt=\"Search for author\">";
                echo twig_escape_filter($this->env, $context["field"], "html", null, true);
                echo "</a></td>
                </tr>
            ";
            } elseif ((0 === twig_compare(            // line 14
$context["key"], "title"))) {
                // line 15
                echo "                <tr>
                    <th class=\"c1\">";
                // line 16
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                echo "</th>
                    <td class=\"c2\" colspan=\"2\"><a href=\"";
                // line 17
                echo twig_escape_filter($this->env, ($context["base_URL"] ?? null), "html", null, true);
                echo "/index.php?page=search&terms=";
                echo twig_escape_filter($this->env, ($context["title_search"] ?? null), "html", null, true);
                echo "\" alt=\"Search for title\">";
                echo twig_escape_filter($this->env, $context["field"], "html", null, true);
                echo "</a></td>
                </tr>
            ";
            } elseif ((0 !== twig_compare(            // line 19
$context["key"], "callList"))) {
                // line 20
                echo "                <tr>
                    <th class=\"c1\">";
                // line 21
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                echo "</th>
                    <td class=\"c2\" colspan=\"2\">";
                // line 22
                echo twig_escape_filter($this->env, $context["field"], "html", null, true);
                echo "</td>
                </tr>
            ";
            } else {
                // line 25
                echo "                ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["field"]);
                foreach ($context['_seq'] as $context["i"] => $context["call"]) {
                    // line 26
                    echo "                    ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($context["call"]);
                    foreach ($context['_seq'] as $context["item_key"] => $context["item"]) {
                        // line 27
                        echo "                        <tr>
                        ";
                        // line 28
                        if ((0 === twig_compare($context["item_key"], "key"))) {
                            // line 29
                            echo "                            <th class=\"c2\" colspan=\"2\">";
                            echo twig_escape_filter($this->env, $context["item_key"], "html", null, true);
                            echo "</th>
                            <td class=\"c1\"><a href=\"";
                            // line 30
                            echo twig_escape_filter($this->env, ($context["base_URL"] ?? null), "html", null, true);
                            echo "/index.php?page=get_item&item_key=";
                            echo twig_escape_filter($this->env, $context["item"], "html", null, true);
                            echo "\" alt=\"Display item record\">";
                            echo twig_escape_filter($this->env, $context["item"], "html", null, true);
                            echo "</a></td>
                        ";
                        } else {
                            // line 32
                            echo "                            <th class=\"c2\" colspan=\"2\">";
                            echo twig_escape_filter($this->env, $context["item_key"], "html", null, true);
                            echo "</th>
                            <td class=\"c1\">";
                            // line 33
                            echo twig_escape_filter($this->env, $context["item"], "html", null, true);
                            echo "</td>
                        ";
                        }
                        // line 35
                        echo "                        </tr>
                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['item_key'], $context['item'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 37
                    echo "                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['i'], $context['call'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 38
                echo "            ";
            }
            // line 39
            echo "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['field'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 40
        echo "    </table>
    <br>
</div>
";
    }

    public function getTemplateName()
    {
        return "_search_result_list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  176 => 40,  170 => 39,  167 => 38,  161 => 37,  154 => 35,  149 => 33,  144 => 32,  135 => 30,  130 => 29,  128 => 28,  125 => 27,  120 => 26,  115 => 25,  109 => 22,  105 => 21,  102 => 20,  100 => 19,  91 => 17,  87 => 16,  84 => 15,  82 => 14,  73 => 12,  69 => 11,  66 => 10,  64 => 9,  55 => 7,  51 => 6,  48 => 5,  45 => 4,  41 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "_search_result_list.html.twig", "/var/www/html-dev/public/templates/_search_result_list.html.twig");
    }
}
