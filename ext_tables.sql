#
# INDEX
#
# tx_radialsearch_postalcodes



#
# Table structure for table 'tx_radialsearch_postalcodes'
#
CREATE TABLE tx_radialsearch_postalcodes (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) DEFAULT '0' NOT NULL,
  crdate int(11) DEFAULT '0' NOT NULL,
  cruser_id int(11) DEFAULT '0' NOT NULL,
  deleted tinyint(4) DEFAULT '0' NOT NULL,

  country_code  varchar(2) DEFAULT NULL, 
  postal_code   varchar(20) DEFAULT NULL, 
  place_name    varchar(180) DEFAULT NULL,
  admin_name1   varchar(100) DEFAULT NULL,
  admin_code1   varchar(20) DEFAULT NULL,
  admin_name2   varchar(100) DEFAULT NULL,
  admin_code2   varchar(20) DEFAULT NULL,
  admin_name3   varchar(100) DEFAULT NULL,
  admin_code3   varchar(20) DEFAULT NULL,
  latitude      float DEFAULT NULL,
  longitude     float DEFAULT NULL,
  accuracy      int(1) DEFAULT NULL,
  
  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY postal_code (postal_code),
  KEY place_name (place_name),
  KEY country_code (country_code,postal_code,place_name)
);