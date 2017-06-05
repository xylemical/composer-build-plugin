<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;

/**
 * Class Bower
 * @package Xylemical\Composer\Build\Plugin
 */
class Bower extends AbstractExecutablePlugin {
  /**
   * {@inheritdoc}
   */
  public function getExecutableName() {
    return 'bower';
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->execute(['install']);
  }
}
