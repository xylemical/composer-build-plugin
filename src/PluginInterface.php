<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;

/**
 * Class BuildPluginInterface
 * @package Xylemical\Composer\Build
 */
interface PluginInterface {
  /**
   * PluginInterface constructor.
   *
   * @param \Composer\Composer $composer
   * @param \Composer\Package\PackageInterface $package
   * @param \Composer\IO\IOInterface $io
   */
  public function __construct(Composer $composer, PackageInterface $package, IOInterface $io);

  /**
   * Execute the build process on the package.
   *
   * @return void
   */
  public function build();
}
