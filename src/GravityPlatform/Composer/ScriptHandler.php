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
    const GRAVIPHOTON_DIR = 'node_modules/graviphoton/';

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
     * call bower install in GRAVIPHOTON_DIR
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function installBowerModules(CommandEvent $event)
    {
        self::runCommand('./node_modules/bower/bin/bower install', self::GRAVIPHOTON_DIR);
    }

    /**
     * call bower update in GRAVIPHOTON_DIR
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function updateBowerModules(CommandEvent $event)
    {
        self::runCommand('./node_modules/bower/bin/bower update', self::GRAVIPHOTON_DIR);
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
        self::runCommand('./node_modules/grunt-cli/bin/grunt --force', self::GRAVIPHOTON_DIR);
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
        self::runCommand(self::findRubyBundler().' --path ruby_modules/');
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
        self::runCommand(self::findRubyBundler().' update');
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
        self::runCommand(self::findRubyBundler().' exec '.self::findLibrarianPuppet().' install');
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
        self::runCommand(self::findRubyBundler().' exec '.self::findLibrarianPuppet().' update');
    }

    /**
     * copy app/ and web/ contents from graviton
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function installGraviton(CommandEvent $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();
        $appDir = $extras['symfony-app-dir'];
        $webDir = $extras['symfony-web-dir'];
        $gravitonDir = __DIR__.'/../../../vendor/graviton/graviton';

        if (!is_dir($webDir)) {
            mkdir($webDir);
        }
        copy($gravitonDir.'/web/app.php', $webDir.'/app.php');
        copy($gravitonDir.'/web/app_dev.php', $webDir.'/app_dev.php');
        copy($gravitonDir.'/web/check.php', $webDir.'/check.php');

        if (!is_dir($appDir)) {
            mkdir($appDir);
        }
        copy($gravitonDir.'/app/AppKernel.php', $appDir.'/AppKernel.php');
        copy($gravitonDir.'/app/autoload.php', $appDir.'/autoload.php');
        copy($gravitonDir.'/app/console', $appDir.'/console');
        if (!is_dir($appDir.'/config')) {
            mkdir($appDir.'/config');
        }
        copy($gravitonDir.'/app/config/config.xml', $appDir.'/config/config.xml');
        copy($gravitonDir.'/app/config/config_dev.xml', $appDir.'/config/config_dev.xml');
        copy($gravitonDir.'/app/config/parameters.xml', $appDir.'/config/parameters.xml');
        copy($gravitonDir.'/app/config/parameters_local.xml', $appDir.'/config/parameters_local.xml');
        copy($gravitonDir.'/app/config/routing.xml', $appDir.'/config/routing.xml');
        copy($gravitonDir.'/app/config/security.xml', $appDir.'/config/security.xml');
        copy($gravitonDir.'/app/config/vcap.php', $appDir.'/config/vcap.php');
    }

    /**
     * copy finished graviphoton to local web/ dir
     *
     * @param CommandEvent $event composer event
     *
     * @return void
     */
    public static function installGraviphotonFiles(CommandEvent $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();
        $webDir = $extras['symfony-web-dir'];
        $graviphotonDir = __DIR__.'/../../../node_modules/graviphoton/dist';

        if (!is_dir($webDir)) {
            mkdir($webDir);
        }
        copy($graviphotonDir.'/index.html', $webDir.'/index.html');
        copy($graviphotonDir.'/graviphoton.min.css', $webDir.'/graviphoton.min.css');
        copy($graviphotonDir.'/graviphoton.min.js', $webDir.'/graviphoton.min.js');
        if (!is_dir($webDir.'/font')) {
            mkdir($webDir.'/font');
        }
        copy($graviphotonDir.'/font/FontAwesome.otf', $webDir.'/font/FontAwesome.otf');
        copy($graviphotonDir.'/font/fontawesome-webfont.svg', $webDir.'/font/fontawesome-webfont.svg');
        copy($graviphotonDir.'/font/fontawesome-webfont.ttf', $webDir.'/font/fontawesome-webfont.ttf');
        copy($graviphotonDir.'/font/fontawesome-webfont.eot', $webDir.'/font/fontawesome-webfont.eot');
        copy($graviphotonDir.'/font/fontawesome-webfont.woff', $webDir.'/font/fontawesome-webfont.woff');
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
            throw new \RuntimeException('An error occurred while running '.$cmd.' in '.$cwd);
        }
    }

    /**
     * find path to installed bundler
     *
     * @return String
     */
    public static function findRubyBundler()
    {
        static $bundler;

        if (!empty($bundler)) {
            return $bundler;
        }

        if (`which bundle-1.9`) {
            $bundler = `which bundle-1.9`;
        } else {
            $bundler = `which bundle`;
        }
        $bundler = trim($bundler);

        if (empty($bundler)) {
            echo "No ruby bundler detected, please install bundler with 'gem install bundler' ";
            return false;
        }

        echo "Detected ruby bundler: ${bundler}".PHP_EOL;
        return $bundler;
    }

    /**
     * find librarian-puppet installed by ruby
     *
     * @return String
     */
    public static function findLibrarianPuppet()
    {
        static $librarian;
        if (!empty($librarian)) {
            return $librarian;
        }
        $librarian = `find ruby_modules/ -name 'librarian-puppet' -maxdepth 5`;
        $librarian = trim($librarian);

        echo "Found librarian-puppet: ${librarian}".PHP_EOL;
        return $librarian;
    }
}
