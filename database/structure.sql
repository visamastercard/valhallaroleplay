
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `accounts`
-- ----------------------------
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`username`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`password`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`registerdate`  datetime NULL DEFAULT NULL ,
`lastlogin`  datetime NULL DEFAULT NULL ,
`ip`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`admin`  tinyint(3) NULL DEFAULT 0 ,
`hiddenadmin`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`adminduty`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`adminjail`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`adminjail_time`  int(11) NULL DEFAULT NULL ,
`adminjail_by`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`adminjail_reason`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`banned`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`banned_by`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`banned_reason`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`muted`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`globalooc`  tinyint(3) UNSIGNED NULL DEFAULT 1 ,
`country`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`friendsmessage`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`adminjail_permanent`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`adminreports`  int(11) NULL DEFAULT 0 ,
`warns`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`chatbubbles`  tinyint(3) UNSIGNED NOT NULL DEFAULT 1 ,
`adminnote`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`appstate`  tinyint(1) NULL DEFAULT 0 ,
`appdatetime`  datetime NULL DEFAULT NULL ,
`appreason`  longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`email`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`help`  int(1) NOT NULL DEFAULT 1 ,
`adblocked`  int(1) NOT NULL DEFAULT 0 ,
`newsblocked`  int(1) NOT NULL DEFAULT 0 ,
`mtaserial`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`d_addiction`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`loginhash`  varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`credits`  int(11) NULL DEFAULT 0 ,
`transfers`  int(11) NULL DEFAULT 0 ,
`monitored`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'New Player',
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `adminhistory`
-- ----------------------------
DROP TABLE IF EXISTS `adminhistory`;
CREATE TABLE `adminhistory` (
`id`  int(10) UNSIGNED NOT NULL ,
`user`  int(10) UNSIGNED NOT NULL ,
`user_char`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`admin`  int(10) UNSIGNED NOT NULL ,
`admin_char`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`date`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`action`  tinyint(3) UNSIGNED NOT NULL ,
`duration`  int(10) UNSIGNED NOT NULL ,
`reason`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`hiddenadmin`  tinyint(3) UNSIGNED NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `apb`
-- ----------------------------
DROP TABLE IF EXISTS `apb`;
CREATE TABLE `apb` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`description`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`doneby`  int(11) NOT NULL ,
`time`  datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `applications`
-- ----------------------------
DROP TABLE IF EXISTS `applications`;
CREATE TABLE `applications` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`accountID`  int(11) NOT NULL ,
`dateposted`  datetime NOT NULL ,
`content`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`datereviewed`  datetime NULL DEFAULT NULL ,
`adminID`  int(11) NULL DEFAULT NULL ,
`adminNote`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`adminAction`  int(11) NOT NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `atms`
-- ----------------------------
DROP TABLE IF EXISTS `atms`;
CREATE TABLE `atms` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`x`  decimal(10,6) NULL DEFAULT 0.000000 ,
`y`  decimal(10,6) NULL DEFAULT 0.000000 ,
`z`  decimal(10,6) NULL DEFAULT 0.000000 ,
`rotation`  decimal(10,6) NULL DEFAULT 0.000000 ,
`dimension`  int(5) NULL DEFAULT 0 ,
`interior`  int(5) NULL DEFAULT 0 ,
`deposit`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`limit`  int(10) UNSIGNED NULL DEFAULT 5000,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `characters`
-- ----------------------------
DROP TABLE IF EXISTS `characters`;
CREATE TABLE `characters` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`charactername`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`account`  int(11) NULL DEFAULT 0 ,
`x`  float NULL DEFAULT 1742.19 ,
`y`  float NULL DEFAULT '-1861.36' ,
`z`  float NULL DEFAULT 13.5776 ,
`rotation`  float NULL DEFAULT 0.986053 ,
`interior_id`  int(5) NULL DEFAULT 0 ,
`dimension_id`  int(5) NULL DEFAULT 0 ,
`health`  float NULL DEFAULT 100 ,
`armor`  float NULL DEFAULT 0 ,
`skin`  int(3) NULL DEFAULT 264 ,
`money`  bigint(20) NULL DEFAULT 250 ,
`gender`  int(1) NULL DEFAULT 0 ,
`cuffed`  int(11) NULL DEFAULT 0 ,
`duty`  int(3) NULL DEFAULT 0 ,
`cellnumber`  int(30) NULL DEFAULT 0 ,
`fightstyle`  int(2) NULL DEFAULT 4 ,
`pdjail`  int(1) NULL DEFAULT 0 ,
`pdjail_time`  int(11) NULL DEFAULT 0 ,
`job`  int(3) NULL DEFAULT 0 ,
`cked`  int(1) NULL DEFAULT 0 ,
`lastarea`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`age`  int(3) NULL DEFAULT 18 ,
`faction_id`  int(11) NULL DEFAULT '-1' ,
`faction_rank`  int(2) NULL DEFAULT 1 ,
`faction_perks`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`skincolor`  int(1) NULL DEFAULT 0 ,
`weight`  int(3) NULL DEFAULT 180 ,
`height`  int(3) NULL DEFAULT 180 ,
`description`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`deaths`  int(11) NULL DEFAULT 0 ,
`faction_leader`  int(1) NULL DEFAULT 0 ,
`fingerprint`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`casualskin`  int(3) NULL DEFAULT 0 ,
`bankmoney`  bigint(20) NULL DEFAULT 500 ,
`car_license`  int(1) NULL DEFAULT 0 ,
`gun_license`  int(1) NULL DEFAULT 0 ,
`tag`  int(3) NULL DEFAULT 1 ,
`hoursplayed`  int(11) NULL DEFAULT 0 ,
`pdjail_station`  int(1) NULL DEFAULT 0 ,
`timeinserver`  int(2) NULL DEFAULT 0 ,
`restrainedobj`  int(11) NULL DEFAULT 0 ,
`restrainedby`  int(11) NULL DEFAULT 0 ,
`dutyskin`  int(3) NULL DEFAULT '-1' ,
`fish`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`truckingruns`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`truckingwage`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`blindfold`  tinyint(4) NOT NULL DEFAULT 0 ,
`lang1`  tinyint(2) NULL DEFAULT 1 ,
`lang1skill`  tinyint(3) NULL DEFAULT 100 ,
`lang2`  tinyint(2) NULL DEFAULT 0 ,
`lang2skill`  tinyint(3) NULL DEFAULT 0 ,
`lang3`  tinyint(2) NULL DEFAULT 0 ,
`lang3skill`  tinyint(3) NULL DEFAULT 0 ,
`currlang`  tinyint(1) NULL DEFAULT 1 ,
`lastlogin`  datetime NULL DEFAULT NULL ,
`creationdate`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`election_candidate`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`election_canvote`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`election_votedfor`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`jobcontract`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`marriedto`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`photos`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`maxvehicles`  int(4) UNSIGNED NOT NULL DEFAULT 5 ,
`ck_info`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`alcohollevel`  float NOT NULL DEFAULT 0 ,
`active`  tinyint(1) UNSIGNED NOT NULL DEFAULT 1 ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `computers`
-- ----------------------------
DROP TABLE IF EXISTS `computers`;
CREATE TABLE `computers` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`posX`  float(10,5) NOT NULL ,
`posY`  float(10,5) NOT NULL ,
`posZ`  float(10,5) NOT NULL ,
`rotX`  float(10,5) NOT NULL ,
`rotY`  float(10,5) NOT NULL ,
`rotZ`  float(10,5) NOT NULL ,
`interior`  int(8) NOT NULL ,
`dimension`  int(8) NOT NULL ,
`model`  int(8) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `dancers`
-- ----------------------------
DROP TABLE IF EXISTS `dancers`;
CREATE TABLE `dancers` (
`id`  int(10) UNSIGNED NOT NULL ,
`x`  float NOT NULL ,
`y`  float NOT NULL ,
`z`  float NOT NULL ,
`rotation`  float NOT NULL ,
`skin`  smallint(5) UNSIGNED NOT NULL ,
`type`  tinyint(3) UNSIGNED NOT NULL ,
`interior`  int(10) UNSIGNED NOT NULL ,
`dimension`  int(10) UNSIGNED NOT NULL ,
`offset`  tinyint(3) UNSIGNED NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `don_transaction_failed`
-- ----------------------------
DROP TABLE IF EXISTS `don_transaction_failed`;
CREATE TABLE `don_transaction_failed` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`output`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`ip`  varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `don_transactions`
-- ----------------------------
DROP TABLE IF EXISTS `don_transactions`;
CREATE TABLE `don_transactions` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`transaction_id`  varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`donator_email`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`amount`  double NOT NULL ,
`original_request`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`dt`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP ,
`handled`  smallint(1) NULL DEFAULT 0 ,
`username`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`realamount`  double NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `donators`
-- ----------------------------
DROP TABLE IF EXISTS `donators`;
CREATE TABLE `donators` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`accountID`  int(11) NOT NULL ,
`charID`  int(11) NOT NULL DEFAULT '-1' ,
`perkID`  int(4) NOT NULL ,
`perkValue`  varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1' ,
`expirationDate`  datetime NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `elevators`
-- ----------------------------
DROP TABLE IF EXISTS `elevators`;
CREATE TABLE `elevators` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`x`  decimal(10,6) NULL DEFAULT 0.000000 ,
`y`  decimal(10,6) NULL DEFAULT 0.000000 ,
`z`  decimal(10,6) NULL DEFAULT 0.000000 ,
`tpx`  decimal(10,6) NULL DEFAULT 0.000000 ,
`tpy`  decimal(10,6) NULL DEFAULT 0.000000 ,
`tpz`  decimal(10,6) NULL DEFAULT 0.000000 ,
`dimensionwithin`  int(5) NULL DEFAULT 0 ,
`interiorwithin`  int(5) NULL DEFAULT 0 ,
`dimension`  int(5) NULL DEFAULT 0 ,
`interior`  int(5) NULL DEFAULT 0 ,
`car`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`disabled`  tinyint(3) UNSIGNED NULL DEFAULT 0,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `emailaccounts`
-- ----------------------------
DROP TABLE IF EXISTS `emailaccounts`;
CREATE TABLE `emailaccounts` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`username`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`password`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`creator`  int(11) NULL DEFAULT NULL,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `emails`
-- ----------------------------
DROP TABLE IF EXISTS `emails`;
CREATE TABLE `emails` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`date`  datetime NOT NULL ,
`sender`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`receiver`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`subject`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`message`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`inbox`  int(1) NOT NULL DEFAULT 0 ,
`outbox`  int(1) NOT NULL DEFAULT 0 ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `factions`
-- ----------------------------
DROP TABLE IF EXISTS `factions`;
CREATE TABLE `factions` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`bankbalance`  bigint(20) NULL DEFAULT NULL ,
`type`  int(11) NULL DEFAULT NULL ,
`rank_1`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_2`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_3`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_4`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_5`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_6`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_7`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_8`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_9`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_10`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_11`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_12`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_13`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_14`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_15`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_16`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_17`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_18`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_19`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`rank_20`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`wage_1`  int(11) NULL DEFAULT 100 ,
`wage_2`  int(11) NULL DEFAULT 100 ,
`wage_3`  int(11) NULL DEFAULT 100 ,
`wage_4`  int(11) NULL DEFAULT 100 ,
`wage_5`  int(11) NULL DEFAULT 100 ,
`wage_6`  int(11) NULL DEFAULT 100 ,
`wage_7`  int(11) NULL DEFAULT 100 ,
`wage_8`  int(11) NULL DEFAULT 100 ,
`wage_9`  int(11) NULL DEFAULT 100 ,
`wage_10`  int(11) NULL DEFAULT 100 ,
`wage_11`  int(11) NULL DEFAULT 100 ,
`wage_12`  int(11) NULL DEFAULT 100 ,
`wage_13`  int(11) NULL DEFAULT 100 ,
`wage_14`  int(11) NULL DEFAULT 100 ,
`wage_15`  int(11) NULL DEFAULT 100 ,
`wage_16`  int(11) NULL DEFAULT 100 ,
`wage_17`  int(11) NULL DEFAULT 100 ,
`wage_18`  int(11) NULL DEFAULT 100 ,
`wage_19`  int(11) NULL DEFAULT 100 ,
`wage_20`  int(11) NULL DEFAULT 100 ,
`motd`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `forgotdetails`
-- ----------------------------
DROP TABLE IF EXISTS `forgotdetails`;
CREATE TABLE `forgotdetails` (
`uniquekey`  varchar(32) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`account`  int(11) NULL DEFAULT 0 ,
`keytimestamp`  timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `friends`
-- ----------------------------
DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
`id`  int(10) UNSIGNED NOT NULL ,
`friend`  int(10) UNSIGNED NOT NULL ,
PRIMARY KEY (`id`, `friend`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `fuelpeds`
-- ----------------------------
DROP TABLE IF EXISTS `fuelpeds`;
CREATE TABLE `fuelpeds` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`posX`  float NOT NULL ,
`posY`  float NOT NULL ,
`posZ`  float NOT NULL ,
`rotZ`  float NOT NULL ,
`skin`  int(3) NOT NULL ,
`priceratio`  int(3) NOT NULL ,
`name`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `fuelstations`
-- ----------------------------
DROP TABLE IF EXISTS `fuelstations`;
CREATE TABLE `fuelstations` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`x`  decimal(10,6) NULL DEFAULT 0.000000 ,
`y`  decimal(10,6) NULL DEFAULT 0.000000 ,
`z`  decimal(10,6) NULL DEFAULT 0.000000 ,
`interior`  int(5) NULL DEFAULT 0 ,
`dimension`  int(5) NULL DEFAULT 0 ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `gates`
-- ----------------------------
DROP TABLE IF EXISTS `gates`;
CREATE TABLE `gates` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`objectID`  int(11) NOT NULL ,
`startX`  float NOT NULL ,
`startY`  float NOT NULL ,
`startZ`  float NOT NULL ,
`startRX`  float NOT NULL ,
`startRY`  float NOT NULL ,
`startRZ`  float NOT NULL ,
`endX`  float NOT NULL ,
`endY`  float NOT NULL ,
`endZ`  float NOT NULL ,
`endRX`  float NOT NULL ,
`endRY`  float NOT NULL ,
`endRZ`  float NOT NULL ,
`gateType`  tinyint(3) UNSIGNED NOT NULL ,
`autocloseTime`  int(4) NOT NULL ,
`movementTime`  int(4) NOT NULL ,
`objectDimension`  int(11) NOT NULL ,
`objectInterior`  int(11) NOT NULL ,
`gateSecurityParameters`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `interiors`
-- ----------------------------
DROP TABLE IF EXISTS `interiors`;
CREATE TABLE `interiors` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`x`  float NULL DEFAULT 0 ,
`y`  float NULL DEFAULT 0 ,
`z`  float NULL DEFAULT 0 ,
`type`  int(1) NULL DEFAULT 0 ,
`owner`  int(11) NULL DEFAULT '-1' ,
`locked`  int(1) NULL DEFAULT 0 ,
`cost`  int(11) NULL DEFAULT 0 ,
`name`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`interior`  int(5) NULL DEFAULT 0 ,
`interiorx`  float NULL DEFAULT 0 ,
`interiory`  float NULL DEFAULT 0 ,
`interiorz`  float NULL DEFAULT 0 ,
`dimensionwithin`  int(5) NULL DEFAULT 0 ,
`interiorwithin`  int(5) NULL DEFAULT 0 ,
`angle`  float NULL DEFAULT 0 ,
`angleexit`  float NULL DEFAULT 0 ,
`supplies`  int(11) NULL DEFAULT 100 ,
`safepositionX`  float NULL DEFAULT NULL ,
`safepositionY`  float NULL DEFAULT NULL ,
`safepositionZ`  float NULL DEFAULT NULL ,
`safepositionRZ`  float NULL DEFAULT NULL ,
`fee`  int(10) UNSIGNED NULL DEFAULT 0 ,
`disabled`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`lastused`  datetime NULL DEFAULT NULL ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `items`
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
`index`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`type`  tinyint(3) UNSIGNED NOT NULL ,
`owner`  int(10) UNSIGNED NOT NULL ,
`itemID`  int(10) NOT NULL ,
`itemValue`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
PRIMARY KEY (`index`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `logtable`
-- ----------------------------
DROP TABLE IF EXISTS `logtable`;
CREATE TABLE `logtable` (
`time`  datetime NOT NULL ,
`action`  int(2) NOT NULL ,
`source`  varchar(12) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`affected`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`content`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `lottery`
-- ----------------------------
DROP TABLE IF EXISTS `lottery`;
CREATE TABLE `lottery` (
`characterid`  int(255) NOT NULL ,
`ticketnumber`  int(3) NOT NULL 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `mdcusers`
-- ----------------------------
DROP TABLE IF EXISTS `mdcusers`;
CREATE TABLE `mdcusers` (
`user_name`  varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`password`  varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '123' ,
`high_command`  int(1) NULL DEFAULT 0 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `objects`
-- ----------------------------
DROP TABLE IF EXISTS `objects`;
CREATE TABLE `objects` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`model`  int(6) NOT NULL DEFAULT 0 ,
`posX`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`posY`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`posZ`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`rotX`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`rotY`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`rotZ`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`interior`  int(5) NOT NULL ,
`dimension`  int(5) NOT NULL ,
`comment`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`solid`  int(1) NOT NULL DEFAULT 1 ,
`doublesided`  int(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `paynspray`
-- ----------------------------
DROP TABLE IF EXISTS `paynspray`;
CREATE TABLE `paynspray` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`x`  decimal(10,6) NULL DEFAULT 0.000000 ,
`y`  decimal(10,6) NULL DEFAULT 0.000000 ,
`z`  decimal(10,6) NULL DEFAULT 0.000000 ,
`dimension`  int(5) NULL DEFAULT 0 ,
`interior`  int(5) NULL DEFAULT 0 ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `phone_contacts`
-- ----------------------------
DROP TABLE IF EXISTS `phone_contacts`;
CREATE TABLE `phone_contacts` (
`phone`  int(11) NOT NULL ,
`entryName`  varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`entryNumber`  int(11) NOT NULL 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `phone_settings`
-- ----------------------------
DROP TABLE IF EXISTS `phone_settings`;
CREATE TABLE `phone_settings` (
`phonenumber`  int(1) NOT NULL ,
`turnedon`  smallint(1) UNSIGNED NOT NULL DEFAULT 1 ,
`secretnumber`  smallint(1) UNSIGNED NOT NULL DEFAULT 0 ,
`ringtone`  smallint(1) NOT NULL DEFAULT 1 ,
`phonebook`  varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`boughtby`  int(11) NOT NULL DEFAULT '-1' ,
PRIMARY KEY (`phonenumber`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `publicphones`
-- ----------------------------
DROP TABLE IF EXISTS `publicphones`;
CREATE TABLE `publicphones` (
`id`  int(10) UNSIGNED NOT NULL ,
`x`  float NOT NULL ,
`y`  float NOT NULL ,
`z`  float NOT NULL ,
`dimension`  int(10) UNSIGNED NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`value`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `shops`
-- ----------------------------
DROP TABLE IF EXISTS `shops`;
CREATE TABLE `shops` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`x`  float NULL DEFAULT 0 ,
`y`  float NULL DEFAULT 0 ,
`z`  float NULL DEFAULT 0 ,
`dimension`  int(5) NULL DEFAULT 0 ,
`interior`  int(5) NULL DEFAULT 0 ,
`shoptype`  tinyint(4) NULL DEFAULT 0 ,
`rotation`  float NOT NULL DEFAULT 0 ,
`skin`  int(11) NULL DEFAULT '-1' ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `speedcams`
-- ----------------------------
DROP TABLE IF EXISTS `speedcams`;
CREATE TABLE `speedcams` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`x`  float(11,7) NOT NULL DEFAULT 0.0000000 ,
`y`  float(11,7) NOT NULL DEFAULT 0.0000000 ,
`z`  float(11,7) NOT NULL DEFAULT 0.0000000 ,
`interior`  int(3) NOT NULL DEFAULT 0 COMMENT 'Stores the location of the pernament speedcams' ,
`dimension`  int(5) NOT NULL DEFAULT 0 ,
`maxspeed`  int(4) NOT NULL DEFAULT 120 ,
`radius`  int(4) NOT NULL DEFAULT 2 ,
`enabled`  smallint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `speedingviolations`
-- ----------------------------
DROP TABLE IF EXISTS `speedingviolations`;
CREATE TABLE `speedingviolations` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`carID`  int(11) NOT NULL ,
`time`  datetime NOT NULL ,
`speed`  int(5) NOT NULL ,
`area`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`personVisible`  int(11) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `suspectcrime`
-- ----------------------------
DROP TABLE IF EXISTS `suspectcrime`;
CREATE TABLE `suspectcrime` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`suspect_name`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`time`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`date`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`officers`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`ticket`  int(11) NULL DEFAULT NULL ,
`arrest`  int(11) NULL DEFAULT NULL ,
`fine`  int(11) NULL DEFAULT NULL ,
`ticket_price`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`arrest_price`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`fine_price`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`illegal_items`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`details`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`done_by`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `suspectdetails`
-- ----------------------------
DROP TABLE IF EXISTS `suspectdetails`;
CREATE TABLE `suspectdetails` (
`suspect_name`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`birth`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`gender`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`ethnicy`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`cell`  int(5) NULL DEFAULT 0 ,
`occupation`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`address`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`other`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`is_wanted`  int(1) NULL DEFAULT 0 ,
`wanted_reason`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`wanted_punishment`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`wanted_by`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`photo`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`done_by`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `tags`
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`x`  decimal(10,6) NULL DEFAULT NULL ,
`y`  decimal(10,6) NULL DEFAULT NULL ,
`z`  decimal(10,6) NULL DEFAULT NULL ,
`interior`  int(5) NULL DEFAULT NULL ,
`dimension`  int(5) NULL DEFAULT NULL ,
`rx`  decimal(10,6) NULL DEFAULT NULL ,
`ry`  decimal(10,6) NULL DEFAULT NULL ,
`rz`  decimal(10,6) NULL DEFAULT NULL ,
`modelid`  int(5) NULL DEFAULT NULL ,
`creationdate`  datetime NULL DEFAULT NULL ,
`creator`  int(11) NOT NULL DEFAULT '-1',
UNIQUE KEY `id` (`id`) 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `tc_comments`
-- ----------------------------
DROP TABLE IF EXISTS `tc_comments`;
CREATE TABLE `tc_comments` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`poster`  int(11) NOT NULL ,
`ip`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`message`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`posted`  int(25) NOT NULL ,
`type`  int(1) NOT NULL ,
`ticket`  int(11) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `tc_tickets`
-- ----------------------------
DROP TABLE IF EXISTS `tc_tickets`;
CREATE TABLE `tc_tickets` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`creator`  int(11) NOT NULL ,
`posted`  int(25) NOT NULL ,
`subject`  varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`message`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`status`  int(1) NOT NULL ,
`lastpost`  int(25) NOT NULL ,
`assigned`  int(11) NOT NULL ,
`IP`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `tempinteriors`
-- ----------------------------
DROP TABLE IF EXISTS `tempinteriors`;
CREATE TABLE `tempinteriors` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`posX`  float NOT NULL ,
`posY`  float NULL DEFAULT NULL ,
`posZ`  float NULL DEFAULT NULL ,
`interior`  int(5) NULL DEFAULT NULL ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for `tempobjects`
-- ----------------------------
DROP TABLE IF EXISTS `tempobjects`;
CREATE TABLE `tempobjects` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`model`  int(6) NOT NULL DEFAULT 0 ,
`posX`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`posY`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`posZ`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`rotX`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`rotY`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`rotZ`  float(12,7) NOT NULL DEFAULT 0.0000000 ,
`interior`  int(5) NOT NULL ,
`dimension`  int(5) NOT NULL ,
`comment`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`solid`  int(1) NOT NULL DEFAULT 1 ,
`doublesided`  int(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `vehicles`
-- ----------------------------
DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`model`  int(3) NULL DEFAULT 0 ,
`x`  decimal(10,6) NULL DEFAULT 0.000000 ,
`y`  decimal(10,6) NULL DEFAULT 0.000000 ,
`z`  decimal(10,6) NULL DEFAULT 0.000000 ,
`rotx`  decimal(10,6) NULL DEFAULT 0.000000 ,
`roty`  decimal(10,6) NULL DEFAULT 0.000000 ,
`rotz`  decimal(10,6) NULL DEFAULT 0.000000 ,
`currx`  decimal(10,6) NULL DEFAULT 0.000000 ,
`curry`  decimal(10,6) NULL DEFAULT 0.000000 ,
`currz`  decimal(10,6) NULL DEFAULT 0.000000 ,
`currrx`  decimal(10,6) NULL DEFAULT 0.000000 ,
`currry`  decimal(10,6) NULL DEFAULT 0.000000 ,
`currrz`  decimal(10,6) NOT NULL DEFAULT 0.000000 ,
`fuel`  int(3) NULL DEFAULT 100 ,
`engine`  int(1) NULL DEFAULT 0 ,
`locked`  int(1) NULL DEFAULT 0 ,
`lights`  int(1) NULL DEFAULT 0 ,
`sirens`  int(1) NULL DEFAULT 0 ,
`paintjob`  int(11) NULL DEFAULT 0 ,
`hp`  float NULL DEFAULT 1000 ,
`color1`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0' ,
`color2`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0' ,
`color3`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`color4`  varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
`plate`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`faction`  int(11) NULL DEFAULT '-1' ,
`owner`  int(11) NULL DEFAULT '-1' ,
`job`  int(11) NULL DEFAULT '-1' ,
`tintedwindows`  int(1) NULL DEFAULT 0 ,
`dimension`  int(5) NULL DEFAULT 0 ,
`interior`  int(5) NULL DEFAULT 0 ,
`currdimension`  int(5) NULL DEFAULT 0 ,
`currinterior`  int(5) NULL DEFAULT 0 ,
`enginebroke`  int(1) NULL DEFAULT 0 ,
`items`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`itemvalues`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`Impounded`  int(3) NULL DEFAULT 0 ,
`handbrake`  int(1) NULL DEFAULT 0 ,
`safepositionX`  float NULL DEFAULT NULL ,
`safepositionY`  float NULL DEFAULT NULL ,
`safepositionZ`  float NULL DEFAULT NULL ,
`safepositionRZ`  float NULL DEFAULT NULL ,
`upgrades`  varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '[ [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ] ]' ,
`wheelStates`  varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '[ [ 0, 0, 0, 0 ] ]' ,
`panelStates`  varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '[ [ 0, 0, 0, 0, 0, 0, 0 ] ]' ,
`doorStates`  varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '[ [ 0, 0, 0, 0, 0, 0 ] ]' ,
`odometer`  int(15) NULL DEFAULT 0 ,
`headlights`  varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '[ [ 255, 255, 255 ] ]' ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `wiretransfers`
-- ----------------------------
DROP TABLE IF EXISTS `wiretransfers`;
CREATE TABLE `wiretransfers` (
`id`  int(10) UNSIGNED NOT NULL ,
`from`  int(11) NOT NULL ,
`to`  int(11) NOT NULL ,
`amount`  int(11) NOT NULL ,
`reason`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`time`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`type`  int(11) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Table structure for `worlditems`
-- ----------------------------
DROP TABLE IF EXISTS `worlditems`;
CREATE TABLE `worlditems` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`itemid`  int(11) NULL DEFAULT 0 ,
`itemvalue`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
`x`  float NULL DEFAULT 0 ,
`y`  float NULL DEFAULT 0 ,
`z`  float NULL DEFAULT 0 ,
`dimension`  int(5) NULL DEFAULT 0 ,
`interior`  int(5) NULL DEFAULT 0 ,
`creationdate`  datetime NULL DEFAULT NULL ,
`rz`  float NULL DEFAULT 0 ,
`creator`  int(10) UNSIGNED NULL DEFAULT 0 ,
UNIQUE KEY `id` (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci
AUTO_INCREMENT=1

;
