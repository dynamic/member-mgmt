<?php

namespace Dynamic\Members\Test\ORM;

use SilverStripe\Assets\File;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Security\Group;
use SilverStripe\Security\Member;

class ProfileMemberDataExtensionTest extends SapphireTest
{
    /**
     * @var array
     */
    protected static $fixture_file = [
        '../fixtures.yml',
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
        $this->assertEquals($object->FirstName.' '.$object->Surname, $object->getFullName());
    }

    /**
     *
     */
    public function testSetMaxProfileImageSize()
    {
        /** @var Member $object */
        $object = $this->objFromFixture(Member::class, 'default');
        $return = $object->setMaxProfileImageSize();

        $this->assertInstanceOf(Member::class, $return);
        $this->assertEquals($object, $return);
        $this->assertEquals(512000, $object->getMaxProfileImageSize());

        $object->setMaxProfileImageSize('string');
        $this->assertEquals(512000, $object->getMaxProfileImageSize());

        $object->setMaxProfileImageSize(2);
        $this->assertEquals(2, $object->getMaxProfileImageSize());
    }

    public function testGetMaxProfileImageSize()
    {
        /** @var Member $object */
        $object = $this->objFromFixture(Member::class, 'default');
        $size = $object->getMaxProfileImageSize();

        $this->assertEquals(512000, $size);
    }

    public function testOnAfterWrite()
    {
        /** @var File $file */
        $file = $this->objFromFixture(File::class, 'File');
        $file->ShowInSearch = true;
        $file->write();

        /** @var Member $member */
        $member = Injector::inst()->create(Member::class);

        $member->ProfileImageID = $file->ID;
        $member->write();

        $image = File::get()->byID($member->ProfileImageID);
        $this->assertFalse((bool) $image->ShowInSearch);
    }

    public function testRequireDefaultRecords()
    {
        // This is so we only get a good test when the group doesn't exist at first
        // No false positive
        $group = Group::get()
            ->filter(array('Code' => 'public'))
            ->first();
        $this->assertNull($group);

        /** @var Member $member */
        $member = Injector::inst()->create(Member::class);

        $member->requireDefaultRecords();

        $group = Group::get()
            ->filter(array('Code' => 'public'))
            ->first();

        $this->assertNotNull($group);
        $this->assertEquals('public', $group->Code);
    }
}
