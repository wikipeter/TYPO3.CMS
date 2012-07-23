<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2012
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Testcase for the Tx_Extensionmanager_Utility_List class in the TYPO3 Core.
 *
 * @package TYPO3
 * @subpackage extensionmanager
 */
class Tx_Extensionmanager_Utility_ListTest extends tx_phpunit_testcase {
	/**
	 * @var Tx_Extensionmanager_Utility_List
	 */
	private $fixture;

	private $loadedExtensions = array();

	public function setUp() {
		$this->fixture = new Tx_Extensionmanager_Utility_List();
		$this->loadedExtensions = $GLOBALS['TYPO3_LOADED_EXT'];
		$GLOBALS['TYPO3_LOADED_EXT'] = array(
			'cms' => 'cms',
			'lang' => 'lang',
			'news' => 'news',
			'saltedpasswords' => 'saltedpasswords',
			'rsaauth' => 'rsaauth',
		);
	}

	public function tearDown() {
		unset($this->fixture);
		$GLOBALS['TYPO3_LOADED_EXT'] = $this->loadedExtensions;
	}


	/**
	 * @return array
	 */
	public function getAvailableAndInstalledExtensionsDataProvider() {
		return array(
			'same extension lists' => array(
				array(
					'cms' => array(),
					'lang' => array(),
					'news' => array(),
					'saltedpasswords' => array(),
					'rsaauth' =>  array(),
				),
				array(
					'cms' => array('installed' => true),
					'lang' => array('installed' => true),
					'news' => array('installed' => true),
					'saltedpasswords' => array('installed' => true),
					'rsaauth' => array('installed' => true),
				)
			),
			'different extension lists' => array(
				array(
					'cms' => array(),
					'lang' => array(),
					'news' => array(),
					'saltedpasswords' => array(),
					'rsaauth' => array(),
				),
				array(
					'cms' => array('installed' => true),
					'lang' => array('installed' => true),
					'news' => array('installed' => true),
					'saltedpasswords' => array('installed' => true),
					'rsaauth' => array('installed' => true),
				),
			),
			'different extension lists - set2' => array(
				array(
					'cms' => array(),
					'lang' => array(),
					'news' => array(),
					'saltedpasswords' => array(),
					'rsaauth' => array(),
					'em' => array(),
				),
				array(
					'cms' => array('installed' => true),
					'lang' => array('installed' => true),
					'news' => array('installed' => true),
					'saltedpasswords' => array('installed' => true),
					'rsaauth' => array('installed' => true),
					'em' => array(),
				)
			),
			'different extension lists - set3' => array(
				array(
					'cms' => array(),
					'lang' => array(),
					'fluid' => array(),
					'news' => array(),
					'saltedpasswords' => array(),
					'rsaauth' => array(),
					'em' => array(),
				),
				array(
					'cms' => array('installed' => true),
					'lang' => array('installed' => true),
					'fluid' => array(),
					'news' => array('installed' => true),
					'saltedpasswords' => array('installed' => true),
					'rsaauth' => array('installed' => true),
					'em' => array(),
				)
			)
		);
	}


	/**
	 * @test
	 * @dataProvider getAvailableAndInstalledExtensionsDataProvider
	 */
	public function getAvailableAndInstalledExtensionsTest($availableExtensions, $expectedResult) {
		$this->assertEquals($expectedResult, $this->fixture->getAvailableAndInstalledExtensions($availableExtensions));
	}
	/**
	 * @return array
	 */
	public function enrichExtensionsWithEmConfInformationDataProvider() {
		return array(
			'simple key value array emconf' => array(
				array(
					'cms' => array('test' => 'test2'),
					'lang' => array('property1' => 'oldvalue'),
					'news' => array(),
					'saltedpasswords' => array(),
					'rsaauth' =>  array(),
				),
				array(
					'property1' => 'property value1'
				),
				array(
					'cms' => array('test' => 'test2', 'property1' => 'property value1'),
					'lang' => array('property1' => 'oldvalue'),
					'news' => array('property1' => 'property value1'),
					'saltedpasswords' => array('property1' => 'property value1'),
					'rsaauth' =>  array('property1' => 'property value1'),
				),
			),
		);
	}


	/**
	 * @test
	 * @dataProvider enrichExtensionsWithEmConfInformationDataProvider
	 */
	public function enrichExtensionsWithEmConfInformation($extensions, $emConf, $expectedResult) {
		$this->fixture->emConfUtility = $this->getMock('Tx_Extensionmanager_Utility_EmConf');
		$this->fixture->emConfUtility->expects($this->any())->method('includeEmConf')->will($this->returnValue($emConf));
		$this->assertEquals($expectedResult, $this->fixture->enrichExtensionsWithEmConfAndTerInformation($extensions));
	}
}
?>