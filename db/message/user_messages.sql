SELECT content, recipient, sender
  FROM messages
  WHERE recipient = :uid OR sender = :uid;
