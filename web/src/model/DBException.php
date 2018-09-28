<?php
namespace agilman\a2\model;
/**
 * Created by PhpStorm.
 * User: Jordan
 * Date: 21/09/2018
 * Time: 12:41 PM
 */
use Exception;

class DBException extends  Exception
{
  /**
     * DBException constructor.
     * @param $mName
     */
    public function __construct($message, $code)
    {
     parent::__construct($message, $code);
    }


    public function __toString()
    {
        return $this->message;
    }


}