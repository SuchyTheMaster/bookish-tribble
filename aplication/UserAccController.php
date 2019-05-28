<?php

class UserAccController extends Controller
{

    public function __construct(DB_Model $db, array $settings, Utilities $utilities, Checker $checker, Template $template)
    {
        parent::__construct($db, $settings, $utilities, $checker, $template);
    }

    public function listAction(): void
    {
        $loggedInUser = $this->utilities->isUserAccLoggedIn();

        if ($loggedInUser)
        {
            $users = $this->db->getAllUsersAcc();

            foreach ($users as &$user)
            {
                $user['id'] = $this->utilities->hashEncode($user['id']);
            }
        }

        $this->template->render('users-acc/list.html.php', ['users' => $users, 'loggedInUser' => $loggedInUser]);
    }

    public function newAction(): void
    {
        $response = $this->checker->checkNewUserAcc();

        $this->template->render('users-acc/new.html.php', ['user' => $response['user_new'], 'form_errors' => $response['form_errors'], 'show_form_errors' => $response['show_form_errors'], 'csrf_token' => $_SESSION['csrf_token']]);
    }

    public function deleteAction(): void
    {
        $request = $this->utilities->getReguest();

        $this->utilities->csrfValidateToken();

        if ($request['type'] === 'delete')
        {
            if (!$this->utilities->isUserAccLoggedIn())
            {
                print 'false';
            }

            $id = $request['delete']['id'];

            $user = $this->db->getUserAccById($this->utilities->hashDecode($id));

            if ($user !== false)
            {
                $this->db->deleteUserAcc($this->utilities->hashDecode($id));
                print 'true';
            }
            else
            {
                print 'false';
            }
        }

        $this->utilities->redirectToRoute('users-acc');
    }

    public function loginAction(): void
    {
        $form_errors = [];

        if (!empty($_POST))
        {
            $email = $this->utilities->cleanEmail($_POST['user_email']);
            $password = $_POST['user_password'];

            $user = [];
            $user['email'] = $email;
            $user['password'] = '';

            if ($this->utilities->isValidEmail($email))
            {
                $user_db = $this->db->getUserAccByEmail($email);

                if ($user_db === false)
                {
                    $form_errors['email'] = 'account doesn\'t exists';
                }

                if (password_verify($password, $user_db['password']))
                {
                    $_SESSION['user_id'] = $user_db['id'];
                    $_SESSION['user_data_hash'] = hash('sha512', $user_db['email'] . $user_db['password'] . $_SERVER['HTTP_USER_AGENT']);

                    $this->utilities->redirectToRoute('news');
                }
                else
                {
                    $form_errors['password'] = 'wrong password';
                }
            }
            else
            {
                $form_errors['email'] = 'invalid email';
            }
        }

        $show_form_errors = [];
        foreach ($user as $key => $value)
        {
            if (isset($form_errors[$key]))
            {
                $show_form_errors[$key] = 'block';
            }
            else
            {
                $show_form_errors[$key] = 'none';
            }
        }

        $this->template->render('users-acc/login.html.php', ['user' => $user, 'form_errors' => $form_errors, 'show_form_errors' => $show_form_errors, 'csrf_token' => $_SESSION['csrf_token']]);
    }

    public function logoutAction(): void
    {
        $this->utilities->session_destroy();

        $this->utilities->redirectToRoute('news');
    }

    public function registerAction(): void
    {
        $response = $this->checker->checkNewUserAcc();

        $this->template->render('users-acc/register.html.php', ['user' => $response['user_new'], 'form_errors' => $response['form_errors'], 'show_form_errors' => $response['show_form_errors'], 'csrf_token' => $_SESSION['csrf_token']]);
    }
}