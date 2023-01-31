<?php
/**
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Mediawiki\Tests;

use PHPUnit\Framework\TestCase;
use Joomla\Http\Response;
use Joomla\Mediawiki\Http;
use Joomla\Registry\Registry;
use Joomla\Mediawiki\Search;

/**
 * Test class for Search.
 *
 * @since  1.0
 */
class SearchTest extends TestCase
{
    /**
     * @var    Registry  Options for the Mediawiki object.
     * @since  1.0
     */
    protected $options;

    /**
     * @var    \PHPUnit\Framework\MockObject\MockObject  Mock client object.
     * @since  1.0
     */
    protected $client;

    /**
     * @var    Search  Object under test.
     * @since  1.0
     */
    protected $object;

    /**
     * @var    \Joomla\Http\Response  Mock response object.
     * @since  1.0
     */
    protected $response;

    /**
     * @var    string  Sample xml string.
     * @since  1.0
     */
    protected $sampleString = '<a><b></b><c></c></a>';

    /**
     * @var    string  Sample xml error message.
     * @since  1.0
     */
    protected $errorString = '<message>Generic Error</message>';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     *
     * @return void
     *
     * @since  1.0
     */
    protected function setUp(): void
    {
        $this->options = new Registry();

        $errorLevel = error_reporting();
        error_reporting($errorLevel & ~E_DEPRECATED);

        $this->client   = $this->createMock(Http::class);
        $this->response = $this->createMock(Response::class);

        error_reporting($errorLevel);

        $this->object = new Search($this->options, $this->client);
    }

    /**
     * Tests the search method
     *
     * @return void
     *
     * @since  1.0
     */
    public function testSearch()
    {
        $this->response->code = 200;
        $this->response->body = $this->sampleString;

        $this->client->expects($this->once())
            ->method('get')
            ->with('/api.php?action=query&list=search&srsearch=test&format=xml')
            ->will($this->returnValue($this->response));

        $this->assertThat(
            $this->object->search('test'),
            $this->equalTo(simplexml_load_string($this->sampleString))
        );
    }

    /**
     * Tests the openSearch method
     *
     * @return void
     *
     * @since  1.0
     */
    public function testOpenSearch()
    {
        $this->response->code = 200;
        $this->response->body = $this->sampleString;

        $this->client->expects($this->once())
            ->method('get')
            ->with('/api.php?action=query&list=search&search=test&format=xml')
            ->will($this->returnValue($this->response));

        $this->assertThat(
            $this->object->openSearch('test'),
            $this->equalTo(simplexml_load_string($this->sampleString))
        );
    }
}
