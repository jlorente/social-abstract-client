<?php
namespace jlorente\social\networks;

/**
 * Class used to establish communication between the jlorente and the 
 * social networks acting like a Facade for OAuth libraries already created.
 * 
 * Classes representing concrete social networks must extend this one and 
 * provide implementation.
 * 
 * @package social
 * @author Jose Lorente
 */
abstract class AbstractClient {
    CONST CYPHER_FILE = '../config/credentials.crt';

    /**
     *
     * @var string
     */
    protected $credentials;

    /**
     *
     * @var ApiExceptionFactory 
     */
    protected $apiExceptionFactory;

    /**
     * Concrete clients MUST define code property.
     * 
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Sets the user credentials (access token or secrets) for use on the api calls. 
     * It should be provided like it where obtained in AbstractClient::getCredentials()
     * 
     * @param array $credentials
     */
    public function setCredentials(array $credentials) {
        $this->credentials = unserialize($credentials);
    }

    /**
     * Gets the cyphered credentials in use by this client.
     * 
     * @return string
     */
    public function getCredentials() {
        return $this->credentials === null ? null : serialize($this->credentials);
    }

    /**
     * 
     * @return bool True if the credentials has been set, false otherwise.
     */
    public function hasCredentials() {
        return $this->credentials !== null;
    }

    /**
     * 
     * @param array $error
     */
    final protected function throwException(array $error) {
        $this->apiExceptionFactory->throwException($error);
    }

    /**
     * First step on the OAuth authentication process.
     * Redirects the user to the concrete social network to perform login.
     * 
     * If optional callback is given, the social network will redirect the user 
     * to the callback endpoint after the login window step. If not, the user 
     * will be redirected to the default callback url defined on the application 
     * configuration of the social network.
     * 
     * @param string $callback
     * @retun void
     */
    abstract public function authorizationRequest($callback = null);

    /**
     * Second step on the OAuth authentication process.
     * 
     * This method must be called on the callback action given on the first step 
     * of the authentication process.
     * 
     * Checks for errors in the first OAuth process and performs a request to a 
     * concrete social network in order to obtain credentials for the user.
     * 
     * @throws jlorente\social\exceptions\AuthorizationRequestException
     */
    abstract public function credentialsRequest();

    /**
     * Revokes the permissions of the current access token.
     * Further api calls with this credentials will fail.
     * 
     * @throws jlorente\social\exceptions\InvalidCredentialsException
     */
    abstract public function revokeCredentials();

    /**
     * Gets user information from the concrete social network.
     * 
     * @return jlorente\social\networks\AbstractUser
     * @throws jlorente\social\exceptions\InvalidCredentialsException
     */
    abstract public function getUserInfo();

    /**
     * Publish something in the concrete social network.
     * 
     * @param jlorente\social\networks\AbstractPublication $publication
     * @throws jlorente\social\exceptions\InvalidCredentialsException
     */
    abstract public function publish(AbstractPublication $publication);
}