/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `favoriteGroups` */


/* For integrity purposes we keep -most of- the old structure, so we can easily migrate the old favourites */
/* New in version: 2.00.00 */
/* Applied in: sandbox */
CREATE TABLE IF NOT EXISTS `favorite` (
	`id` INTEGER(9) NOT NULL AUTO_INCREMENT ,
	`id_owner` VARCHAR(150) NOT NULL ,
	`id_favorite` VARCHAR(150) NOT NULL ,
	`category` INT NOT NULL DEFAULT 1 COMMENT '1 --> Groups' ,
	`creation_date` DATETIME NOT NULL ,
	`type` INT NOT NULL DEFAULT 1 COMMENT '1 --> User owner (default)  ||  2 --> Organization owner' ,
	`order_pos` int(11) DEFAULT NULL COMMENT 'Order of the default favorite for organizations',
	PRIMARY KEY (`id`) ,
	KEY (`id_owner`) ,
	KEY (`id_favorite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;