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
  postal_code   varchar(20), 
  place_name    varchar(180), 
  admin_name1   varchar(100), 
  admin_code1   varchar(20), 
  admin_name2   varchar(100), 
  admin_code2   varchar(20),
  admin_name3   varchar(100), 
  admin_code3   varchar(20),
  latitude      tinytext, 
  longitude     tinytext, 
  accuracy      tinyint(4)
  
  PRIMARY KEY (uid),
  KEY parent (pid)
);