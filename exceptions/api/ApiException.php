<?php
namespace jlorente\social\exceptions\api;

use \jlorente\social\exceptions\SocialException;

/**
 * Default exception for api's response exceptions.
 * All the package exceptions must extend from this one.
 * 
 * @package social\api
 * @author Jose Lorente
 */
class ApiException extends SocialException {}