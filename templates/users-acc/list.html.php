<?php $title = 'Users' ?>
<?php ob_start() ?>
<div>
   <?php if ($data['loggedInUser']): ?>
      Hello <?= $this->utilities->escapeOutput($data['loggedInUser']['first_name']) ?>! [ <a href="<?= BASE_URI ?>/users-acc/logout">Logout</a> ]
   <?php else: ?>
      Hello stranger. [ <a href="<?= BASE_URI ?>/users-acc/login">Login</a> ]
   <?php endif ?>
</div>
<?php if ($data['loggedInUser']): ?>
   <div>
      [ <a href="<?= BASE_URI ?>/users-acc/new">Add user</a> | <a href="<?= BASE_URI ?>/news">View articles</a> ]
   </div>
   <div>
      <table>
         <thead>
            <tr>
               Users list
            </tr>
            <tr>
               <td>actions</td>
               <td>first_name</td>
               <td>last_name</td>
               <td>email</td>
               <td>gender</td>
               <td>is_active</td>
               <td>password</td>
               <td>created_at</td>
               <td>updated_at</td>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($data['users'] as $user): ?>
               <tr>
                  <td>
                     [ <button class="delete-user" value="<?= $user['id'] ?>">DELETE</button> ]
                  </td>
                  <td>
                     <?= $this->utilities->escapeOutput($user['first_name']) ?>
                  </td>
                  <td>
                     <?= $this->utilities->escapeOutput($user['last_name']) ?>
                  </td>
                  <td>
                     <?= $this->utilities->escapeOutput($user['email']) ?>
                  </td>
                  <td>
                     <?= $this->utilities->escapeOutput($user['gender']) ?>
                  </td>
                  <td>
                     <?= $this->utilities->escapeOutput($user['is_active']) ?>
                  </td>
                  <td>
                     <?= $this->utilities->escapeOutput($user['password']) ?>
                  </td>
                  <td>
                     <?= $this->utilities->escapeOutput($user['created_at']) ?>
                  </td>
                  <td>
                     <?= $this->utilities->escapeOutput($user['updated_at']) ?>
                  </td>
               </tr>
            <?php endforeach ?>
         </tbody>
      </table>
   </div>
<?php endif ?>
<script>
   $(document).ready(function ()
   {
      let btnDeleteClick = function (e)
      {
         $(e.currentTarget).prop('disabled', true)

         let id = e.currentTarget.value;
         let csrf_token = '<?= $data['csrf_token'] ?>'

         let formData =
                 {
                    'id': id,
                    'csrf_token': csrf_token
                 }

         let request = $.ajax({
            url: '<?= $this->utilities->getScriptURL() ?>/users-acc/delete',
            type: 'DELETE',
            data: formData
         })

         request.done(function (response, textStatus, jqXHR)
         {
            if (response === 'true')
            {
               $(e.currentTarget).closest('tr').css("background-color", "red").slideUp('fast');
            }
         })

         request.fail(function (xhr, textStatus, errorThrown)
         {
            $(e.currentTarget).prop('disabled', false);
         })
      }

      $('.delete-user').on('click', btnDeleteClick)
   });

</script>
<?php $content = ob_get_clean() ?>
<?php include 'templates/layout.html.php' ?>