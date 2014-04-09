<?php
namespace jlorente\social\exceptions\api;

/**
 * Exception thrown when the credentials used to call the api are invalid, 
 * have expired.
 * 
 * @package social\api
 * @author Jose Lorente
 */
class InvalidAuthorizationException extends ApiException {}