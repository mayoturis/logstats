DROP TABLE IF EXISTS "logstats_project_role_user";
DROP TABLE IF EXISTS "logstats_users";
DROP TABLE IF EXISTS "logstats_roles";
DROP TABLE IF EXISTS "logstats_email_send";
DROP TABLE IF EXISTS "logstats_property_types";
DROP TABLE IF EXISTS "logstats_properties";
DROP TABLE IF EXISTS "logstats_records";
DROP TABLE IF EXISTS "logstats_messages";
DROP TABLE IF EXISTS "logstats_levels";
DROP TABLE IF EXISTS "logstats_projects";

DROP TABLE IF EXISTS "logstats_migrations";

CREATE TABLE "logstats_projects" (
  "id" serial NOT NULL,
  "name" varchar(255) NOT NULL,
  "token" varchar(255) NOT NULL,
  "created_at" timestamp NOT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("name"),
  UNIQUE ("token")
);

INSERT INTO "logstats_projects" ("name", "token", "created_at") VALUES
('project1',	'project1Token',	'2016-01-18 23:36:28'),
('queryProject',	'queryProjectToken',	'2016-01-20 16:10:57');

CREATE TABLE "logstats_levels" (
  "name" varchar(255) NOT NULL,
  PRIMARY KEY ("name")
);

INSERT INTO "logstats_levels" ("name") VALUES
('alert'),
('critical'),
('debug'),
('emergency'),
('error'),
('info'),
('notice'),
('warning');

CREATE TABLE "logstats_email_send" (
  "id" serial NOT NULL,
  "project_id" integer NOT NULL,
  "level" varchar(255) NOT NULL,
  "email" varchar(255) NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "email_send_level_500f7" FOREIGN KEY ("level") REFERENCES "logstats_levels" ("name"),
  CONSTRAINT "email_send_project_id_0b818" FOREIGN KEY ("project_id") REFERENCES "logstats_projects" ("id")
);

INSERT INTO "logstats_email_send" ("project_id", "level", "email") VALUES
(1,	'info',	'email@email.com'),
(1,	'emergency',	'email2@email.com');


CREATE TABLE "logstats_messages" (
  "id" serial NOT NULL,
  "message" text NOT NULL,
  "project_id" integer NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "messages_project_id_b3146" FOREIGN KEY ("project_id") REFERENCES "logstats_projects" ("id")
);

INSERT INTO "logstats_messages" ("message", "project_id") VALUES
('terrible',	2),
('purchase',	2),
('visit',	2);

CREATE TABLE "logstats_migrations" (
  "migration" varchar(255) NOT NULL,
  "batch" integer NOT NULL
);

INSERT INTO "logstats_migrations" ("migration", "batch") VALUES
('2014_10_12_100000_create_password_resets_table',	1),
('2015_11_13_134501_init_migration',	1),
('2015_11_21_222653_add_init_data',	1);

DROP TABLE IF EXISTS "logstats_password_resets";
CREATE TABLE "logstats_password_resets" (
  "email" varchar(255) NOT NULL,
  "token" varchar(255) NOT NULL,
  "created_at" timestamp NOT NULL
);

CREATE TABLE "logstats_roles" (
  "name" varchar(255) NOT NULL,
  PRIMARY KEY ("name")
);

INSERT INTO "logstats_roles" ("name") VALUES
('admin'),
('datamanager'),
('visitor');


CREATE TABLE "logstats_users" (
  "id" serial NOT NULL,
  "name" varchar(255) NOT NULL,
  "email" varchar(255) DEFAULT NULL,
  "password" varchar(60) NOT NULL,
  "role" varchar(255) DEFAULT NULL,
  "remember_token" varchar(100) DEFAULT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("name"),
  CONSTRAINT "role_id_cf1ad" FOREIGN KEY ("role") REFERENCES "logstats_roles" ("name")
);

INSERT INTO "logstats_users" ("name", "email", "password", "role", "remember_token") VALUES
('admin',	'',	'$2y$10$0wdlnZW301nIAg6gBhhNtuNru2Fq6407r8OYs.rUerRCp/YZH0oiC',	'admin',	'1zz4k8PirJNSz2BKOChEm9KKba0UxQU4ndIxQbqfAusNjsi72NJ14YRR4MCu'),
('visitor_user',	'',	'$2y$10$fI3AkwUBkdcrvyzejgOngev.HMjLlUYd9xl4EGzJ7.73iiIMTRdMO',	'visitor',	'bHGoQAZYya6ROZCbyeuSEeLeYpPSbGAH9iE2XUtvuS3WUzhunbw8DjmPjSGK'),
('gono',	'',	'$2y$10$XHuQinO7yc2E4CKmWQ7VEe1VfJHYSccsIVT8hsLILYoizFj8kcCNC',	NULL,	'kuZ5QQUwIOvo47XC8YNbqNacRMwXYsrHs4uSOpcuowUnHksbbW4DoxMAPbPb');


CREATE TABLE "logstats_project_role_user" (
  "id" serial NOT NULL,
  "user_id" integer NOT NULL,
  "project_id" integer NOT NULL,
  "role" varchar(255) NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "project_role_user_project_id_7ba4e" FOREIGN KEY ("project_id") REFERENCES "logstats_projects" ("id"),
  CONSTRAINT "project_role_user_role_4a928" FOREIGN KEY ("role") REFERENCES "logstats_roles" ("name"),
  CONSTRAINT "project_role_user_user_id_77629" FOREIGN KEY ("user_id") REFERENCES "logstats_users" ("id")
);

INSERT INTO "logstats_project_role_user" ("user_id", "project_id", "role") VALUES
(1,	1,	'admin'),
(1,	2,	'admin');

CREATE TABLE "logstats_records" (
  "id" serial NOT NULL,
  "date" timestamp NOT NULL,
  "minute" smallint NOT NULL,
  "hour" smallint NOT NULL,
  "day" smallint NOT NULL,
  "month" smallint NOT NULL,
  "year" smallint NOT NULL,
  "project_id" integer NOT NULL,
  "level" varchar(255) NOT NULL,
  "message_id" integer NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "records_level_5e42b" FOREIGN KEY ("level") REFERENCES "logstats_levels" ("name"),
  CONSTRAINT "records_message_id_9b9d7" FOREIGN KEY ("message_id") REFERENCES "logstats_messages" ("id"),
  CONSTRAINT "records_project_id_ae3da" FOREIGN KEY ("project_id") REFERENCES "logstats_projects" ("id")
);

INSERT INTO "logstats_records" ("date", "minute", "hour", "day", "month", "year", "project_id", "level", "message_id") VALUES
('2016-01-20 16:12:04',	12,	17,	20,	1,	2016,	2,	'emergency',	1),
('2016-01-20 16:12:05',	12,	17,	20,	1,	2016,	2,	'info',	2),
('2016-01-20 16:12:05',	12,	17,	20,	1,	2016,	2,	'info',	3);

CREATE TABLE "logstats_properties" (
  "id" serial NOT NULL,
  "name" varchar(255) NOT NULL,
  "value_string" varchar(255) DEFAULT NULL,
  "value_number" numeric(20,5) DEFAULT NULL,
  "value_boolean" smallint DEFAULT NULL,
  "record_id" integer NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "properties_record_id_4a0f1" FOREIGN KEY ("record_id") REFERENCES "logstats_records" ("id")
);

INSERT INTO "logstats_properties" ("name", "value_string", "value_number", "value_boolean", "record_id") VALUES
('number',	NULL,	5.00000,	NULL,	1),
('price',	NULL,	5.00000,	NULL,	2),
('user',	'marek',	NULL,	NULL,	2),
('page',	'project/1',	NULL,	NULL,	3);

CREATE TABLE "logstats_property_types" (
  "id" serial NOT NULL,
  "property_name" varchar(255) NOT NULL,
  "type" varchar(255) DEFAULT NULL,
  "message_id" integer NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "property_types_message_id_24e6f" FOREIGN KEY ("message_id") REFERENCES "logstats_messages" ("id")
);

INSERT INTO "logstats_property_types" ("property_name", "type", "message_id") VALUES
('number',	'number',	1),
('price',	'number',	2),
('user',	'string',	2),
('page',	'string',	3);





-- 2016-01-20 17:13:50