<?php
if (DEBUG) {
    $withJsonEnc = JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT;
} else {
    $withJsonEnc = 0;
}

$dir = dirname(__DIR__);
if (file_exists(dirname($dir) . '/slimphp')) {
    define('CONTENT_DIR_PATH', dirname($dir) . '/slimphp');
} else {
    define('CONTENT_DIR_PATH', $dir . '/slimphp');
}

return array(
    'auth' => array(
        'enable_basic_auth' => ENABLE_BASIC_AUTH,

        // can use arrays as const in php5.6 or upper
        // e.g. 'basic_auth' => BASIC_AUTH,
        'basic_auth' => array(
            BASIC_AUTH_USER => BASIC_AUTH_PASS
        ),

        'basic_auth_path' => array(
            '/users'
        )
    ),

    'settings' => array(
        'displayErrorDetails' => DEBUG,

        'debug_mode'  => DEBUG,
        'environment' => ENVIRONMENT_MODE,
        'withJsonEnc' => $withJsonEnc,

        'db' => array(
            'host' => DB_HOST,
            'name' => DB_NAME,
            'user' => DB_USER,
            'pass' => DB_PASS,
            'port' => DB_PORT,
            'charset' => DB_CHARSET
        ),

        'mail' => array(
            'host' => MAIL_HOST,
            'port' => MAIL_PORT,
            'user' => MAIL_USER,
            'pass' => MAIL_PASS,

            'from' => MAIL_FROM,
            'name' => MAIL_NAME,
            'addr' => MAIL_ADDR,

            'fail' => MAIL_FAIL
        ),

        'logger' => array(
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/slim/app.log'
        ),

        'content_dir' => array(
            'path' => CONTENT_DIR_PATH
        )
    )
);
