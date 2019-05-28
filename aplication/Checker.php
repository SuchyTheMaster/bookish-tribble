<?php

class Checker
{
    private $util;
    private $db;

    public function __construct($db, $util)
    {
        $this->db = $db;
        $this->util = $util;
    }

    /* USER ACC BEGIN */

    public function checkNewUserAcc(): array
    {
        $userNew = [];
        $formErrors = [];

        $request = $this->util->getReguest();

        if ($request['type'] === 'post')
        {
            if (!$this->util->csrfValidateToken())
            {
                exit('wrong csrf token');
            }

            $userNew['first_name'] = trim($request['post']['user_first_name']);
            $userNew['last_name'] = trim($request['post']['user_last_name']);
            $userNew['email'] = $this->util->cleanEmail($request['post']['user_email']);
            $userNew['gender'] = trim($request['post']['user_gender']);
            $userNew['is_active'] = trim($request['post']['user_is_active']);
            $userNew['password'] = $request['post']['user_password'];
            $userNew['created_at'] = date('Y-m-d H:i:s', time());
            $userNew['updated_at'] = $userNew['created_at'];

            if (empty($userNew['first_name']))
            {
                $formErrors['first_name'] = 'enter your name';
            }

            if (empty($userNew['last_name']))
            {
                $formErrors['last_name'] = 'enter your surname';
            }

            if (empty($userNew['email']))
            {
                $formErrors['email'] = 'enter your email';
            }
            else if ($this->util->isValidEmail($userNew['email']))
            {
                if ($this->db->getUserAccByEmail($userNew['email']) !== false)
                {
                    $formErrors['email'] = 'account exists';
                }
            }
            else
            {
                $formErrors['email'] = 'invalid email';
            }

            if (empty($userNew['gender']))
            {
                $formErrors['gender'] = 'pick your gender';
            }
            else if (($userNew['gender'] !== 'boy') and ($userNew['gender'] !== 'girl'))
            {
                $formErrors['gender'] = 'incorrect gender';
            }

            if (empty($userNew['is_active']))
            {
                $formErrors['is_active'] = 'pick your account status';
            }
            else if (($userNew['is_active'] !== 'true') and ($userNew['is_active'] !== 'false'))
            {
                $formErrors['is_active'] = 'incorrect account status';
            }

            if (!empty($userNew['password']))
            {
                $userNew['password'] = password_hash($userNew['password'], PASSWORD_BCRYPT, ["cost" => 11]);
            }
            else
            {
                $formErrors['password'] = 'no password';
            }

            if (empty($formErrors))
            {
                $userNew['is_active'] = ($userNew['is_active'] === 'true') ? true : false;

                $id = $this->db->insertUserAcc($userNew);
                if ((int)$id > 0)
                {
                    $this->util->redirectToRoute('users-acc');
                }
                else
                {
                    $formErrors['email'] = 'account exists';
                }
            }
        }

        if (!empty($formErrors))
        {
            $userNew['password'] = '';
        }

        $showFormErrors = [];
        foreach ($userNew as $key => $value)
        {
            if (isset($formErrors[$key]))
            {
                $showFormErrors[$key] = 'block';
            }
            else
            {
                $showFormErrors[$key] = 'none';
            }
        }

        $results = [];
        $results['user_new'] = $userNew;
        $results['form_errors'] = $formErrors;
        $results['show_form_errors'] = $showFormErrors;

        return $results;
    }

    /* USER ACC END */

    /* NEWS BEGIN */

    public function checkNewArticle(): array
    {
        $newArticle = [];
        $formErrors = [];

        $request = $this->util->getReguest();

        if ($request['type'] === 'post')
        {
            if (!$this->util->csrfValidateToken())
            {
                exit('wrong csrf token');
            }

            $newArticle['name'] = trim($request['post']['article_name']);
            $newArticle['description'] = trim($request['post']['article_description']);
            $newArticle['is_active'] = trim($request['post']['article_is_active']);
            $newArticle['created_at'] = date('Y-m-d H:i:s', time());
            $newArticle['updated_at'] = $newArticle['created_at'];
            $newArticle['author_id'] = $_SESSION['user_id'];

            if (empty($newArticle['name']))
            {
                $formErrors['name'] = 'provide the name';
            }

            if (empty($newArticle['description']))
            {
                $formErrors['description'] = 'provide the description';
            }

            if (empty($newArticle['is_active']))
            {
                $formErrors['is_active'] = 'pick your account status';
            }
            else if (($newArticle['is_active'] !== 'true') and ($newArticle['is_active'] !== 'false'))
            {
                $formErrors['is_active'] = 'incorrect account status';
            }

            if (empty($formErrors))
            {
                $newArticle['is_active'] = ($newArticle['is_active'] === 'true') ? true : false;

                $id = $this->db->insertArticle($newArticle);
                if ((int)$id > 0)
                {
                    $this->util->redirectToRoute('news');
                }
            }
        }

        $showFormErrors = [];
        foreach ($newArticle as $key => $value)
        {
            if (isset($formErrors[$key]))
            {
                $showFormErrors[$key] = 'block';
            }
            else
            {
                $showFormErrors[$key] = 'none';
            }
        }

        $results = [];
        $results['article_new'] = $newArticle;
        $results['form_errors'] = $formErrors;
        $results['show_form_errors'] = $showFormErrors;

        return $results;
    }

    public function checkEditArticle(): array
    {
        $request = $this->util->getReguest();

        $id = $request['put']['id'];

        if ($request['type'] === 'get')
        {
            $id = $request['get']['id'];
            $currentArticle = $this->db->getArticleById($this->util->hashDecode($id));

            if ($currentArticle === false)
            {
                $this->util->redirectToRoute('users-acc');
            }

            $formErrors = [];
        }

        if ($request['type'] === 'put')
        {
            if (!$this->util->csrfValidateToken())
            {
                exit('wrong csrf token');
            }

            $newArticle['id'] = trim($request['get']['id']);
            $newArticle['name'] = trim($request['put']['article_name']);
            $newArticle['description'] = trim($request['put']['article_description']);
            $newArticle['is_active'] = trim($request['put']['article_is_active']);
            $newArticle['updated_at'] = date('Y-m-d H:i:s', time());

            if (empty($newArticle['name']))
            {
                $formErrors['name'] = 'provide the name';
            }

            if (empty($newArticle['description']))
            {
                $formErrors['description'] = 'provide the description';
            }

            if (empty($newArticle['is_active']))
            {
                $formErrors['is_active'] = 'pick your account status';
            }
            else if (($newArticle['is_active'] !== 'true') and ($newArticle['is_active'] !== 'false'))
            {
                $formErrors['is_active'] = 'incorrect account status';
            }

            if (empty($formErrors))
            {
                $newArticle['is_active'] = ($newArticle['is_active'] === 'true') ? true : false;

                $newArticle['id'] = $this->util->hashDecode($newArticle['id']);

                $result = $this->db->updateArticle($newArticle);

                if ($result)
                {
                    $this->util->redirectToRoute('news');
                }
            }
        }
        else
        {
            $currentArticle['id'] = $this->util->hashEncode($currentArticle['id']);
            $currentArticle['is_active'] = ($currentArticle['is_active'] === '1') ? 'true' : 'false';
            $newArticle = $currentArticle;
        }

        $showFormErrors = [];
        foreach ($newArticle as $key => $value)
        {
            if (isset($formErrors[$key]))
            {
                $showFormErrors[$key] = 'block';
            }
            else
            {
                $showFormErrors[$key] = 'none';
            }
        }

        $results = [];
        $results['article_new'] = $newArticle;
        $results['form_errors'] = $formErrors;
        $results['show_form_errors'] = $showFormErrors;

        return $results;
    }

    /* NEWS END */
}