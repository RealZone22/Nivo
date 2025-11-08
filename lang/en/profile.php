<?php

return [
    'title' => 'Profile',

    'tabs' => [
        'overview' => 'Overview',
        'sessions' => 'Sessions',
    ],

    'notifications' => [
        'profile_updated' => 'Profile updated successfully.',
        'password_updated' => 'Password updated successfully.',
    ],

    'language_and_theme' => [
        'title' => 'Language & Theme',
        'language' => 'Language',
        'theme' => 'Theme',

        'languages' => [
            'en' => 'English',
            'de' => 'German',
        ],
        'themes' => [
            'light' => 'Light',
            'dark' => 'Dark',
        ],
    ],

    'actions' => [
        'title' => 'Actions',

        'buttons' => [
            'activate_two_factor' => 'Activate 2FA',
            'disable_two_factor' => 'Disable 2FA',
            'regenerate_recovery_codes' => 'Regenerate Recovery Codes',
            'delete_account' => 'Delete Account',
        ],
    ],

    'profile' => [
        'title' => 'Profile',

        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'username' => 'Username',
        'email' => 'Email',
    ],

    'password' => [
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'confirm_password' => 'Confirm Password',
    ],

    'sessions' => [
        'title' => 'Sessions',
        'ip_address' => 'IP Address',
        'user_agent' => 'User Agent',
        'platform' => 'Platform',
        'last_active' => 'Last Active',

        'device_types' => [
            'desktop' => 'Desktop',
            'mobile' => 'Mobile',
            'tablet' => 'Tablet',
            'other' => 'Other',
        ],

        'buttons' => [
            'logout_all' => 'Logout all Sessions',
        ],

        'modals' => [
            'logout_all' => [
                'title' => 'Logout all Sessions',
                'description' => 'Are you sure you want to logout all other sessions?',
                'confirm' => 'Yes, logout all other sessions',
            ],
        ],

        'notifications' => [
            'logged_out' => 'Logged out successfully.',
            'logged_out_all' => 'Logged out all other sessions successfully.',
        ],
    ],

    'modals' => [
        'activate_two_fa' => [
            'title' => 'Activate Two Factor Authentication',
            'two_fa_code' => 'Two Factor Code',
            'invalid_two_factor_code' => 'The two factor code is invalid.',

            'notifications' => [
                'two_fa_enabled' => 'Two Factor Authentication enabled successfully.',
            ],
        ],
        'disable_two_fa' => [
            'title' => 'Disable Two Factor Authentication',
            'description' => 'Are you sure you want to disable two-factor authentication?',

            'buttons' => [
                'disable' => 'Disable',
            ],

            'notifications' => [
                'two_fa_disabled' => 'Two Factor Authentication disabled successfully.',
            ],
        ],
        'recovery_codes' => [
            'title' => 'Recovery Codes',
            'description' => 'Save these recovery codes in a secure place. You will not be able to see them again.',

            'buttons' => [
                'regenerate' => 'Regenerate',
                'download' => 'Download',
            ],
        ],
        'delete_account' => [
            'title' => 'Delete Account',
            'description' => 'Are you sure you want to delete your account?',

            'notifications' => [
                'account_deleted' => 'Account deleted successfully.',
            ],
        ],
        'change_avatar' => [
            'title' => 'Change Avatar',
            'description' => 'Change your avatar by uploading a new image or providing a URL.',
            'avatar' => 'Avatar',
            'avatar_url' => 'Avatar URL',

            'buttons' => [
                'reset' => 'Reset',
            ],

            'notifications' => [
                'avatar_changed' => 'Avatar changed successfully.',
                'avatar_reset' => 'Avatar reset successfully.',
            ],
        ],
    ],
];
