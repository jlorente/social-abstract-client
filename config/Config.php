<?php
namespace jlorente\social\config;

/**
 * Singleton class for access the configuration data.
 * 
 * @package social
 * @author Jose Lorente
 */
class Config
{
    /**
     *
     * @var \jlorente\social\config\Config 
     */
    private static $instance;
    
    /**
     *
     * @var object
     */
    protected $config;
    
    /**
     * Constructs a new \jlorente\social\config\Config object.
     */
    private function __construct() {
        $config = require 'config.php';
        $this->config = (object) $config;
    }
    
    /**
     * Public method to get the singleton \jlorente\social\config\Config object.
     * 
     * @return \jlorente\social\config\Config 
     */
    public function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
    
    /**
     * Magic method to access configuration properties.
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
    }
    
    /**
     * Magic method to check configuration properties.
     * 
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->config->$name);
    }
}