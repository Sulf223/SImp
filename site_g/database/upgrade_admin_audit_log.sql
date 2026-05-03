-- Tabel pentru jurnalul acțiunilor administrative
-- Fiecare modificare făcută din panoul admin (change role, reset progress, delete user)
-- este înregistrată aici pentru forensics / accountability.
CREATE TABLE IF NOT EXISTS admin_audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_user_id INT NOT NULL,
    admin_username VARCHAR(100) NOT NULL,
    action_type VARCHAR(40) NOT NULL,
    target_user_id INT NULL,
    target_username VARCHAR(100) NULL,
    details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin (admin_user_id, created_at),
    INDEX idx_target (target_user_id, created_at),
    INDEX idx_action (action_type, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
