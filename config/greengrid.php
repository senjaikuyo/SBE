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

    /*
    |--------------------------------------------------------------------------
    | KYC documents
    |--------------------------------------------------------------------------
    | Per-country document lists live here, not in code. Keys match the values
    | submitted in the KYC form. Vietnam and Laos use a temporary entry until
    | decision D-04 is final (see docs/decisions.md).
    */

    'kyc_documents' => [
        'ID' => [
            ['name' => 'company_registration', 'types' => 'NIB', 'needs_expiry' => true],
            ['name' => 'tax_identification', 'types' => 'NPWP', 'needs_expiry' => true],
            ['name' => 'company_deed', 'types' => 'Akta pendirian dan pengesahan', 'needs_expiry' => false],
        ],
        'SG' => [
            ['name' => 'company_profile', 'types' => 'ACRA profile number', 'needs_expiry' => false],
        ],
        'MY' => [
            ['name' => 'company_registration', 'types' => 'SSM number', 'needs_expiry' => false],
        ],
        'VN' => [
            ['name' => 'company_registration', 'types' => 'Sertifikat registrasi perusahaan', 'needs_expiry' => false],
        ],
        'LA' => [
            ['name' => 'company_registration', 'types' => 'Sertifikat registrasi perusahaan', 'needs_expiry' => false],
        ],
    ],

    'kyc_shared_documents' => [
        ['name' => 'director_identity', 'note' => 'Photo identity of the director'],
        ['name' => 'beneficial_owner_documents', 'note' => 'Proof for each beneficial owner holding 25 percent or more'],
        ['name' => 'bank_statement', 'note' => 'Company bank account statement'],
        ['name' => 'signatory_appointment', 'note' => 'Letter of appointment for signatory'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Files
    |--------------------------------------------------------------------------
    */

    'files' => [
        'disk' => 'local',
    ],

];
