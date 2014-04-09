<?php
namespace jlorente\social\networks\twitter;

use \jlorente\social\networks\AbstractApiExceptionFactory;

/**
 * Concrete factory to create and throw exceptions based on twitter api 
 * errors.
 * 
 * @package social\api
 * @author Jose Lorente
 */
class ApiExceptionFactory extends AbstractApiExceptionFactory
{
    /**
     * @see \jlorente\social\networks\AbstractApiExceptionFactory::getErrorMap()
     */
    protected function getErrorMap()
    {
        return [
            '0' => 'ApiException',
            '32' => 'InvalidAuthorizationException',
            '89' => 'InvalidAuthorizationException', 
            '64' => 'InvalidAuthorizationException',
            '88' => 'UserRequestException',
            '187' => 'UserRequestException',
            '185' => 'UserRequestException',
            '251' => 'ApplicationRequestException',
            '130' => 'ServerResponseException',
            '131' => 'ServerResponseException'
        ];
    }
    
    /**
     * @see \jlorente\social\networks\AbstractApiExceptionFactory::throwException(array $error)
     */
    public function throwException(array $error)
    {
        if (isset($this->errorExceptionMap[$error['code']])) {
            $className = static::NAMESPACE_PATH . $this->errorExceptionMap[$error['code']];
        } else {
            $className = static::NAMESPACE_PATH . static::DEFAULT_EXCEPTION;
        }
        throw new $className($error['message'], $error['code']);
    }
}