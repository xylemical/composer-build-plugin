<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

/**
 * Class ComposerPlugin
 * @package Xylemical\Composer\Build
 */
class PluginComposer implements PluginInterface, EventSubscriberInterface {
  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
    // Performs no action.
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ScriptEvents::POST_INSTALL_CMD => 'onCommand',
      ScriptEvents::POST_UPDATE_CMD => 'onCommand',
    ];
  }

  /**
   * Build packages that support build actions.
   *
   * @param \Composer\Script\Event $event
   *
   * @throws \UnexpectedValueException
   */
  public function onCommand(Event $event) {
    $executor = new PluginExecutor();
    $executor->buildPackages($event->getComposer(), $event->getIO());
  }
}
