<?php if ($data->user->authenticated): ?>
  <h2><?php echo $data->title; ?></h2>
  <p>Here is the data we have about you.</p>
  
  <dl class="user data">
    <dt>Name</dt><dd><?php echo $data->user->name; ?></dd>
    <dt>Email</dt><dd><?php echo $data->user->mail; ?></dd>
    <dt>Accepted the terms of use?</dt><dd><?php echo $data->user->accepted_terms?"Yes":"No"; ?></dd>
    <dt>Public_key</dt><dd><?php echo $data->user->public_key; ?></dd>
  </dl>
  


<?php else: ?>

  <h1>Please, provide your credentials.</h1>
  <section class="login">
    <form method="post" >

      <label for="username" >Name: </label>
      <input type="text" name="username" placeholder="Your username" >

      <fieldset id="password">
          <legend>*** Passwords</legend>
          <p>Three passwords?!? Yup. Much easier than special chars. Choose three
          passwords that will keep your secret messages secret.</p>
          <label for="pwd-square">■ Password:
            <input type="password" name="pwd-square" required >
          </label>

          <label for="pwd-triangle">▲ Password:
            <input type="password" name="pwd-triangle" required>
          </label>

          <label for="pwd-circle">● Password:
            <input type="password" name="pwd-circle" required>
          </label>
      </fieldset>
      <input type="submit" value="submit">
  </form>
  </section>
  <section class="help">
    <h4>Help</h4>
    <details>
      <summary>Did you forget your password?</summary>
    </details>
    <details>
      <summary>Three passwords?</summary>
    </details>
  </section>

<?php endif; ?>
