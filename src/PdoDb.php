<?php

/*
 * This file is part of the wisonlau/validation.
 *
 * (c) wisonlau <122022066@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wisonlau\Validation;

class PdoDb
{
    private static $_instance;
    private $dbConn;


    private function __construct()
    {
        $path = require_once __DIR__ . '/config.php';

        try
        {
            $this->dbConn = new \PDO($path['dsn'], $path['username'], $path['password']);
        }
        catch (\PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if ( ! (self::$_instance instanceof self))
        {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function __clone()
    {
        trigger_error('Clone is not allow' ,E_USER_ERROR);
    }

    public function prepare($sql)
    {
        $result = $this->dbConn->prepare($sql);
        $result->execute();
        return $result->fetch(\PDO::FETCH_ASSOC);
    }
} 