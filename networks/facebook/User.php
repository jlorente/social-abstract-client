<?php
namespace jlorente\social\networks\facebook;

use \jlorente\social\networks\AbstractUser;
use \Datetime;

/**
 * Concrete facebook implementation of the AbstractUser populate template method 
 * pattern.
 * 
 * @package social
 * @author Jose Lorente
 */
class User extends AbstractUser {

    /**
     * @see \jlorente\social\networks\AbstractUser::populate(\stdClass $obj)
     */
    protected function populate(\stdClass $obj) {
        $this->id = $obj->id;
        $this->name = $obj->first_name;
        $this->lastName = $obj->last_name;
        $this->birthdate = new Datetime($obj->birthday);
        $this->gender = $this->getStandardGender($obj->gender);
        $this->email = $obj->email;
        $this->username = $obj->username;
        $this->profileUrl = $obj->link;
        $this->profileImage = $obj->picture;
        $this->friendsCount = $obj->friendsCount;
        $this->credentials = $obj->credentials;

        return $this;
    }

    /**
     * 
     * @param string $gender
     * @return string
     */
    protected function getStandardGender($gender) {
        $genders = ['male' => 'H', 'female' => 'M'];
        return isset($genders[$gender]) ? $genders[$gender] : null;
    }

}