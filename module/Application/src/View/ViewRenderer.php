<?php

namespace Application\View;

/**
 * Class responsible from template rendering
 *
 * @package Application
 * @subpackage View
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class ViewRenderer {

    const APPEND_DELIMITER = "\n";

    /**
     * @var array
     */
    private $variables = [] ;

    /**
     * Retrieves variables.
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Sets variables.
     *
     * @param array $variables
     * @return ViewRenderer
     */
    public function setVariables($variables)
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * Retrieves variables.
     *
     * @param string $variable
     * @return mixed
     */
    public function getVariable($variable)
    {
        if (!isset($this->variables[$variable])) {
            return null;
        }

        return $this->variables[$variable];
    }

    /**
     * Sets variables.
     *
     * @param string $variable
     * @param mixed $value
     *
     * @return ViewRenderer
     */
    public function setVariable($variable, $value)
    {
        $this->variables[$variable] = $value;

        return $this;
    }

    /**
     * Sets variables.
     *
     * @param string $variable
     * @param string $value
     *
     * @return ViewRenderer
     */
    public function appendVariable($variable, $value)
    {
        $this->variables[$variable] .= self::APPEND_DELIMITER . $value;

        return $this;
    }

    /**
     * @param string $template Template name e.g. 'list'
     * @param string $module Where template is lying e.g. 'Application'
     * @param array $data Data model
     *
     * @return string Rendered output
     */
    public function renderTemplate($template, $module, $data = [])
    {
        $data = array_merge($this->variables, $data);
        $templateFile = sprintf('module/%s/view/%s.phtml', $module, $template);

        ob_start();
        include($templateFile);
        $templateOutput = ob_get_contents();
        ob_end_clean();

        return $templateOutput;
    }

    /**
     * Start to capture output
     */
    public function captureStart()
    {
        ob_start();
    }

    /**
     * Return captures output
     *
     * @return string
     */
    public function captureEnd()
    {
        $captureOutput = ob_get_contents();
        ob_end_clean();

        return $captureOutput;
    }

}
