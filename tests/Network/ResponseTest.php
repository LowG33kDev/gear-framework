<?php

namespace Gear\Test\Network;

use \Gear\Network\Response;
use \InvalidArgumentException;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testOutput()
    {
        $r = new Response();
        $r->setContent('ok');
        $r->send();
        $this->expectOutputString('ok');
    }

    public function testAppendOutput()
    {
        $r = new Response();
        $r->setContent('ok');
        $r->append('-valide');
        $r->send();
        $this->expectOutputString('ok-valide');
    }

    /**
     * @dataProvider providerStatusCode
     */
    public function testStatusCode($status, $expected)
    {
        $this->assertEquals($expected, Response::$codes[$status]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function providerStatusCode()
    {
        return [
            'Continue'                      => [100, 'Continue'],
            'Switching Protocols'           => [101, 'Switching Protocols'],
            'Processing'                    => [102, 'Processing'],
            'OK'                            => [200, 'OK'],
            'Created'                       => [201, 'Created'],
            'Accepted'                      => [202, 'Accepted'],
            'Non-Authoritative Information' => [203, 'Non-Authoritative Information'],
            'No Content'                    => [204, 'No Content'],
            'Reset Content'                 => [205, 'Reset Content'],
            'Partial Content'               => [206, 'Partial Content'],
            'Multi-Status'                  => [207, 'Multi-Status'],
            'Already Reported'              => [208, 'Already Reported'],
            'IM Used'                       => [226, 'IM Used'],
            'Multiple Choices'              => [300, 'Multiple Choices'],
            'Moved Permanently'             => [301, 'Moved Permanently'],
            'Found'                         => [302, 'Found'],
            'See Other'                     => [303, 'See Other'],
            'Not Modified'                  => [304, 'Not Modified'],
            'Use Proxy'                     => [305, 'Use Proxy'],
            '(Unused)'                      => [306, '(Unused)'],
            'Temporary Redirect'            => [307, 'Temporary Redirect'],
            'Permanent Redirect'            => [308, 'Permanent Redirect'],
            'Bad Request'                   => [400, 'Bad Request'],
            'Unauthorized'                  => [401, 'Unauthorized'],
            'Payment Required'              => [402, 'Payment Required'],
            'Forbidden'                     => [403, 'Forbidden'],
            'Not Found'                     => [404, 'Not Found'],
            'Method Not Allowed'            => [405, 'Method Not Allowed'],
            'Not Acceptable'                => [406, 'Not Acceptable'],
            'Proxy Authentication Required' => [407, 'Proxy Authentication Required'],
            'Request Timeout'               => [408, 'Request Timeout'],
            'Conflict'                      => [409, 'Conflict'],
            'Gone'                          => [410, 'Gone'],
            'Length Required'               => [411, 'Length Required'],
            'Precondition Failed'           => [412, 'Precondition Failed'],
            'Payload Too Large'             => [413, 'Payload Too Large'],
            'URI Too Long'                  => [414, 'URI Too Long'],
            'Unsupported Media Type'        => [415, 'Unsupported Media Type'],
            'Range Not Satisfiable'         => [416, 'Range Not Satisfiable'],
            'Expectation Failed'            => [417, 'Expectation Failed'],
            'Unprocessable Entity'          => [422, 'Unprocessable Entity'],
            'Locked'                        => [423, 'Locked'],
            'Failed Dependency'             => [424, 'Failed Dependency'],
            'Upgrade Required'              => [426, 'Upgrade Required'],
            'Precondition Required'         => [428, 'Precondition Required'],
            'Too Many Requests'             => [429, 'Too Many Requests'],
            'Request Header Fields Too Large' => [431, 'Request Header Fields Too Large'],
            'Internal Server Error'         => [500, 'Internal Server Error'],
            'Not Implemented'               => [501, 'Not Implemented'],
            'Bad Gateway'                   => [502, 'Bad Gateway'],
            'Service Unavailable'           => [503, 'Service Unavailable'],
            'Gateway Timeout'               => [504, 'Gateway Timeout'],
            'HTTP Version Not Supported'    => [505, 'HTTP Version Not Supported'],
            'Variant Also Negotiates'       => [506, 'Variant Also Negotiates'],
            'Insufficient Storage'          => [507, 'Insufficient Storage'],
            'Loop Detected'                 => [508, 'Loop Detected'],
            'Not Extended'                  => [510, 'Not Extended'],
            'Network Authentication Required' => [511, 'Network Authentication Required']
        ];
    }

    public function testBadStatusCode()
    {
        $r = new Response();

        try{
            $r->status(512);
        } catch (InvalidArgumentException $expected) {
            return;
        }
        // @codeCoverageIgnoreStart
        $this->fail('An expected exception has not been raised.');
    } // @codeCoverageIgnoreEnd

    public function testCacheHeaders()
    {
        $r = new Response();
        $r->cache(false);
        $headers = $r->headers();
        $this->assertArrayHasKey('Pragma', $headers);

        $r->cache('2015-10-21');
        $headers = $r->headers();
        $this->assertArrayNotHasKey('Pragma', $headers);
        $this->assertEquals('Wed, 21 Oct 2015 00:00:00 GMT', $headers['Expires']);
    }
}
