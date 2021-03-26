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

require_once "data/manual_generator_test.php";
require_once "data/persistent_test_object.php";

/**
 * Tests the code manager.
 *
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentManualGeneratorTest extends ezcTestCase
{
    private $session = null;
    private $hasTables = false;

    protected function setUp()
    {
        try
        {
            $db = ezcDbInstance::get();
        }
        catch ( Exception $e )
        {
            $this->markTestSkipped( 'There was no database configured' );
        }

        ManualGeneratorTest::setupTable();
        ManualGeneratorTest::insertCleanData();
//        PersistentTestObject::saveSqlSchemas();
        $this->session = new ezcPersistentSession( ezcDbInstance::get(),
                                                   new ezcPersistentCodeManager( dirname( __FILE__ ) . "/data/" ) );
    }

    protected function tearDown()
    {
        ManualGeneratorTest::cleanup();
    }

    public static function suite()
    {
        return new \PHPUnit\Framework\TestSuite( 'ezcPersistentManualGeneratorTest' );
    }

    // test no id error
    public function testErrorID()
    {
        $object = new ManualGeneratorTest();
        $object->id = null;
        $object->varchar = 'Finland';
        $object->integer = 42;
        $object->decimal = 1.42;
        $object->text = "Finland has Nokia!";
        try
        {
            $this->session->save( $object );
            $this->fail( "Did not get exception when saving with null id." );
        }
        catch ( ezcPersistentIdentifierGenerationException $e ){}
    }

    // test save single
    public function testSaveValid()
    {
        $object = new ManualGeneratorTest();
        $object->id = 42;
        $object->varchar = 'Finland';
        $object->integer = 42;
        $object->decimal = 1.42;
        $object->text = "Finland has Nokia!";
        $this->session->save( $object );

        $this->assertEquals( 42, $object->id );
        $object2 = $this->session->loadIfExists( 'PersistentTestObject', 42 );
        $this->assertEquals( 'Finland', $object2->varchar );
        $this->assertEquals( 42, (int)$object2->integer );
        $this->assertEquals( 1.42, (float)$object2->decimal );
        $this->assertEquals( 'Finland has Nokia!', $object2->text );
    }

    // test save already stored
    public function testSaveAlreadyPersistent()
    {
        $object = new ManualGeneratorTest();
        $object->id = 42;
        $object->varchar = 'Finland';
        $object->integer = 42;
        $object->decimal = 1.42;
        $object->text = "Finland has Nokia!";
        $this->session->save( $object );
        try{
            $this->session->save( $object );
        }catch( Exception $e ){ return; }
        $this->fail( "Did not get exception when saving object twice.." );
    }

    public function testSaveZeroIdentifier()
    {
        $object = new ManualGeneratorTest();
        $object->id = 0;
        $object->varchar = 'Finland';
        $object->integer = 42;
        $object->decimal = 1.42;
        $object->text = "Finland has Nokia!";
        $this->session->save( $object );
        $this->assertEquals( 0, $object->id );

        $object2 = new ManualGeneratorTest();
        $this->session->loadIntoObject( $object2, 0 );
        $this->assertEquals( 'Finland', $object2->varchar );
        $this->assertEquals( 42, (int)$object2->integer );
        $this->assertEquals( 1.42, (float)$object2->decimal );
        $this->assertEquals( 'Finland has Nokia!', $object2->text );
    }

    public function testUpdateZeroIdentifier()
    {
        $object = new ManualGeneratorTest();
        $object->id = 0;
        $object->varchar = 'Finland';
        $object->integer = 42;
        $object->decimal = 1.42;
        $object->text = "Finland has Nokia!";
        $this->session->save( $object );

        $object2 = $this->session->loadIfExists( 'PersistentTestObject', 0 );
        $object2->integer = 99; // gretzky the greatest.
        $this->session->update( $object2 );

        $object3 = $this->session->loadIfExists( 'PersistentTestObject', 0 );
        $this->assertEquals( 'Finland', $object3->varchar );
        $this->assertEquals( 99, (int)$object3->integer );
        $this->assertEquals( 1.42, (float)$object3->decimal );
        $this->assertEquals( 'Finland has Nokia!', $object3->text );
    }

    public function testSaveNegativeIdentifier()
    {
        $object = new ManualGeneratorTest();
        $object->id = -1;
        $object->varchar = 'Finland';
        $object->integer = 42;
        $object->decimal = 1.42;
        $object->text = "Finland has Nokia!";
        $this->session->save( $object );

        $this->assertEquals( -1, $object->id );
        $object2 = $this->session->loadIfExists( 'PersistentTestObject', -1 );
        $this->assertEquals( 'Finland', $object2->varchar );
        $this->assertEquals( 42, (int)$object2->integer );
        $this->assertEquals( 1.42, (float)$object2->decimal );
        $this->assertEquals( 'Finland has Nokia!', $object2->text );
    }

    public function testUpdateNegativeIdentifier()
    {
        $object = new ManualGeneratorTest();
        $object->id = -1;
        $object->varchar = 'Finland';
        $object->integer = 42;
        $object->decimal = 1.42;
        $object->text = "Finland has Nokia!";
        $this->session->save( $object );

        $object2 = $this->session->loadIfExists( 'PersistentTestObject', -1 );
        $object2->integer = 99; // gretzky the greatest.
        $this->session->update( $object2 );

        $object3 = $this->session->loadIfExists( 'PersistentTestObject', -1 );
        $this->assertEquals( 'Finland', $object3->varchar );
        $this->assertEquals( 99, (int)$object3->integer );
        $this->assertEquals( 1.42, (float)$object3->decimal );
        $this->assertEquals( 'Finland has Nokia!', $object3->text );
    }

    // test struct
    public function testGeneratorDefinitionStruct()
    {
        $generator = new ezcPersistentGeneratorDefinition( "TestClass", array( "param" => true ) );
        $res = ezcPersistentGeneratorDefinition::__set_state(array(
            'class' => 'TestClass',
            'params' => array(
                'param' => true,
            ),
        ));

        $this->assertEquals( $res, $generator );
    }
}

?>
