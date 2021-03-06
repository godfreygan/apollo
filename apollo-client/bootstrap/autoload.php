<?php
ini_set('date.timezone', 'Asia/Shanghai');
$loader = require dirname(__DIR__) . '/vendor/autoload.php';

require dirname(__DIR__) . '/app/Config/services.php';
require dirname(__DIR__) . '/app/Config/alias.php';

App::setBasePath(dirname(__DIR__));
if(file_exists(App::basePath() . '/.env')) {
    App\Util\EnvLoader::load(App::basePath());
}
// 初始化参数
\LightTracer\Plugin\LightService::init([
    'endpoint_name'  => 'apollo-client',
    'trace_log_path' => env('LOG_DIR', App::storagePath('logs')) . '/apollo-client/trace_log',
    'trace_log_span' => 'day'
]);
define('HDS_EACH_REQUEST_ID', \LightTracer\GlobalTracer::traceId());

if(defined('PHPUNIT_TEST_ENV_OVERRIDE') && PHPUNIT_TEST_ENV_OVERRIDE === 'test' ) {
    App\Util\EnvLoader::load(App::basePath() . '/tests/');
}

App::setConfigPath(App::appPath() . '/Config/');
Config::loadPhp(App::configPath('app.php'), 'app');
Config::loadPhp(App::configPath('db.php'), 'db');
Config::loadPhp(App::configPath('log.php'), 'log');
require dirname(__DIR__) . '/app/Config/rpc.php';

set_error_handler(function ($errno, $errstr, $errfile = '', $errline = '') {
     $types = array(
        E_ERROR             => 'Error',
        E_WARNING           => 'Warning',
        E_PARSE             => 'Parse error',
        E_NOTICE            => 'Notice',
        E_CORE_ERROR        => 'Core error',
        E_CORE_WARNING      => 'Core warning',
        E_COMPILE_ERROR     => 'Compile error',
        E_COMPILE_WARNING   => 'Compile warning',
        E_USER_ERROR        => 'User error',
        E_USER_WARNING      => 'User warning',
        E_USER_NOTICE       => 'User notice',
        E_STRICT            => 'Strict warning',
        E_RECOVERABLE_ERROR => 'Recoverable fatal error',
        E_DEPRECATED        => 'Deprecated function',
        E_USER_DEPRECATED   => 'User deprecated function',
    );
    App::make('log')->error('ERROR_HANDLER '.$types[$errno].' ' .
                            $errstr.' in file ' . $errfile .' on line '
        .$errline . PHP_EOL);
    return true;
}, E_ALL | E_STRICT);

//Redis配置
\CjsRedis\ConfigFile::setFile(App::configPath('redis.php'));
\CjsRedis\Sequence::setDbConfig(Config::get('app.sequence_db_config'));
\CjsRedis\Sequence::setTblPrifix(Config::get('app.sequence_tbl_prefix'));

App::make('events')->listen('Illuminate\\Database\\Events\\QueryExecuted', function ($queryObj) {
    Log::info(sprintf('sql:%s bindings:%s dbname:%s time:%s', $queryObj->sql, var_export($queryObj->bindings, true), $queryObj->connectionName, $queryObj->time));
});

