<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;

/**
 * Class Compass
 * @package Xylemical\Composer\Build\Plugin
 */
class Compass extends AbstractExecutablePlugin {
  /**
   * {@inheritdoc}
   */
  public function getExecutableName() {
    return 'compass';
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->execute(['compile', '--force']);
  }
}
