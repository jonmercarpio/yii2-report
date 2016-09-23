/*
 Navicat Premium Data Transfer

 Source Server         : 200
 Source Server Type    : MySQL
 Source Server Version : 50709
 Source Host           : 186.150.200.131
 Source Database       : mcms

 Target Server Type    : MySQL
 Target Server Version : 50709
 File Encoding         : utf-8

 Date: 05/20/2016 15:54:59 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `report_filter`
-- ----------------------------
DROP TABLE IF EXISTS `report_filter`;
CREATE TABLE `report_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `widget_class` varchar(100) DEFAULT NULL,
  `widget_class_data` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `report_id` (`report_id`),
  CONSTRAINT `report_filter_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `report_report` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `report_report`
-- ----------------------------
DROP TABLE IF EXISTS `report_report`;
CREATE TABLE `report_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `query_select` text NOT NULL,
  `query_from` text NOT NULL,
  `query_where` text,
  `permissions` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
