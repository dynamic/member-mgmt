<?php

/**
 * Class RegistrationForm
 */
class RegistrationForm extends BootstrapForm
{

    /**
     * RegistrationForm constructor.
     * @param Controller $controller
     * @param string $name
     */
    public function __construct(Controller $controller, $name)
    {

        $member = singleton('Member');

        $fields = $member->getMemberFields();
        $this->extend('updateVenuRegistrationFormFields', $fields);
        $fields->bootstrapIgnore('ProfilePictureID');

        $actions = $member->getMemberActions();
        $this->extend('updateVenuRegistrationFormActions', $actions);

        $validator = $member->getRegistrationRequiredFields();
        $this->extend('updateVenuRegistrationFormRequiredFields', $validator);

        parent::__construct($controller, $name, $fields, $actions, $validator);

        $this->loadDataFrom($_REQUEST);
    }

}