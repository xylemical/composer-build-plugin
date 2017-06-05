<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;

use Symfony\Component\Process\ExecutableFinder;

/**
 * Class CompassTest
 * @package Xylemical\Composer\Build\Plugin
 */
class CompassTest extends AbstractTestCase {
  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Setup the path used by the test.
    $this->path = __DIR__ . '/compass';

    // Setup the compass plugin.
    $this->plugin = new Compass($this->composer, $this->package, $this->io);

    // Setup the compass path.
    $this->installer->method('getInstallPath')->willReturn($this->path);
  }

  /**
   * Test the execution name is as expected.
   */
  public function testExecutionName() {
    $this->assertEquals($this->plugin->getExecutableName(), 'compass');
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
    $path = $this->path . '/css';
    $this->removePath($path);

    // Build the directory.
    $this->plugin->build();

    // Assert the directory exists.
    $this->assertTrue(file_exists($path));
  }
}
