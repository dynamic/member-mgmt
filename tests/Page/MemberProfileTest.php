<?php

namespace Dynamic\Members\Test\Page;

use Dynamic\Profiles\Page\MemberProfile;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class MemberProfileTest extends SapphireTest
{
    /**
     * @var array
     */
    protected static $fixture_file = [
        '../fixtures.yml',
    ];

    public function testProvidePermissions()
    {
        $object = $this->objFromFixture(MemberProfile::class, 'page');
        $this->assertTrue(is_array($object->providePermissions()));
    }

    /**
     *
     */
    public function testCanEdit()
    {
        $object = $this->objFromFixture(MemberProfile::class, 'page');

        $admin = $this->objFromFixture(Member::class, 'admin');
        $this->assertTrue($object->canEdit($admin));

        $siteowner = $this->objFromFixture(Member::class, 'site-owner');
        $this->assertTrue($object->canEdit($siteowner));

        $member = $this->objFromFixture(Member::class, 'other');
        $this->assertFalse($object->canEdit($member));
    }

    /**
     *
     */
    public function testCanDelete()
    {
        $object = $this->objFromFixture(MemberProfile::class, 'page');

        $admin = $this->objFromFixture(Member::class, 'admin');
        $this->assertTrue($object->canDelete($admin));

        $siteowner = $this->objFromFixture(Member::class, 'site-owner');
        $this->assertTrue($object->canDelete($siteowner));

        $member = $this->objFromFixture(Member::class, 'other');
        $this->assertFalse($object->canDelete($member));
    }

    /**
     *
     */
    public function testCanCreate()
    {
        $object = $this->objFromFixture(MemberProfile::class, 'page');

        $admin = $this->objFromFixture(Member::class, 'admin');
        $this->assertTrue($object->canCreate($admin));

        $siteowner = $this->objFromFixture(Member::class, 'site-owner');
        $this->assertTrue($object->canCreate($siteowner));

        $member = $this->objFromFixture(Member::class, 'other');
        $this->assertFalse($object->canCreate($member));
    }

    /**
     *
     */
    public function testCanPublish()
    {
        $object = $this->objFromFixture(MemberProfile::class, 'page');

        $admin = $this->objFromFixture(Member::class, 'admin');
        $this->assertTrue($object->canPublish($admin));

        $siteowner = $this->objFromFixture(Member::class, 'site-owner');
        $this->assertTrue($object->canPublish($siteowner));

        $member = $this->objFromFixture(Member::class, 'other');
        $this->assertFalse($object->canPublish($member));
    }
}
