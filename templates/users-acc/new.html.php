<?php $title = 'Register' ?>
<?php ob_start() ?>
<h1>Register:</h1>
<form action="<?= BASE_URI ?>/users-acc/new" method="post" novalidate>
   <div>
      <label for="email">E-mail:</label>
      <input type="email" id="email" name="user_email" value="<?= $this->utilities->escapeOutput($data['user']['email']) ?>" placeholder="office@zoidberg.com">
      <div id="email_error" name="user_email_error" style="display: <?= $data['show_form_errors']['email'] ?>;" ><?= $data['form_errors']['email'] ?></div>
   </div>
   <div>
      <label for="password">Password:</label>
      <input type="password" id="password" name="user_password" value="<?= $this->utilities->escapeOutput($data['user']['password']) ?>" placeholder="Woop Woop Woop" autocomplete="off">
      <div id="password_error" name="user_password_error" style="display: <?= $data['show_form_errors']['password'] ?>;" ><?= $data['form_errors']['password'] ?></div>
   </div>
   <div>
      <label for="first_name">First name:</label>
      <input type="text" id="first_name" name="user_first_name" value="<?= $this->utilities->escapeOutput($data['user']['first_name']) ?>" placeholder="John">
      <div id="first_name_error" name="user_first_name_error" style="display: <?= $data['show_form_errors']['first_name'] ?>;" ><?= $data['form_errors']['first_name'] ?></div>
   </div>
   <div>
      <label for="last_name">Last name:</label>
      <input type="text" id="last_name" name="user_last_name" value="<?= $this->utilities->escapeOutput($data['user']['last_name']) ?>" placeholder="Zoidberg">
      <div id="last_name_error" name="user_last_name_error" style="display: <?= $data['show_form_errors']['last_name'] ?>;" ><?= $data['form_errors']['last_name'] ?></div>
   </div>
   <div>
      <label>Gender:</label>
      <label><input type="radio" name="user_gender" value="boy" <?= ($this->utilities->escapeOutput($data['user']['gender']) === 'boy') ? 'checked' : ''; ?>>boy</label>
      <label><input type="radio" name="user_gender" value="girl" <?= ($this->utilities->escapeOutput($data['user']['gender']) === 'girl') ? 'checked' : ''; ?>>girl</label>
      <div id="gender_error" name="user_gender_error" style="display: <?= $data['show_form_errors']['gender'] ?>;" ><?= $data['form_errors']['gender'] ?></div>
   </div>
   <div>
      <label>Account status:</label>
      <label><input type="radio" name="user_is_active" value="true" <?= ($this->utilities->escapeOutput($data['user']['is_active']) === 'true') ? 'checked' : ''; ?>>yes</label>
      <label><input type="radio" name="user_is_active" value="false" <?= ($this->utilities->escapeOutput($data['user']['is_active']) === 'false') ? 'checked' : ''; ?>>no</label>
      <div id="is_active_error" name="user_is_active_error" style="display: <?= $data['show_form_errors']['is_active'] ?>;" ><?= $data['form_errors']['is_active'] ?></div>
   </div>
   <div>
      <input type="text" id="csrf_token" name="csrf_token" value="<?= $this->utilities->escapeOutput($data['csrf_token']) ?>" hidden="true">
   </div>
   <div>
      <button type="submit">Save</button>
   </div>
</form>
<?php $content = ob_get_clean() ?>
<?php include 'templates/layout.html.php' ?>