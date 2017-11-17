<?php

namespace Dynamic\Profiles\Form;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\Form;
use SilverStripe\Security\Member;

/**
 * Class RegistrationForm.
 */
class ProfileForm extends Form
{
    /**
     * ProfileForm constructor.
     *
     * @param Controller $controller
     * @param string     $name
     */
    public function __construct(Controller $controller, $name)
    {
        $member = Member::singleton();

        $fields = $member->getProfileFields();
        $this->extend('updateProfileFields', $fields);

        $actions = $member->getProfileActions();
        $this->extend('updateProfileActions', $actions);

        $validator = $member->getProfileRequiredFields();
        $this->extend('updateProfileRequiredFields', $validator);

        parent::__construct($controller, $name, $fields, $actions, $validator);

        $this->loadDataFrom($_REQUEST);
    }
}
