<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;

use Symfony\Component\Process\ExecutableFinder;

/**
 * Class GruntTest
 * @package Xylemical\Composer\Build\Plugin
 */
class GruntTest extends AbstractTestCase {
  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Setup the path used by the test.
    $this->path = __DIR__ . '/grunt';

    // Setup the grunt plugin.
    $this->plugin = new Grunt($this->composer, $this->package, $this->io);

    // Setup the grunt path.
    $this->installer->method('getInstallPath')->willReturn($this->path);
  }

  /**
   * Test the execution name is as expected.
   */
  public function testExecutionName() {
    $this->assertEquals($this->plugin->getExecutableName(), 'grunt');
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
    $path = $this->path . '/grunt_test';
    $this->removePath($path);

    // We need to run the npm install before running grunt.
    $npm = new Npm($this->composer, $this->package, $this->io);
    $npm->build();

    // Build the directory.
    $this->plugin->build();

    // Assert the directory exists.
    $this->assertTrue(file_exists($path));
  }
}