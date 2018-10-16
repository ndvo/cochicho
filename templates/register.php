<main>
  
    
  <h1><?php echo $data->title ?></h1>
  <form method="post" >

    <fieldset>
      <legend>User name</legend>
      <p>A unique name to identify you in our messaging system.</p>
      <label for="username" >User name:
        <input type="text" name="username" title="Please, provide a username with at least 3 characters. " placeholder="Your username" pattern="[\d\w+@-][\d\w\.+ '@-]+[\d\w+@-]" required <?php if (!empty($data->recovering_user)){ echo 'value="'.$data->recovering_user.'" disabled' ;} ?> >
      </label>
      <?php if (empty($data->recovering_user)): ?>
      <details class="help"><summary>Help:</summary>
       <ul>
         <li>You may use any letter, number, apostrophes, space and the symbols "@", "+" and "-" in your username. <span class="example">John D'antagna, peter75 and Mary-Anne are valid usernames.</span></li>
         <li>Don't use whitespaces at the start or end of your username. They'll be ignored.  <span class="example">Leading and trailing spaces may hide typos.</span></li>
         <li>Your username needs to be unique. <span class="example">Using a full name or an email as username helps avoiding collision with other names.</span></li>
         <li>Your username may be shown to others.  <span class="example">Do not use any sensitive information as part of your username</span></li>
       </ul>
      </details>
    <?php  endif; ?>
    </fieldset>

    <?php if (empty($data->recovering_user)) : ?>
    <fieldset id="email">
      <legend>🖃 e-mail</legend>
      <p>Please, provide an email. It will be used to reach you if you loose your
      password and to notify about new messages.</p>
      <label for="mail" >What is your e-mail?
        <input id="username" name="mail" type="email" placeholder="Your email" required >
      </label>
      <label for="mail2" >Confirm your email:
        <input type="email" name="mail2" placeholder="Please, confirm your email" title="Your email is the only way you can recover your messages if you loose your password. It is important to make sure it is right." required >
      </label>
      <details class="help"><summary>Help:</summary>
       <ul>
         <li>Choose a valid email. <span class="example">We may need to contact you.</span></li>
         <li>We won't spam you. <span class="example">We will only send messages if you require us to.</span></li>
         <li>Don't use a dotless domain names or comments. <span class="example">Although your browser may accept these as valid email, our server won't. </span></li>
         <li>Your email won't be shown to other users. <span class="example">But your username is. </span></li>
       </ul>
      </details>
    </fieldset>

    <?php endif; ?>
    
    <fieldset id="password">
    <legend>*** Passwords</legend>
    <p>Three passwords?!? Yup. Much easier than special chars. Choose three
    passwords that will keep your secret messages secret.</p>
    <p>To make your password safe, at least one of them should have <strong>a character that is not a letter</strong>. Do not use only numbers.</p>
    <label for="pwd-square">■ Password:
      <input onchange="setPassStrengthMeter()" type="password" name="pwd-square" required >
    </label>

    <label for="pwd-triangle">▲ Password:
      <input onchange="setPassStrengthMeter()" type="password" name="pwd-triangle" required>
    </label>

    <label for="pwd-circle">● Password:
      <input onchange="setPassStrengthMeter()" type="password" name="pwd-circle" required>
    </label>
    <label >Password Strength:
      <meter min="0" max="300" value="0"></meter>
      <output></output>
    </label>
      <details class="help"><summary>Help:</summary>
       <ul>
         <li>Use the TAB key to quickly mode between password fields.<span class="example">This can actually be only two keystrokes longer than your usual password: "my&lt;TAB&gt;usual&lt;TAB&gt;password"</span></li>
         <li>A password needs to be strong and that means lots of characters from a large character set. <span class="example">This is why most websites require you to use a long password with letters, capital letters, digits and symbols.</span></li>
         <li>A password needs to be known by heart. <span class="example">A password is something you know, and only you know. It shouldn't be written down, shouldn't be shared, shouldn't be forgotten.</span></li>
         <li>Strong passwords are usually hard to know by heart, and vice versa.<span class="example">The password "*maç&WYap)0*=_ 7" is strong, but hard to memorize. The password "Jetsons" is easy to memorize, but weak.</span></li>
         <li>Three simple passwords are easier to memorize and harder to crack.<span class="example">Let's say you choose the passwords: "Mom" "Lives" "15th Av." That's easy enough to remember and it makes up to a 16 digit password with symbols, lower and upper case letters and numbers, the same as before.</span></li>
         <li>You can make it even stronger by not using only dictionary words. <span class="example">You may use one of the passwords for a weird symbol like | or ªª, or to use a word from a different language.</span></li>
       </ul>
      </details>
    </fieldset>

    <?php if (empty($data->recovering_user)) : ?>
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
    <?php endif; ?>

    <?php if (!empty($data->recovering_user)) : ?>
    <button name="action" id="cancel" type="submit" value="cancel" formnovalidate>Cancel</button>
    <?php endif; ?>
    <button name="action" id="register" type="submit" value="submit">Submit</button>
  </form>

</main>
