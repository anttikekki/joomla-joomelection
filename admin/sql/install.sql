DROP TABLE IF EXISTS `#__joomelection_election`;

CREATE TABLE `#__joomelection_election` (
  `election_id` int(11) NOT NULL auto_increment,
  `election_type_id` int(11) NOT NULL,
  `election_name` varchar(255) NOT NULL,
  `election_description` TEXT NOT NULL DEFAULT '',
  `date_to_open` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_to_close` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `confirm_vote` tinyint(1) NOT NULL default '1',
  `confirm_vote_by_sign` tinyint(1) NOT NULL default '1',
  `confirm_vote_by_sign_description` TEXT NOT NULL DEFAULT '',
  `confirm_vote_by_sign_error` TEXT NOT NULL DEFAULT '',
  `vote_success_description` TEXT NOT NULL DEFAULT '',
  `election_voter_email_header` varchar(500) NOT NULL,
  `election_voter_email_text` TEXT NOT NULL DEFAULT '',
  PRIMARY KEY  (`election_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;


DROP TABLE IF EXISTS `#__joomelection_election_type`;

CREATE TABLE `#__joomelection_election_type` (
  `election_type_id` int(11) NOT NULL auto_increment,
  `type_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`election_type_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

INSERT INTO `#__joomelection_election_type` VALUES
(1, 'ELECTION_TYPE_CANDIDATE'),
(2, 'ELECTION_TYPE_LIST');



DROP TABLE IF EXISTS `#__joomelection_list`;

CREATE TABLE `#__joomelection_list` (
  `list_id` int(11) NOT NULL auto_increment,
  `election_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` TEXT NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`list_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;



DROP TABLE IF EXISTS `#__joomelection_option`;

CREATE TABLE `#__joomelection_option` (
  `option_id` int(11) NOT NULL auto_increment,
  `election_id` int(11) NOT NULL,
  `list_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `description` TEXT NOT NULL DEFAULT '',
  `option_number` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`option_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;



DROP TABLE IF EXISTS `#__joomelection_vote`;

CREATE TABLE `#__joomelection_vote` (
  `vote_id` int(11) NOT NULL auto_increment,
  `option_id` int(11) NOT NULL,
  PRIMARY KEY  (`vote_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;



DROP TABLE IF EXISTS `#__joomelection_election_voter_status`;

CREATE TABLE `#__joomelection_election_voter_status` (
  `election_id` int(11) NOT NULL,
  `voter_id` int(11) NOT NULL,
  `voted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`election_id`, `voter_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;


DROP TABLE IF EXISTS `#__joomelection_voter`;

CREATE TABLE `#__joomelection_voter` (
  `voter_id` int(11) NOT NULL,
  `email_sent` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`voter_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;