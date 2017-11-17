<?php

namespace Dynamic\Members\Controller;

/**
 * Class Member_Controller.
 */
class Member_Controller extends \PageController
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
        if (!$this->profile) {
            $id = ($this->request->latestParam('ProfileID'))
                ? $this->request->latestParam('ProfileID')
                : Member::currentUserID();
            $this->profile = Member::get()->byID($id);

            return $this;
        }

        return $this;
    }

    /**
     * @param SS_HTTPRequest $request
     *
     * @return ViewableData_Customised|void
     */
    public function index(SS_HTTPRequest $request)
    {
        if (!$this->getProfile()) {
            $this->setProfile(Member::currentUser());
        }
        if ($member = $this->getProfile()) {
            return $this->renderWith(
                array(
                    'ProfilePage',
                    'Page',
                ),
                array(
                    'Profile' => $member,
                )
            );
        }
        //ProfileErrorEmail::send_email(Member::currentUserID());
        //todo determine proper error handling
        return Security::permissionFailure($this, 'Please login to view your profile.');
    }

    /**
     * @param SS_HTTPRequest $request
     *
     * @return ViewableData_Customised|void]
     */
    public function view(SS_HTTPRequest $request)
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
            if ($profile->ID == Member::currentUserID()) {
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

    public function update(SS_HTTPRequest $request)
    {
        if (!$this->getProfile()) {
            $this->setProfile(Member::currentUser());
        }
        if ($member = $this->getProfile()) {
            return $this->renderWith(
                array(
                    'RegistrationPage',
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
     * @return RegistrationForm
     */
    public function UpdateForm()
    {
        $form = RegistrationForm::create($this, __FUNCTION__)
            ->setFormAction(Controller::join_links('profile', __FUNCTION__.'?debug_request'))
            ->setValidator(singleton('Member')->getUpdateRequiredFields());
        if ($form->hasExtension('FormSpamProtectionExtension')) {
            $form->enableSpamProtection();
        }
        $fields = $form->Fields();
        $fields->dataFieldByName('Password')->setCanBeEmpty(true);

        return $form;
    }

    /**
     * @param $data
     * @param RegistrationForm $form
     */
    public function processmember($data, RegistrationForm $form)
    {
        $member = Member::get()->byID($this->getProfile()->ID);
        $form->saveInto($member);
        $member->write();
        $this->redirect('/profile');
    }
}
