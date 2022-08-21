<?php
/**
 * Part of the Joomla Framework Mediawiki Package
 *
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Mediawiki\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Joomla\Http\Response;
use Joomla\Mediawiki\Http;
use Joomla\Registry\Registry;
use Joomla\Mediawiki\Links;

/**
 * Test class for Links.
 *
 * @since  1.0
 */
class LinksTest extends TestCase
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
	 * @var    Links  Object under test.
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
		$this->options = new Registry;

		$errorLevel = error_reporting();
		error_reporting($errorLevel & ~E_DEPRECATED);

		$this->client = $this->createMock(Http::class);
		$this->response = $this->createMock(Response::class);

		error_reporting($errorLevel);

		$this->object = new Links($this->options, $this->client);
	}

	/**
	 * Tests the getCategories method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testGetLinks()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&prop=links&titles=Main Page&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getLinks(['Main Page']),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}

	/**
	 * Tests the getCategories method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testGetLinksUsed()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&generator=links&prop=info&titles=Main Page&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getLinksUsed(['Main Page']),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}

	/**
	 * Tests the getCategories method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testGetIWLinks()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&prop=links&titles=Main Page&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getIWLinks(['Main Page']),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}

	/**
	 * Tests the getCategories method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testGetLangLinks()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&prop=langlinks&titles=Main Page&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getLangLinks(['Main Page']),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}

	/**
	 * Tests the getCategories method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testGetExtLinks()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&prop=extlinks&titles=Main Page&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getExtLinks(['Main Page']),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}

	/**
	 * Tests the getCategories method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testEnumerateLinks()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&meta=siteinfo&alcontinue=&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->enumerateLinks(['Main Page']),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}
}
