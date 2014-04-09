<?php
namespace jlorente\social\exceptions\api;

/**
 * Exception thrown when an application level error occurs like 
 * application request limit reached or invalid endpoint, etc...
 * 
 * @package social\api
 * @author Jose Lorente
 */
class ApplicationRequestException extends ApiException {}