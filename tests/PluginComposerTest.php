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
use Composer\Repository\ArrayRepository;
use Composer\Repository\RepositoryManager;
use Composer\Repository\WritableArrayRepository;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Eloquent\Phony\Phony;
use PHPUnit\Framework\TestCase;

/**
 * Class PluginComposerTest
 * @package Xylemical\Composer\Build
 */
class PluginComposerTest extends TestCase {
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
   * Test the construction of the plugin.
   */
  public function testConstructorWithoutArguments() {
    $this->assertInstanceOf('Xylemical\Composer\Build\PluginComposer', new PluginComposer());
  }

  /**
   * Test the plugin activate behaviour.
   */
  public function testActivate() {
    $this->assertNull($this->plugin->activate($this->composer, $this->io));
  }

  /**
   * Test the subscribed events are properly covered.
   */
  public function testGetSubscribedEvents() {
    $this->assertSame(
      array(
        ScriptEvents::POST_INSTALL_CMD => 'onCommand',
        ScriptEvents::POST_UPDATE_CMD => 'onCommand',
      ),
      $this->plugin->getSubscribedEvents()
    );
  }

  /**
   * Test the command process.
   */
  public function testOnCommand() {
    $this->plugin->onCommand(new Event(ScriptEvents::POST_INSTALL_CMD, $this->composer, $this->io, true));
  }
}