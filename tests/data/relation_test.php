<?php
/**
 * File containing test code for the PersistentObject component.
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package PersistentObject
 * @version //autogentag//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

class RelationTest
{
    /**
     * Insert data for the test
     */
    public static function insertData( $db = null )
    {
        $db = ( $db === null ? ezcDbInstance::get() : $db );

        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_addresses" ) . " (" . $db->quoteIdentifier( "street" ) . ", " . $db->quoteIdentifier( "zip" ) . ", " . $db->quoteIdentifier( "city" ) . ", " . $db->quoteIdentifier( "type" ) . ") VALUES (" . $db->quote( "Httproad 23" ) . ", " . $db->quote( "12345" ) . ", " . $db->quote( "Internettown" ) . ", " . $db->quote( "work" ) . ")" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_addresses" ) . " (" . $db->quoteIdentifier( "street" ) . ", " . $db->quoteIdentifier( "zip" ) . ", " . $db->quoteIdentifier( "city" ) . ", " . $db->quoteIdentifier( "type" ) . ") VALUES (" . $db->quote( "Ftpstreet 42" ) . ", " . $db->quote( "12345" ) . ", " . $db->quote( "Internettown" ) . ", " . $db->quote( "work" ) . ")" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_addresses" ) . " (" . $db->quoteIdentifier( "street" ) . ", " . $db->quoteIdentifier( "zip" ) . ", " . $db->quoteIdentifier( "city" ) . ", " . $db->quoteIdentifier( "type" ) . ") VALUES (" . $db->quote( "Phpavenue 21" ) . ", " . $db->quote( "12345" ) . ", " . $db->quote( "Internettown" ) . ", " . $db->quote( "private" ) . ")" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_addresses" ) . " (" . $db->quoteIdentifier( "street" ) . ", " . $db->quoteIdentifier( "zip" ) . ", " . $db->quoteIdentifier( "city" ) . ", " . $db->quoteIdentifier( "type" ) . ") VALUES (" . $db->quote( "Pythonstreet 13" ) . ", " . $db->quote( "12345" ) . ", " . $db->quote( "Internettown" ) . ", " . $db->quote( "private" ) . ")" );

        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_employers" ) . " (" . $db->quoteIdentifier( "name" ) . ") VALUES (" . $db->quote( "Great Web 2.0 company" ) . ")" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_employers" ) . " (" . $db->quoteIdentifier( "name" ) . ") VALUES (" . $db->quote( "Oldschool Web 1.x company" ) . ")" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_employers" ) . " (" . $db->quoteIdentifier( "name" ) . ") VALUES (" . $db->quote( "Very oldschool print media company" ) . ")" );

        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons" ) . " (" . $db->quoteIdentifier( "firstname" ) . ", " . $db->quoteIdentifier( "surname" ) . ", " . $db->quoteIdentifier( "employer" ) . ") VALUES (" . $db->quote( "Theodor" ) . ", " . $db->quote( "Gopher" ) . ", 2)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons" ) . " (" . $db->quoteIdentifier( "firstname" ) . ", " . $db->quoteIdentifier( "surname" ) . ", " . $db->quoteIdentifier( "employer" ) . ") VALUES (" . $db->quote( "Frederick" ) . ", " . $db->quote( "Ajax" ) . ", 1)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons" ) . " (" . $db->quoteIdentifier( "firstname" ) . ", " . $db->quoteIdentifier( "surname" ) . ", " . $db->quoteIdentifier( "employer" ) . ") VALUES (" . $db->quote( "Raymond" ) . ", " . $db->quote( "Socialweb" ) . ", 1)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons" ) . " (" . $db->quoteIdentifier( "firstname" ) . ", " . $db->quoteIdentifier( "surname" ) . ", " . $db->quoteIdentifier( "employer" ) . ") VALUES (" . $db->quote( "Pete" ) . ", " . $db->quote( "Javascript" ) . ", 0)" );

        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons_addresses" ) . " ( " . $db->quoteIdentifier( "person_id" ) . ", " . $db->quoteIdentifier( "address_id" ) . ") VALUES ( 1, 1)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons_addresses" ) . " ( " . $db->quoteIdentifier( "person_id" ) . ", " . $db->quoteIdentifier( "address_id" ) . ") VALUES ( 1, 2)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons_addresses" ) . " ( " . $db->quoteIdentifier( "person_id" ) . ", " . $db->quoteIdentifier( "address_id" ) . ") VALUES ( 1, 4)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons_addresses" ) . " ( " . $db->quoteIdentifier( "person_id" ) . ", " . $db->quoteIdentifier( "address_id" ) . ") VALUES ( 2, 1)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons_addresses" ) . " ( " . $db->quoteIdentifier( "person_id" ) . ", " . $db->quoteIdentifier( "address_id" ) . ") VALUES ( 2, 3)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons_addresses" ) . " ( " . $db->quoteIdentifier( "person_id" ) . ", " . $db->quoteIdentifier( "address_id" ) . ") VALUES ( 2, 4)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_persons_addresses" ) . " ( " . $db->quoteIdentifier( "person_id" ) . ", " . $db->quoteIdentifier( "address_id" ) . ") VALUES ( 3, 4)" );
        
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_secondpersons_addresses" ) . " ( " . $db->quoteIdentifier( "person_firstname" ) . ", " . $db->quoteIdentifier( "person_surname" ) . ", " . $db->quoteIdentifier( "address_id" ) . ") VALUES ( " . $db->quote( "Theodor" ) . ", " . $db->quote( "Gopher" ) . ", 1)" );

        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_birthdays" ) . " (" . $db->quoteIdentifier( "person_id" ) . ", " . $db->quoteIdentifier( "birthday" ) . ") VALUES (1, 327535201)"  );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_birthdays" ) . " (" . $db->quoteIdentifier( "person_id" ) . ", " . $db->quoteIdentifier( "birthday" ) . ") VALUES (2, -138243599)" );
        
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_friends" ) . " (" . $db->quoteIdentifier( "id" ) . ", " . $db->quoteIdentifier( "friend_id" ) . ") VALUES (1, 2)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_friends" ) . " (" . $db->quoteIdentifier( "id" ) . ", " . $db->quoteIdentifier( "friend_id" ) . ") VALUES (1, 3)" );
        $db->exec( "INSERT INTO " . $db->quoteIdentifier( "PO_friends" ) . " (" . $db->quoteIdentifier( "id" ) . ", " . $db->quoteIdentifier( "friend_id" ) . ") VALUES (2, 1)" );
    }

    /**
     * Saves the schema from database to file.
     *
     * Use this method if you have changed the definition of the persistent object
     * and need to update the file on disk.
     */
    public function saveSchema()
    {
        $db = ezcDbInstance::get();
        $schema = ezcDbSchema::createFromDb( $db );
        $schema->writeToFile( "array", dirname( __FILE__ ) . "/relation.dba" );
    }

    /**
     * Loads the schema from file into the database.
     */
    public static function setupTables( $db = null )
    {
        $db = ( $db === null ? ezcDbInstance::get() : $db );
        $schema = ezcDbSchema::createFromFile( "array", dirname( __FILE__ ) . "/relation.dba" );
        $schema->writeToDb( $db );
    }

    public static function cleanup( $db = null )
    {
        $db = ( $db === null ? ezcDbInstance::get() : $db );

        $db->exec( "DROP TABLE " . $db->quoteIdentifier( "PO_addresses" ) );
        $db->exec( "DROP TABLE " . $db->quoteIdentifier( "PO_birthdays" ) );
        $db->exec( "DROP TABLE " . $db->quoteIdentifier( "PO_employers" ) );
        $db->exec( "DROP TABLE " . $db->quoteIdentifier( "PO_persons" ) );
        $db->exec( "DROP TABLE " . $db->quoteIdentifier( "PO_persons_addresses" ) );
        $db->exec( "DROP TABLE " . $db->quoteIdentifier( "PO_secondpersons_addresses" ) );
        $db->exec( "DROP TABLE " . $db->quoteIdentifier( "PO_friends" ) );
        if ( $db->getName() === "oracle" )
        {
            $db->exec( "DROP SEQUENCE " . $db->quoteIdentifier( "PO_addresses_id_seq" ) );
            $db->exec( "DROP SEQUENCE " . $db->quoteIdentifier( "PO_employers_id_seq" ) );
            $db->exec( "DROP SEQUENCE " . $db->quoteIdentifier( "PO_persons_id_seq" ) );
        }
    }

}


?>
