    <ul>
      <?php if ( empty($data->user->authenticated)): ?>
      <li><a href="/register">Register</a></li>
      <li><a href="/login">Login</a></li>
      <?php else: ?>
      <li><a href="/panel">My messages</a></li>
      <li><a href="/settings">Settings</a></li>
      <li><a href="/logout">Logout</a></li>
      <?php endif; ?>
      <li><a href="/about">About this project</a></li>
      <li><a href="/dbdump" title="Yup! That's exactly it">Dump the database</a></li>
    </ul>
