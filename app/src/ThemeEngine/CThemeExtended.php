<?php
/**
 * Extended class to handle easy change of themes.
 */
namespace CR\ThemeEngine;

class CThemeExtended extends \Anax\ThemeEngine\CThemeBasic
{
	use \Anax\TConfigure;

	public function getClassAttributeFor($element, $classes = "") {
		if (isset($this->data[$element])) {
			foreach ($this->data[$element] as $value) {
				$classes .= " " . $value;
			}
			return "class='" . $classes ."'";
		}
	}

	public function addClassAttributeFor($element, $classes) {
		if (!isset($this->data[$element])) {
			$this->data[] = $element;
		}
		$this->data[$element][] = $classes;
	}

    /**
     * Render the theme by applying the variables onto the template files.
     *
     * @return void
     */
    public function render()
    {
        // Prepare details
    	$path       = $this->config['settings']['path'];
    	$name       = $this->config['settings']['name'] . '/';
    	$theme		= $this->config['data']['theme'];
    	$template   = 'index.tpl.php';
    	$functions  = 'functions.php';

        // Check whether html-theme has been set in page controller,
        // otherwise use theme-setting in config
    	if (!isset($this->data['html'])) {
    		$this->addClassAttributeFor('html', $theme);
    	}

        // Include theme specific functions file
    	$file = $path . $name . $functions;
    	if (is_readable($file)) {
    		include $file;
    	}

        // Create views for regions, from config-file
    	if (isset($this->config['views'])) {
    		foreach ($this->config['views'] as $view) {
    			$this->di->views->add($view['template'], $view['data'], $view['region'], $view['sort']);
    		}
    	}

        // Sen response headers, if any.
    	$this->di->response->sendHeaders();

        // Create a view to execute the default template file
    	$tpl  = $path . $name . $template;
    	$data = array_merge($this->config['data'], $this->data);
    	$view = $this->di->get('view');
    	$view->set($tpl, $data);
    	$view->setDI($this->di);
    	$view->render();

    }
}
