<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Xylemical\Composer\Build\PluginInterface;

/**
 * Class AbstractPlugin
 * @package Xylemical\Composer\Build\Plugin
 */
abstract class AbstractPlugin implements PluginInterface {
  /**
   * @var \Composer\Composer
   */
  private $composer;

  /**
   * @var \Composer\Package\PackageInterface
   */
  private $package;

  /**
   * @var \Composer\IO\IOInterface
   */
  private $io;

  /**
   * {@inheritdoc}
   */
  public function __construct(Composer $composer, PackageInterface $package, IOInterface $io) {
    $this->composer = $composer;
    $this->package = $package;
    $this->io = $io;
  }

  /**
   * Get
   * @return \Composer\Composer
   */
  public function getComposer() {
    return $this->composer;
  }

  /**
   * Get the package the plugin will be building.
   *
   * @return \Composer\Package\PackageInterface
   */
  public function getPackage() {
    return $this->package;
  }

  /**
   * Get the IO interface.
   *
   * @return \Composer\IO\IOInterface
   */
  public function getIO() {
    return $this->io;
  }
}