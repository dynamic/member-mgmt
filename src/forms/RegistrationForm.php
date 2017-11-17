<?php

namespace Dynamic\Members\Form;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\Form;
use SilverStripe\Security\Member;

/**
 * Class RegistrationForm.
 */
class RegistrationForm extends Form
{
    /**
     * RegistrationForm constructor.
     *
     * @param Controller $controller
     * @param string     $name
     */
    public function __construct(Controller $controller, $name)
    {
        $member = Member::singleton();

        $fields = $member->getMemberFields();

        //$this->extend('updateVenuRegistrationFormFields', $fields);
        //$fields->bootstrapIgnore('ProfilePictureID');

        $actions = $member->getMemberActions();
        $this->extend('updateVenuRegistrationFormActions', $actions);

        $validator = $member->getRegistrationRequiredFields();
        $this->extend('updateVenuRegistrationFormRequiredFields', $validator);

        parent::__construct($controller, $name, $fields, $actions, $validator);

        $this->loadDataFrom($_REQUEST);
    }
}
