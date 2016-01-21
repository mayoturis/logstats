-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `logstats_email_send`;
CREATE TABLE `logstats_email_send` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email_send_project_id_0b818` (`project_id`),
  KEY `email_send_level_500f7` (`level`),
  CONSTRAINT `email_send_level_500f7` FOREIGN KEY (`level`) REFERENCES `logstats_levels` (`name`),
  CONSTRAINT `email_send_project_id_0b818` FOREIGN KEY (`project_id`) REFERENCES `logstats_projects` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_email_send` (`id`, `project_id`, `level`, `email`) VALUES
(1,	1,	'info',	'email@email.com'),
(2,	1,	'emergency',	'email2@email.com');


DROP TABLE IF EXISTS `logstats_levels`;
CREATE TABLE `logstats_levels` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_levels` (`name`) VALUES
('alert'),
('critical'),
('debug'),
('emergency'),
('error'),
('info'),
('notice'),
('warning');

DROP TABLE IF EXISTS `logstats_messages`;
CREATE TABLE `logstats_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_project_id_b3146` (`project_id`),
  CONSTRAINT `messages_project_id_b3146` FOREIGN KEY (`project_id`) REFERENCES `logstats_projects` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_messages` (`id`, `message`, `project_id`) VALUES
(1,	'terrible',	2),
(2,	'purchase',	2),
(3,	'visit',	2);

DROP TABLE IF EXISTS `logstats_migrations`;
CREATE TABLE `logstats_migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_migrations` (`migration`, `batch`) VALUES
('2014_10_12_100000_create_password_resets_table',	1),
('2015_11_13_134501_init_migration',	1),
('2015_11_21_222653_add_init_data',	1);

DROP TABLE IF EXISTS `logstats_password_resets`;
CREATE TABLE `logstats_password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `logstats_projects`;
CREATE TABLE `logstats_projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_name_unique` (`name`),
  UNIQUE KEY `projects_token_unique` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_projects` (`id`, `name`, `token`, `created_at`) VALUES
(1,	'project1',	'project1Token',	'2016-01-18 23:36:28'),
(2,	'queryProject',	'queryProjectToken',	'2016-01-20 16:10:57');

DROP TABLE IF EXISTS `logstats_project_role_user`;
CREATE TABLE `logstats_project_role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_role_user_user_id_77629` (`user_id`),
  KEY `project_role_user_project_id_7ba4e` (`project_id`),
  KEY `project_role_user_role_4a928` (`role`),
  CONSTRAINT `project_role_user_project_id_7ba4e` FOREIGN KEY (`project_id`) REFERENCES `logstats_projects` (`id`),
  CONSTRAINT `project_role_user_role_4a928` FOREIGN KEY (`role`) REFERENCES `logstats_roles` (`name`),
  CONSTRAINT `project_role_user_user_id_77629` FOREIGN KEY (`user_id`) REFERENCES `logstats_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_project_role_user` (`id`, `user_id`, `project_id`, `role`) VALUES
(1,	1,	1,	'admin'),
(2,	1,	2,	'admin');

DROP TABLE IF EXISTS `logstats_properties`;
CREATE TABLE `logstats_properties` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value_string` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value_number` decimal(20,5) DEFAULT NULL,
  `value_boolean` tinyint(3) unsigned DEFAULT NULL,
  `record_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `properties_record_id_4a0f1` (`record_id`),
  CONSTRAINT `properties_record_id_4a0f1` FOREIGN KEY (`record_id`) REFERENCES `logstats_records` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_properties` (`id`, `name`, `value_string`, `value_number`, `value_boolean`, `record_id`) VALUES
(1,	'number',	NULL,	5.00000,	NULL,	1),
(2,	'price',	NULL,	5.00000,	NULL,	2),
(3,	'user',	'marek',	NULL,	NULL,	2),
(4,	'page',	'project/1',	NULL,	NULL,	3);

DROP TABLE IF EXISTS `logstats_property_types`;
CREATE TABLE `logstats_property_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `property_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `property_types_message_id_24e6f` (`message_id`),
  CONSTRAINT `property_types_message_id_24e6f` FOREIGN KEY (`message_id`) REFERENCES `logstats_messages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_property_types` (`id`, `property_name`, `type`, `message_id`) VALUES
(1,	'number',	'number',	1),
(2,	'price',	'number',	2),
(3,	'user',	'string',	2),
(4,	'page',	'string',	3);

DROP TABLE IF EXISTS `logstats_records`;
CREATE TABLE `logstats_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `minute` tinyint(4) NOT NULL,
  `hour` tinyint(4) NOT NULL,
  `day` tinyint(4) NOT NULL,
  `month` tinyint(4) NOT NULL,
  `year` smallint(6) NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `records_project_id_ae3da` (`project_id`),
  KEY `records_level_5e42b` (`level`),
  KEY `records_message_id_9b9d7` (`message_id`),
  CONSTRAINT `records_level_5e42b` FOREIGN KEY (`level`) REFERENCES `logstats_levels` (`name`),
  CONSTRAINT `records_message_id_9b9d7` FOREIGN KEY (`message_id`) REFERENCES `logstats_messages` (`id`),
  CONSTRAINT `records_project_id_ae3da` FOREIGN KEY (`project_id`) REFERENCES `logstats_projects` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_records` (`id`, `date`, `minute`, `hour`, `day`, `month`, `year`, `project_id`, `level`, `message_id`) VALUES
(1,	'2016-01-20 16:12:04',	12,	17,	20,	1,	2016,	2,	'emergency',	1),
(2,	'2016-01-20 16:12:05',	12,	17,	20,	1,	2016,	2,	'info',	2),
(3,	'2016-01-20 16:12:05',	12,	17,	20,	1,	2016,	2,	'info',	3);

DROP TABLE IF EXISTS `logstats_roles`;
CREATE TABLE `logstats_roles` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_roles` (`name`) VALUES
('admin'),
('datamanager'),
('visitor');

DROP TABLE IF EXISTS `logstats_users`;
CREATE TABLE `logstats_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_name_unique` (`name`),
  KEY `role_id_cf1ad` (`role`),
  CONSTRAINT `role_id_cf1ad` FOREIGN KEY (`role`) REFERENCES `logstats_roles` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `logstats_users` (`id`, `name`, `email`, `password`, `role`, `remember_token`) VALUES
(1,	'admin',	'',	'$2y$10$0wdlnZW301nIAg6gBhhNtuNru2Fq6407r8OYs.rUerRCp/YZH0oiC',	'admin',	'1zz4k8PirJNSz2BKOChEm9KKba0UxQU4ndIxQbqfAusNjsi72NJ14YRR4MCu'),
(2,	'visitor_user',	'',	'$2y$10$fI3AkwUBkdcrvyzejgOngev.HMjLlUYd9xl4EGzJ7.73iiIMTRdMO',	'visitor',	'bHGoQAZYya6ROZCbyeuSEeLeYpPSbGAH9iE2XUtvuS3WUzhunbw8DjmPjSGK');
(3,	'gono',	'',	'$2y$10$XHuQinO7yc2E4CKmWQ7VEe1VfJHYSccsIVT8hsLILYoizFj8kcCNC',	NULL,	'kuZ5QQUwIOvo47XC8YNbqNacRMwXYsrHs4uSOpcuowUnHksbbW4DoxMAPbPb'),
-- 2016-01-20 17:13:50