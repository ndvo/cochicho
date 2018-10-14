
SELECT content, recipient, sender
  FROM messages
  WHERE id = :mid
  ;
