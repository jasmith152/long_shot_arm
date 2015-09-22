/*  ------- MySQL ------
 *  If you desire to use MySQL to store the login / password
 *  combinations, I suggest you use a database with the following
 *  structure. Note however that you can also use other database, table
 *  and column names. They can be changed in the configuration file.
 *
 *  MySQL-Dump
 *  Database: phpSecurePages
 *  Table structure for table 'phpSP_users'
 */

CREATE TABLE phpSP_users (
   primary_key MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
   user VARCHAR(50) NOT NULL,
   password VARCHAR(32) NOT NULL,
   userlevel TINYINT(3),
   PRIMARY KEY (primary_key),
   KEY (user)
);


/*  ---- PHP3 ---
 *  If you use phpSecurePages on a server with PHP3, the following two
 *  tables MUST be created. These two tables are not used with PHP4.
 *  The above table remains optional. Unlike the above table, only
 *  database and tables names can be changed (not column names).
 *
 *  MySQL-Dump
 *  Database : phpSecurePages
 *  Table structure for tables 'phpSP_sessions' and 'phpSP_sessionVars'
 */

CREATE TABLE phpSP_sessions (
   id CHAR(20) NOT NULL,
   LastAction DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
   ip CHAR(15) NOT NULL,
   userID MEDIUMINT(9),
   PRIMARY KEY (id),
   KEY id (id),
   UNIQUE id_2 (id)
);

CREATE TABLE phpSP_sessionVars (
   id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AUTO_INCREMENT,
   session VARCHAR(20) NOT NULL,
   name VARCHAR(32) NOT NULL,
   intval INT(10) UNSIGNED,
   strval VARCHAR(100),
   PRIMARY KEY (id),
   KEY sessionID (session),
   UNIQUE id (id)
);