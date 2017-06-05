<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;

/**
 * Class PluginExecutor
 * @package Xylemical\Composer\Build
 */
class PluginExecutor {
  /**
   * Builds all the packages for the local repository.
   *
   * @param \Composer\Composer $composer
   *
   * @return void
   *
   * @throws \UnexpectedValueException
   */
  public function buildPackages(Composer $composer, IOInterface $io) {
    $build = [];

    // Cycle through all installed packages detecting any required build-plugins.
    $packages = $composer->getRepositoryManager()->getLocalRepository()->getPackages();
    foreach ($packages as $package) {
      if ($this->containsPlugins($package)) {
        $build[] = $package;
      }
    }

    // Apply to the current project as well.
    $package = $composer->getPackage();
    if ($this->containsPlugins($package)) {
      $build[] = $package;
    }

    // Build the selected packages.
    foreach ($build as $package) {
      $this->buildPackage($composer, $package, $io);
    }
  }

  /**
   * Checks to see if a package is using build plugins.
   *
   * @param \Composer\Package\PackageInterface $package
   *
   * @return bool
   *
   * @throws \UnexpectedValueException
   */
  protected function containsPlugins(PackageInterface $package) {
    // Check the extra information for plugins to execute.
    $extra = $package->getExtra();
    if (empty($extra['build-plugins'])) {
      return FALSE;
    }

    // Check the build plugins all support the plugin interface
    foreach ($extra['build-plugins'] as $class) {
      if (!class_exists($class)) {
        throw new \UnexpectedValueException('Build plugin ' . $class . ' does not exist');
      }
      if (!is_subclass_of($class, 'Xylemical\Composer\Build\PluginInterface')) {
        throw new \UnexpectedValueException('Build plugin ' . $class . ' does not implement \Xylemical\Composer\Build\PluginInterface.');
      }
    }

    return TRUE;
  }

  /**
   * Builds the plugins for a package.
   *
   * @param \Composer\Composer $composer
   * @param \Composer\Package\PackageInterface $package
   * @param \Composer\IO\IOInterface $io
   *
   * @return void
   */
  protected function buildPackage(Composer $composer, PackageInterface $package, IOInterface $io) {
    // Check the extra information for plugins to execute.
    $extra = $package->getExtra();

    // Provide user feedback.
    $io->write('<info>Building package</info> <comment>' . $package->getName() . '</comment>');

    // Execute the build plugins in the order they were specified.
    foreach ($extra['build-plugins'] as $class) {
      /** @var \Xylemical\Composer\Build\PluginInterface $plugin */
      $plugin = new $class($composer, $package, $io);
      $plugin->build();
    }
  }
}
