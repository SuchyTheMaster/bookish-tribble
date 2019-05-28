<?php $title = 'News' ?>
<?php ob_start() ?>
<div>
   <?php if ($data['loggedInUser']): ?>
      Hello <?= $this->utilities->escapeOutput($data['loggedInUser']['first_name']) ?>! [ <a href="<?= BASE_URI ?>/users-acc/logout">Logout</a> ]
   <?php else: ?>
      Hello stranger. [ <a href="<?= BASE_URI ?>/users-acc/login">Login</a> | <a href="<?= BASE_URI ?>/users-acc/new">Register</a>]
   <?php endif ?>
</div>
<?php if ($data['loggedInUser']): ?>
   <div>
      [ <a href="<?= BASE_URI ?>/news/new">Add article</a> | <a href="<?= BASE_URI ?>/users-acc">View users</a> ]
   </div>
<?php endif ?>
<div>
   <?php if (!empty($data['articles'])): ?>
      <table>
         <thead>
            <tr>
               Articles:
            </tr>
            <tr>
               <?php if ($data['loggedInUser']): ?>
                  <td>actions</td>
               <?php endif ?>
               <td>name</td>
               <td>description</td>
               <?php if ($data['loggedInUser']): ?>
                  <td>is_active</td>
               <?php endif ?>
               <td>created_at</td>
               <td>updated_at</td>
               <td>author</td>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($data['articles'] as $article): ?>
               <?php if ($data['loggedInUser'] OR $article['is_active']): ?>
                  <tr>
                     <?php if ($data['loggedInUser']): ?>
                        <td>
                           [
                           <button class="button-edit" value="<?= $article['id'] ?>">EDIT</button> | 
                           <button class="delete-article" value="<?= $article['id'] ?>">DELETE</button>
                           ]
                        </td>
                     <?php endif ?>
                     <td>
                        <?= $this->utilities->escapeOutput($article['name']) ?>
                     </td>
                     <td>
                        <?= $this->utilities->escapeOutput($article['description']) ?>
                     </td>
                     <?php if ($data['loggedInUser']): ?>
                        <td>
                           <?= $this->utilities->escapeOutput($article['is_active']) ?>
                        </td>
                     <?php endif ?>
                     <td>
                        <?= $this->utilities->escapeOutput($article['created_at']) ?>
                     </td>
                     <td>
                        <?= $this->utilities->escapeOutput($article['updated_at']) ?>
                     </td>
                     <td>
                        <?= $this->utilities->escapeOutput($article['author_name']) ?>
                     </td>
                  </tr>
               <?php endif ?>
            <?php endforeach ?>
         </tbody>
      </table>
   <?php endif ?>
</div>

<script>
   $(document).ready(function ()
   {
      let editButtonClick = function (e)
      {
         location.href = 'news/edit?id=' + e.currentTarget.value
      }

      $('.button-edit').on('click', editButtonClick)

      let btnDeleteClick = function (e)
      {
         $(e.currentTarget).prop('disabled', true)

         let id = e.currentTarget.value
         let csrf_token = '<?= $data['csrf_token'] ?>'


         let formData =
                 {
                    'id': id,
                    'csrf_token': csrf_token
                 }

         let request = $.ajax({
            url: '<?= $this->utilities->getScriptURL() ?>/news/delete',
            type: 'DELETE',
            data: formData
         })



         request.done(function (response, textStatus, jqXHR)
         {
            if (response === 'true')
            {
               $(e.currentTarget).closest('tr').css("background-color", "red").slideUp('fast')
            }
         })

         request.fail(function (xhr, textStatus, errorThrown)
         {
            $(e.currentTarget).prop('disabled', false)
         })
      }

      $('.delete-article').on('click', btnDeleteClick)
   });

</script>
<?php $content = ob_get_clean() ?>
<?php include 'templates/layout.html.php' ?>