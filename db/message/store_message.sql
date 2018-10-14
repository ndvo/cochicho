INSERT INTO messages
  ( content, recipient, sender )
  values
  (:message, :to, :from);
