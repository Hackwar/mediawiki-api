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
use Joomla\Mediawiki\Categories;

/**
 * Test class for Categories.
 *
 * @since  1.0
 */
class CategoriesTest extends TestCase
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
	 * @var    Categories  Object under test.
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

		$this->object = new Categories($this->options, $this->client);
	}

	/**
	 * Tests the getCategories method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testGetCategories()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&prop=categories&titles=Main Page&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getCategories(['Main Page']),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}

	/**
	 * Tests the getCategoriesUsed method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testGetCategoriesUsed()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&generator=categories&prop=info&titles=Main Page&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getCategoriesUsed(['Main Page']),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}

	/**
	 * Tests the getCategoriesInfo method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testGetCategoriesInfo()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&prop=categoryinfo&titles=Main Page&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getCategoriesInfo(['Main Page']),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}

	/**
	 * Tests the getCategoryMembers method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testGetCategoryMembers()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&list=categorymembers&cmtitle=Category:Help&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getCategoryMembers('Category:Help'),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}

	/**
	 * Tests the enumerateCategories method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testEnumerateCategories()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&list=allcategories&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->enumerateCategories(),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}

	/**
	 * Tests the getChangeTags method
	 *
	 * @return void
	 *
	 * @since  1.0
	 */
	public function testGetChangeTags()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&list=tags&format=xml')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getChangeTags(),
			$this->equalTo(simplexml_load_string($this->sampleString))
		);
	}
}
