<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;

use Composer\Composer;
use Composer\Config;
use Composer\Installer\InstallationManager;
use Composer\IO\NullIO;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Composer\Repository\RepositoryManager;
use Composer\Repository\WritableArrayRepository;
use PHPUnit\Framework\TestCase;


/**
 * Class AbstractTestCase
 * @package Xylemical\Composer\Build\Plugin
 */
class AbstractTestCase extends TestCase {
  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->io = new NullIO();
    $this->config = new Config();
    $this->rootPackage = new RootPackage('test/root', '0.0.1', 'test');
    $this->package = new Package('test/test', '0.0.1', 'test');
    $this->repo = new WritableArrayRepository([$this->package]);

    // Setup the repoManager
    $this->repoManager = new RepositoryManager($this->io, $this->config);
    $this->repoManager->addRepository($this->repo);
    $this->repoManager->setLocalRepository($this->repo);

    // Setup the installer
    $this->installer = $this->getMockBuilder('Composer\Installer\InstallerInterface')
      ->getMock();
    $this->installer->method('supports')->willReturn(TRUE);

    // Setup the InstallationManager
    $this->installManager = new InstallationManager();
    $this->installManager->addInstaller($this->installer);

    // Setup composer with the repoManager.
    $this->composer = new Composer();
    $this->composer->setRepositoryManager($this->repoManager);
    $this->composer->setInstallationManager($this->installManager);
    $this->composer->setPackage($this->rootPackage);
  }

  /**
   * Recursively deletes a directory.
   *
   * @param $path
   */
  protected function removePath($path) {
    if (file_exists($path) && is_dir($path)) {
      $files = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::CHILD_FIRST
      );

      foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
      }

      rmdir($path);
    }
  }
}
