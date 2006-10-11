<?php
/**
 * File containing the ezcPersistentRelatedObjectNotFoundException class
 *
 * @package PersistentObject
 * @version //autogen//
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Exception thrown, if the given relation class ćould not be found.
 *
 * @package PersistentObject
 * @version //autogen//
 */
class ezcPersistentRelatedObjectNotFoundException extends ezcPersistentObjectException
{

    /**
     * Constructs a new ezcPersistentRelatedObjectNotFoundException for the class $class
     * which does not have a relation for $relatedClass.
     *
     * @param string $class
     * @param string $relatedClass
     * @return void
     */
    public function __construct( $object, $relatedClass )
    {
        parent::__construct( "No related object found with class <{$relatedClass}> for object of class <" . get_class( $object ) . ">." );
    }
}
?>