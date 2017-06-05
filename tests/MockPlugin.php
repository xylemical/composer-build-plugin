<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build;

/**
 * Class MockPlugin
 * @package Xylemical\Composer\Build
 */
class MockPlugin implements PluginInterface {
  /**
   * {@inheritdoc}
   */
  public function __construct(\Composer\Composer $composer, \Composer\Package\PackageInterface $package, \Composer\IO\IOInterface $io) {
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    self::getCalled(TRUE);
  }

  /**
   * Get the number of times the plugin has been called.
   *
   * @param null $value
   *
   * @return int
   */
  public static function getCalled($value = NULL) {
    static $called = 0;
    if ($value) {
      $called++;
    }
    return $called;
  }
}
