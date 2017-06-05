<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;
use Composer\Util\ProcessExecutor;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Process\ExecutableFinder;
use Xylemical\Composer\Build\ExecutionException;

/**
 * Class AbstractExecutablePluginTest
 * @package Xylemical\Composer\Build\Plugin
 */
class AbstractExecutablePluginTest extends AbstractTestCase {
  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->plugin = $this->getMockBuilder('Xylemical\Composer\Build\Plugin\AbstractExecutablePlugin')
      ->setMethods(['build', 'getExecutableName'])
      ->setConstructorArgs([$this->composer, $this->package, $this->io])
      ->getMock();
  }

  /**
   * Test the getters and settings for the object.
   */
  public function testGetSets() {
    $executor = new ExecutableFinder();
    $this->plugin->setExecutableFinder($executor);
    $this->assertSame($executor, $this->plugin->getExecutableFinder());

    $executor = new ProcessExecutor();
    $this->plugin->setProcessExecutor($executor);
    $this->assertSame($executor, $this->plugin->getProcessExecutor());
  }

  /**
   * Test that the executable path fails.
   */
  public function testExecutablePath() {
    try {
      $this->plugin->getExecutable();
      $this->fail('This should be throwing an exception.');
    }
    catch (FileNotFoundException $e) {
      $this->assertTrue(TRUE);
    }
  }

  /**
   * Test the environment variables are being added.
   */
  public function testEnvironment() {
    $this->plugin->method('getExecutableName')->willReturn('true');

    $method = new \ReflectionMethod($this->plugin, 'execute');
    $method->setAccessible(TRUE);

    // Invoke with environment variables.
    $method->invoke($this->plugin, [], ['TEST' => 'TEST']);
  }

  /**
   * Test an exception is thrown on execution failure.
   */
  public function testExecutionFailure() {
    $this->plugin->method('getExecutableName')->willReturn('false');

    $method = new \ReflectionMethod($this->plugin, 'execute');
    $method->setAccessible(TRUE);

    try {
      $method->invoke($this->plugin, [], []);
      $this->fail('Expecting an exception to occur here.');
    }
    catch (ExecutionException $e) {
      $this->assertTrue(TRUE);
    }
  }
}
