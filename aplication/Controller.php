<?php

class Controller
{
    protected $db;
    protected $settings;
    protected $utilities;
    protected $checker;

    protected $template;

    public function __construct(DB_Model $db, array $settings, Utilities $utilities, Checker $checker, Template $template)
    {
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');

        $this->db = $db;
        $this->settings = $settings;
        $this->utilities = $utilities;
        $this->checker = $checker;

        $this->template = $template;

        header('X-Frame-Options: SAMEORIGIN');

        if (!$this->utilities->is_session_active())
        {
            $this->utilities->session_start();
        }
    }
}