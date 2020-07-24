<?php

class Connection extends \Mysqli
{

    public $table;

    function __construct()
    {
        $env = require 'config/database.php';
        $mysqli = new mysqli($env['db_host'],
            $env['db_username'],
            $env['db_password'],
            $env['db_name']);

        if ($mysqli->connect_errno) {
            showError("Connect failed: ".$mysqli->connect_error,"403");
            exit();
        }

        parent::__construct(
            $env['db_host'],
            $env['db_username'],
            $env['db_password'],
            $env['db_name']
        );
    }

}