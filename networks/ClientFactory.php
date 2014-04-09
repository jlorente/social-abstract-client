<?php
namespace jlorente\social\networks;

use \jlorentes\social\exceptions\UnsupportedNetworkException;

/**
 * Class to encapsulate the creation of concrete clients.
 * 
 * @package social
 * @author Jose Lorente
 */
class ClientFactory {
    /**
     * Facebook client
     */
    const FACEBOOK = 'facebook';

    /**
     * Twitter client
     */
    const TWITTER = 'twitter';

    /**
     * Clients will be created of this type of network.
     * 
     * @var string 
     */
    protected $network;

    /**
     * Used to check if arguments are valid.
     * 
     * @var array 
     */
    protected static $validNetworks = [
        self::FACEBOOK => 1,
        self::TWITTER => 1,
    ];

    /**
     * Constructs a new ClientFactory object.
     * 
     * @param string $network
     * @throws jlorente\social\exceptions\UnsupportedNetworkException
     */
    public function __construct($network) {
        $this->setNetwork($network);
    }

    /**
     * Sets a network from which create clients.
     * 
     * @param string $network
     * @throws jlorente\social\exceptions\UnsupportedNetworkException
     */
    public function setNetwork($network) {
        if (!isset(static::$validNetworks[$network])) {
            throw new UnsupportedNetworkException($network . ' isn\'t supported');
        }
        $this->network = $network;
    }

    /**
     * Gets the current network.
     * 
     * @return string
     */
    public function getNetwork() {
        return $this->network;
    }

    /**
     * Creates a new concrete Client.
     * 
     * @return jlorente\social\network\AbstractClient
     */
    public function create() {
        return static::createNetwork($this->network);
    }

    /**
     * 
     * @param string $network
     * @return jlorente\social\networks\AbstractClient
     * @throws jlorente\social\exceptions\UnsupportedNetworkException
     */
    public static function createNetwork($network) {
        if (!isset(static::$validNetworks[$network])) {
            throw new UnsupportedNetworkException($network . ' isn\'t supported');
        }

        $class = 'jlorente\\social\\networks\\' . $network . '\\Client';
        return new $class();
    }

}