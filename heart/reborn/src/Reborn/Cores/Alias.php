<?php

namespace Reborn\Cores;

use Reborn\Exception\RbException;

/**
 * Class Alias Library for Reborn
 *
 * @package Reborn\Cores
 * @author Myanmar Links Professional Web Development Team
 **/
class Alias
{

    /**
     * Marking for Reborn's Core Class are alias or not
     *
     * @var bool
     **/
    protected static $coreIsAlias = false;

    /**
     * Array list for Reborn's Core Class
     *
     * @var array
     **/
    protected static $coreClasses = array(
            'Cache' => 'Reborn\Cache\CacheManager',
            'Config' => 'Reborn\Config\Config',
            'AdminController' => 'Reborn\MVC\Controller\AdminController',
            'PublicController' => 'Reborn\MVC\Controller\PublicController',
            'PrivateController' => 'Reborn\MVC\Controller\PrivateController',
            'Controller' => 'Reborn\MVC\Controller\Controller',
            'DB' => 'Reborn\Connector\DB\DBManager',
            'Dir' => 'Reborn\Filesystem\Directory',
            'Eloquent' => 'Illuminate\Database\Eloquent\Model',
            'Event' => 'Reborn\Event\EventManager',
            'File' => 'Reborn\Filesystem\File',
            'Flash' => 'Reborn\Util\Flash',
            'Hash' => 'Reborn\Util\Hash',
            'Html' => 'Reborn\Util\Html',
            'Pagination' => 'Reborn\Util\Pagination',
            'RbException' => 'Reborn\Exception\RbException',
            'Request' => 'Reborn\Http\Request',
            'Redirect' => 'Reborn\Http\Redirect',
            'Form' => 'Reborn\Form\Form',
            'Input' => 'Reborn\Http\Input',
            'Log' => 'Reborn\Connector\Log\LogManager',
            'Module' => 'Reborn\Module\ModuleManager',
            'Registry' => 'Reborn\Cores\Registry',
            'Route' => 'Reborn\Route\Route',
            'Router' => 'Reborn\Route\Router',
            'Model' => 'Reborn\MVC\Model\Model',
            'Schema' => 'Reborn\Connector\DB\Schema',
            'Security' => 'Reborn\Util\Security',
            'Sentry' => 'Reborn\Connector\Sentry\Sentry',
            'Setting' => 'Reborn\Cores\Setting',
            'Str' => 'Reborn\Util\Str',
            'Translate' => 'Reborn\Translate\TranslateManager',
            'Uri' => 'Reborn\Http\Uri',
            'Validation' => 'Reborn\Form\Validation',
            'Widget' => 'Reborn\Widget\Widget',
            'AbstractWidget' => 'Reborn\Widget\AbstractWidget'
        );

    /**
     * Construct method is final, don't allow to override these method.
     *
     */
    final function __construct() {}

    /**
     * Class Alias for Reborn's Cores Class.
     * This method is call when application started.
     * But not allow to call after one time.
     *
     * @return void
     */
    public static function coreClassAlias()
    {
        if (! static::$coreIsAlias) {
            static::aliasRegister(static::$coreClasses);
            static::$coreIsAlias = true;
        }
    }

    /**
     * Register the class alias.
     * You can use your class (external of Reborn's Cores) to alies use this method.
     * <code>
     *      // If you want to use Products\Libs\Shopper to Shopper
     *      Alias::aliasRegister(array('Shopper' => 'Products\Libs\Shopper'));
     *
     *      // Ok, Shopper class is aliased now
     *      Shopper::calculate();
     * </code>
     *
     * @param array $classes Array data for class alias
     * @return void
     */
    public static function aliasRegister($classes = array())
    {
        foreach ($classes as $alias => $class) {
            if (static::$coreIsAlias) {
                if (array_key_exists($alias, static::$coreClasses) and
                $class == static::$coreClasses[$alias]) {
                    throw new RbException("$alias is already exists!");
                }
            }

            class_alias($class, $alias);
        }
    }

} // END class Alias
