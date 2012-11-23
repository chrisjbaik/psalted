<?php
require_once __DIR__ . '/../vendor/autoload.php';

// FIXME: ick
\Slim\Slim::autoload('Slim_Environment');

class IntegrationTest extends PHPUnit_Framework_TestCase {
    public function request($method, $path, $options=array()) {
        // Capture STDOUT
        ob_start();

        // Prepare a mock environment
        Slim_Environment::mock(array_merge(array(
            'REQUEST_METHOD' => $method,
            'PATH_INFO' => $path,
            'SERVER_NAME' => 'localhost',
        ), $options));

        // Run the application
        require __DIR__ . '/../public/index.php';

        $this->app = $app;
        $this->request = $app->request();
        $this->response = $app->response();

        // Return STDOUT
        return ob_get_clean();
    }

    public function get($path, $options=array()) {
        $this->request('GET', $path, $options);
    }

    public function testIndex() {
        $this->get('/');
        $this->assertEquals('200', $this->response->status());
        $this->assertEquals('http://localhost', $this->response['Location']);
    }
}