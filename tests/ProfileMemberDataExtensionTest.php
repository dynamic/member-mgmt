<?php

namespace Dynamic\Members\Test;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Security\Member;

class ProfileMemberDataExtensionTest extends SapphireTest
{
    /**
     * @var array
     */
    protected static $fixture_file = [
        'fixtures.yml',
    ];

    /**
     *
     */
    public function testGetProfileFields()
    {
        $object = Injector::inst()->create(Member::class);
        $fields = $object->getProfileFields();
        $this->assertInstanceOf(FieldList::class, $fields);
    }

    /**
     *
     */
    public function testGetProfileActions()
    {
        $object = Injector::inst()->create(Member::class);
        $fields = $object->getProfileActions();
        $this->assertInstanceOf(FieldList::class, $fields);
    }

    /**
     *
     */
    public function testGetProfileRequiredFields()
    {
        $object = Injector::inst()->create(Member::class);
        $fields = $object->getProfileRequiredFields();
        $this->assertInstanceOf(RequiredFields::class, $fields);
    }

    /**
     *
     */
    public function testGetUpdateRequiredFields()
    {
        $object = Injector::inst()->create(Member::class);
        $fields = $object->getUpdateRequiredFields();
        $this->assertInstanceOf(RequiredFields::class, $fields);
    }

    /**
     *
     */
    public function testGetFullName()
    {
        $object = $this->objFromFixture(Member::class, 'default');
        $this->assertEquals($object->FirstName . ' ' . $object->Surname, $object->getFullName());
    }

    /**
     *
     */
    public function testSetMaxProfileImageSize()
    {
        $this->markTestSkipped('TODO');
    }

    public function testGetMaxProfileImageSize()
    {
        $this->markTestSkipped('TODO');
    }

    public function testOnAfterWrite()
    {
        $this->markTestSkipped('TODO');
    }

    public function testRequireDefaultRecords()
    {
        $this->markTestSkipped('TODO');
    }
}
