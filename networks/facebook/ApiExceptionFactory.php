<?php
namespace jlorente\social\networks\facebook;

use \jlorente\social\networks\AbstractApiExceptionFactory;

/**
 * Concrete factory to create and throw exceptions based on facebook api 
 * errors.
 * 
 * @package social\api
 * @author Jose Lorente
 */
class ApiExceptionFactory extends AbstractApiExceptionFactory {
    /**
     * 
     * @var array 
     */
    protected $error;

    /**
     * @see \jlorente\social\networks\AbstractApiExceptionFactory::getErrorMap()
     */
    protected function getErrorMap() {
        return [
            '0' => 'ApiException',
            'OAuthException' => 'InvalidAuthorizationException',
            '102' => 'InvalidAuthorizationException',
            '10' => 'InvalidAuthorizationException',
            '2500' => 'InvalidAuthorizationException',
            '17' => 'UserRequestException',
            '506' => 'UserRequestException',
            '1' => 'ApplicationRequestException',
            '4' => 'ApplicationRequestException',
            '341' => 'ApplicationRequestException',
            '2' => 'ServerResponseException'
        ];
    }

    /**
     * @see \jlorente\social\networks\AbstractApiExceptionFactory::throwException(array $error)
     */
    public function throwException(array $error) {
        $this->error = $error;
        $type = $this->getType();
        $code = $this->getCode();
        if (isset($this->errorExceptionMap[$type])) {
            $className = static::NAMESPACE_PATH . $this->errorExceptionMap[$type];
        } elseif (isset($this->errorExceptionMap[$code])) {
            $className = static::NAMESPACE_PATH . $this->errorExceptionMap[$code];
        } else {
            $className = static::NAMESPACE_PATH . static::DEFAULT_EXCEPTION;
        }
        throw new $className();
    }

    /**
     * Gets the response error code.
     * 
     * @return string
     */
    protected function getCode() {
        if (isset($this->error['error_code'])) {
            $code = $this->error['error_code'];
        } elseif (isset($this->error['error']['code'])) {
            $code = $this->error['error']['code'];
        } else {
            $code = 0;
        }
        return (string) $code;
    }

    /**
     * Gets the type of the exception.
     * 
     * @return string
     */
    protected function getType() {
        if (isset($this->error['error'])) {
            $error = $this->error['error'];
            if (is_string($error)) {
                // OAuth 2.0 Draft 10 style
                return $error;
            } else if (is_array($error)) {
                // OAuth 2.0 Draft 00 style
                if (isset($error['type'])) {
                    return $error['type'];
                }
            }
        }
        return 'Exception';
    }

}