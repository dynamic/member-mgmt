<?php

namespace Dynamic\Profiles\Controller;

use Dynamic\Profiles\Form\ProfileForm;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\Group;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

/**
 * Class RegistrationController.
 */
class RegistrationController extends ContentController
{
    /**
     * @var array
     */
    private static $url_handlers = array(
        'register' => 'index',
        'ProfileForm' => 'ProfileForm',
    );

    /**
     * @var array
     */
    private static $allowed_actions = array(
        'index',
        'ProfileForm',
    );

    /**
     * @param HTTPRequest $request
     *
     * @return DBHTMLText
     */
    public function index(HTTPRequest $request)
    {
        if (!Security::getCurrentUser()) {
            $content = DBField::create_field('HTMLText', '<p>Create a profile.</p>');

            return $this->renderWith(
                array(
                    'RegistrationPage',
                    'Page',
                ),
                array(
                    'Title' => 'Register',
                    'Content' => $content,
                    'Form' => self::ProfileForm(),
                )
            );
        }

        return $this->redirect('/profile/');
    }

    /**
     * @return ProfileForm
     */
    public function ProfileForm()
    {
        $form = ProfileForm::create($this, __FUNCTION__)
            ->setFormAction(Controller::join_links('register', __FUNCTION__));
        if ($form->hasExtension('FormSpamProtectionExtension')) {
            $form->enableSpamProtection();
        }

        return $form;
    }

    /**
     * @param ProfileForm $form
     * @param $data
     *
     * @return HTTPResponse|void
     */
    public function processmember($data, ProfileForm $form)
    {
        $member = Member::create();
        $form->saveInto($member);
        $public = Group::get()
            ->filter(array('Code' => 'public'))
            ->first();
        if ($member->write()) {
            $groups = $member->Groups();
            $groups->add($public);
            $member->login();

            return $this->redirect('/profile');
        }

        //RegistrationErrorEmail::send_email(Member::currentUserID());
        //todo figure out proper error handling
        return $this->httpError(404);
    }
}
