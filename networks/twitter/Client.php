<?php
namespace jlorente\social\networks\twitter;

use \Codebird\Codebird;
use \jlorente\social\networks\AbstractClient;
use \jlorente\social\networks\AbstractPublication;
use \jlorente\social\exceptions\AuthorizationRequestException;
use \jlorente\social\exceptions\InvalidCredentialsException;
use \jlorente\social\config\Config;

/**
 * Class wrapper for Twitter codebird class that provides interface for 
 * AbstractClient class.
 * 
 * @package social
 * @author Jose Lorente
 */
class Client extends AbstractClient {
    /**
     * 
     * @var string 
     */
    protected $code = 'twitter';

    /**
     * Stores the Codebird object.
     * 
     * @var Codebird\Codebird
     */
    protected $tClient;
    
    /**
     *
     * @var type 
     */
    protected $apiExceptionFactory;
    
    /**
     *
     * @var type 
     */
    protected static $kSupportedKeys = array(
        'request_token' => 1,
        'request_token_secret' => 1,
        'oauth_token' => 1,
        'oauth_token_secret' => 1,
        'oauth_verify' => 1,
        'user_id' => 1
    );

    /**
     * Prints to the error log if you aren't in command line mode.
     *
     * @param string $msg Log message
     */
    protected static function errorLog($msg) {
        if (php_sapi_name() != 'cli') {
            error_log($msg);
        }
    }

    public function __construct() {
        $this->tClient = new Codebird();
        $this->tClient->setConsumerKey(
                Config::getInstance()->twitter->appId, 
                Config::getInstance()->twitter->appSecret
        );
        $this->apiExceptionFactory = new ApiExceptionFactory;
    }

    /**
     * @see \jlorente\social\networks\AbstractClient::authorizationRequest()
     */
    public function authorizationRequest($callback = null) {
        $reply = $this->tClient->oauth_requestToken([
            'oauth_callback' => $callback
        ]);
        $this->checkResponseForErrors($reply);

        $this->tClient->setToken(
                $reply->oauth_token, $reply->oauth_token_secret
        );
        $this->setPersistentData('request_token', $reply->oauth_token);
        $this->setPersistentData(
                'request_token_secret', $reply->oauth_token_secret
        );
        $this->setPersistentData('oauth_verify', true);

        header('Location: ' . $this->tClient->oauth_authorize(true));
    }

    /**
     * @see \jlorente\social\networks\AbstractClient::credentialsRequest()
     */
    public function credentialsRequest() {
        if (!isset($_GET['oauth_verifier'])) {
            throw new AuthorizationRequestException();
        }
        $oauthVerifier = $_GET['oauth_verifier'];

        $requestToken = $this->getPersistentData('request_token', null);
        $requestTokenSecret = $this->getPersistentData(
                'request_token_secret', null
        );
        if ($requestToken === null || $requestTokenSecret === null) {
            throw new AuthorizationRequestException();
        }

        $this->tClient->setToken(
                $requestToken, $requestTokenSecret
        );
        $reply = $this->tClient->oauth_accessToken([
            'oauth_verifier' => $oauthVerifier
        ]);
        $this->checkResponseForErrors($reply);

        $this->clearAllPersistentData();
        $this->credentials['oauth_token'] = $reply->oauth_token;
        $this->credentials['oauth_token_secret'] = $reply->oauth_token_secret;
    }

    /**
     * Twitter does not support credentials revocation.
     * 
     * @see \jlorente\social\networks\AbstractClient::revokeCredentials()
     */
    public function revokeCredentials() {
        return true;
    }

    /**
     * @see \jlorente\social\networks\AbstractClient::getUserInfo()
     */
    public function getUserInfo() {
        $this->wrapCredentials();

        if (isset($this->credentials['userId'])) {
            $result = $this->tClient->users_show(
                    'user_id=' . $this->credentials['userId']
            );
            $this->checkResponseForErrors($result);
        } else {
            $result = $this->tClient->account_verifyCredentials();
            $this->checkResponseForErrors($result);
            $this->credentials['userId'] = $result->id;
        }
        $result->credentials = $this->getCredentials();
        return User::create($result);
    }

    /**
     * @see \jlorente\social\networks\AbstractClient::publish()
     */
    public function publish(AbstractPublication $publication) {
        $this->wrapCredentials();

        $params = $publication->getParams();
        $result = $this->tClient->statuses_update(
                'status=' . $params['message'] . ' ' . urlencode($params['link'])
        );
        $this->checkResponseForErrors($result);
    }

    protected function checkResponseForErrors($result) {
        if (isset($result->httpstatus) === false || $result->httpstatus > 200 || isset($result->errors) === true) {
            $this->throwException((array) $result->errors[0]);
        }
    }

    /**
     * Stores the given ($key, $value) pair, so that future calls to
     * getPersistentData($key) return $value. This call may be in another request.
     *
     * @param string $key
     * @param array $value
     *
     * @return void
     */
    protected function setPersistentData($key, $value) {
        if (isset(self::$kSupportedKeys[$key]) === false) {
            self::errorLog('Unsupported key passed to setPersistentData.');
            return;
        }

        $session_var_name = $this->constructSessionVariableName($key);
        $_SESSION[$session_var_name] = $value;
    }

    /**
     * Get the data for $key, persisted by BaseFacebook::setPersistentData()
     *
     * @param string $key The key of the data to retrieve
     * @param boolean $default The default value to return if $key is not found
     *
     * @return mixed
     */
    protected function getPersistentData($key, $default = false) {
        if (isset(self::$kSupportedKeys[$key]) === false) {
            self::errorLog('Unsupported key passed to getPersistentData.');
            return $default;
        }

        $session_var_name = $this->constructSessionVariableName($key);
        return isset($_SESSION[$session_var_name]) ?
                $_SESSION[$session_var_name] : $default;
    }

    /**
     * Clear the data with $key from the persistent storage
     *
     * @param string $key
     * @return void
     */
    protected function clearPersistentData($key) {
        if (isset(self::$kSupportedKeys[$key]) === false) {
            self::errorLog('Unsupported key passed to clearPersistentData.');
            return;
        }

        $session_var_name = $this->constructSessionVariableName($key);
        unset($_SESSION[$session_var_name]);
    }

    /**
     * Clear all data from the persistent storage
     *
     * @return void
     */
    protected function clearAllPersistentData() {
        foreach (self::$kSupportedKeys as $key => $v) {
            $this->clearPersistentData($key);
        }
    }

    /**
     * Returns a name to identify a session variable with twitter.
     * 
     * @param string $key
     * @return string
     */
    protected function constructSessionVariableName($key) {
        return 'tw_' . $key;
    }

    /**
     * Wraps credentials between the Facade and the wrapped Client.
     * This method should be called before every api call.
     * 
     * @throws jlorente\social\exceptions\InvalidCredentialsException
     */
    protected function wrapCredentials() {
        if ($this->hasCredentials() === false) {
            throw new InvalidCredentialsException();
        }

        $this->tClient->setToken(
                $this->credentials['oauth_token'], $this->credentials['oauth_token_secret']
        );
    }

}