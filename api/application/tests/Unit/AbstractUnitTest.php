<?php

declare(strict_types=1);

namespace Tests\Unit;

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Request;
use Phalcon\Incubator\Test\PHPUnit\UnitTestCase;
use PHPUnit\Framework\IncompleteTestError;

require_once 'RequestMock.php';

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

abstract class AbstractUnitTest extends UnitTestCase
{
    private bool $loaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        $config = new \Phalcon\Config([
            'database' => [
                'adapter'     => 'Mysql',
                'host'        => 'mysql',
                'username'    => 'root',
                'password'    => 'root',
                'dbname'      => 'phalcon_app',
                'charset'     => 'utf8',
            ],
            'application' => [
                'appDir'         => APP_PATH . '/',
                'controllersDir' => APP_PATH . '/controllers/',
                'modelsDir'      => APP_PATH . '/models/',
                'migrationsDir'  => APP_PATH . '/migrations/',
                'viewsDir'       => APP_PATH . '/views/',
                'pluginsDir'     => APP_PATH . '/plugins/',
                'servicesDir'     => APP_PATH . '/services/',
                'libraryDir'     => APP_PATH . '/library/',
                'cacheDir'       => BASE_PATH . '/cache/',
                'baseUri'        => '/',
            ]
        ]);
        $loader = new \Phalcon\Loader();
        $loader->registerDirs(
            [
                APP_PATH . '/controllers/',
                APP_PATH . '/models/',
                APP_PATH . '/library/',
            ]
        )
        ->register();

        $di = new FactoryDefault();
        $di->set('db', function() use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname
            ));
        });

        $di->set('request', new \RequestMock());

        Di::reset();
        Di::setDefault($di);

        $this->loaded = true;
    }

    public function __destruct()
    {
        if (!$this->loaded) {
            throw new IncompleteTestError(
                "Please run parent::setUp()."
            );
        }
    }
}