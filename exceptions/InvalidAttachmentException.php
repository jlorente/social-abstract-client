<?php
namespace jlorente\social\exceptions;

/**
 * Exception thrown when trying to add an attachment to a Publication and the 
 * file doesn't exist.
 * 
 * @package social
 * @author Jose Lorente
 */
class InvalidAttachmentException extends SocialException {}