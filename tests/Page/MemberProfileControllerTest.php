<?php

namespace Dynamic\Profiles\Test\Page;

use Dynamic\Profiles\Form\ProfileForm;
use Dynamic\Profiles\Page\MemberProfile;
use Dynamic\Profiles\Page\MemberProfileController;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use SilverStripe\View\SSViewer;

/**
 * Class MemberProfileControllerTest
 * @package Dynamic\Profiles\Test\Page
 */
class MemberProfileControllerTest extends FunctionalTest
{

    /**
     * @var array
     */
    protected static $fixture_file = [
        '../fixtures.yml',
    ];

    /**
     * @var bool
     */
    protected static $use_draft_site = true;

    /**
     *
     */
    public function testGetProfile()
    {
        /** @var MemberProfile $object */
        $page = Injector::inst()->create(MemberProfile::class);
        $controller = MemberProfileController::create($page);

        $profile = $controller->getProfile();
        $this->assertNull($profile);

        /** @var Member $member */
        $member = $this->objFromFixture(Member::class, 'default');
        $controller->setProfile($member);

        $this->assertEquals($member, $controller->getProfile());
    }

    /**
     *
     */
    public function testSetProfile()
    {
        /** @var Member $member */
        $member = $this->objFromFixture(Member::class, 'default');
        /** @var MemberProfile $object */
        $page = Injector::inst()->create(MemberProfile::class);
        $controller = MemberProfileController::create($page);

        Security::setCurrentUser($member);
        $profile = $controller->setProfile()->getProfile();

        $this->assertEquals($member, $profile);
    }

    public function testIndex()
    {
        $this->autoFollowRedirection = false;

        /** @var MemberProfile $profilePage */
        $profilePage = $this->objFromFixture(MemberProfile::class, 'page');

        $page = $this->get($profilePage->Link());
        $this->assertInstanceOf(HTTPResponse::class, $page);
        $this->assertEquals(302, $page->getStatusCode());
        $this->assertRegExp('/register\/?$/', $page->getHeader('location'));

        /** @var Member $member */
        $member = $this->objFromFixture(Member::class, 'default');
        Security::setCurrentUser($member);
        $page = $this->get($profilePage->Link());

        $this->assertInstanceOf(HTTPResponse::class, $page);
        $this->assertEquals(200, $page->getStatusCode());
    }

    /**
     *
     */
    public function testView()
    {
        Config::modify()->set(SSViewer::class, 'theme', "simple");
        $this->autoFollowRedirection = false;

        /** @var MemberProfile $profilePage */
        $profilePage = $this->objFromFixture(MemberProfile::class, 'page');

        $page = $this->get($profilePage->Link('view'));
        $this->assertInstanceOf(HTTPResponse::class, $page);
        $this->assertEquals(404, $page->getStatusCode());

        /** @var Member $member */
        $member = $this->objFromFixture(Member::class, 'default');
        /** @var Member $otherMember */
        $otherMember = $this->objFromFixture(Member::class, 'other');

        Security::setCurrentUser($otherMember);
        $link = Controller::join_links($profilePage->Link('view'), $member->ID);
        $page = $this->get($link);
        $this->assertInstanceOf(HTTPResponse::class, $page);
        $this->assertEquals(200, $page->getStatusCode());

        Security::setCurrentUser($member);
        $page = $this->get($link);
        $this->assertInstanceOf(HTTPResponse::class, $page);
        $this->assertEquals(302, $page->getStatusCode());
        $this->assertRegExp('/profile\/?$/', $page->getHeader('location'));

        Security::setCurrentUser();
        $page = $this->get($link);
        $this->assertInstanceOf(HTTPResponse::class, $page);
        $this->assertEquals(404, $page->getStatusCode());
    }

    /**
     *
     */
    public function testUpdate()
    {
        $this->autoFollowRedirection = false;

        /** @var MemberProfile $profilePage */
        $profilePage = $this->objFromFixture(MemberProfile::class, 'page');
        /** @var Member $member */
        $member = $this->objFromFixture(Member::class, 'default');
        $link = Controller::join_links($profilePage->Link('update'), $member->ID);

        $page = $this->get($link);
        $this->assertInstanceOf(HTTPResponse::class, $page);
        $this->assertEquals(302, $page->getStatusCode());

        Security::setCurrentUser($member);
        $page = $this->get($link);

        $this->assertInstanceOf(HTTPResponse::class, $page);
        $this->assertEquals(200, $page->getStatusCode());
        $this->assertContains(
            '<input type="hidden" name="ID" value="' . $member->ID . '" class="hidden" id="ID" />',
            $page->getBody()
        );
    }

    /**
     *
     */
    public function testRegister()
    {
        $this->autoFollowRedirection = false;

        /** @var MemberProfile $profilePage */
        $profilePage = $this->objFromFixture(MemberProfile::class, 'page');
        /** @var Member $member */
        $member = $this->objFromFixture(Member::class, 'default');
        $link = Controller::join_links($profilePage->Link('register'), $member->ID);

        $page = $this->get($link);
        $this->assertInstanceOf(HTTPResponse::class, $page);
        $this->assertEquals(200, $page->getStatusCode());

        Security::setCurrentUser($member);
        $page = $this->get($link);
        $this->assertInstanceOf(HTTPResponse::class, $page);
        $this->assertEquals(302, $page->getStatusCode());
        $this->assertStringEndsWith($profilePage->Link(), $page->getHeader('location'));
    }

    /**
     *
     */
    public function testProfileForm()
    {
        /** @var MemberProfile $profilePage */
        $profilePage = $this->objFromFixture(MemberProfile::class, 'page');
        $controller = MemberProfileController::create($profilePage);
        $this->assertInstanceOf(ProfileForm::class, $controller->ProfileForm());
    }
}
