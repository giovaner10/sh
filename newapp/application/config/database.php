<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'homologabanco.showtecnologia.com:3306';
$db['default']['username'] = 'devs_homologacao';
$db['default']['password'] = '192uUA_sp_VF';
$db['default']['database'] = 'showtecsystem';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$db['rastreamento']['hostname'] = 'homologabanco.showtecnologia.com:3307';
$db['rastreamento']['username'] = 'devs_homologacao';
$db['rastreamento']['password'] = '192uUA_sp_VF';
$db['rastreamento']['database'] = 'rastreamento';
$db['rastreamento']['dbdriver'] = 'mysqli';
$db['rastreamento']['dbprefix'] = '';
$db['rastreamento']['pconnect'] = FALSE;
$db['rastreamento']['db_debug'] = FALSE;
$db['rastreamento']['cache_on'] = FALSE;
$db['rastreamento']['cachedir'] = '';
$db['rastreamento']['char_set'] = 'utf8';
$db['rastreamento']['dbcollat'] = 'utf8_general_ci';
$db['rastreamento']['swap_pre'] = '';
$db['rastreamento']['autoinit'] = FALSE;
$db['rastreamento']['stricton'] = FALSE;

$db['aurora']['hostname'] = '3.15.117.58:3306';
$db['aurora']['username'] = 'devs_homologacao';
$db['aurora']['password'] = '192uUA_sp_VF';
$db['aurora']['database'] = 'gestorvendas';
$db['aurora']['dbdriver'] = 'mysql';
$db['aurora']['dbprefix'] = '';
$db['aurora']['pconnect'] = FALSE;
$db['aurora']['db_debug'] = FALSE;
$db['aurora']['cache_on'] = FALSE;
$db['aurora']['cachedir'] = '';
$db['aurora']['char_set'] = 'utf8';
$db['aurora']['dbcollat'] = 'utf8_general_ci';
$db['aurora']['swap_pre'] = '';
$db['aurora']['autoinit'] = FALSE;
$db['aurora']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
