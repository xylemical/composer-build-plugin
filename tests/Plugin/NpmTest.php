<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;

use Symfony\Component\Process\ExecutableFinder;

/**
 * Class NpmTest
 * @package Xylemical\Composer\Build\Plugin
 */
class NpmTest extends AbstractTestCase {
  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Setup the path used by the test.
    $this->path = __DIR__ . '/npm';

    // Setup the npm plugin.
    $this->plugin = new Npm($this->composer, $this->package, $this->io);

    // Setup the npm path.
    $this->installer->method('getInstallPath')->willReturn($this->path);
  }

  /**
   * Test the execution name is as expected.
   */
  public function testExecutionName() {
    $this->assertEquals($this->plugin->getExecutableName(), 'npm');
  }

  /**
   * Test the actual execution of the plugin.
   */
  public function testExecution() {
    $finder = new ExecutableFinder();
    if (!$finder->find($this->plugin->getExecutableName())) {
      $this->markTestSkipped('Missing the executable required to properly test the plugin');
      return;
    }

    // Make sure there is nothing left over from previous executions.
    $path = $this->path . '/node_modules';
    $this->removePath($path);

    // Build the directory.
    $this->plugin->build();

    // Assert the directory exists.
    $this->assertTrue(file_exists($path));
  }
}