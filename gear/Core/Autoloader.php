<?php
/**
 * This file is part of Gear.
 *
 * @copyright 2015 Loïc Marchand
 * @license http://www.spdx.org/licenses/MIT MIT License
 */

namespace Gear\Core;

/**
 * The Autoloader class is responsible to load automatically framework's classes.
 * (based on http://www.php-fig.org/psr/psr-4/examples/)
 */
class Autoloader
{

    /**
     * This array contains namespace with the corresponding base directory.
     *
     * @var array $prefixes
     */
    protected $prefixes = [];


    /**
     * Default constructor.
     *
     * @param string|array $prefixes
     * @param null|string|array $baseDir
     */
    public function __construct($prefixes = [], $baseDir = null)
    {
        $this->addNamespace($prefixes, $baseDir);
    }

    /**
     * Register with SPL autoloader.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Add a base directory for a namespace.
     *
     * @param mixed $prefixes An array contains namespace with corresponding base directory, or string with namespace.
     * @param mixed $baseDir If $prefixes is an array $baseDir is ignored, else is a string containing base directory.
     *
     * @return \Gear\Core\Autoloader Pointer for chaining call.
     */
    public function addNamespace($prefixes, $baseDir = null)
    {
        if (is_array($prefixes)) {
            foreach ($prefixes as $prefix => $dir) {
                $this->addNamespace($prefix, $dir);
            }
        } else {
            $prefix = trim($prefixes, '\\') . '\\';

            if (!isset($this->prefixes[$prefix])) {
                $this->prefixes[$prefix] = [];
            }
            if (is_array($baseDir)) {
                foreach ($baseDir as $dir) {
                    $this->addNamespace($prefix, $dir);
                }
            } else {
                $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                $this->prefixes[$prefix][] = $baseDir;
            }
        }

        return $this;
    }

    /**
     * Load the class file for a given class name.
     *
     * @param string $className The fully qualified class name.
     *
     * @return boolean|string False if not found class file, the class file otherwise.
     */
    public function loadClass($className)
    {
        $prefix = $className;

        while (false !== ($pos = strrpos($prefix, '\\'))) {
            $prefix = substr($className, 0, $pos + 1);
            $relativeClass = substr($className, $pos + 1);

            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);
            if ($mappedFile !== false) {
                return $mappedFile;
            }

            $prefix = rtrim($prefix, '\\');
        }

        return false;
    }

    /**
     * Load the mapped class for a namespace prefix.
     *
     * @param string $prefix Class prefix.
     * @param string $relativeClass Relative class name.
     *
     * @return boolean|string False if not found class file, the class file otherwise.
     */
    protected function loadMappedFile($prefix, $relativeClass)
    {
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        foreach ($this->prefixes[$prefix] as $basDir) {
            $file = $basDir . str_replace('\\', '/', $relativeClass) . '.php';
            if ($this->requireFile($file)) {
                return $file;
            }
        }

        return false;
    }

    /**
     * Used to include class file.
     *
     * @param string $file File need to include.
     *
     * @return boolean True if find $file, false otherwise.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
