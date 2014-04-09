<?php
namespace jlorente\social\networks\facebook;

use \jlorente\social\networks\AbstractPublication;
use \InvalidArgumentException;

/**
 * Concrete class to perform twitter publications.
 * 
 * @package social
 * @author Jose Lorente
 */
class Publication extends AbstractPublication {

    /**
     * 
     * @param string $picturePath
     * @throws InvalidArgumentException
     */
    public function setPicture($picturePath) {
        if (is_string($picturePath) === false) {
            throw new InvalidArgumentException('$picturePath should be a string value, ' . gettype($picturePath) . ' given.');
        }
        $this->params['picture'] = $picturePath;
    }

    /**
     * 
     * @param string $name
     * @throws InvalidArgumentException
     */
    public function setName($name) {
        if (is_string($name) === false) {
            throw new InvalidArgumentException('$name should be a string value, ' . gettype($name) . ' given.');
        }
        $this->params['name'] = $name;
    }

    /**
     * 
     * @param string $caption
     * @throws InvalidArgumentException
     */
    public function setCaption($caption) {
        if (is_string($caption) === false) {
            throw new InvalidArgumentException('$caption should be a string value, ' . gettype($caption) . ' given.');
        }
        $this->params['caption'] = $caption;
    }

    /**
     * 
     * @param string $description
     * @throws InvalidArgumentException
     */
    public function setDescription($description) {
        if (is_string($description) === false) {
            throw new InvalidArgumentException('$description should be a string value, ' . gettype($description) . ' given.');
        }
        $this->params['description'] = $description;
    }

}