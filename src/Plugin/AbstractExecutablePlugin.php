<?php
/**
 * @file
 */

namespace Xylemical\Composer\Build\Plugin;

use Composer\IO\IOInterface;
use Composer\Util\ProcessExecutor;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Process\ExecutableFinder;
use Xylemical\Composer\Build\ExecutionException;

/**
 * Class AbstractExecutablePlugin
 * @package Xylemical\Composer\Build\Plugin
 */
abstract class AbstractExecutablePlugin extends AbstractPlugin {
  /**
   * @var string
   */
  private $executablePath;

  /**
   * @var \Symfony\Component\Process\ExecutableFinder
   */
  private $executableFinder;

  /**
   * @var \Composer\Util\ProcessExecutor
   */
  private $processExecutor;

  /**
   * Get the process executor.
   *
   * @return \Composer\Util\ProcessExecutor
   */
  public function getProcessExecutor() {
    if (!isset($this->processExecutor)) {
      $this->processExecutor = new ProcessExecutor($this->getIO());
    }
    return $this->processExecutor;
  }

  /**
   * Set the process executor.
   *
   * @param \Composer\Util\ProcessExecutor $executor
   *
   * @return $this
   */
  public function setProcessExecutor(ProcessExecutor $executor) {
    $this->processExecutor = $executor;
    return $this;
  }

  /**
   *
   * @return \Symfony\Component\Process\ExecutableFinder
   */
  public function getExecutableFinder() {
    if (!isset($this->executableFinder)) {
      $this->executableFinder = new ExecutableFinder();
    }
    return $this->executableFinder;
  }

  /**
   * Set the executable finder.
   *
   * @param \Symfony\Component\Process\ExecutableFinder $finder
   *
   * @return $this
   */
  public function setExecutableFinder(ExecutableFinder $finder) {
    $this->executableFinder = $finder;
    return $this;
  }

  /**
   * Get the executable path.
   *
   * @return string
   *
   * @throws \Symfony\Component\Filesystem\Exception\FileNotFoundException
   */
  public function getExecutable() {
    if (!isset($this->executablePath)) {
      $this->executablePath = $this->getExecutableFinder()->find($this->getExecutableName());
      if ($this->executablePath === NULL) {
        throw new FileNotFoundException('Unable to locate the ' . $this->getExecutableName() . ' executable.');
      }
    }
    return $this->executablePath;
  }

  /**
   * Get the installed path of the package.
   *
   * @return string
   */
  public function getInstalledPath() {
    return $this->getComposer()->getInstallationManager()->getInstallPath($this->getPackage());
  }

  /**
   * Execute the command on the package's installed path.
   *
   * @param array $arguments
   * @param array $environment
   *
   * @return void
   *
   * @throws \Xylemical\Composer\Build\ExecutionException
   */
  protected function execute($arguments = [], $environment = []) {
    $process = $this->getProcessExecutor();

    // Construct the command line arguments as array, so it can be space separated later.
    $cmd = [];

    // Prefix the executable with any environment variables.
    if (!empty($environment)) {
      foreach ($environment as $key => $value) {
        $cmd[] = $key . '=' . ProcessExecutor::escape($value);
      }
    }

    // Add the executable.
    $cmd[] = ProcessExecutor::escape($this->getExecutable());

    // Add any arguments for the command.
    if (!empty($arguments)) {
      foreach ($arguments as $value) {
        $cmd[] = ProcessExecutor::escape($value);
      }
    }

    // Generate the string version of the command.
    $cmd = implode(' ', $cmd);

    // Execute the command with notification to the end-user of what is taking place.
    $this->getIO()->write('<info>Beginning execution of the command</info> <comment>' . $cmd . '</comment>', true, IOInterface::DEBUG);
    if (0 !== ($exitCode = $process->execute($cmd, $output, $this->getInstalledPath()))) {
      throw new ExecutionException('Unable to execute command: ' . $process->getErrorOutput());
    }
    $this->getIO()->write('<info>Completed execution of the command</info> <comment>' . $cmd . '</comment>', true, IOInterface::DEBUG);
  }

  /**
   * Get the name of the executable.
   *
   * @return string
   */
  abstract public function getExecutableName();
}
