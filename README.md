# Alex Unruh - Config

This library is based on the Laravel config concept. It values performance and was built on top of the library [Dflydev Dot Access data](https://github.com/dflydev/dflydev-dot-access-data).

The difference is that we can access the data from a file instead an simple array. 

### Dependencies:

```
dflydev/dot-access-data
```

### Requirements:

```
php ^7.2+
```

### How to install:

```
composer require alex-unruh/dot-notation-config
```

### Usage with files:

```
// config/app.php

return [
	'app_name' => 'My App',
	
	'app_version => '1.0.0'

	'connection_params' => [
		'host' => 'localhost',
		'dbname' => 'my_database',
		'user' => 'root',
		'password' => '',
		'port' => '3306'
 	]
];


// index.php

use AlexUnruh\Config;

Config::setDir('/config');

echo Config::get('app.app_name'); // 'My App'
echo Config::get('app.connection_params.host'); // 'localhost'

print_r(Config::get('app')); // Returns all the array data placed in the app file.
```

### Usage with virtual data:

```
Config::setData('my_data', [
		'app_name' => 'My App',
		'app_version' => '1.0.0', 
		'connection_params' => 
		[
			'host' => 'localhost',
			'dbname' => 'my_database',
			'user' => 'root',
			'password' => '',
			'port' => '3306'
		}
]);

echo Config::get('my_data.app_name'); // 'My App'
echo Config::get('my_data.connection_params.host'); // 'localhost'

print_r(Config::get('my_data')); // Returns all the array data placed in the my_data array.
```

### Methods:

The methods is the same presents in [Dflydev Dot Access data](https://github.com/dflydev/dflydev-dot-access-data). The difference is that the first argument in dot-notations is a file or a virtual config set alias defined on setDir or setdata methods described above.

Methods: setDir, setData, get, set, has, remove and append.

The first two is described in this documentation, the other is described in the [Dflydev Dot Access data](https://github.com/dflydev/dflydev-dot-access-data) docs.

### Tricks:

As the library only has static methods, you can set the configuration files directory at any time or in any file called before manipulating the data through the class's methods.

Define the config dir in a file like a entry point and don't worry about him anymore...

```
// public/index.php
$config_path = $_SERVER['DOCUMENT_ROOT'] . $_ENV['BASE_PATH'] . '/config';
Config::setDir('/config_path);

// controllers/services/api_service.php
echo Config::get('app.app_name'); // 'My App'