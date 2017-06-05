<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build;

use Composer\Composer;
use Composer\Config;
use Composer\IO\NullIO;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Composer\Repository\RepositoryManager;
use Composer\Repository\WritableArrayRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class PluginExecutorTest
 * @package Xylemical\Composer\Build
 */
class PluginExecutorTest extends TestCase {
  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->io = new NullIO();
    $this->config = new Config();
    $this->rootPackage = new RootPackage('test/root', '0.0.1', 'test');
    $this->package = new Package('test/test', '0.0.1', 'test');
    $this->repo = new WritableArrayRepository([$this->package]);
    $this->plugin = new PluginComposer();

    // Setup the manager
    $this->manager = new RepositoryManager($this->io, $this->config);
    $this->manager->addRepository($this->repo);
    $this->manager->setLocalRepository($this->repo);

    // Setup composer with the manager.
    $this->composer = new Composer();
    $this->composer->setRepositoryManager($this->manager);
    $this->composer->setPackage($this->rootPackage);
  }

  /**
   * Test that an empty plugin list does not produce errors.
   */
  public function testEmptyPluginList() {
    // Set the package extra information.
    $this->package->setExtra([
      'build-plugins' => []
    ]);

    // Run the execution test.
    $executor = new PluginExecutor();
    $executor->buildPackages($this->composer, $this->io);
  }

  /**
   * Test that the root plugin is executed.
   */
  public function testRootExecution() {
    // Set the package extra information.
    $this->rootPackage->setExtra([
      'build-plugins' => ['Xylemical\Composer\Build\MockPlugin']
    ]);

    // Keep the original number of times the plugin was called.
    $original = MockPlugin::getCalled();

    // Run the execution test.
    $executor = new PluginExecutor();
    $executor->buildPackages($this->composer, $this->io);

    // The plugin was executed.
    $this->assertEquals($original + 1, MockPlugin::getCalled());
  }

  /**
   * Test that a plugin is executed.
   */
  public function testExecution() {
    // Set the package extra information.
    $this->package->setExtra([
      'build-plugins' => ['Xylemical\Composer\Build\MockPlugin']
    ]);

    // Keep the original number of times the plugin was called.
    $original = MockPlugin::getCalled();

    // Run the execution test.
    $executor = new PluginExecutor();
    $executor->buildPackages($this->composer, $this->io);

    // The plugin was executed.
    $this->assertEquals($original + 1, MockPlugin::getCalled());
  }

  /**
   * Test that an invalid plugin class fails to execute.
   */
  public function testFalseClass() {
    // Set the package extra information.
    $this->package->setExtra([
      'build-plugins' => ['Xylemical\Composer\Build\MockPlugin_Broken']
    ]);

    // Keep the original number of times the plugin was called.
    $original = MockPlugin::getCalled();

    // Run the execution test.
    $executor = new PluginExecutor();
    try {
      $executor->buildPackages($this->composer, $this->io);
      $this->fail('An exception should have occurred.');
    }
    catch (\UnexpectedValueException $e) {
      $this->assertTrue(TRUE);
    }
  }


}