-- Add foreign key column to reference the created usuarios table.
-- On the local server this requires to modify the lastLogin column (due to unsupported '0000-00-00 00:00:00' value
ALTER TABLE `members`
CHANGE COLUMN `lastLogin` `lastLogin` TIMESTAMP NULL DEFAULT NULL AFTER `lastUpdated`,
ADD COLUMN `usuario_id` INT(11) UNSIGNED NOT NULL AFTER `userID`;

