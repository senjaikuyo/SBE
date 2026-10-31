<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Values from the Phase 1 specification. Change them here, not in code.
    |
    */

    'password_min_length' => 12,

    'session_idle_minutes' => 30,

    'default_timezone' => 'Asia/Jakarta',

    'invitation_expiry_days' => 7,

    /*
    |--------------------------------------------------------------------------
    | Company
    |--------------------------------------------------------------------------
    */

    'countries' => [
        'ID' => 'Indonesia',
        'SG' => 'Singapore',
        'MY' => 'Malaysia',
        'VN' => 'Vietnam',
        'LA' => 'Laos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    |
    | Company roles live on memberships. `company_role` on the company itself
    | is assigned by the server after KYC approval; users never choose it.
    |
    */

    'membership_roles' => [
        'company_admin',
        'procurement',
        'finance',
        'legal',
        'sustainability',
        'viewer',
    ],

    'company_roles' => [
        'buyer',
        'seller',
        'both',
    ],

    /*
    |--------------------------------------------------------------------------
    | KYC
    |--------------------------------------------------------------------------
    */

    'kyc_statuses' => [
        'NOT_SUBMITTED',
        'PENDING_VERIFICATION',
        'APPROVED',
        'REJECTED',
    ],

];
