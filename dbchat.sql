/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50527
Source Host           : localhost:3306
Source Database       : dbchat

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2016-04-28 00:50:25
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `tbl_messages`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_messages`;
CREATE TABLE `tbl_messages` (
  `sender_id` int(100) NOT NULL,
  `recepient_id` int(100) NOT NULL,
  `message` varchar(100) NOT NULL,
  `readYN` int(1) NOT NULL,
  `pid` int(100) NOT NULL AUTO_INCREMENT,
  `timesend` datetime NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_messages
-- ----------------------------
INSERT INTO `tbl_messages` VALUES ('1', '3', 'awee', '1', '53', '2016-04-27 22:56:44');

-- ----------------------------
-- Table structure for `tbl_users`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `pid` int(100) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_users
-- ----------------------------
INSERT INTO `tbl_users` VALUES ('mike@gmail.com', 'mike123', '1');
INSERT INTO `tbl_users` VALUES ('jay@yahoo.com', 'jay33', '2');
INSERT INTO `tbl_users` VALUES ('pom@iol.co.za', 'p990', '3');
