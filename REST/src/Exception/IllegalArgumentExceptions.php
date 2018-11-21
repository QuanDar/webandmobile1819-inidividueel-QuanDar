<?php
/**
 * Created by PhpStorm.
 * User: QuanDar
 * Date: 18/11/2018
 * Time: 18:21
 */

namespace App\Exception;


use Exception;

class IllegalArgumentExceptions extends Exception
{
    public function __construct($message, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code);
    }

}