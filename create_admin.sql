-- Admin account: admin123 / admin123
-- Chạy file này SAU KHI đã import special.sql

-- Xóa admin cũ nếu có (tránh trùng username)
DELETE FROM `users` WHERE `username` = 'admin123';

-- Tạo admin mới
INSERT INTO `users` (`username`, `password`, `fullname`, `level`, `status`, `saldo`, `uplink`, `user_ip`, `created_at`, `updated_at`) VALUES (
  'admin123',
  CONCAT('$', '2b$', '08$', 'i7C3yDDouWoURQVhoZ3OauU87C3Gg3sjkqgsUqiVyjGkJuBJ8RbrS'),
  'Admin',
  1,
  1,
  99999,
  NULL,
  NULL,
  NOW(),
  NOW()
);
