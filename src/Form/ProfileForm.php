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

        $actions = $member->getProfileActions();

        $validator = $member->getProfileRequiredFields();

        parent::__construct($controller, $name, $fields, $actions, $validator);

        $this->loadDataFrom($_REQUEST);
    }
}
