<?php $title = 'New article' ?>
<?php ob_start() ?>
<h1>New article</h1>
<form action="<?= BASE_URI ?>/news/new" method="post" novalidate>
   <div>
      <label for="name">Name:</label>
      <input type="text" id="name" name="article_name" value="<?= $this->utilities->escapeOutput($data['article_new']['name']) ?>" placeholder="Lorem ipsum">
      <div id="name_error" name="article_name_error" style="display: <?= $data['show_form_errors']['email'] ?>;" ><?= $data['form_errors']['name'] ?></div>
   </div>
   <div>
      <label for="description">Description:</label>
      <br />
      <textarea id="description" name="article_description" rows="5" cols="21"><?= $this->utilities->escapeOutput($data['article_new']['description']) ?></textarea>
      <div id="description_error" name="article_description_error" style="display: <?= $data['show_form_errors']['description'] ?>;" ><?= $data['form_errors']['description'] ?></div>

   </div>
   <div>
      <label>Is article active:</label>
      <label><input type="radio" name="article_is_active" value="true" <?= ($this->utilities->escapeOutput($data['article_new']['is_active']) === 'true') ? 'checked' : ''; ?>>yes</label>
      <label><input type="radio" name="article_is_active" value="false" <?= ($this->utilities->escapeOutput($data['article_new']['is_active']) === 'false') ? 'checked' : ''; ?>>no</label>
      <div id="is_active_error" name="article_is_active_error" style="display: <?= $data['show_form_errors']['is_active'] ?>;" ><?= $data['form_errors']['is_active'] ?></div>
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
