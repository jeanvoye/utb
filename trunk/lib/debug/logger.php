<?php

/**
 * File containing the debugLogger class.
 *
 * @package
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */


/**
 * The debugLogger class is a wrapper around the ezcDebug system.
 * It will be called from places in the code where debug is needed,
 * but debug will be actually written only when the development mode
 * is enabled in ezcBase.
 */
class debugLogger
{
    /**
     * Decorated debug handler.
     * @var ezcDebug
     */
    private static $ezcDebugInstance = null;

    public static function __callstatic( $name, $args )
    {
        if ( ezcBase::getRunMode() == ezcBase::MODE_DEVELOPMENT )
        {
            return call_user_func_array( array( self::$ezcDebugInstance, $name ), $args );
        }
        else
            return null;
    }

    /**
     * Stores an instance of ezcDebug as class member.
     *
     * @param ezcDebug $handler instance of ezcDebug
     */
    public static function setHandler( ezcDebug $handler )
    {
        self::$ezcDebugInstance = $handler;
    }
}
 ?>