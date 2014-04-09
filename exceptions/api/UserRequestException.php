<?php
namespace jlorente\social\exceptions\api;

/**
 * Exception thrown when a user level error occurs like user daily or hourly 
 * request limit reached, etc...
 * 
 * @package social\api
 * @author Jose Lorente
 */
class UserRequestException extends ApiException {}