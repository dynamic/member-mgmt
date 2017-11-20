<?php

namespace Dynamic\Profiles\ORM;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\ConfirmedPasswordField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FileField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Group;

/**
 * Class ProfileMemberDataExtension.
 */
class ProfileMemberDataExtension extends DataExtension
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
        'ProfileImage' => Image::class,
    );

    /**
     * @var array
     */
    private static $owns = [
        'ProfileImage'
    ];

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
    public function getProfileFields()
    {
        $image = FileField::create('ProfileImage', 'Profile Photo')
            ->setFolderName('Uploads/ProfileImages')
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
            $image
        );

        $this->owner->extend("updateProfileFields", $fields);

        return $fields;
    }

    /**
     * @return FieldList
     */
    public function getProfileActions()
    {
        $actions = FieldList::create(
            FormAction::create('processmember')
                ->setTitle('Submit')
        );

        $this->owner->extend('updateProfileActions', $actions);

        return $actions;
    }

    /**
     * @return RequiredFields
     */
    public function getProfileRequiredFields()
    {
        $validator = RequiredFields::create(
            'FirstName',
            'Surname',
            'Email',
            'Password'
        );

        $this->owner->extend('updateProfileRequiredFields', $validator);

        return $validator;
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
     * @return \SilverStripe\ORM\DataObject
     */
    public function setFullName()
    {
        $this->full_name = $this->owner->FirstName.' '.$this->owner->Surname;

        return $this->owner;
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
     * @param null $size
     *
     * @return \SilverStripe\ORM\DataObject
     */
    public function setMaxProfileImageSize($size = null)
    {
        $this->max_profile_image_size = ((int) $size === $size)
            ? $size
            : $this->owner->config()->get('max_profile_image_size');

        return $this->owner;
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
        if ($this->owner->ProfileImageID != 0) {
            if ($image = File::get()->byID($this->owner->ProfileImageID)) {
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
        }

        parent::requireDefaultRecords();
    }
}
