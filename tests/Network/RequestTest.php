<?php

namespace Gear\Test\Network;

use \Gear\Network\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider providerRequest
     */
    public function testRequest($data)
    {
        $this->generateRequest($data);

        $request = new Request();

        $this->assertEquals($data['server_expected']['REQUEST_METHOD'], $request->method);
        $this->assertEquals($data['server_expected']['REQUEST_SCHEME'], $request->scheme);
        $this->assertEquals($data['server_expected']['IsSecure'], $request->isSecure);
        $this->assertEquals($data['server_expected']['SERVER_PROTOCOL'], $request->protocol);
        $this->assertEquals($data['server_expected']['IsAjax'], $request->ajax);
    }

    /**
     * @dataProvider providerUrl
     */
    public function testUrl($uri, $scriptName, $expected)
    {
        $queryString = explode('?', $uri);
        if (count($queryString) === 2) {
            $_SERVER['QUERY_STRING'] = $queryString[1];
        }
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['SCRIPT_NAME'] = $scriptName;

        $request = new Request();

        $this->assertEquals($expected, $request->url);
    }

    /**
     * @codeCoverageIgnore
     */
    public function providerRequest()
    {
        return [
            'GET request' => [
                ['server' =>
                    [
                        'REQUEST_METHOD' => 'GET',
                        'REQUEST_SCHEME' => 'http',
                        'SERVER_PROTOCOL' => 'HTTP/1.1',
                        'HTTP_X_REQUESTED_WITH' => ''
                    ],
                 'server_expected' =>
                    [
                        'REQUEST_METHOD' => 'GET',
                        'REQUEST_SCHEME' => 'http',
                        'IsSecure' => false,
                        'SERVER_PROTOCOL' => 'HTTP/1.1',
                        'IsAjax' => false
                    ]
                ]
            ],
            'GET secure request' => [
                ['server' =>
                    [
                        'REQUEST_METHOD' => 'GET',
                        'REQUEST_SCHEME' => 'https',
                        'SERVER_PROTOCOL' => 'HTTP/1.1',
                        'HTTP_X_REQUESTED_WITH' => ''
                    ],
                 'server_expected' =>
                    [
                        'REQUEST_METHOD' => 'GET',
                        'REQUEST_SCHEME' => 'https',
                        'IsSecure' => true,
                        'SERVER_PROTOCOL' => 'HTTP/1.1',
                        'IsAjax' => false
                    ]
                ]
            ],
            'GET ajax request' => [
                ['server' =>
                    [
                        'REQUEST_METHOD' => 'GET',
                        'REQUEST_SCHEME' => 'http',
                        'SERVER_PROTOCOL' => 'HTTP/1.1',
                        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
                    ],
                 'server_expected' =>
                    [
                        'REQUEST_METHOD' => 'GET',
                        'REQUEST_SCHEME' => 'http',
                        'IsSecure' => false,
                        'SERVER_PROTOCOL' => 'HTTP/1.1',
                        'IsAjax' => true
                    ]
                ]
            ],
            'POST request' => [
                ['server' =>
                    [
                        'REQUEST_METHOD' => 'POST',
                        'REQUEST_SCHEME' => 'http',
                        'SERVER_PROTOCOL' => 'HTTP/1.0',
                        'HTTP_X_REQUESTED_WITH' => ''
                    ],
                 'server_expected' =>
                    [
                        'REQUEST_METHOD' => 'POST',
                        'REQUEST_SCHEME' => 'http',
                        'IsSecure' => false,
                        'SERVER_PROTOCOL' => 'HTTP/1.0',
                        'IsAjax' => false
                    ]
                ]
            ],
            'POST secure request' => [
                ['server' =>
                    [
                        'REQUEST_METHOD' => 'POST',
                        'REQUEST_SCHEME' => 'https',
                        'SERVER_PROTOCOL' => 'HTTP/1.1',
                        'HTTP_X_REQUESTED_WITH' => ''
                    ],
                 'server_expected' =>
                    [
                        'REQUEST_METHOD' => 'POST',
                        'REQUEST_SCHEME' => 'https',
                        'IsSecure' => true,
                        'SERVER_PROTOCOL' => 'HTTP/1.1',
                        'IsAjax' => false
                    ]
                ]
            ],
            'POST ajax request' => [
                ['server' =>
                    [
                        'REQUEST_METHOD' => 'POST',
                        'REQUEST_SCHEME' => 'http',
                        'SERVER_PROTOCOL' => 'HTTP/1.1',
                        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
                    ],
                 'server_expected' =>
                    [
                        'REQUEST_METHOD' => 'POST',
                        'REQUEST_SCHEME' => 'http',
                        'IsSecure' => false,
                        'SERVER_PROTOCOL' => 'HTTP/1.1',
                        'IsAjax' => true
                    ]
                ]
            ]
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function providerUrl()
    {
        return [
            'Basic url' => ['/', '/index.php', '/'],
            'Subdir url' => ['/', '/gear/index.php', '/'],
            'With params' => ['/gear/blog/my-article?p=3', '/gear/index.php', '/blog/my-article/']
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    protected function generateRequest($data)
    {
        if (isset($data['server'])) {
            foreach ($data['server'] as $key => $value) {
                $_SERVER[$key] = $value;
            }
        }
    }
}
