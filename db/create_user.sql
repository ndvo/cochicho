INSERT OR IGNORE INTO users
  (mail, name, password, pubkey, privkey, iv, terms)
  values (:mail, :name, :password, :pubkey, :privkey, :iv, :terms)
  ;
