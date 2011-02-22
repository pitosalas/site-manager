CREATE TABLE classes (
  id              INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
  name            VARCHAR(25) NOT NULL,
  title           VARCHAR(255) NOT NULL,
  description	  VARCHAR(255),
  UNIQUE INDEX class_name(name)
) TYPE=InnoDB;

CREATE TABLE instances (
  id              INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
  class_id        INTEGER UNSIGNED NOT NULL,
  name            VARCHAR(25) NOT NULL,
  title           VARCHAR(255) NOT NULL,
  db_name         VARCHAR(25) NOT NULL,
  sponsor_name    VARCHAR(255) NOT NULL,
  sponsor_email   VARCHAR(255) NOT NULL,
  enabled         BIT NOT NULL DEFAULT 1,
  UNIQUE INDEX instance_name(name),
  UNIQUE INDEX instance_db_name(name),
  INDEX instance_class(class_id),
  FOREIGN KEY(class_id)
    REFERENCES classes(id)
      ON UPDATE CASCADE
      ON DELETE CASCADE
) TYPE=InnoDB;
