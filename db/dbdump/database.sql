PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE users (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  mail TEXT NOT NULL,
  password TEXT ,
  pubkey TEXT,
  privkey TEXT,
  terms BOOLEAN 
);
INSERT INTO "users" VALUES(1,'ieao','ieao@ieaoieao.com','$2y$10$xqeOI6GTCmkMkahPuUvtGO5byxl8zjC90L9YosHNYyJopDT0y44Tq','-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoGmtUJx3Uo5wmklRZKar
1NC2c8yetqfJCzXkYW5bHo9yDuXb6nekPccb4eD7y8/5bz7hWVf/B0+ML6GlVh97
bBALjm/2VcjiYmiktl4qAkMnpTsU7Ok33Zlv3p4Qlyc+oDN3Pd7T8yCnVBBTBwYI
cvb3+jdD+c6vbkB1LMHvEHrw8DTM64KOyAZxCiSWHtqfMOzVRBYm7yAAQxbExij5
WhkcmVO117zFJ+oRcx2Qc2PWlLdHmu1i3I3v2w1eIj166iI8/cIIlTrBrezYs38H
6kriskL6O3DKEDfaz6ncVGAgsR45naGVNSNIGRx6z85TfJuXTNa2koSui7uls4nF
7QIDAQAB
-----END PUBLIC KEY-----
',NULL,1);
CREATE TABLE messages(
  id INTEGER PRIMARY KEY,
  content TEXT,
  recipient INTEGER NOT NULL references users(id),
  sender    INTEGER NOT NULL references users(id)
);
COMMIT;
