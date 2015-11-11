<?php
/**
 * This file is part of Gear.
 *
 * @copyright 2015 Loïc Marchand
 * @license http://www.spdx.org/licenses/MIT MIT License
 */

namespace Gear\View;

use \Gear\View\Template\Template;

/**
 * View class.
 */
class View
{

    /**
     * Views directory.
     *
     * @var string $directory
     */
    protected $directory = '';

    /**
     * Contains data can be used by all templates.
     *
     * @var array $sharedData
     */
    protected $sharedData = [];

    /**
     * Contains templates data.
     *
     * @var array $templateData
     */
    protected $templateData = [];

    /**
     * Default constructor.
     *
     * @param string $directory
     */
    public function __construct($directory)
    {
        $this->setDirectory($directory);
    }

    /**
     * Set views data.
     *
     * @param array $data
     * @param string $templates
     *
     * @return \Gear\View\View Self instance.
     */
    public function setData(array $data, $templates = null)
    {
        if (is_null($templates)) {
            $this->sharedData = array_merge($this->sharedData, $data);
        } elseif (is_array($templates)) {
            foreach ($templates as $template) {
                $this->setData($data, $template);
            }
        } elseif (is_string($templates)) {
            if (isset($this->templateData[$templates])) {
                $this->templateData[$templates] = [];
            }
            $this->templateData[$templates] = array_merge($this->templateData[$templates], $data);
        }
        return $this;
    }

    /**
     * Get view data. You can get shared data, or a template data.
     * (template data have shared data).
     *
     * @param string|null $template Null if you need shared data.
     *
     * @return array
     */
    public function getData($template = null)
    {
        if (is_null($template)) {
            return $this->sharedData;
        } elseif (is_string($template)) {
            if (isset($this->templateData[$template])) {
                return array_merge($this->sharedData, $this->templateData[$template]);
            } else {
                return $this->sharedData;
            }
        }

        return [];
    }

    /**
     * Get views directory.
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Set views directory.
     *
     * @param string $newDirectory
     *
     * @return void
     */
    public function setDirectory($newDirectory)
    {
        $this->directory = rtrim($newDirectory, '/') . '/';
    }

    /**
     * Create a new template.
     *
     * @param string $templateName
     *
     * @return \Gear\View\Template\Template
     */
    public function template($templateName)
    {
        return new Template($this, $templateName);
    }

    /**
     * Render the view.
     *
     * @param string $templateName
     * @param array $data
     *
     * @return string
     */
    public function render($templateName, $data = [])
    {
        return $this->template($templateName)->render($data);
    }
}
