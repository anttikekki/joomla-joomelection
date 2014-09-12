DROP TABLE IF EXISTS `#__joomelection_election`;

CREATE TABLE `#__joomelection_election` (
  `election_id` int(11) NOT NULL auto_increment,
  `election_type_id` int(11) NOT NULL,
  `date_to_open` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_to_close` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `confirm_vote` tinyint(1) NOT NULL default '1',
  `confirm_vote_by_sign` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`election_id`)
) CHARACTER SET `utf8`;


DROP TABLE IF EXISTS `#__joomelection_election_type`;

CREATE TABLE `#__joomelection_election_type` (
  `election_type_id` int(11) NOT NULL auto_increment,
  `type_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`election_type_id`)
) CHARACTER SET `utf8`;

INSERT INTO `#__joomelection_election_type` VALUES
(1, 'COM_JOOMELECTION_ELECTION_TYPE_CANDIDATE'),
(2, 'COM_JOOMELECTION_ELECTION_TYPE_LIST');



DROP TABLE IF EXISTS `#__joomelection_list`;

CREATE TABLE `#__joomelection_list` (
  `list_id` int(11) NOT NULL auto_increment,
  `election_id` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`list_id`)
) CHARACTER SET `utf8`;



DROP TABLE IF EXISTS `#__joomelection_option`;

CREATE TABLE `#__joomelection_option` (
  `option_id` int(11) NOT NULL auto_increment,
  `election_id` int(11) NOT NULL,
  `list_id` int(11) NOT NULL default '0',
  `option_number` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`option_id`)
) CHARACTER SET `utf8`;



DROP TABLE IF EXISTS `#__joomelection_vote`;

CREATE TABLE `#__joomelection_vote` (
  `vote_id` int(11) NOT NULL auto_increment,
  `option_id` int(11) NOT NULL,
  PRIMARY KEY  (`vote_id`)
) CHARACTER SET `utf8`;



DROP TABLE IF EXISTS `#__joomelection_election_voter_status`;

CREATE TABLE `#__joomelection_election_voter_status` (
  `election_id` int(11) NOT NULL,
  `voter_id` int(11) NOT NULL,
  `voted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`election_id`, `voter_id`)
) CHARACTER SET `utf8`;


DROP TABLE IF EXISTS `#__joomelection_voter`;

CREATE TABLE `#__joomelection_voter` (
  `voter_id` int(11) NOT NULL,
  `email_sent` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`voter_id`)
) CHARACTER SET `utf8`;


DROP TABLE IF EXISTS `#__joomelection_translation`;

CREATE TABLE `#__joomelection_translation` (
  `language` varchar(5) NOT NULL,
  `entity_type` varchar(20) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_field` varchar(50) NOT NULL,
  `translationText` TEXT NOT NULL,
  PRIMARY KEY  (`language`, `entity_type`, `entity_id`, `entity_field`)
) CHARACTER SET `utf8`;