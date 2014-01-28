CREATE DATABASE `chat` /*!40100 DEFAULT CHARACTER SET utf8 */;

DROP TABLE IF EXISTS `chat`.`messages`;
CREATE TABLE  `chat`.`messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `timestamp` datetime NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `chat`.`users`;
CREATE TABLE  `chat`.`users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nick` varchar(45) NOT NULL,
  `pass` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
