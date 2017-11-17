<?php

namespace Dynamic\Members\ORM;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\ConfirmedPasswordField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Group;

/**
 * Class VenuMember.
 */
class DynamicMemberDataExtension extends DataExtension
{
    /**
     * @var array
     */
    private static $db = array(
        'PublicProfile' => 'Boolean',
    );

    /**
     * @var array
     */
    private static $has_one = array(
        'ProfilePicture' => Image::class,
    );

    /**
     * @var
     */
    private $full_name;

    /**
     * @var
     */
    private $max_profile_image_size;

    /**
     * @return FieldList
     */
    public function getMemberFields()
    {
        $image = UploadField::create('ProfilePictureID', 'Profile Picture')
            //->setAcceptedFiles(array('.gif', '.jpg', '.jpeg', '.png'))
            //->setView('grid')
            //->setMultiple(false)
            //->setAutoProcessQueue(true)
            ->setFolderName('Uploads/Profile-Pictures')
            //->setMaxFilesize($this->getMaxProfileImageSize());
        ;

        $fields = FieldList::create(
            TextField::create('FirstName')
                ->setTitle('First Name'),
            TextField::create('Surname')
                ->setTitle('Last Name'),
            CheckboxField::create('PublicProfile')
                ->setTitle('Make my profile public'),
            EmailField::create('Email')
                ->setTitle('Email'),
            ConfirmedPasswordField::create('Password'),
                //->setTitle('')
            $image
        );

        //$this->owner->extend("updateMemberFields", $fields);

        return $fields;
    }

    /**
     * @return FieldList
     */
    public function getMemberActions()
    {
        return FieldList::create(
            FormAction::create('processmember')
                ->setTitle('Sign Up')
        );
    }

    /**
     * @return RequiredFields
     */
    public function getRegistrationRequiredFields()
    {
        return RequiredFields::create(
            'FirstName',
            'Surname',
            'Email',
            'Password'
        );
    }

    /**
     * @return RequiredFields
     */
    public function getUpdateRequiredFields()
    {
        return RequiredFields::create(
            'FirstName',
            'Surname',
            'Email'
        );
    }

    /**
     * @return $this
     */
    public function setFullName()
    {
        $this->full_name = $this->owner->FirstName.' '.$this->owner->Surname;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        if (!$this->full_name) {
            $this->setFullName();
        }

        return $this->full_name;
    }

    /**
     * @param int|null $size
     *
     * @return $this
     */
    public function setMaxProfileImageSize($size = null)
    {
        $this->max_profile_image_size = ((int) $size === $size)
            ? $size
            : $this->owner->config()->get('max_profile_image_size');

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxProfileImageSize()
    {
        if (!$this->max_profile_image_size) {
            $this->setMaxProfileImageSize();
        }

        return $this->max_profile_image_size;
    }

    /**
     *
     */
    public function onAfterWrite()
    {
        if ($this->owner->ProfilePictureID != 0) {
            if ($image = File::get()->byID($this->owner->ProfilePictureID)) {
                $image->OwnerID = $this->owner->ID;
                $image->ShowInSearch = false;
                $image->write();
            }
        }

        parent::onAfterWrite();
    }

    /**
     *
     */
    public function requireDefaultRecords()
    {

        //Add public Group
        $publicGroup = Group::get()
            ->filter(array('Code' => 'public'))
            ->first();
        if (!$publicGroup) {
            $publicGroup = Group::create();
            $publicGroup->Title = 'Public';
            $publicGroup->Code = 'public';
            $publicGroup->write();
            //$publicGroup->Roles()->add($advisorRole);
        }

        parent::requireDefaultRecords();
    }
}
