<?php
namespace jlorente\social\networks;

use stdClass;

/**
 * Simple class to provide a common interface for client user info responses.
 * 
 * @package social
 * @author Jose Lorente
 */
abstract class AbstractUser {
    /**
     * Should be a string due to PHP integer limitations.
     * 
     * @var string 
     */
    protected $id;

    /**
     *
     * @var string 
     */
    protected $name;

    /**
     *
     * @var string 
     */
    protected $lastName;

    /**
     *
     * @var Datetime 
     */
    protected $birthdate;

    /**
     *
     * @var string 
     */
    protected $gender;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $username;

    /**
     *
     * @var int
     */
    protected $friendsCount;

    /**
     * @var string
     */
    protected $profileUrl;

    /**
     *
     * @var string 
     */
    protected $profileImage;

    /**
     * Credentials for use in the jlorente\social\networks\AbstractClient 
     * concrete objects.
     * 
     * @var string 
     */
    protected $credentials;

    /**
     * @private Only jlorente\social\networks\AbstractClient objects 
     * should construct AbstractUser objects using the static create method.
     */
    final private function __construct() {
        
    }

    /**
     * Creates a new AbstractUser object and populates it with the incoming
     * standard object.
     * 
     * @param stdClass $obj
     * @return jlorente\social\networks\AbstractUser
     */
    public static function create(stdClass $obj) {
        $user = new static();
        $user->populate($obj);
        return $user;
    }

    /**
     * Magic method to get properties values like public properties.
     * 
     * @param string $name
     * @return mixed
     * @throws ErrorException
     */
    public function __get($name) {
        if (property_exists($this, $name) === false) {
            throw new ErrorException('Invalid property name');
        }
        return $this->$name;
    }

    /**
     * Magic method to check existing properties like public properties.
     * 
     * @param string $name
     * @return bool
     */
    public function __isset($name) {
        return isset($this->$name);
    }

    /**
     * Populates the object with the incoming params.
     * 
     * @param stdClass $obj
     */
    abstract protected function populate(stdClass $obj);
}