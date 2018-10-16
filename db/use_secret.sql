UPDATE recovery SET used = 1
  WHERE secret = :secret
  AND user = :user
  AND generated > :expiry
  AND used = 0
  ;

