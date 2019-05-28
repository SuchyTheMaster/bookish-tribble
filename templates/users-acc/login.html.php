<?php $title = 'Login' ?>
<?php ob_start() ?>
<h1>Login</h1>
<form action="<?= BASE_URI ?>/users-acc/login" method="post" novalidate>
   <div>
      <label for="email">E-mail:</label>
      <input type="email" id="email" name="user_email" value="<?= $this->utilities->escapeOutput($data['user']['email']) ?>" placeholder="office@zoidberg.com">
      <div id="email_error" name="user_email_error" style="display: <?= $data['show_form_errors']['email'] ?>;" ><?= $data['form_errors']['email'] ?></div>
   </div>
   <div>
      <label for="password">Password:</label>
      <input type="password" id="password" name="user_password" value="<?= $this->utilities->escapeOutput($data['user']['password']) ?>" placeholder="Woop Woop Woop" autocomplete="off">
      <div id="email_error" name="user_email_error" style="display: <?= $data['show_form_errors']['password'] ?>;" ><?= $data['form_errors']['password'] ?></div>
   </div>
   <div>
      <input type="text" id="csrf_token" name="csrf_token" value="<?= $this->utilities->escapeOutput($data['csrf_token']) ?>" hidden="true">
   </div>
   <div>
      <button type="submit">Login</button>
   </div>
</form>
<?php $content = ob_get_clean() ?>
<?php include 'templates/layout.html.php' ?>