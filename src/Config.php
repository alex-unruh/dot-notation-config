<?php

namespace AlexUnruh;

use Dflydev\DotAccessData\Data;

class Config
{

  private static $data = [];
  private static $dir = null;
  private static $file = null;
  private static $config = null;

  /**
   * Defines the directory where the configuration files will be published 
   *
   * @param string $dir The directory path without final slash eg: (/config) or (/myqpp/config to use with localhost, for example)
   * @return void
   */
  public static function setDir(string $dir)
  {
    if (file_exists($dir)) self::$dir = $dir . '/';
  }

  /**
   * Defines a virtual array data set to be called for an alias
   *
   * @param string $data_alias The alias to data set
   * @param array $data 
   * @return void
   */
  public static function setData(string $data_alias, array $data)
  {
    $params = ['params' => $data];
    self::$data[$data_alias] = new Data($params);
  }

  /**
   * A layer built on get method from https://packagist.org/packages/dflydev/dot-access-data
	 * The difference is that the first aurgument in dot notation is a file whre the data set is located.
   *
   * @param string $requested_config eg: (app.app_name)
   * @return string|array
   */
  public static function get(string $requested_config, $default = null)
  {
    self::setParts($requested_config);
    return self::$data[self::$file]->get(self::$config, $default);
  }

  /**
 	* A layer built on set method from https://packagist.org/packages/dflydev/dot-access-data
	 * The difference is that the first aurgument in dot notation is a file whre the data set is located.
   *
   * @param string $requested_config eg:(app.app_name)
   * @param string|array $value eg: 'My App"
   * @return void
   */
  public static function set(string $requested_config, $value)
  {
    self::setParts($requested_config);
    self::$data[self::$file]->set(self::$config, $value);
  }

  /**
   * A layer built on append method from https://packagist.org/packages/dflydev/dot-access-data
	 * The difference is that the first aurgument in dot notation is a file whre the data set is located.
   *
    * @param string $requested_config eg:(app.app_name)
   * @param string|array $value eg: 'My App"
   * @return void
   */
  public static function append(string $requested_config, $value)
  {
    self::setParts($requested_config);
    self::$data[self::$file]->append(self::$config, $value);
  }

  /**
   * Remove an item from config array data in execution time
   *
   * @param string $requested_config eg:(app.app_name)
   * @param string|array $value eg: 'My App"
   * @return void
   */
  public static function remove(string $requested_config)
  {
    self::setParts($requested_config);
    if (!self::$data[self::$file]->has(self::$config)) return false;
    self::$data[self::$file]->remove(self::$config);
    return true;
  }

  /**
   * Check if the requested data are present in the current array data set
   *
   * @param string $requested_config eg:(app.app_name)
   * @return boolean
   */
  public static function has(string $requested_config): bool
  {
    self::setParts($requested_config);
    return self::$data[self::$file]->has(self::$config);
  }

  /**
   * Detach the file from the requested array
   *
   * @param string $config
   * @throws \InvalidArgumentException
   * @return void
   */
  private static function setParts(string $config)
  {
    $parts = explode('.', $config, 2);
    self::$file = $parts[0];
    self::$config = isset($parts[1]) ? 'params.' . $parts[1] : 'params';

    if (isset(self::$data[self::$file])) return;
    if (!self::$dir) throw new \InvalidArgumentException("Config files directory missing. Please, use 'setDir' method before.");

    $config_file_path = self::$dir . self::$file . '.php';
    $array_data = require($config_file_path);
    $params = ['params' => $array_data];
    self::$data[self::$file] = new Data($params);
  }
}
