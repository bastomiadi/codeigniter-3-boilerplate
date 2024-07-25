<p align="center"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/CodeIgniter_Logo.svg/1024px-CodeIgniter_Logo.svg.png" width="200"></p>

Codeigniter 3 Application Boilerplate
=====================================
This package for Codeigniter 3 serves as a basic platform for quickly creating a back-office application. It includes profile creation and management, user management, roles, permissions and a dynamically-generated menu.

Feature
-------
* Configurable backend theme AdminLTE 3
* Ajax Crud With Sweetalert
* Support PHP 8
* User & Menu Management
* Backend, frontend, Api With Versioning Modular
* etc.

This project is still early in its development... please feel free to contribute!
------------------------------------------------------------
Screenshoot |
-------------------------------------------------------------------------------
![Login](screenshot/web/login.png?raw=true)
![Register](screenshot/web/register.png?raw=true)
![Dashboard](screenshot/web/dashboard.png?raw=true)

Installation
------------

**1.** Get The Repository

```bash
D:\laragon\www>git clone https://github.com/bastomiadi/codeigniter-3-boilerplate

```
**2.** Setting Database in application/config/database.php

```bash
D:\laragon\www\codeigniter-3-boilerplate>composer install
```

**3.** Set your database config in /application/config/database.php. If the database does not exist, create the database first.

```bash
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'ci3_boilerplate',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
```
**5.** Run migrate 
```bash
http://localhost/ci3-boilerplate/migrate
```

**6.** Run Seeder 

```bash
http://localhost/ci3-boilerplate/seed
```

**7.** Open in browser http://localhost/ci3-boilerplate/backend/auth/login
```bash
Default user and password
+----+-------------+-------------+
| No |    User     |   Password  |
+----+-------------+-------------+
| 1  | admin       |   password  |
| 2  | member      |   password  |
+----+-------------+-------------+
```

Usage
-----
You can find how it works with the read code, controller and views etc. Finnally... Happy Coding!
..

Restful Api and Docs Work in progress.. : ...


Changelog
--------
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

Contributing
------------
Contributions are very welcome.

License
-------

This package is free software distributed under the terms of the [MIT license](LICENSE.md).