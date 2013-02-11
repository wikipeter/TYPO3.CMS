<?php
namespace TYPO3\CMS\Core\Core\Booting;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Thomas Maroschik <tmaroschik@dfau.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
require_once __DIR__ . '/../../../../TYPO3.Flow/Classes/TYPO3/Flow/Core/Booting/Scripts.php';

/**
 */
class Scripts extends \TYPO3\Flow\Core\Booting\Scripts {

	/**
	 * Load several base classes during bootstrap
	 *
	 * @return void
	 */
	static public function requireBaseClasses() {
		require_once __DIR__ . '/../../Utility/PhpOptionsUtility.php';
		require_once __DIR__ . '/../SystemEnvironmentBuilder.php';
		require_once __DIR__ . '/../../../../TYPO3.Flow/Classes/TYPO3/Flow/Core/ClassLoader.php';
//		if (PHP_VERSION_ID < 50307) {
//			require_once __DIR__ . '/../Compatibility/CompatbilityClassLoaderPhpBelow50307.php';
//		}
	}

	/**
	 *
	 */
	static public function defineConstants() {
		// This version, branch and copyright
		define('TYPO3_version', '6.1-dev');
		define('TYPO3_branch', '6.1');
		define('TYPO3_copyright_year', '1998-2012');
		// TYPO3 external links
		define('TYPO3_URL_GENERAL', 'http://typo3.org/');
		define('TYPO3_URL_ORG', 'http://typo3.org/');
		define('TYPO3_URL_LICENSE', 'http://typo3.org/licenses');
		define('TYPO3_URL_EXCEPTION', 'http://typo3.org/go/exception/v4/');
		define('TYPO3_URL_MAILINGLISTS', 'http://lists.typo3.org/cgi-bin/mailman/listinfo');
		define('TYPO3_URL_DOCUMENTATION', 'http://typo3.org/documentation/');
		define('TYPO3_URL_DOCUMENTATION_TSREF', 'http://typo3.org/documentation/document-library/core-documentation/doc_core_tsref/current/view/');
		define('TYPO3_URL_DOCUMENTATION_TSCONFIG', 'http://typo3.org/documentation/document-library/core-documentation/doc_core_tsconfig/current/view/');
		define('TYPO3_URL_CONSULTANCY', 'http://typo3.org/support/professional-services/');
		define('TYPO3_URL_CONTRIBUTE', 'http://typo3.org/contribute/');
		define('TYPO3_URL_SECURITY', 'http://typo3.org/teams/security/');
		define('TYPO3_URL_DOWNLOAD', 'http://typo3.org/download/');
		define('TYPO3_URL_SYSTEMREQUIREMENTS', 'http://typo3.org/about/typo3-the-cms/system-requirements/');
		define('TYPO3_URL_DONATE', 'http://typo3.org/donate/online-donation/');
		// A tabulator, a linefeed, a carriage return, a CR-LF combination
		define('TAB', chr(9));
		define('LF', chr(10));
		define('CR', chr(13));
		define('CRLF', CR . LF);
		// Security related constant: Default value of fileDenyPattern
		define('FILE_DENY_PATTERN_DEFAULT', '\\.(php[3-6]?|phpsh|phtml)(\\..*)?$|^\\.htaccess$');
		// Security related constant: List of file extensions that should be registered as php script file extensions
		define('PHP_EXTENSIONS_DEFAULT', 'php,php3,php4,php5,php6,phpsh,inc,phtml');
		// List of extensions required to run the core
		define('REQUIRED_EXTENSIONS', 'TYPO3.Flow,TYPO3.CMS.Core,backend,TYPO3.CMS.Frontend,cms,lang,sv,extensionmanager,recordlist,TYPO3.CMS.Extbase,TYPO3.CMS.Fluid,cshmanual,TYPO3.Party');
		// Operating system identifier
		// Either "WIN" or empty string
		define('TYPO3_OS', (!stristr(PHP_OS, 'darwin') && stristr(PHP_OS, 'win')) ? 'WIN' : '');
		static::defineFlowConstants();
	}

	/**
	 *
	 */
	static protected function defineFlowConstants() {
		if (defined('FLOW_SAPITYPE')) {
			return;
		}
		define('FLOW_SAPITYPE', (PHP_SAPI === 'cli' ? 'CLI' : 'Web'));

		//@TODO This one has to be replaced everytime FLOW is beeing updated
		define('FLOW_VERSION_BRANCH', '2.0');
	}

	/**
	 * @param string $relativePathPart
	 */
	static public function definePathConstants(\TYPO3\Flow\Core\ApplicationContext $context) {
		// Relative path from document root to typo3/ directory
		// Hardcoded to "typo3/"
		define('TYPO3_mainDir', 'typo3/');
		// Absolute path of the entry script that was called
		// All paths are unified between Windows and Unix, so the \ of Windows is substituted to a /
		// Example "/var/www/instance-name/htdocs/typo3conf/ext/wec_map/mod1/index.php"
		// Example "c:/var/www/instance-name/htdocs/typo3/backend.php" for a path in Windows
		define('PATH_thisScript', \TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::buildPathThisScript());
		// Absolute path of the document root of the instance with trailing slash
		// Example "/var/www/instance-name/htdocs/"
		$relativePathPart = '';
		if (PHP_SAPI === 'cli') {
			$relativePathPart = 'typo3/sysext/TYPO3.CMS.Core/Scripts/';
		}
		define('PATH_site', \TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::buildPathSite($relativePathPart));
		// Absolute path of the typo3 directory of the instance with trailing slash
		// Example "/var/www/instance-name/htdocs/typo3/"
		define('PATH_typo3', PATH_site . TYPO3_mainDir);
		// Relative path (from the PATH_typo3) to a BE module NOT using mod.php dispatcher with trailing slash
		// Example "sysext/perms/mod/" for an extension installed in typo3/sysext/
		// Example "install/" for the install tool entry script
		// Example "../typo3conf/ext/templavoila/mod2/ for an extension installed in typo3conf/ext/
		define('PATH_typo3_mod', defined('TYPO3_MOD_PATH') ? TYPO3_MOD_PATH : '');
		// Absolute path to the t3lib directory with trailing slash
		// Example "/var/www/instance-name/htdocs/t3lib/"
		define('PATH_t3lib', PATH_site . 't3lib/');
		// Absolute path to the typo3conf directory with trailing slash
		// Example "/var/www/instance-name/htdocs/typo3conf/"
		define('PATH_typo3conf', PATH_site . 'typo3conf/');
		// Absolute path to the tslib directory with trailing slash
		// Example "/var/www/instance-name/htdocs/typo3/sysext/cms/tslib/"
		define('PATH_tslib', PATH_typo3 . 'sysext/cms/tslib/');
		static::defineFlowPathConstants();
	}

	/**
	 *
	 */
	static protected function defineFlowPathConstants() {
		if (!defined('FLOW_PATH_FLOW')) {
			define('FLOW_PATH_FLOW', PATH_typo3 . 'sysext/TYPO3.Flow/');
		}

		if (!defined('FLOW_PATH_ROOT')) {
			$rootPath = PATH_site;
			if ($rootPath !== FALSE) {
				$testPath = \TYPO3\Flow\Utility\Files::getUnixStylePath(realpath(\TYPO3\Flow\Utility\Files::concatenatePaths(array(PATH_typo3, 'sysext/TYPO3.Flow')))) . '/';
				$expectedPath = \TYPO3\Flow\Utility\Files::getUnixStylePath(realpath(FLOW_PATH_FLOW)) . '/';
				if ($testPath !== $expectedPath) {
					echo('Flow: Invalid root path. (Error #1248964375)' . PHP_EOL . '"' . $testPath . '" does not lead to' . PHP_EOL . '"' . $expectedPath .'"' . PHP_EOL);
					exit(1);
				}
				define('FLOW_PATH_ROOT', $rootPath);
				unset($rootPath);
				unset($testPath);
			}
		}

		if (FLOW_SAPITYPE === 'CLI') {
			if (!defined('FLOW_PATH_ROOT')) {
				echo('Flow: No root path defined in environment variable FLOW_ROOTPATH (Error #1248964376)' . PHP_EOL);
				exit(1);
			}
			if (!defined('FLOW_PATH_WEB')) {
				if (isset($_SERVER['FLOW_WEBPATH']) && is_dir($_SERVER['FLOW_WEBPATH'])) {
					define('FLOW_PATH_WEB', \TYPO3\Flow\Utility\Files::getUnixStylePath(realpath($_SERVER['FLOW_WEBPATH'])) . '/');
				} else {
					define('FLOW_PATH_WEB', FLOW_PATH_ROOT . 'Web/');
				}
			}
		} else {
			if (!defined('FLOW_PATH_ROOT')) {
				define('FLOW_PATH_ROOT', \TYPO3\Flow\Utility\Files::getUnixStylePath(realpath(dirname($_SERVER['SCRIPT_FILENAME']) . '/../')) . '/');
			}
			define('FLOW_PATH_WEB', \TYPO3\Flow\Utility\Files::getUnixStylePath(realpath(dirname($_SERVER['SCRIPT_FILENAME']))) . '/');
		}

		define('FLOW_PATH_CONFIGURATION', PATH_typo3conf);
		define('FLOW_PATH_DATA', FLOW_PATH_ROOT . 'uploads/');
		define('FLOW_PATH_PACKAGES', PATH_typo3conf . 'Packages/');
	}

	/**
	 *
	 */
	static public function ensureLinkedFlowDirectories() {
		if (!is_dir(FLOW_PATH_CONFIGURATION) && !is_link(FLOW_PATH_CONFIGURATION)) {
			if (!@mkdir(FLOW_PATH_CONFIGURATION)) {
				echo('TYPO3 CMS could not create the directory "' . FLOW_PATH_CONFIGURATION . '". Please check the file permissions manually.');
				exit(1);
			}
		}
		if (!is_dir(FLOW_PATH_PACKAGES) && !is_link(FLOW_PATH_PACKAGES)) {
			if (!@mkdir(FLOW_PATH_PACKAGES)) {
				echo('TYPO3 CMS could not create the directory "' . FLOW_PATH_PACKAGES . '". Please check the file permissions manually.');
				exit(1);
			}
		}
		if (!is_link(FLOW_PATH_PACKAGES . 'Application')) {
			if (!@symlink(PATH_typo3conf . 'ext/', FLOW_PATH_PACKAGES . 'Application')) {
				echo('TYPO3 CMS could not link the directory "' . FLOW_PATH_PACKAGES . 'Application". Please check the file permissions manually.');
				exit(1);
			}
		}
		if (!is_link(FLOW_PATH_PACKAGES . 'Framework')) {
			if (!@symlink(PATH_typo3 . 'sysext/', FLOW_PATH_PACKAGES . 'Framework')) {
				echo('TYPO3 CMS could not link the directory "' . FLOW_PATH_PACKAGES . 'Framework". Please check the file permissions manually.');
				exit(1);
			}
		}
		if (!is_dir(FLOW_PATH_PACKAGES . 'Libraries') && !is_link(FLOW_PATH_PACKAGES . 'Libraries')) {
			if (!@mkdir(FLOW_PATH_PACKAGES . 'Libraries')) {
				echo('TYPO3 CMS could not create the directory "' . FLOW_PATH_PACKAGES . 'Libraries". Please check the file permissions manually.');
				exit(1);
			}
		}
		if (!is_dir(FLOW_PATH_DATA) && !is_link(FLOW_PATH_DATA)) {
			if (!@mkdir(FLOW_PATH_DATA)) {
				echo('TYPO3 CMS could not link the directory "' . FLOW_PATH_DATA . '". Please check the file permissions manually.');
				exit(1);
			}
		}
		if (!is_link(FLOW_PATH_DATA . 'Persistent')) {
			if (!@symlink(FLOW_PATH_DATA, FLOW_PATH_DATA . 'Persistent')) {
				echo('TYPO3 CMS could not link the directory "' . FLOW_PATH_DATA . 'Persistent". Please check the file permissions manually.');
				exit(1);
			}
		}
		if (!is_link(FLOW_PATH_DATA . 'Temporary')) {
			if (!@symlink(PATH_site . 'typo3temp/', FLOW_PATH_DATA . 'Temporary')) {
				echo('TYPO3 CMS could not link the directory "' . FLOW_PATH_DATA . 'Temporary". Please check the file permissions manually.');
				exit(1);
			}
		}
	}

	/**
	 * Initializes the Class Loader
	 *
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 * @return void
	 */
	static public function initializeClassLoader(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		require_once(PATH_typo3 . 'sysext/TYPO3.CMS.Core/Classes/Core/ClassLoader.php');
		require_once(PATH_typo3 . 'sysext/TYPO3.CMS.Core/Classes/Core/ClassAliasMap.php');
		$classLoader = new \TYPO3\CMS\Core\Core\ClassLoader();
		$classAliasMap = new \TYPO3\CMS\Core\Core\ClassAliasMap();
		$classLoader->injectClassAliasMap($classAliasMap);
		spl_autoload_register(array($classLoader, 'loadClass'), TRUE, TRUE);
		$bootstrap->setEarlyInstance('TYPO3\Flow\Core\ClassLoader', $classLoader);
		$bootstrap->setEarlyInstance('TYPO3\CMS\Core\Core\ClassAliasMap', $classAliasMap);
	}


	/**
	 * Populate the local configuration.
	 * Merge default TYPO3_CONF_VARS with content of typo3conf/LocalConfiguration.php,
	 * execute typo3conf/AdditionalConfiguration.php, define database related constants.
	 *
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 * @return \TYPO3\CMS\Core\Core\Bootstrap
	 */
	static public function initializeLocalConfiguration(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		try {
			// We need an early instance of the configuration manager.
			// Since makeInstance relies on the object configuration, we create it here with new instead
			// and register it as singleton instance for later use.
			$configuarationManager = new \TYPO3\CMS\Core\Configuration\ConfigurationManager();
			$bootstrap->setEarlyInstance('TYPO3\CMS\Core\Configuration\ConfigurationManager', $configuarationManager);
			$configuarationManager->exportConfiguration();
		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	/**
	 * Initializes the package system and loads the package configuration and settings
	 * provided by the packages.
	 *
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 * @return void
	 */
	static public function initializePackageManagement(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$packageManager = new \TYPO3\CMS\Core\Package\PackageManager();
		$bootstrap->setEarlyInstance('TYPO3\Flow\Package\PackageManagerInterface', $packageManager);
		$packageManager->injectClassLoader($bootstrap->getEarlyInstance('TYPO3\Flow\Core\ClassLoader'));
		$packageManager->initialize($bootstrap, PATH_site);
	}

	/**
	 * @param \TYPO3\CMS\Core\Core\Bootstrap $bootstrap
	 */
	static public function initializeClassAliasMapping(\TYPO3\CMS\Core\Core\Bootstrap $bootstrap) {
		$classesCache = $bootstrap->getEarlyInstance('TYPO3\Flow\Cache\CacheManager')->getCache('Core_Object_ClassAliases');
		$classAliasMap = $bootstrap->getEarlyInstance('TYPO3\CMS\Core\Core\ClassAliasMap');
		$classAliasMap->injectClassAliasCache($classesCache);
		$classAliasMap->initialize();
	}

	/**
	 * Initializes the runtime Object Manager
	 *
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 * @return void
	 */
	static public function initializeObjectManager(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$configurationManager = $bootstrap->getEarlyInstance('TYPO3\Flow\Configuration\ConfigurationManager');
		$objectConfigurationCache = $bootstrap->getEarlyInstance('TYPO3\Flow\Cache\CacheManager')->getCache('Flow_Object_Configuration');

		$objectManager = new \TYPO3\CMS\Core\Object\ObjectManager($bootstrap->getContext());
		\TYPO3\CMS\Core\Core\Bootstrap::$staticObjectManager = $objectManager;

		$objectManager->injectClassAliasMap($bootstrap->getEarlyInstance('TYPO3\CMS\Core\Core\ClassAliasMap'));
		$objectManager->injectAllSettings($configurationManager->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS));
		$objectManager->setObjects($objectConfigurationCache->get('objects'));

		foreach ($bootstrap->getEarlyInstances() as $objectName => $instance) {
			$objectManager->setInstance($objectName, $instance);
		}

		$objectManager->get('TYPO3\Flow\SignalSlot\Dispatcher')->injectObjectManager($objectManager);
		\TYPO3\Flow\Error\Debugger::injectObjectManager($objectManager);
		$bootstrap->setEarlyInstance('TYPO3\Flow\Object\ObjectManagerInterface', $objectManager);
	}

	/**
	 * Executes the given command as a sub-request to the Flow CLI system.
	 *
	 * @param string $commandIdentifier E.g. typo3.flow:cache:flush
	 * @param array $settings The Flow settings
	 * @param boolean $outputResults if FALSE the output of this command is only echoed if the execution was not successful
	 * @return boolean TRUE if the command execution was successful (exit code = 0)
	 * @api
	 * @throws \TYPO3\Flow\Core\Booting\Exception\SubProcessException if execution of the sub process failed
	 */
	static public function executeCommand($commandIdentifier, array $settings, $outputResults = TRUE) {
		$subRequestEnvironmentVariables = array(
			'FLOW_ROOTPATH' => FLOW_PATH_ROOT,
			'FLOW_CONTEXT' => $settings['core']['context']
		);
		if (isset($settings['core']['subRequestEnvironmentVariables'])) {
			$subRequestEnvironmentVariables = array_merge($subRequestEnvironmentVariables, $settings['core']['subRequestEnvironmentVariables']);
		}

		$command = '';
		foreach ($subRequestEnvironmentVariables as $argumentKey => $argumentValue) {
			if (DIRECTORY_SEPARATOR === '/') {
				$command .= sprintf('%s=%s ', $argumentKey, escapeshellarg($argumentValue));
			} else {
				$command .= sprintf('SET %s=%s&', $argumentKey, escapeshellarg($argumentValue));
			}
		}
		if (DIRECTORY_SEPARATOR === '/') {
			$phpBinaryPathAndFilename = '"' . escapeshellcmd(\TYPO3\Flow\Utility\Files::getUnixStylePath($settings['core']['phpBinaryPathAndFilename'])) . '"';
		} else {
			$phpBinaryPathAndFilename = escapeshellarg(\TYPO3\Flow\Utility\Files::getUnixStylePath($settings['core']['phpBinaryPathAndFilename']));
		}
		$command .= sprintf('%s -c %s %s %s', $phpBinaryPathAndFilename, escapeshellarg(php_ini_loaded_file()), escapeshellarg(PATH_typo3 . 'sysext/TYPO3.CMS.Core/Scripts/core.php'), escapeshellarg($commandIdentifier));
		$output = array();
		exec($command, $output, $result);
		if ($result !== 0) {
			$exceptionMessage = sprintf('Execution of subprocess failed with exit code %d', $result);
			if (count($output) > 0) {
				$exceptionMessage .= ' and output:' .  PHP_EOL . PHP_EOL . implode(PHP_EOL, $output);
			} else {
				$exceptionMessage .= ' and no output.';
			}
			$exceptionMessage .= PHP_EOL . PHP_EOL . 'The erroneous command was:' . PHP_EOL . $command;
			throw new \TYPO3\Flow\Core\Booting\Exception\SubProcessException($exceptionMessage, 1355480641);
		}
		if ($outputResults) {
			echo implode(PHP_EOL, $output);
		}
		return $result === 0;
	}

}


?>