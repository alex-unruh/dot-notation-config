<?php

namespace AlexUnruh\Config;

use Dflydev\DotAccessData\Data;

class Config
{

  private static $data = [];
  private static $dir = null;
  private static $file = null;
  private static $config = null;

  /**
   * Undocumented function
   *
   * @param string $dir
   * @return void
   */
  public static function setDir(string $dir)
  {
    if (file_exists($dir)) self::$dir = $dir . '/';
  }

  /**
   * Undocumented function
   *
   * @param string $data_alias
   * @param array $data
   * @return void
   */
  public static function setData(string $data_alias, array $data)
  {
    $params = ['params' => $data];
    self::$data[$data_alias] = new Data($params);
  }

  /**
   * Undocumented function
   *
   * @param string $requested_config
   * @return string|array
   */
  public static function get(string $requested_config, $default = null)
  {
    self::setParts($requested_config);
    return self::$data[self::$file]->get(self::$config, $default);
  }

  /**
   * Undocumented function
   *
   * @param string $requested_config
   * @param string|array $value
   * @return void
   */
  public static function set(string $requested_config, $value)
  {
    self::setParts($requested_config);
    self::$data[self::$file]->set(self::$config, $value);
  }

  /**
   * Undocumented function
   *
   * @param string $requested_config
   * @param string|array $value
   * @return void
   */
  public static function append(string $requested_config, $value)
  {
    self::setParts($requested_config);
    self::$data[self::$file]->append(self::$config, $value);
  }

  /**
   * Undocumented function
   *
   * @param string $requested_config
   * @param string|array $value
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
   * Undocumented function
   *
   * @param string $requested_config
   * @return boolean
   */
  public static function has(string $requested_config): bool
  {
    self::setParts($requested_config);
    return self::$data[self::$file]->has(self::$config);
  }

  /**
   * Undocumented function
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
