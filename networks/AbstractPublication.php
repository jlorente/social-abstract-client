<?php
namespace jlorente\social\networks;

/**
 * Class representing a model of social network publication.
 * 
 * @package social
 * @author Jose Lorente
 */
abstract class AbstractPublication {
    /**
     *
     * @var array
     */
    protected $params;

    /**
     *
     * @var string 
     */
    protected $attachment;

    /**
     * 
     * @return array Params to send on the request.
     */
    final public function getParams() {
        return $this->params;
    }

    /**
     * 
     * @param string $message The message to send on the request.
     * @throws InvalidArgumentException
     */
    public function setMessage($message) {
        if (is_string($message) === false) {
            throw new InvalidArgumentException('$message should be a string value, ' . gettype($message) . ' given.');
        }
        $this->params['message'] = $message;
    }

    /**
     * 
     * @param string $link A link to attach on the request.
     * @throws InvalidArgumentException
     */
    public function setLink($link) {
        if (is_string($link) === false) {
            throw new InvalidArgumentException('$link should be a string value, ' . gettype($link) . ' given.');
        }
        $this->params['link'] = $link;
    }

    /**
     * @param string $attachment Path to the file to attach to the publication.
     * @throws InvalidArgumentException
     * @throws jlorente\social\exceptions\InvalidAttachmentException
     */
    public function setAttachment($attachment) {
        if (is_string($attachment) === false) {
            throw new InvalidArgumentException('$link should be a string value, ' . gettype($attachment) . ' given.');
        }

        if (file_exists($attachment) === false) {
            throw new InvalidAttachmentException('File [' . $attachment . '] doesn\'t exist');
        }

        $this->attachment = $attachment;
    }

}