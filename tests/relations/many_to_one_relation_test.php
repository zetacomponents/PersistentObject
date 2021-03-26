<?php
/**
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
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version //autogentag//
 * @filesource
 * @package PersistentObject
 * @subpackage Tests
 */

require_once dirname( __FILE__ ) . "/../data/relation_test_employer.php";
require_once dirname( __FILE__ ) . "/../data/relation_test_person.php";

/**
 * Tests ezcPersistentManyToOneRelation class.
 *
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentManyToOneRelationTest extends ezcTestCase
{

    private $session;

    public static function suite()
    {
        return new \PHPUnit\Framework\TestSuite( "ezcPersistentManyToOneRelationTest" );
    }

    public function setup()
    {
        try
        {
            $this->db = ezcDbInstance::get();
        }
        catch ( Exception $e )
        {
            $this->markTestSkipped( 'There was no database configured' );
        }
        RelationTestPerson::setupTables();
        RelationTestPerson::insertData();
        $this->session = new ezcPersistentSession(
            ezcDbInstance::get(),
            new ezcPersistentCodeManager( dirname( __FILE__ ) . "/../data/" )
        );
    }

    public function teardown()
    {
        RelationTestEmployer::cleanup();
    }

    // Tests of the relation definition class

    public function testGetAccessSuccess()
    {
        $relation = new ezcPersistentManyToOneRelation( "PO_persons", "PO_addresses" );

        $this->assertEquals( "PO_persons", $relation->sourceTable );
        $this->assertEquals( "PO_addresses", $relation->destinationTable );
        $this->assertEquals( array(), $relation->columnMap );
        $this->assertEquals( true, $relation->reverse );
        $this->assertEquals( false, $relation->cascade );
    }

    public function testGetAccessFailure()
    {
        $relation = new ezcPersistentManyToOneRelation( "PO_persons", "PO_addresses" );
        try
        {
            $foo = $relation->non_existent;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on access of non existent property." );
    }

    public function testIssetAccessSuccess()
    {
        $relation = new ezcPersistentManyToOneRelation( "PO_persons", "PO_addresses" );

        $this->assertTrue( isset( $relation->sourceTable ) );
        $this->assertTrue( isset( $relation->destinationTable ) );
        $this->assertTrue( isset( $relation->columnMap ) );
        $this->assertTrue( isset( $relation->reverse ) );
        $this->assertTrue( isset( $relation->cascade ) );
    }

    public function testSetAccessSuccess()
    {
        $relation = new ezcPersistentManyToOneRelation( "PO_persons", "PO_addresses" );
        $tableMap = new ezcPersistentSingleTableMap( "other_persons_id", "other_addresses_id" );

        $relation->sourceTable = "PO_other_persons";
        $relation->destinationTable = "PO_other_addresses";
        $relation->columnMap = array( $tableMap );
        $relation->cascade = true;

        $this->assertEquals( $relation->sourceTable, "PO_other_persons" );
        $this->assertEquals( $relation->destinationTable, "PO_other_addresses" );
        $this->assertEquals( $relation->columnMap, array( $tableMap ) );
        $this->assertEquals( $relation->reverse, true );
        $this->assertEquals( $relation->cascade, true );
    }

    public function testSetAccessFailure()
    {
        $relation = new ezcPersistentManyToOneRelation( "PO_persons", "PO_addresses" );
        $tableMap = new ezcPersistentDoubleTableMap( "other_persons_id", "other_persons_id", "other_addresses_id", "other_addresses_id" );

        try
        {
            $relation->sourceTable = 23;
            $this->fail( "Exception not thrown on invalid value for ezcPersistentManyToOneRelation->sourceTable." );
        }
        catch ( ezcBaseValueException $e )
        {
        }

        try
        {
            $relation->destinationTable = 42;
            $this->fail( "Exception not thrown on invalid value for ezcPersistentManyToOneRelation->destinationTable." );
        }
        catch ( ezcBaseValueException $e )
        {
        }

        try
        {
            $relation->columnMap = array( $tableMap );
            $this->fail( "Exception not thrown on invalid value for ezcPersistentManyToOneRelation->columnMap." );
        }
        catch ( ezcBaseValueException $e )
        {
        }

        try
        {
            $relation->columnMap = array();
            $this->fail( "Exception not thrown on invalid value for ezcPersistentManyToOneRelation->columnMap." );
        }
        catch ( ezcBaseValueException $e )
        {
        }

        try
        {
            $relation->reverse = false;
            $this->fail( "Exception not thrown on set access to ezcPersistentManyToOneRelation->reverse." );
        }
        catch ( ezcBasePropertyPermissionException $e )
        {
        }

        try
        {
            $relation->reverse = array();
            $this->fail( "Exception not thrown on set access to ezcPersistentManyToOneRelation->reverse." );
        }
        catch ( ezcBasePropertyPermissionException $e )
        {
        }

        try
        {
            $relation->cascade = array();
            $this->fail( "Exception not thrown on invalid value for ezcPersistentManyToOneRelation->cascade." );
        }
        catch ( ezcBaseValueException $e )
        {
        }

        try
        {
            $relation->non_existent = true;
            $this->fail( "Exception not thrown on set access on non existent property." );
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
        }
    }

    // Tests using the actual relation definition

    public function testGetRelatedObjectsEmployer1()
    {
        $person = $this->session->load( "RelationTestPerson", 1 );
        $res = array (
        2 =>
            RelationTestEmployer::__set_state(array(
                'id' => '2',
                'name' => 'Oldschool Web 1.x company',
            )),
        );

        $this->assertEquals(
            $res,
            $this->session->getRelatedObjects( $person, "RelationTestEmployer" ),
            "Related RelationTestPerson objects not fetched correctly."
        );
    }

    public function testGetRelatedObjectsEmployer2()
    {
        $person = $this->session->load( "RelationTestPerson", 2 );
        $res = array (
            1 => RelationTestEmployer::__set_state(array(
                'id' => '1',
                'name' => 'Great Web 2.0 company',
            )),
        );

        $this->assertEquals(
            $res,
            $this->session->getRelatedObjects( $person, "RelationTestEmployer" ),
            "Related RelationTestPerson objects not fetched correctly."
        );
    }

    public function testGetRelatedObjectEmployer1()
    {
        $person = $this->session->load( "RelationTestPerson", 1 );
        $res = RelationTestEmployer::__set_state(array(
                'id' => '2',
                'name' => 'Oldschool Web 1.x company',
        ));

        $this->assertEquals(
            $res,
            $this->session->getRelatedObject( $person, "RelationTestEmployer" ),
            "Related RelationTestPerson objects not fetched correctly."
        );
    }

    public function testGetRelatedObjectEmployer2()
    {
        $person = $this->session->load( "RelationTestPerson", 2 );
        $res = RelationTestEmployer::__set_state(array(
                'id' => '1',
                'name' => 'Great Web 2.0 company',
        ));

        $this->assertEquals(
            $res,
            $this->session->getRelatedObject( $person, "RelationTestEmployer" ),
            "Related RelationTestPerson objects not fetched correctly."
        );
    }

    public function testAddRelatedObjectEmployerFailureReverse()
    {
        $person = $this->session->load( "RelationTestPerson", 2 );
        $employer = $this->session->load( "RelationTestEmployer", 2 );

        try
        {
            $this->session->addRelatedObject( $person, $employer );
        }
        catch ( ezcPersistentRelationOperationNotSupportedException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on adding a new relation that is marked as reverse." );
    }

    public function testRemoveRelatedObjectEmployerFailureReverse()
    {
        $person = $this->session->load( "RelationTestPerson", 2 );
        $employer = $this->session->load( "RelationTestEmployer", 2 );

        try
        {
            $this->session->removeRelatedObject( $person, $employer );
        }
        catch ( ezcPersistentRelationOperationNotSupportedException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on adding a new relation that is marked as reverse." );
    }

    public function testIsRelatedSuccess()
    {
        $person = $this->session->load( "RelationTestPerson", 1 );
        $employer = $this->session->load( "RelationTestEmployer", 2 );

        $this->assertTrue( $this->session->isRelated( $person, $employer ) );
    }

    public function testIsRelatedReverseSuccess()
    {
        $person = $this->session->load( "RelationTestPerson", 1 );
        $employer = $this->session->load( "RelationTestEmployer", 2 );

        $this->assertTrue( $this->session->isRelated( $employer, $person ) );
    }

    public function testIsRelatedFailure()
    {
        $person = $this->session->load( "RelationTestPerson", 1 );

        $employer = new RelationTestEmployer();
        $employer->id = 2342;

        $this->assertFalse( $this->session->isRelated( $person, $employer ) );
        $this->assertFalse( $this->session->isRelated( $employer, $person ) );
    }
}

?>
