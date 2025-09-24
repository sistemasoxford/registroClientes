<?php

class Otp{
    var $recipient;
    var $content;
    var $otp;

    function __construct($recipient = null, $content = null, $otp = null){
        if($recipient !== null){
            $this->setRecipient($recipient);
        }

        if($content !== null){
            $this->setContent($content);
        }

        if($otp !== null){
            $this->setOtp($otp);
        }
    }

    function getRecipient(){
        return $this->recipient;
    }

    function setRecipient($recipient){
        $this->recipient = $recipient;
    }

    function getOtp(){
        return $this->otp;
    }

    function getContent(){
        return $this->content;
    }

    function setContent($content){
        $this->content = $content;
    }

    function setOtp($otp){
        $this->otp = $otp;
    }
}