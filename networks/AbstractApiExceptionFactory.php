<?php
namespace jlorente\social\networks;

/**
 * 
 * @package social\api
 * @author Jose Lorente
 */
abstract class AbstractApiExceptionFactory {
    /**
     * Default Exception launch by this factory
     */
    const DEFAULT_EXCEPTION = 'ApiException';

    /**
     * Namespace path to the api exceptions
     */
    const NAMESPACE_PATH = '\\jlorente\\social\\exceptions\\api\\';

    /**
     *
     * @var array 
     */
    protected $errorExceptionMap;

    /**
     * Construct an AbstractApiExceptionFactory.
     * Implements a Template Method pattern.
     */
    public function __construct() {
        $this->errorExceptionMap = $this->getErrorMap();
    }

    /**
     * Defines the error map used to map the response api error codes to 
     * the package Exceptions.
     * 
     * @return array
     */
    abstract protected function getErrorMap();

    /**
     * Throws an exception depending on the api response error code.
     * 
     * @param array $error
     * @throws \jlorente\social\exceptions\api\ApiExceptionRequestException
     * @throws \jlorente\social\exceptions\api\ApplicationRequestException
     * @throws \jlorente\social\exceptions\api\InvalidAuthorizationException
     * @throws \jlorente\social\exceptions\api\ServerResponseException
     * @throws \jlorente\social\exceptions\api\UserRequestException
     */
    abstract public function throwException(array $error);
}