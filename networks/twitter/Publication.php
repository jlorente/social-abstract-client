<?php
namespace jlorente\social\networks\twitter;

use \jlorente\social\networks\AbstractPublication;

/**
 * Concrete class to perform twitter publications.
 * 
 * @package social
 * @author Jose Lorente
 */
class Publication extends AbstractPublication {
    const MESSAGE_LINK_LENGTH = 22;
    const MESSAGE_MAX_LENGTH = 140;

}