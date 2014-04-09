<?php
namespace jlorente\social\exceptions;

/**
 * Exception thrown when trying to perform an api call that needs credentials 
 * and it are invalid or null.
 * 
 * @package social
 * @author Jose Lorente
 */
class InvalidCredentialsException extends SocialException {}