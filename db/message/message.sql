
SELECT id as mid, content, recipient, sender, ekeys
  FROM messages
  WHERE id = :mid
  ;
