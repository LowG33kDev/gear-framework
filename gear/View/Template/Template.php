<?php
/**
 * This file is part of Gear.
 *
 * @copyright 2015 Loïc Marchand
 * @license http://www.spdx.org/licenses/MIT MIT License
 */
namespace Gear\View\Template;

use \Gear\View\View;
use \LogicException;

/**
 * Template used to render a view.
 */
class Template
{

    /**
     * @var \Gea\View\View $view
     */
    protected $view = null;

    /**
     * Template name.
     *
     * @var string $name
     */
    protected $name = '';

    /**
     * Name of layout if needed.
     *
     * @var string $layout
     */
    protected $layout = '';

    /**
     * Layout's data.
     *
     * @var array $layoutData
     */
    protected $layoutData = [];

     /**
      * Template's data.
      *
      * @var array $data
      */
    protected $data = [];

     /**
      * Array contains template's sections.
      *
      * @var array $sections
      */
    protected $sections = [];


    /**
     * Default constructor.
     *
     * @param \Gear\View\View $view
     * @param string $template
     */
    public function __construct(View $view, $template)
    {
        $this->view = $view;
        $this->name = $template;
    }

    /**
     * Check if template file exists.
     *
     * @return boolean
     */
    public function exists()
    {
        return file_exists($this->getPath());
    }

    /**
     * Get view file path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->view->getDirectory() . $this->name . '.php';
    }

    /**
     * Render template.
     *
     * @param array $data
     *
     * @return string Content after rendering.
     */
    public function render(array $data = [])
    {
        // Get data adding on view.
        $this->data = array_merge($this->view->getData($this->name), $data);

        unset($data);

        extract($this->data);

        ob_start();
        if ($this->exists()) {
            include $this->getPath();
        } else {
            throw new LogicException('Template ' . $this->name . ' not found.');
        }
        $content = ob_get_clean();

        if ($this->layout) { // render layout if have one.
            $layout = $this->view->template($this->layout);
            $layout->sections = array_merge($layout->sections, ['content' => $content]);
            $content = $layout->render($this->layoutData);
        }

        return $content;
    }

    /**
     * Set template layout.
     *
     * @param string $layout
     * @param array $data
     *
     * @return void
     */
    public function layout($layout, array $data = [])
    {
        $this->layout = $layout;
        $this->layoutData = $data;
    }

    /**
     * Return section content.
     *
     * @param string $section
     * @param string $default
     *
     * @return string Section content, of default value if section no exists.
     */
    public function section($section, $default = '')
    {
        if (isset($this->sections[$section])) {
            return $this->sections[$section];
        }
        return $default;
    }

    /**
     * Start a new section. The section 'content' is reserved for internal usage.
     *
     * @param string $section
     *
     * @return void
     *
     * @throw \LogicException
     */
    public function start($section)
    {
        if ($section === 'content') {
            throw new LogicException('Can\'t use content section. Is reserved for internal use.');
        }
        $this->sections[$section] = '';

        ob_start();
    }

    /**
     * Finish a section.
     *
     * @return void
     *
     * @throw \LogicException
     */
    public function end()
    {
        if (count($this->sections) <= 0) {
            throw new LogicException('No section found. Need to start section before end.');
        }
        end($this->sections);

        $this->sections[key($this->sections)] = ob_get_clean();
    }

    /**
     * Fetch a partial view.
     *
     * @param string $template Template needed to render.
     * @param array $data Data send to partial view.
     *
     * @return string Content of rendering.
     */
    public function fetch($partial, array $data = [])
    {
        return $this->view->render($partial, $data);
    }

    /**
     * Render a partial view.
     *
     * @param string $template Template needed to render.
     * @param array $data Data send to partial view.
     *
     * @return void
     */
    public function insert($partial, array $data = [])
    {
        echo $this->fetch($partial, $data);
    }
}
