<?php

class InstagramUser {
    var $username;

    function __construct($username = null) {
        if ($username !== null) {
            $this->setUsername($username);
        }
    }

    // Getter
    function getUsername() {
        return $this->username;
    }

    // Setter
    function setUsername($username) {
        $this->username = $username;
    }
}
