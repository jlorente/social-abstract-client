<?php
namespace jlorente\social\networks\facebook;

use \jlorente\social\networks\AbstractClient;
use \jlorente\social\networks\AbstractPublication;
use \jlorente\social\exceptions\AuthorizationRequestException,
    \jlorente\social\exceptions\InvalidCredentialsException;
use \Facebook,
    \FacebookApiException;

/**
 * Class wrapper for Facebook class that provides interface for AbstractClient
 * class.
 * 
 * @package social
 * @author Jose Lorente
 */
class Client extends AbstractClient {
    /**
     * 
     * @var string 
     */
    protected $code = 'facebook';

    /**
     * Stores the Facebook object.
     * 
     * @var Facebook
     */
    protected $fClient;

    public function __construct() {
        $this->fClient = new Facebook([
            'appId' => FB_APP_ID,
            'secret' => FB_APP_SECRET
        ]);

        $this->apiExceptionFactory = new ApiExceptionFactory;
    }

    /**
     * @see jlorente\social\networks\AbstractClient::authorizationRequest()
     */
    public function authorizationRequest($callback = null) {
        $params = ['scope' => 'email, user_birthday, publish_actions'];
        if ($callback !== null) {
            $params['redirect_uri'] = $callback;
        }

        header('Location: ' . $this->fClient->getLoginUrl($params));
    }

    /**
     * @see jlorente\social\networks\AbstractClient::credentialsRequest()
     */
    public function credentialsRequest() {
        try {
            $accessToken = $this->fClient->getAccessToken();
            if ($accessToken === null) {
                throw AuthorizationRequestException();
            }
            $this->credentials['access_token'] = $accessToken;
            $this->fClient->setAccessToken($accessToken);
        } catch (FacebookApiException $e) {
            $this->throwException($e->getResult());
        }
    }

    /**
     * @see jlorente\social\networks\AbstractClient::revokeAccessToken()
     */
    public function revokeCredentials() {
        $this->wrapCredentials();

        try {
            $this->fClient->api('/me/permissions', 'DELETE');
        } catch (FacebookApiException $e) {
            $this->throwException($e->getResult());
        }
    }

    /**
     * @see jlorente\social\networks\AbstractClient::getUserInfo()
     */
    public function getUserInfo() {
        $this->wrapCredentials();
        try {
            $base = $this->fClient->api('/me', 'GET');
            $picture = $this->getProfilePicture('normal', 250, 250);
            $base['picture'] = $picture->is_silhouette === true ? null : $picture->url;
            $base['friendsCount'] = $this->getFriendsNumber();
            $base['credentials'] = $this->getCredentials();
            return User::create((object) $base);
        } catch (FacebookApiException $e) {
            $this->throwException($e->getResult());
        }
    }

    /**
     * @see jlorente\social\networks\AbstractClient::publish()
     */
    public function publish(AbstractPublication $publication) {
        $this->wrapCredentials();

        try {
            return $this->fClient->api(
                            '/me/feed', 'POST', $publication->getParams());
        } catch (FacebookApiException $e) {
            $this->throwException($e->getResult());
        }
    }

    /**
     * Gets the user profile picture.
     * Returned object properties are:
     *  (string) url => Complete url of the image
     *  (bool) is_silhouette => True if the image is the default profile image
     * 
     * @param string $size Accepted sizes [square|large|small|normal]
     * @return stdClass 
     * @throws jlorente\social\exceptions\InvalidCredentialsException
     */
    public function getProfilePicture($type = 'normal', $width = null, $height = null) {
        $sizes = ['square' => 1, 'large' => 1, 'small' => 1, 'normal' => 1];
        if (isset($sizes[$type]) === false) {
            throw new \InvalidArgumentException(
            '$size must be one of following set [square|large|small|normal]'
            );
        }

        $this->wrapCredentials();
        
        $params = ['redirect' => false, 'type' => $type];
        if ($width !== null ) {
            $params['width'] = $width;
        }
        if ($height !== null ) {
            $params['height'] = $height;
        }
        try {
            $result = $this->fClient->api(
                    '/me/picture', 'GET', $params
            );
            return $this->getPictureObject($result);
        } catch (FacebookApiException $e) {
            $this->throwException($e->getResult());
        }
    }

    /**
     * 
     * @param array $pictureArray
     * @return \stdClass
     */
    protected function getPictureObject(array $pictureResponse) {
        $obj = new \stdClass();
        $obj->is_silhouette = null;
        $obj->url = null;
        if (isset($pictureResponse['data'])) {
            $data = $pictureResponse['data'];
            $obj->is_silhouette = isset($data['is_silhouette']) ? $data['is_silhouette'] : null;
            $obj->url = isset($data['url']) ? $data['url'] : null;
        }
        return $obj;
    }

    /**
     * Gets the number of friends of the user.
     * 
     * @return int
     * @throws jlorente\social\exceptions\InvalidCredentialsException
     */
    public function getFriendsNumber() {
        $this->wrapCredentials();

        try {
            $result = $this->fClient->api(
                    '/fql', 'GET', ['q' => 'SELECT friend_count FROM user WHERE uid = me()']);
            return isset($result['data'][0]['friend_count']) ?
                    $result['data'][0]['friend_count'] : 0;
        } catch (FacebookApiException $e) {
            $this->throwException($e->getResult());
        }
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
        $this->fClient->setAccessToken($this->credentials['access_token']);
    }

}