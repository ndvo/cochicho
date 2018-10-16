<?php if (empty($data->account)): ?>
<h2>Let's try to recover your account</h2>

<p>We've never stored your plain password, so we can't send it back to you. Buit we can try to verify you are the owner of the account you claim and, if so, allow you to create a new password.</p>
<p>Here is how it will work: you provide us your username and we'll send you a one time password to the registered email. The one time password expires in a day, but you can ask for another one if you forget it. </p>
<p>You'll need to create a new password upon login.</p>

  <section class="reset-password">
    <form method="post" >

      <fieldset id="password">
      <legend>Password reset</legend>
      <label for="username" >Username: </label>
      <input type="text" name="username" placeholder="Your username" >
      <input type="submit" value="submit">
      </fieldset>
  </form>
  </section>
<?php else: ?>

<h2>We've sent recovery secret</h2>

<p>If the provided account was registered, we have sent an email to the registered address with a recovery secret valid for 24 hours.</p>
<p>Please, check your email.</p>

<?php endif; ?>
