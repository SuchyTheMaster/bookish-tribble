<?php

require_once 'aplication/Configuration.php';
require_once 'aplication/Utilities.php';
require_once 'aplication/Controller.php';
require_once 'aplication/UserAccController.php';
require_once 'aplication/NewsController.php';
require_once 'aplication/DB_Model.php';
require_once 'aplication/Template.php';
require_once 'aplication/Checker.php';
require_once 'lib/Hashids/HashGenerator.php';
require_once 'lib/Hashids/Hashids.php';

$config = new Configuration();
$settings = $config->getSettings();
$db_model = new DB_Model($settings['db_settings']);
$hashids = new Hashids\Hashids($settings['hashids']['salt'], $settings['hashids']['hashMinLength']);
$hashids_uri = new Hashids\Hashids($settings['hashids_uri']['salt'], $settings['hashids_uri']['hashMinLength']);
$utilities = new Utilities($db_model, $settings, $hashids, $hashids_uri);
$checker = new Checker($db_model, $utilities);
$template = new Template($utilities);
$userAccController = new UserAccController($db_model, $settings, $utilities, $checker, $template);
$newsController = new NewsController($db_model, $settings, $utilities, $checker, $template);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$usersAcc = 'users-acc';
$news = 'news';

switch ($uri)
{
   case '/':
   case '/site/panda-czy/':
   case BASE_URI:
   case BASE_URI . '/':
   case BASE_URI . '/' . $news:
   case BASE_URI . '/' . $news . '/':
      $newsController->listAction();
      break;
   case BASE_URI . '/' . $news . '/' . 'edit':
      $newsController->editAction();
      break;
   case BASE_URI . '/' . $news . '/' . 'delete':
      $newsController->deleteAction();
      break;
   case BASE_URI . '/' . $news . '/' . 'new':
      $newsController->newAction();
      break;

   case BASE_URI . '/' . $usersAcc:
   case BASE_URI . '/' . $usersAcc . '/':
      $userAccController->listAction();
      break;
   case BASE_URI . '/' . $usersAcc . '/' . 'delete':
      $userAccController->deleteAction();
      break;
   case BASE_URI . '/' . $usersAcc . '/' . 'new':
      $userAccController->newAction();
      break;
   case BASE_URI . '/' . $usersAcc . '/' . 'login':
      $userAccController->loginAction();
      break;
   case BASE_URI . '/' . $usersAcc . '/' . 'logout':
      $userAccController->logoutAction();
      break;

   default:
      header('HTTP/1.1 404 Not Found');
      echo '<html><body>[404] Page not found</body></html>';
      break;
}
