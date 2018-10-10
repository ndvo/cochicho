    <ul>
      <?php if ( empty($data->user)): ?>
      <li><a href="/register">Register</a></li>
      <li><a href="/login">Login</a></li>
      <?php else: ?>
      <li><a href="/my-messages">My messages</a></li>
      <li><a href="/settings">Settings</a></li>
      <li><a href="/logout">Logout</a></li>
      <?php endif; ?>
    </ul>
