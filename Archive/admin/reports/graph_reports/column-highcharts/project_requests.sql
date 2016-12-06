/*
SQLyog Community Edition- MySQL GUI v8.05 
MySQL - 5.5.16-log : Database - bfsdemo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `project_requests` */

CREATE TABLE `project_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(100) DEFAULT NULL,
  `wordpress` int(11) DEFAULT NULL,
  `codeigniter` int(11) DEFAULT NULL,
  `highcharts` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `project_requests` */

insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (1,'Jan',4,5,7);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (2,'Feb',5,2,8);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (3,'Mar',6,3,9);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (4,'Apr',2,6,6);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (5,'May',5,7,7);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (6,'Jun',7,1,10);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (7,'Jul',2,2,9);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (8,'Aug',1,6,7);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (9,'Sep',6,6,6);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (10,'Oct',7,4,9);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (11,'Nov',3,6,8);
insert  into `project_requests`(`id`,`month`,`wordpress`,`codeigniter`,`highcharts`) values (12,'Dec',4,3,4);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;