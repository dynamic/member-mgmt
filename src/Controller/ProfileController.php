<?php

namespace Dynamic\Profiles\Controller;

use Dynamic\Profiles\Form\ProfileForm;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use SilverStripe\SpamProtection\Extension\FormSpamProtectionExtension;
use SilverStripe\View\ViewableData_Customised;

/**
 * Class Profile_Controller.
 */
class ProfileController extends \PageController
{
    /**
     * @var array
     */
    private static $url_handlers = array(
        'profile/view/$ProfileID' => 'view',
        'profile/update' => 'update',
        'UpdateForm' => 'UpdateForm',
        '' => 'index',
    );

    /**
     * @var array
     */
    private static $allowed_actions = array(
        'index',
        'view',
        'update',
        'UpdateForm',
    );

    /**
     * @var
     */
    private $profile;

    /**
     * @return mixed
     */
    public function getProfile()
    {
        if (!$this->profile) {
            $this->setProfile();
        }

        return $this->profile;
    }

    /**
     * @param Member|null $member
     *
     * @return $this
     */
    public function setProfile(Member $member = null)
    {
        if ($member !== null && $member instanceof Member) {
            $this->profile = $member;

            return $this;
        }
        if (!$this->profile && Security::getCurrentUser()) {
            $id = ($this->request->latestParam('ProfileID'))
                ? $this->request->latestParam('ProfileID')
                : Security::getCurrentUser()->ID;
            $this->profile = Member::get()->byID($id);

            return $this;
        }

        return $this;
    }

    /**
     * @param HTTPRequest $request
     * @return \SilverStripe\Control\HTTPResponse|\SilverStripe\ORM\FieldType\DBHTMLText
     */
    public function index(HTTPRequest $request)
    {
        if (!$this->getProfile()) {
            if ($member = Security::getCurrentUser()) {
                $this->setProfile($member);
            }
        }
        if ($member = $this->getProfile()) {
            return $this->renderWith(
                array(
                    'ProfilePage',
                    'Page',
                ),
                array(
                    'Title' => 'Your Profile',
                    'Profile' => $member,
                )
            );
        }
        //ProfileErrorEmail::send_email(Member::currentUserID());
        //todo determine proper error handling
        return Security::permissionFailure($this, 'Please login to view your profile.');
    }

    /**
     * @param HTTPRequest $request
     *
     * @return ViewableData_Customised|void]
     */
    public function view(HTTPRequest $request)
    {
        if (!$request->latestParam('ProfileID')) {
            return $this->httpError(404);
        }

        if (!$this->getProfile()) {
            $this->setProfile();
        }

        if ($profile = $this->getProfile()) {
            //redirect to /profile if they're trying to view their own
            //todo implement view public profile feature
            if ($profile->ID == Security::getCurrentUser()->ID) {
                $this->redirect('/profile');
            }

            return $this->renderWith(
                array(
                    'ProfilePage',
                    'Page',
                ),
                array(
                    'Profile' => $profile,
                )
            );
        }

        //ProfileErrorEmail::send_email(Member::currentUserID());
        return $this->httpError(404, "Your profile isn't available at this time. A our developers have been notified.");
    }

    public function update(HTTPRequest $request)
    {
        if (!$this->getProfile()) {
            $this->setProfile(Security::getCurrentUser());
        }
        if ($member = $this->getProfile()) {
            return $this->renderWith(
                array(
                    'ProfilePage_update',
                    'Page',
                ),
                array(
                    'Title' => 'Update your Profile',
                    'Form' => $this->UpdateForm()->loadDataFrom($member),
                )
            );
        }
        //ProfileErrorEmail::send_email(Member::currentUserID());
        //todo determine proper error handling
        return Security::permissionFailure($this, 'Please login to update your profile.');
    }

    /**
     * @return ProfileForm
     */
    public function UpdateForm()
    {
        $form = ProfileForm::create($this, __FUNCTION__)
            ->setFormAction(Controller::join_links('profile', __FUNCTION__))
            ->setValidator(Member::singleton()->getUpdateRequiredFields());
        if ($form->hasExtension(FormSpamProtectionExtension::class)) {
            $form->enableSpamProtection();
        }
        $fields = $form->Fields();
        $fields->dataFieldByName('Password')->setCanBeEmpty(true);

        return $form;
    }

    /**
     * @param $data
     * @param ProfileForm $form
     */
    public function processmember($data, ProfileForm $form)
    {
        $member = Member::get()->byID($this->getProfile()->ID);
        $form->saveInto($member);
        $member->write();
        $this->redirect('/profile');
    }
}
