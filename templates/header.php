    <section class="logo">
      <a href="/" title="Home"><span id="logo"> ⚿</span> Messaging privately</a>
    </section>
    <?php if (!empty($data->user->authenticated)): ?>
    <section class="profile">
      <a href="profile" title="Check out your profile">Hi <?php echo $data->user->name; ?></a>
    </section>
    <?php endif; ?>

