/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : dbnovels

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2016-04-26 17:04:37
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `tblauthor`
-- ----------------------------
DROP TABLE IF EXISTS `tblauthor`;
CREATE TABLE `tblauthor` (
  `author_ID` int(10) NOT NULL AUTO_INCREMENT,
  `authorname` varchar(200) NOT NULL,
  `authorsurname` varchar(200) NOT NULL,
  PRIMARY KEY (`author_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tblauthor
-- ----------------------------
INSERT INTO tblauthor VALUES ('1', 'James', 'HJSGBSAJHDGS456');
INSERT INTO tblauthor VALUES ('2', 'Peter', 'HJGhjJHGJH6556');
INSERT INTO tblauthor VALUES ('3', 'Lebo', 'dfsdf');
INSERT INTO tblauthor VALUES ('4', 'Klaas', 'Rikso');
INSERT INTO tblauthor VALUES ('8', 'sdfsdf', 'dsfsd');

-- ----------------------------
-- Table structure for `tblnovels`
-- ----------------------------
DROP TABLE IF EXISTS `tblnovels`;
CREATE TABLE `tblnovels` (
  `novel_ID` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `isbn` varchar(200) NOT NULL,
  `author_ID` int(200) NOT NULL,
  PRIMARY KEY (`novel_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tblnovels
-- ----------------------------
INSERT INTO tblnovels VALUES ('1', 'Harry Potter', 'HJSGBSAJHDGS456', '1');
INSERT INTO tblnovels VALUES ('2', 'Long Walk', 'HJGhjJHGJH6556', '2');
INSERT INTO tblnovels VALUES ('3', 'Placess', 'sdfhlskdjh', '1');
INSERT INTO tblnovels VALUES ('4', 'Blaah', 'sdlfkjk', '4');
INSERT INTO tblnovels VALUES ('5', 'Meeh', 'hghjgh', '5');

-- ----------------------------
-- Table structure for `tbl_messages`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_messages`;
CREATE TABLE `tbl_messages` (
  `sender_id` int(100) NOT NULL,
  `recepient_id` int(100) NOT NULL,
  `message` varchar(100) NOT NULL,
  `read` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_messages
-- ----------------------------

-- ----------------------------
-- Table structure for `tbl_users`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `pid` int(100) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_users
-- ----------------------------
INSERT INTO tbl_users VALUES ('mike@gmail.com', 'mike123', '1');
INSERT INTO tbl_users VALUES ('jay@yahoo.com', 'jay33', '2');
INSERT INTO tbl_users VALUES ('pom@iol.co.za', 'p990', '3');

-- ----------------------------
-- Procedure structure for `proc_selectAll`
-- ----------------------------
DROP PROCEDURE IF EXISTS `proc_selectAll`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_selectAll`(IN `id` int)
BEGIN
	#Routine body goes here...
 select * from tblnovels where id=id;
END
;;
DELIMITER ;
