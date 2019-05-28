<?php

class NewsController extends Controller
{
    public function __construct(DB_Model $db, array $settings, Utilities $utilities, Checker $checker, Template $template)
    {
        parent::__construct($db, $settings, $utilities, $checker, $template);
    }

    public function listAction(): void
    {
        $loggedInUser = $this->utilities->isUserAccLoggedIn();

        $articles = $this->db->getAllArticles();

        foreach ($articles as &$article)
        {
            $article['id'] = $this->utilities->hashEncode($article['id']);
            if ($article['author_id'] === null)
            {
                $article['author_name'] = '[account deleted]';
            }
            else
            {
                $article['author_name'] = $article['first_name'] . ' ' . $article['last_name'];
            }
        }

        $this->template->render('news/list.html.php', ['articles' => $articles, 'loggedInUser' => $loggedInUser, 'csrf_token' => $_SESSION['csrf_token']]);
    }

    public function newAction(): void
    {
        $this->utilities->checkAccPermissions('users-acc/login');

        $response = $this->checker->checkNewArticle();

        $this->template->render('news/new.html.php', ['article_new' => $response['article_new'], 'form_errors' => $response['form_errors'], 'show_form_errors' => $response['show_form_errors'], 'csrf_token' => $_SESSION['csrf_token']]);
    }

    public function editAction(): void
    {
        $this->utilities->checkAccPermissions('users-acc/login');

        $response = $this->checker->checkEditArticle();

        $this->template->render('news/edit.html.php', ['user' => $response['user'], 'article_new' => $response['article_new'], 'form_errors' => $response['form_errors'], 'show_form_errors' => $response['show_form_errors'], 'csrf_token' => $_SESSION['csrf_token']]);
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

            $article = $this->db->getArticleById($this->utilities->hashDecode($id));

            if ($article !== false)
            {
                $this->db->deleteArticle($this->utilities->hashDecode($id));
                print 'true';
            }
            else
            {
                print 'false';
            }
        }

        $this->utilities->redirectToRoute('news');
    }
}