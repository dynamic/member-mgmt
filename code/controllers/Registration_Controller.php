<?php

/**
 * Class Registration_Controller
 */
class Registration_Controller extends ContentController
{

    /**
     * @var array
     */
    private static $url_handlers = array(
        'register' => 'index',
        'RegistrationForm' => 'RegistrationForm',
    );

    /**
     * @var array
     */
    private static $allowed_actions = array(
        'index',
        'RegistrationForm',
    );

    /**
     * @param SS_HTTPRequest $request
     * @return HTMLText
     */
    public function index(SS_HTTPRequest $request)
    {
        if (!Member::currentUser()) {
            $content = DBField::create_field('HTMLText', '<p>Create a profile on Venu365.</p>');

            return $this->renderWith(
                array(
                    'RegistrationPage',
                    'Page',
                ),
                array(
                    'Title' => 'Register',
                    'Content' => $content,
                    'Form' => self::RegistrationForm()
                )
            );
        }

        return $this->redirect('/profile/update');;
    }

    /**
     * @return RegistrationForm
     */
    public function RegistrationForm()
    {
        $form = RegistrationForm::create($this, __FUNCTION__)
            ->setFormAction(Controller::join_links('register', __FUNCTION__));
        if ($form->hasExtension('FormSpamProtectionExtension')) {
            $form->enableSpamProtection();
        }
        return $form;
    }

    /**
     * @param RegistrationForm $form
     * @param $data
     * @return SS_HTTPResponse|void
     */
    public function processmember($data, RegistrationForm $form)
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