<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;

/**
 * Class Grunt
 * @package Xylemical\Composer\Build\Plugin
 */
class Grunt extends AbstractExecutablePlugin {
  /**
   * {@inheritdoc}
   */
  public function getExecutableName() {
    return 'grunt';
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->execute();
  }
}
