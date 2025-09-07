-- Migration script to implement role-based authentication system
-- This script creates the new roles and user_roles tables and migrates existing data

-- Create roles table
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `priority` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create user_roles table (many-to-many relationship)
CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `assigned_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_role_unique` (`user_id`, `role_id`),
  KEY `role_id` (`role_id`),
  KEY `assigned_by` (`assigned_by`),
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `staff` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default roles with priority system
-- Lower priority number = higher privilege level
INSERT INTO `roles` (`name`, `description`, `priority`) VALUES
('system_admin', 'System Administrator - Full system access', 1),
('admin', 'Administrator - Full application access', 2),
('medewerker', 'Medewerker - Standard employee access', 3),
('api_admin', 'API Administrator - API access only', 4);

-- Migrate existing admin users to appropriate roles
-- Users with admin=1 get 'admin' role
INSERT INTO `user_roles` (`user_id`, `role_id`, `assigned_at`)
SELECT s.id, r.id, NOW()
FROM `staff` s
CROSS JOIN `roles` r
WHERE s.admin = 1 AND r.name = 'admin';

-- Users with admin=0 get 'medewerker' role
INSERT INTO `user_roles` (`user_id`, `role_id`, `assigned_at`)
SELECT s.id, r.id, NOW()
FROM `staff` s
CROSS JOIN `roles` r
WHERE s.admin = 0 AND r.name = 'medewerker';

-- Add a system admin user (optional - you can remove this if not needed)
-- This creates a system admin role for the first admin user
INSERT INTO `user_roles` (`user_id`, `role_id`, `assigned_at`)
SELECT s.id, r.id, NOW()
FROM `staff` s
CROSS JOIN `roles` r
WHERE s.admin = 1 AND r.name = 'system_admin'
LIMIT 1;

-- Note: The admin column in staff table will be kept for now
-- You can remove it later after confirming the new system works
-- ALTER TABLE `staff` DROP COLUMN `admin`;
