# Alex Unruh - Config

This library is based on the Laravel config concept. It values performance and was built on top of the library [Dflydev Dot Access data](https://github.com/dflydev/dflydev-dot-access-data).

The difference is that we can also access the data from a file instead of just an array in runtime execution.

### How to install:

```
composer require alex-unruh/dot-notation-config
```

### Usage with files:

You can have as many configuration files as you want

```php
// config/app.php

return [
  'app_name' => 'My App',

  'app_version' => '1.0.0',

  'connection_params' => [
    'host' => 'localhost',
    'dbname' => 'my_database',
    'user' => 'root',
    'password' => '',
    'port' => '3306'
  ]
];

// config/messages.php

return [
  'internal_error' => 'Internal server error',
  400 => 'Bad request'
];

// index.php

use AlexUnruh\Config;

Config::setDir('/config');

// Search data in /config/app.php file
echo Config::get('app.app_name'); // 'My App'
echo Config::get('app.connection_params.host'); // 'localhost'

// Search data in /config/messages.php file
echo Config::get('messages.400'); // 'Bad request'

print_r(Config::get('app')); // Returns all the array data placed in the app file.
```

### Usage with virtual data:

```php

// index.php

use AlexUnruh\Config;

$data = [
  'app_name' => 'My App',

  'app_version' => '1.0.0',

  'connection_params' => [
    'host' => 'localhost',
    'dbname' => 'my_database',
    'user' => 'root',
    'password' => '',
    'port' => '3306'
  ]
];

Config::setData('my_data', $data);

echo Config::get('my_data.app_name'); // 'My App'
echo Config::get('my_data.connection_params.host'); // 'localhost'

print_r(Config::get('my_data')); // Returns all the array data placed in the my_data array.
```

### Methods:

The methods is the same presents in [Dflydev Dot Access data](https://github.com/dflydev/dflydev-dot-access-data). The difference is that the first argument in dot-notations is a file or a virtual config set alias defined on setDir or setData methods described above.

Methods: setDir, setData, get, set, has, remove and append.

```php

// $my_config_dir = '/my-config-dir'
Config::setDir($my_config_dir);

// $my_array = ['app_name' => 'My App', 'app_version' => '1.0.0']
Config::setData($my_array);

// $my_array_search = 'app.app_name'
// $default_if_key_not_exists = 'My App'
Config::get($my_array_search, $default_if_key_not_exists); // 'My App'

// $my_array_item = 'app.name'
// $my_new_value = 'My New App Name'
Config::set($my_array_item, $my_new_value);

// $my_array_data = 'app'
// $the_key_im_looking_for = 'app_version'
Config::has($my_array_data, $the_key_im_looking_for); // true

// $my_array_item = 'app'
// $value_to_append = ['app_licence' => 'MIT']
Config::append($my_array_item, $value_to_append);

// $my_array_item = 'app'
// $value_to_remove = 'app_licence'
Config::remove($my_array_item, $value_to_remove);
```

The first two is described in this documentation, the other is described in the [Dflydev Dot Access data](https://github.com/dflydev/dflydev-dot-access-data) docs.

### Tricks:

As the library only has static methods, you can set the configuration files directory at any time or in any file called before manipulating the data through the class's methods.

Define the config dir in a file like a entry point and don't worry about him anymore...

```php
// public/index.php

$config_path = $_SERVER['DOCUMENT_ROOT'] . $_ENV['BASE_PATH'] . '/config';
Config::setDir($config_path);

// controllers/services/MyService.php

echo Config::get('app.app_name'); // 'My App'
