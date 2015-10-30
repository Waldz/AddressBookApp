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

    /**
     * @param string $template Template name e.g. 'list'
     * @param string $module Where template is lying e.g. 'Application'
     * @param array $data Data model
     *
     * @return string Rendered output
     */
    public function renderTemplate($template, $module, $data = [])
    {
        $templateFile = sprintf('module/%s/view/%s.phtml', $module, $template);

        ob_start();
        include($templateFile);
        $templateOutput = ob_get_contents();
        ob_end_clean();

        return $templateOutput;
    }

}
