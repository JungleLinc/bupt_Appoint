/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50709
 Source Host           : localhost
 Source Database       : mavappointdb2s

 Target Server Type    : MySQL
 Target Server Version : 50709
 File Encoding         : utf-8

 Date: 04/22/2018 18:03:51 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `ma_advising_schedule`
-- ----------------------------
DROP TABLE IF EXISTS `ma_advising_schedule`;
CREATE TABLE `ma_advising_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `date` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `studentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userId` (`userId`,`date`,`start`),
  CONSTRAINT `ma_advising_schedule_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `ma_user_advisor` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1615 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_advising_schedule`
-- ----------------------------
BEGIN;
INSERT INTO `ma_advising_schedule` VALUES ('1557', '435', '2018-04-10', '14:00:00', '14:05:00', null), ('1558', '435', '2018-04-10', '14:05:00', '14:10:00', null), ('1559', '435', '2018-04-10', '14:10:00', '14:15:00', null), ('1560', '435', '2018-04-10', '14:15:00', '14:20:00', null), ('1561', '435', '2018-04-10', '14:20:00', '14:25:00', null), ('1562', '435', '2018-04-10', '14:25:00', '14:30:00', null), ('1563', '435', '2018-04-10', '14:30:00', '14:35:00', null), ('1564', '435', '2018-04-10', '14:35:00', '14:40:00', null), ('1565', '435', '2018-04-10', '14:40:00', '14:45:00', null), ('1566', '435', '2018-04-10', '14:45:00', '14:50:00', null), ('1567', '435', '2018-04-10', '14:50:00', '14:55:00', null), ('1568', '435', '2018-04-10', '14:55:00', '15:00:00', null), ('1569', '435', '2018-04-17', '14:00:00', '14:05:00', null), ('1570', '435', '2018-04-17', '14:05:00', '14:10:00', null), ('1571', '435', '2018-04-17', '14:10:00', '14:15:00', null), ('1572', '435', '2018-04-17', '14:15:00', '14:20:00', null), ('1573', '435', '2018-04-17', '14:20:00', '14:25:00', null), ('1574', '435', '2018-04-17', '14:25:00', '14:30:00', null), ('1575', '435', '2018-04-17', '14:30:00', '14:35:00', null), ('1576', '435', '2018-04-17', '14:35:00', '14:40:00', null), ('1577', '435', '2018-04-17', '14:40:00', '14:45:00', null), ('1578', '435', '2018-04-17', '14:45:00', '14:50:00', null), ('1579', '435', '2018-04-17', '14:50:00', '14:55:00', null), ('1580', '435', '2018-04-17', '14:55:00', '15:00:00', null), ('1581', '435', '2018-04-12', '11:00:00', '11:05:00', null), ('1582', '435', '2018-04-12', '11:05:00', '11:10:00', null), ('1583', '435', '2018-04-12', '11:10:00', '11:15:00', null), ('1584', '435', '2018-04-12', '11:15:00', '11:20:00', null), ('1585', '435', '2018-04-12', '11:20:00', '11:25:00', null), ('1586', '435', '2018-04-12', '11:25:00', '11:30:00', null);
COMMIT;

-- ----------------------------
--  Table structure for `ma_appointment_types`
-- ----------------------------
DROP TABLE IF EXISTS `ma_appointment_types`;
CREATE TABLE `ma_appointment_types` (
  `userId` int(11) NOT NULL,
  `type` varchar(45) NOT NULL,
  `duration` int(11) NOT NULL,
  PRIMARY KEY (`userId`,`type`),
  UNIQUE KEY `userId` (`userId`,`type`),
  CONSTRAINT `ma_appointment_types_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `ma_user_advisor` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_appointment_types`
-- ----------------------------
BEGIN;
INSERT INTO `ma_appointment_types` VALUES ('435', 'Other', '10'), ('435', '课程相关', '10'), ('435', '选课', '10'), ('438', 'Other', '10');
COMMIT;

-- ----------------------------
--  Table structure for `ma_appointments`
-- ----------------------------
DROP TABLE IF EXISTS `ma_appointments`;
CREATE TABLE `ma_appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advisorUserId` int(11) NOT NULL,
  `studentUserId` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `type` varchar(45) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `studentId` int(11) NOT NULL,
  `studentEmail` varchar(50) DEFAULT NULL,
  `studentCell` varchar(20) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `isCanceledBy` varchar(11) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advisor_userId` (`advisorUserId`,`type`),
  CONSTRAINT `ma_appointments_ibfk_1` FOREIGN KEY (`advisorUserId`) REFERENCES `ma_user_advisor` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ma_appointments_ibfk_2` FOREIGN KEY (`advisorUserId`, `type`) REFERENCES `ma_appointment_types` (`userId`, `type`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1042 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_appointments`
-- ----------------------------
BEGIN;
INSERT INTO `ma_appointments` VALUES ('1030', '435', '436', '2018-04-10', '14:20:00', '14:30:00', '课程相关', 'test', '2015140635', '301091063@qq.com', '13805413333', '-1', 'student', '学生自己取消'), ('1031', '435', '436', '2018-04-10', '14:40:00', '14:50:00', '选课', 'test2', '2015140635', '301091063@qq.com', '13805413333', '-1', 'student', '学生自己取消'), ('1032', '435', '436', '2018-04-23', '13:10:00', '13:20:00', '选课', '', '2015140635', '301091063@qq.com', '13805413333', '-1', 'student', '没时间'), ('1033', '435', '436', '2018-04-23', '13:10:00', '13:20:00', '选课', '111', '2015140635', '301091063@qq.com', '13805413333', '-1', 'student', 'none'), ('1034', '435', '436', '2018-04-23', '13:00:00', '13:10:00', '课程相关', '222', '2015140635', '301091063@qq.com', '13805413333', '-1', 'student', '1'), ('1035', '435', '436', '2018-04-23', '13:00:00', '13:10:00', '选课', '2', '2015140635', '301091063@qq.com', '13805413333', '-1', 'student', 's'), ('1036', '435', '436', '2018-04-23', '13:00:00', '13:10:00', '选课', '5', '2015140635', '301091063@qq.com', '13805413333', '-1', 'advisor', '有事'), ('1037', '435', '436', '2018-04-23', '13:10:00', '13:20:00', '课程相关', '人人', '2015140635', '301091063@qq.com', '13805413333', '-1', 'student', 's'), ('1038', '435', '436', '2018-04-23', '13:10:00', '13:20:00', '选课', 'e', '2015140635', '301091063@qq.com', '13805413333', '-1', 'advisor', '有事'), ('1039', '435', '436', '2018-04-23', '14:30:00', '14:40:00', '选课', '123', '2015140635', '301091063@qq.com', '13805413333', '-1', 'advisor', '教师手动删除'), ('1040', '435', '436', '2018-04-23', '14:50:00', '15:00:00', '选课', '44', '2015140635', '301091063@qq.com', '13805413333', '-1', 'advisor', '教师手动删除'), ('1041', '438', '439', '2018-04-25', '10:20:00', '10:30:00', 'Other', '2', '2015130428', 'linc0722@gmail.com', '19482756362', '-1', 'admin', '管理员删除操作');
COMMIT;

-- ----------------------------
--  Table structure for `ma_department`
-- ----------------------------
DROP TABLE IF EXISTS `ma_department`;
CREATE TABLE `ma_department` (
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_department`
-- ----------------------------
BEGIN;
INSERT INTO `ma_department` VALUES ('CSE'), ('MATH'), ('软件学院');
COMMIT;

-- ----------------------------
--  Table structure for `ma_department_user`
-- ----------------------------
DROP TABLE IF EXISTS `ma_department_user`;
CREATE TABLE `ma_department_user` (
  `name` varchar(45) NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`name`,`userId`),
  UNIQUE KEY `userId` (`userId`,`name`),
  CONSTRAINT `ma_department_user_ibfk_1` FOREIGN KEY (`name`) REFERENCES `ma_department` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ma_department_user_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `ma_user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_department_user`
-- ----------------------------
BEGIN;
INSERT INTO `ma_department_user` VALUES ('软件学院', '435'), ('软件学院', '436'), ('软件学院', '439');
COMMIT;

-- ----------------------------
--  Table structure for `ma_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `ma_feedback`;
CREATE TABLE `ma_feedback` (
  `fid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `targetId` int(11) unsigned NOT NULL,
  `type` varchar(127) NOT NULL,
  `title` varchar(127) NOT NULL,
  `content` text NOT NULL,
  `isHandled` int(11) NOT NULL,
  PRIMARY KEY (`fid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_feedback`
-- ----------------------------
BEGIN;
INSERT INTO `ma_feedback` VALUES ('1', '300', '200', 'system', 'test title', 'test content', '1'), ('2', '412', '103', 'Advising', 'sss', 'ss1', '1'), ('12', '414', '103', 'advisor', 'title', 'content', '0'), ('13', '414', '200', 'system', 'title', 'content', '0'), ('14', '414', '103', 'advisor', 'title', 'content', '0'), ('15', '414', '200', 'system', 'title', 'content', '0'), ('16', '414', '103', 'advisor', 'title', 'content', '0'), ('17', '414', '200', 'system', 'title', 'content', '0'), ('18', '414', '103', 'advisor', 'title', 'content', '0'), ('19', '414', '200', 'system', 'title', 'content', '0'), ('20', '414', '103', 'advisor', 'title', 'content', '0'), ('21', '414', '200', 'system', 'title', 'content', '0'), ('22', '414', '103', 'advisor', 'title', 'content', '0'), ('23', '414', '200', 'system', 'title', 'content', '0'), ('24', '414', '103', 'advisor', 'title', 'content', '0'), ('25', '414', '200', 'system', 'title', 'content', '0'), ('26', '414', '103', 'advisor', 'title', 'content', '0'), ('27', '414', '200', 'system', 'title', 'content', '0'), ('28', '414', '103', 'advisor', 'title', 'content', '0'), ('29', '414', '200', 'system', 'title', 'content', '0'), ('30', '414', '103', 'advisor', 'title', 'content', '0'), ('31', '414', '200', 'system', 'title', 'content', '0'), ('32', '414', '103', 'advisor', 'title', 'content', '0'), ('33', '414', '200', 'system', 'title', 'content', '0'), ('34', '414', '103', 'advisor', 'title', 'content', '0'), ('35', '414', '200', 'system', 'title', 'content', '0'), ('36', '414', '103', 'advisor', 'title', 'content', '0'), ('37', '414', '200', 'system', 'title', 'content', '0'), ('38', '414', '103', 'advisor', 'title', 'content', '0'), ('39', '414', '200', 'system', 'title', 'content', '0'), ('40', '414', '103', 'advisor', 'title', 'content', '0'), ('41', '414', '200', 'system', 'title', 'content', '0'), ('42', '414', '103', 'advisor', 'title', 'content', '0'), ('43', '414', '200', 'system', 'title', 'content', '0'), ('44', '414', '103', 'advisor', 'title', 'content', '0'), ('45', '414', '200', 'system', 'title', 'content', '0'), ('46', '414', '103', 'advisor', 'title', 'content', '0'), ('47', '414', '200', 'system', 'title', 'content', '0'), ('48', '414', '103', 'advisor', 'title', 'content', '0'), ('49', '414', '200', 'system', 'title', 'content', '0'), ('50', '414', '103', 'advisor', 'title', 'content', '0'), ('51', '414', '200', 'system', 'title', 'content', '0'), ('52', '414', '103', 'advisor', 'title', 'content', '0'), ('53', '414', '200', 'system', 'title', 'content', '0'), ('54', '414', '103', 'advisor', 'title', 'content', '0'), ('55', '414', '200', 'system', 'title', 'content', '0'), ('56', '414', '103', 'advisor', 'title', 'content', '0'), ('57', '414', '200', 'system', 'title', 'content', '0'), ('58', '414', '103', 'advisor', 'title', 'content', '0'), ('59', '414', '200', 'system', 'title', 'content', '0'), ('60', '414', '103', 'advisor', 'title', 'content', '0'), ('61', '414', '200', 'system', 'title', 'content', '0'), ('62', '414', '103', 'advisor', 'title', 'content', '0'), ('63', '414', '200', 'system', 'title', 'content', '0'), ('64', '414', '103', 'advisor', 'title', 'content', '0'), ('65', '414', '200', 'system', 'title', 'content', '0'), ('66', '414', '103', 'advisor', 'title', 'content', '0'), ('67', '414', '200', 'system', 'title', 'content', '1'), ('68', '414', '103', 'advisor', 'title', 'content', '0'), ('69', '414', '200', 'system', 'title', 'content', '0'), ('70', '414', '103', 'advisor', 'title', 'content', '0'), ('71', '414', '200', 'system', 'title', 'content', '1'), ('73', '414', '103', 'Advising', '测试1', '测试1', '0');
COMMIT;

-- ----------------------------
--  Table structure for `ma_major`
-- ----------------------------
DROP TABLE IF EXISTS `ma_major`;
CREATE TABLE `ma_major` (
  `name` varchar(45) NOT NULL,
  `depName` varchar(45) NOT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`),
  KEY `dep_name` (`depName`),
  CONSTRAINT `ma_major_ibfk_1` FOREIGN KEY (`depName`) REFERENCES `ma_department` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_major`
-- ----------------------------
BEGIN;
INSERT INTO `ma_major` VALUES ('Computer Engineering', 'CSE'), ('Computer Science', 'CSE'), ('Software Engineering', 'CSE'), ('Mathematics', 'MATH'), ('信息工程', '软件学院'), ('软件工程', '软件学院');
COMMIT;

-- ----------------------------
--  Table structure for `ma_major_user`
-- ----------------------------
DROP TABLE IF EXISTS `ma_major_user`;
CREATE TABLE `ma_major_user` (
  `name` varchar(45) NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`name`,`userId`),
  UNIQUE KEY `userId` (`userId`,`name`),
  CONSTRAINT `ma_major_user_ibfk_1` FOREIGN KEY (`name`) REFERENCES `ma_major` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ma_major_user_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `ma_user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_major_user`
-- ----------------------------
BEGIN;
INSERT INTO `ma_major_user` VALUES ('Software Engineering', '435'), ('信息工程', '439'), ('软件工程', '436');
COMMIT;

-- ----------------------------
--  Table structure for `ma_tempassword_expiration`
-- ----------------------------
DROP TABLE IF EXISTS `ma_tempassword_expiration`;
CREATE TABLE `ma_tempassword_expiration` (
  `time` int(11) NOT NULL,
  PRIMARY KEY (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_tempassword_expiration`
-- ----------------------------
BEGIN;
INSERT INTO `ma_tempassword_expiration` VALUES ('9');
COMMIT;

-- ----------------------------
--  Table structure for `ma_user`
-- ----------------------------
DROP TABLE IF EXISTS `ma_user`;
CREATE TABLE `ma_user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `netId` varchar(20) DEFAULT NULL,
  `role` varchar(32) DEFAULT NULL,
  `validated` tinyint(4) NOT NULL DEFAULT '0',
  `lastModDate` date DEFAULT NULL,
  `sendTemPWDate` date DEFAULT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_user`
-- ----------------------------
BEGIN;
INSERT INTO `ma_user` VALUES ('65', 'admin@uta.edu', '5f4dcc3b5aa765d61d8327deb882cf99', null, 'admin', '1', '2018-02-07', null), ('435', 'gaolin@bupt.edu.cn', '5f4dcc3b5aa765d61d8327deb882cf99', null, 'advisor', '1', '2018-04-08', '2018-04-08'), ('436', '301091063@qq.com', '5f4dcc3b5aa765d61d8327deb882cf99', null, 'student', '1', '2018-04-08', '2018-04-08'), ('438', '1218544410@qq.com', '5f4dcc3b5aa765d61d8327deb882cf99', null, 'advisor', '1', '2018-04-21', '2018-04-21'), ('439', 'linc0722@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', null, 'student', '1', '2018-04-21', '2018-04-21');
COMMIT;

-- ----------------------------
--  Table structure for `ma_user_advisor`
-- ----------------------------
DROP TABLE IF EXISTS `ma_user_advisor`;
CREATE TABLE `ma_user_advisor` (
  `userId` int(11) NOT NULL,
  `pName` varchar(32) NOT NULL,
  `notification` varchar(45) NOT NULL,
  `nameLow` varchar(2) NOT NULL,
  `nameHigh` varchar(2) NOT NULL,
  `degreeTypes` int(11) NOT NULL,
  `cutOffTime` varchar(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userId` (`userId`),
  CONSTRAINT `ma_user_advisor_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `ma_user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_user_advisor`
-- ----------------------------
BEGIN;
INSERT INTO `ma_user_advisor` VALUES ('435', '高林', 'yes', 'a', 'Z', '7', '0'), ('438', '李萌', 'yes', 'a', 'Z', '7', '0');
COMMIT;

-- ----------------------------
--  Table structure for `ma_user_student`
-- ----------------------------
DROP TABLE IF EXISTS `ma_user_student`;
CREATE TABLE `ma_user_student` (
  `userId` int(11) NOT NULL,
  `studentId` int(11) NOT NULL,
  `degreeType` int(11) NOT NULL,
  `phoneNum` varchar(45) NOT NULL,
  `notification` varchar(45) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userId` (`userId`),
  CONSTRAINT `ma_user_student_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `ma_user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ma_user_student`
-- ----------------------------
BEGIN;
INSERT INTO `ma_user_student` VALUES ('436', '2015140635', '2', '13805413333', 'yes', '腾耀', '李'), ('439', '2015130428', '4', '19482756362', 'yes', '州', '龙');
COMMIT;

-- ----------------------------
--  Table structure for `ma_wait_list_schedule`
-- ----------------------------
DROP TABLE IF EXISTS `ma_wait_list_schedule`;
CREATE TABLE `ma_wait_list_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aptId` int(11) DEFAULT NULL,
  `studentUserId` int(11) DEFAULT NULL,
  `studentId` int(11) DEFAULT NULL,
  `aptType` varchar(45) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `studentEmail` varchar(50) DEFAULT NULL,
  `studentCell` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aptId` (`aptId`),
  CONSTRAINT `ma_wait_list_schedule_ibfk_1` FOREIGN KEY (`aptId`) REFERENCES `ma_appointments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
