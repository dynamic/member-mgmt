<?php

namespace Dynamic\Profiles\Page;

use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;

class MemberProfile extends \Page implements PermissionProvider
{

    /**
     * @var string
     */
    private static $table_name = 'MemberProfile';

    /**
     * @return array
     */
    public function providePermissions()
    {
        return [
            'Create_MemberProfile' => [
                'name' => _t(
                    'MEMBERPROFILE.CREATE_MEMBERPROFILE',
                    'Create Member Profile Pages'
                ),
                'category' => _t(
                    'Permissions.PERMISSIONS_MEMBERPROFILE_PERMISSION',
                    'Member Profile Page'
                ),
                'help' => _t(
                    'MemberProfile.CREATE_PERMISSION_MEMBERPROFILE_PERMISSION',
                    'Ability to create new Member Profile Pages.'
                ),
                'sort' => 400,
            ],
            'Edit_MemberProfile' => [
                'name' => _t(
                    'MEMBERPROFILE.EDIT_MEMBERPROFILE',
                    'Edit Member Profile Pages'
                ),
                'category' => _t(
                    'Permissions.PERMISSIONS_MEMBERPROFILE_PERMISSION',
                    'Member Profile Page'
                ),
                'help' => _t(
                    'MemberProfile.EDIT_PERMISSION_MEMBERPROFILE_PERMISSION',
                    'Ability to update Member Profile Pages.'
                ),
                'sort' => 400,
            ],
            'Delete_MemberProfile' => [
                'name' => _t(
                    'MEMBERPROFILE.PUBLISH_MEMBERPROFILE',
                    'Delete Member Profile Pages'
                ),
                'category' => _t(
                    'Permissions.PERMISSIONS_MEMBERPROFILE_PERMISSION',
                    'Member Profile Page'
                ),
                'help' => _t(
                    'MemberProfile.PUBLISH_PERMISSION_MEMBERPROFILE_PERMISSION',
                    'Ability to delete Member Profile Pages.'
                ),
                'sort' => 400,
            ],
            'Publish_MemberProfile' => [
                'name' => _t(
                    'MEMBERPROFILE.PUBLISH_MEMBERPROFILE',
                    'Publish Member Profile Pages'
                ),
                'category' => _t(
                    'Permissions.PERMISSIONS_MEMBERPROFILE_PERMISSION',
                    'Member Profile Page'
                ),
                'help' => _t(
                    'MemberProfile.PUBLISH_PERMISSION_MEMBERPROFILE_PERMISSION',
                    'Ability to publish Member Profile Pages.'
                ),
                'sort' => 400,
            ],
        ];
    }

    /**
     * @param null $member
     * @param array $context
     * @return bool|int
     */
    public function canCreate($member = null, $context = [])
    {
        return Permission::check('Create_MemberProfile', 'any', $member);
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canEdit($member = null)
    {
        return Permission::check('Edit_MemberProfile', 'any', $member);
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canDelete($member = null)
    {
        return Permission::check('Delete_MemberProfile', 'any', $member);
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canPublish($member = null)
    {
        return Permission::check('Publish_MemberProfile', 'any', $member);
    }
}
