SELECT content, recipient, sender, ekeys
  FROM messages
  WHERE recipient = :uid OR sender = :uid
  ORDER BY id DESC
  ;
