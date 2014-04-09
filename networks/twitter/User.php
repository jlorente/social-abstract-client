<?php
namespace jlorente\social\networks\twitter;

use \jlorente\social\networks\AbstractUser;

/**
 * Concrete twitter implementation of the AbstractUser populate template method 
 * pattern.
 * 
 * @package social
 * @author Jose Lorente
 */
class User extends AbstractUser
{
    /**
     * @see \jlorente\social\networks\AbstractUser::populate(\stdClass $obj)
     */
    protected function populate(\stdClass $obj)
    {
        $this->id = $obj->id_str;
        
        $names = explode(' ', $obj->name);
        $this->name = $names[0];
        unset($names[0]);
        $this->lastName = implode(' ', $names);
        $this->username = $obj->screen_name;
        $this->friendsCount = $obj->followers_count;
        $this->profileImage = $obj->profile_image_url;
        $this->profileUrl = 'https://twitter.com/' . $obj->screen_name;
        $this->credentials = $obj->credentials;
    }
}