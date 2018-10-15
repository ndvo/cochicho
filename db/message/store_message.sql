INSERT INTO messages
  ( content, recipient, sender, ekeys )
  values
  ( :message, :to, :from , :ekeys );
