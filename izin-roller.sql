-- Tüm izinleri "admin" rolüne atayan script
-- "admin" rolünün ID'sini alıp tüm izinleri bu role atama
INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id
FROM permissions p, roles r
WHERE r.name = 'admin';

-- Admin izinlerini temizleyip, tekrar eklemek isterseniz:
-- DELETE FROM role_has_permissions WHERE role_id = (SELECT id FROM roles WHERE name = 'admin');
-- Sonra yukarıdaki INSERT komutunu çalıştırın

-- İzin önbelleğini temizleyin:
-- php artisan permission:cache-reset
