# PHP 7.0+ MySQL Session Handler

This repository contains a custom PHP session handler using MySQL as a backend. 

## How to Install

#### Using [Composer](http://getcomposer.org/)

Install composer if you haven't already ([Linux instructions](http://blog.programster.org/ubuntu-install-composer)).

Navigate to your project's directory and run the following command:
    
```bash
composer require "programster/mysql-session-handler"
```

## How to use

It may be easiest just to watch this [Youtube demonstration video](https://youtu.be/avRhoo6DGA0).

Below is a script demonstrating how to use this tool.

```sh
require 'vendor/autoload.php';

# Create your MySQL database connection
$db = new mysqli('localhost', 'username', 'password', 'database');

# Create the session handler using that connection and pass it the name of the table
# The handler will try to create it if it doesn't already exist.
$handler = new \Programster\SessionHandler\SessionHandler($db, 'my_sessions_table');

# Tell PHP to use the handler we just created.
session_set_save_handler($handler, true);

# Start your session
session_start();

# Set a session variable.
$_SESSION['my_session_variable'] = 'some data here';

```

### Manually Creating Sessions Table
The tool will try to use the database connection to create the sessions table if it doesn't already exist. If you provide the handler with user credentials that haven't been granted the rights to create tables, you can manually create the table with the following SQL statement:

```sql
CREATE TABLE IF NOT EXISTS `sessions` (
    `id` varchar(32) NOT NULL,
    `modified_timestamp` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `data` mediumtext,
    PRIMARY KEY (`id`),
    KEY `modified_timestamp` (`modified_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## Authors

* [Programster](https://github.com/Programster)
* [Jamie Cressey](https://github.com/JamieCressey)

## License

MIT Public License
