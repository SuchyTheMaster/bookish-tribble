<?php

class Utilities
{
    private $db_model;
    private $settings;
    private $hashids;
    private $hashids_uri;

    public function __construct($db_model, $settings, $hashids, $hashids_uri)
    {
        $this->db_model = $db_model;
        $this->settings = $settings;
        $this->hashids = $hashids;
        $this->hashids_uri = $hashids_uri;
    }

    public function escapeOutput($string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'utf-8');
    }

    public function isValidEmail($emailAddress): bool
    {
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL))
            return true;

        return false;
    }

    public function cleanEmail($emailAddress): string
    {
        return filter_var($emailAddress, FILTER_SANITIZE_EMAIL);
    }

    public function session_start(): void
    {
        $sessionName = 'session';
        $lifetime = 0;
        $secure = false;
        $httpOnly = true;
        $cookieParams = session_get_cookie_params();

        session_name($sessionName);
        session_set_cookie_params($lifetime, $cookieParams['path'], $cookieParams['domain'], $secure, $httpOnly);
        session_start();
        session_regenerate_id();
        $this->csrfValidateToken();
    }

    public function session_destroy(): void
    {
        $_SESSION = [];
        $cookieParams = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], $cookieParams['httponly']);
        session_destroy();
    }

    public function is_session_active(): bool
    {
        if (session_status() === PHP_SESSION_ACTIVE)
        {
            return true;
        }

        return false;
    }

    public function isUserAccLoggedIn()
    {
        if (isset($_SESSION['user_id'], $_SESSION['user_data_hash']))
        {
            if ($user_db = $this->db_model->getUserAccById($_SESSION['user_id']))
            {
                if (hash_equals($_SESSION['user_data_hash'], hash('sha512', $user_db['email'] . $user_db['password'] . $_SERVER['HTTP_USER_AGENT'])))
                {
                    return $user_db;
                }

                return false;
            }

            return false;
        }

        return false;
    }

    public function checkAccPermissions(string $routeToRedirectOnNoAccess = null): void
    {
        if (!$this->isUserAccLoggedIn())
        {
            $this->redirectToRoute($routeToRedirectOnNoAccess);
        }
    }

    public function redirectToRoute(string $route = null): void
    {
        ($route === null) ? $route = '' : $route = '/' . $route;

        if ($route !== '')
        {
            $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . $route;
        }
        else
        {
            $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
        }


        header('Location: ' . $url);
        exit;
    }

    public function hashEncode(string $value, bool $uri = false): string
    {
        if ($uri)
        {
            return $this->hashids_uri->encode($value);
        }
        return $this->hashids->encode($value);
    }

    public function hashDecode(string $hash, bool $uri = false): string
    {
        if ($uri)
        {
            return $this->hashids_uri->decode($hash)[0];
        }
        return $this->hashids->decode($hash)[0];
    }

    public function csrfValidateToken($token = false): bool
    {
        if (empty($_SESSION['csrf_token']))
        {
            $this->generateCsrfToken();
        }

        $request = $this->getReguest();

        if ($request['type'])
        {
            if (hash_equals($_SESSION['csrf_token'], $request[$request['type']]['csrf_token']))
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        exit('wrong csrf token');
    }

    public function generateCsrfToken(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    public function getReguest(): array
    {
        $request = [];

        if (isset($_SERVER['REQUEST_METHOD']))
        {
            $request['type'] = null;

            switch ($_SERVER['REQUEST_METHOD'])
            {
                case 'POST':
                    if ($_POST['_method'] === 'PUT')
                    {
                        $request['type'] = 'put';
                        $request['put'] = $_POST;
                        unset($request['put']['_method']);
                    }
                    else
                    {
                        $request['type'] = 'post';
                        $request['post'] = $_POST;
                    }

                    if (isset($_GET) and !empty($_GET))
                        $request['get'] = $_GET;
                    break;

                case 'GET':
                    $request['type'] = 'get';
                    $request['get'] = $_GET;

                    if (isset($_POST) and !empty($_POST))
                        $request['post'] = $_POST;

                    break;

                case 'PUT':
                    $request['type'] = 'put';
                    parse_str(file_get_contents('php://input'), $request['put']);
                    break;

                case 'DELETE':
                    $request['type'] = 'delete';
                    parse_str(file_get_contents('php://input'), $request['delete']);
                    break;

                default:
                    break;
            }
        }

        return $request;
    }

    public function getWebsiteAddress(): string
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
    }

    public function getScriptURL(): string
    {
        return $this->getWebsiteAddress() . $_SERVER['SCRIPT_NAME'];
    }

}