DROP TABLE IF EXISTS `doctor`;
CREATE TABLE `doctor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(11) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `special_field` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `doctor_symptom_relation`;
CREATE TABLE `doctor_symptom_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` int(11) DEFAULT NULL,
  `doctor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `symptom`;
CREATE TABLE `symptom` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

