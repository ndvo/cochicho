<main>
  
    
  <h1><?php echo $data->title ?></h1>
  <form method="post" >

    <fieldset>
    <legend>User name</legend>
    <p>A unique name to identify you in our messaging system.</p>
    <label for="username" >User name:
      <input type="text" name="username" placeholder="Your username" required >
    </label>
    </fieldset>

    <fieldset id="email">
    <legend>🖃 e-mail</legend>
    <p>Please, provide an email. It will be used to reach you if you loose your
    password and to notify about new messages.</p>
    <label for="mail" >What is your e-mail?
      <input id="username" name="mail" type="email" placeholder="Your username" required >
    </label>
    <label for="mail2" >Confirm your email:
      <input type="email" name="mail2" placeholder="Please, type you email again to be sure is is right." title="Your email is the only way you can recover your messages if you loose your password. It is important to make sure it is right." required >
    </label>
    </fieldset>
    
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
    
    <fieldset>
      <legend>Termos of Use</legend>
      <label for="terms" >
      <details style="display: inline-block">
        <summary>I understand that I am responsible for my privacy:</summary>
        <ul>
          <li>I may not be able to recover messages if I forget my password</li>
          <li>The security provided is that of encrypting messages</li>
          <li>I must not share my passwords</li>
          <li>I need to remember all three passwords to read my messages</li>
        </ul>
        </details>
        <input type="checkbox" name="terms" > I fully understand and agree.
      </label>
    </fieldset>
    <button type="submit" value="submit">Submit</button>
  </form>
</main>
