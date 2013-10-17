<?php
/**
 * script handler for composer scripts
 */

namespace GravityPlatform\Composer;

use Composer\Script\CommandEvent;
use Symfony\Component\Process\Process;

/**
 * ScriptHandler
 *
 * Handles call from composer to third party package management systems.
 *
 * @category Composer
 * @package  Gravity-Platform
 * @author   Lucas Bickel <lucas.bickel@swisscom.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.com
 */
class ScriptHandler
{
    /**
     * location where graviphoton is installed by npm
     *
     * @const String
     */
    const GRAVIPHOTON_DIR = 'node_modules/graviphoton';

    /**
     * call npm install
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function installNpmModules(CommandEvent $event)
    {
        self::runCommand('npm install');
    }

    /**
     * call npm update
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function updateNpmModules(CommandEvent $event)
    {
        self::runCommand('npm update');
    }

    /**
     * call npm install --dev-all in GRAVIPHOTON_DIR
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function installGraviphoton(CommandEvent $event)
    {
        self::runCommand('npm install --dev-all', self::GRAVIPHOTON_DIR);
    }

    /**
     * call npm update --dev-all in GRAVIPHOTON_DIR
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function updateGraviphoton(CommandEvent $event)
    {
        self::runCommand('npm update --dev-all', self::GRAVIPHOTON_DIR);
    }

    /**
     * call vendorized grunt in GRAVIPHOTON_DIR
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function buildGraviphoton(CommandEvent $event)
    {
        self::runCommand('./node_modules/grunt-cli/bin/grunt', self::GRAVIPHOTON_DIR);
    }

    /**
     * call bundle with vendorized install
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function installLibrarianPuppet(CommandEvent $event)
    {
        self::runCommand('bundle install --path ruby_modules/');
    }

    /**
     * call bundle with vendorized update
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function updateLibrarianPuppet(CommandEvent $event)
    {
        self::runCommand('bundle update');
    }

    /**
     * call librarian-puppet for vendorized install
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function installPuppetModules(CommandEvent $event)
    {
        self::runCommand('./ruby_modules/ruby/1.8/bin/librarian-puppet install');
    }

    /**
     * call librarian-puppet for vendorized update
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function updatePuppetModules(CommandEvent $event)
    {
        self::runCommand('./ruby_modules/ruby/1.8/bin/librarian-puppet update');
    }

    /**
     * run a command
     *
     * @param String $cmd command
     * @param String $cwd command working directory
     *
     * @return void
     */
    public static function runCommand($cmd, $cwd = null)
    {
        $process = new Process($cmd, $cwd, null, null, 0);
        $process->run(function ($type, $buffer) { echo $buffer; });

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('An error occurred while running '.$cmd);
        }
    }
}
