<?php

class Configuration
{
    private $config;

    public function __construct()
    {
        define('BASE_URI', $_SERVER['SCRIPT_NAME']);

        $this->config = [];
        $this->config['hashids'] = [
            'salt' => '1234567890',
            'hashMinLength' => '8'
        ];

        $this->config['hashids_uri'] = [
            'salt' => '1234567890',
            'hashMinLength' => '8'
        ];

        $this->config['db_settings'] = [
            'dsn' => 'mysql',
            'hostname' => '',
            'username' => '',
            'password' => '',
            'database' => '',
            'char_set' => 'utf8mb4',
        ];
    }

    public function getSettings(): array
    {
        return $this->config;
    }
}