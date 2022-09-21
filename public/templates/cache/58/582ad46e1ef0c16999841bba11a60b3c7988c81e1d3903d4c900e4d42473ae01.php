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

/* _footer.html.twig */
class __TwigTemplate_b0cbd46a679344edafa1131e8c6b6c784f56ae6e728a6ad08336322c0692439f extends Template
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
        echo "        <footer>
            <p class=\"center-text footer\">This application uses 
            <a href=\"https://github.com/multnomah-county-it/libilsws\" 
            alt=\"GitHub Repository for LibILSWS\">LibILSWS</a> 
            to access SirsiDynix Symphony Web Services</p>
        </footer>
    </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "_footer.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "_footer.html.twig", "/var/www/html-dev/public/templates/_footer.html.twig");
    }
}
