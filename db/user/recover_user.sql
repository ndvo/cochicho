UPDATE users SET
  (password, pubkey, privkey, iv) =
  (:password, :pubkey, :privkey, :iv)
  WHERE 
  name = :name
  ;
