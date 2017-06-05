<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;

/**
 * Class Npm
 * @package Xylemical\Composer\Build\Plugin
 */
class Npm extends AbstractExecutablePlugin {
  /**
   * {@inheritdoc}
   */
  public function getExecutableName() {
    return 'npm';
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->execute(['install']);
  }
}
