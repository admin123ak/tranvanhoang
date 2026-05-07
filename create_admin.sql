-- Admin account: admin123 / admin123
-- Run this SQL in your database to create admin login

INSERT INTO `users` (`username`, `password`, `fullname`, `level`, `status`, `saldo`, `uplink`, `user_ip`, `created_at`, `updated_at`)
VALUES (
  'admin123',
  '$2b$08$i7C3yDDouWoURQVhoZ3OauU87C3Gg3sjkqgsUqiVyjGkJuBJ8RbrS',
  'Admin',
  1,
  1,
  99999,
  '',
  '',
  NOW(),
  NOW()
);
