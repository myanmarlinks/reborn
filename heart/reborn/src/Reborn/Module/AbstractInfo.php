<?php

namespace Reborn\Module;

use \Reborn\Util\Str;

/**
 * Module Info Abstract Class for Reborn Module
 *
 * @package Reborn\Module
 * @author Myanmar Links Professional Web Development Team
 **/
abstract class AbstractInfo
{
	/**
	 * Module name variable
	 *
	 * @var string
	 **/
	protected $name;

	/**
	 * Module version variable
	 *
	 * @var string
	 **/
	protected $version;

	/**
	 * Module description variable
	 *
	 * @var string
	 **/
	protected $description = '';

	/**
	 * Module author name variable
	 *
	 * @var string
	 **/
	protected $author;

	/**
	 * Module author URL variable
	 *
	 * @var string
	 **/
	protected $authorUrl;

	/**
	 * Module author Email variable
	 *
	 * @var string
	 **/
	protected $authorEmail;

	/**
	 * Module Frontend support variable
	 *
	 * @var boolean
	 **/
	protected $frontendSupport;

	/**
	 * Module Backend support variable
	 *
	 * @var boolean
	 **/
	protected $backendSupport;

	/**
	 * Module can be use Default Module for Frontend variable
	 *
	 * @var boolean
	 **/
	protected $useAsDefaultModule = false;

	/**
	 * Module's URI Prefix variable
	 *
	 * @var string
	 **/
	protected $uriPrefix;

	/**
	 * Variable for Allow to change the URI Prefix from user.
	 *
	 * @var boolean
	 **/
	protected $allowToChangeUriPrefix = false;

	/**
	 * Variable for Module Actions Roles list.
	 * Module Action permission will be decided on this role.
	 *
	 * @var array
	 **/
	protected $roles = array();

	/**
	 * Get the All Module Info
	 *
	 * @return array
	 */
	public function getAll()
	{
		$isCore = false;
		$cores = \Config::get('app.module.cores');
		$ref = new \ReflectionObject($this);
		$ns = $ref->getNamespaceName();
		$path = dirname($ref->getFileName());

		if (in_array($ns, $cores)) {
			$isCore = true;
		}

		// This is Temp
		$uri = (is_null($this->uriPrefix)) ? Str::snake($ns, '-') : $this->uriPrefix;

		return array(
				'ns' => $ns, // Namespace for $this module
				'uri' => $uri, //$this->uriPrefix,
				'path' => $path.DS, // Module path
				'name' => $this->name, // Name of $this module
				'roles' => $this->roles, // Module Action roles
				'isCore' => $isCore, // Module is Core Module or not
				'version' => $this->version, // Version no. string of $this module
				'description' => $this->description, // Description of $this module
				'author' => $this->author, // Author of $this module
				'authorUrl' => $this->authorUrl, // Author URL of $this module
				'authorEmail' => $this->authorEmail, // Author Email of $this module
				'frontendSupport' => $this->frontendSupport,
				'backendSupport' => $this->backendSupport,
				'useAsDefaultModule' => $this->useAsDefaultModule,
				'allowUriChange' => $this->allowToChangeUriPrefix
			);
	}

	/**
	 * Magic method __call
	 *
	 */
	public function __call($method, $args)
	{
		$property = lcfirst(substr($method, 3));

		if (property_exists($this, $property)) {
			return $this->{$property};
		}
	}

} // End  of class AbstractInfo
