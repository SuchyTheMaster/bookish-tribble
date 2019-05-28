<?php

class Template
{
    private $utilities;

    public function __construct($utilities)
    {
        $this->utilities = $utilities;
    }

    public function render($name, $data): void
    {
        require_once 'templates/' . $name;
    }
}