<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Company;
use App\Models\KycSubmission;
use App\Models\Listing;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('Password1234!');

        // 1. Platform Admins
        $admin1 = User::create([
            'name' => 'Rani Kusuma',
            'email' => 'admin1@greengrid.test',
            'password' => $defaultPassword,
            'timezone' => 'Asia/Jakarta',
            'is_platform_admin' => true,
            'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code-admin-1', 'code-admin-2'])),
            'two_factor_confirmed_at' => now(),
        ]);

        $admin2 = User::create([
            'name' => 'Bima Pratama',
            'email' => 'admin2@greengrid.test',
            'password' => $defaultPassword,
            'timezone' => 'Asia/Jakarta',
            'is_platform_admin' => true,
            'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code-admin2-1', 'code-admin2-2'])),
            'two_factor_confirmed_at' => now(),
        ]);

        // 2. Approved Seller Company
        $sellerCompany = Company::create([
            'name' => 'PT Nusantara Solar Prima',
            'country' => 'ID',
            'registration_number' => 'NIB-9120301928',
            'kyc_status' => 'APPROVED',
            'company_role' => 'seller',
            'version' => 1,
        ]);

        $sellerAdmin = User::create([
            'name' => 'Agus Setiawan',
            'email' => 'seller.admin@nusantara-solar.test',
            'password' => $defaultPassword,
            'timezone' => 'Asia/Jakarta',
            'is_platform_admin' => false,
            'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code-s1', 'code-s2'])),
            'two_factor_confirmed_at' => now(),
        ]);

        $sellerProcurement = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'seller.procure@nusantara-solar.test',
            'password' => $defaultPassword,
            'timezone' => 'Asia/Jakarta',
            'is_platform_admin' => false,
            'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code-sp1', 'code-sp2'])),
            'two_factor_confirmed_at' => now(),
        ]);

        Membership::create([
            'company_id' => $sellerCompany->id,
            'user_id' => $sellerAdmin->id,
            'role' => 'company_admin',
        ]);

        Membership::create([
            'company_id' => $sellerCompany->id,
            'user_id' => $sellerProcurement->id,
            'role' => 'procurement',
        ]);

        // 3. Approved Buyer Company
        $buyerCompany = Company::create([
            'name' => 'Singapore Data Centers Pte Ltd',
            'country' => 'SG',
            'registration_number' => 'UEN-202319203K',
            'kyc_status' => 'APPROVED',
            'company_role' => 'buyer',
            'version' => 1,
        ]);

        $buyerAdmin = User::create([
            'name' => 'Marcus Tan',
            'email' => 'buyer.admin@singaporedc.test',
            'password' => $defaultPassword,
            'timezone' => 'Asia/Singapore',
            'is_platform_admin' => false,
            'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code-b1', 'code-b2'])),
            'two_factor_confirmed_at' => now(),
        ]);

        Membership::create([
            'company_id' => $buyerCompany->id,
            'user_id' => $buyerAdmin->id,
            'role' => 'company_admin',
        ]);

        // 4. Three ACTIVE Energy Listings
        Listing::create([
            'company_id' => $sellerCompany->id,
            'type' => 'energy',
            'capacity_mw' => 20,
            'volume_available_mw' => 8.50,
            'price_per_kwh' => 0.0725,
            'duration_years' => 20,
            'start_date' => '2027-01-01',
            'location' => 'Sumba, East Nusa Tenggara, Indonesia',
            'permit_reference' => 'IUPTL-SUMBA-2026-001',
            'photo' => 'panorama-sumba.jpg',
            'status' => 'ACTIVE',
            'version' => 1,
        ]);

        Listing::create([
            'company_id' => $sellerCompany->id,
            'type' => 'energy',
            'capacity_mw' => 15,
            'volume_available_mw' => 15.00,
            'price_per_kwh' => 0.0780,
            'duration_years' => 18,
            'start_date' => '2027-06-01',
            'location' => 'Johor Solar Park, Malaysia',
            'permit_reference' => 'MY-EC-JOHOR-2026-08',
            'photo' => 'panorama-johor.jpg',
            'status' => 'ACTIVE',
            'version' => 1,
        ]);

        Listing::create([
            'company_id' => $sellerCompany->id,
            'type' => 'energy',
            'capacity_mw' => 12,
            'volume_available_mw' => 12.00,
            'price_per_kwh' => 0.0690,
            'duration_years' => 15,
            'start_date' => '2027-03-01',
            'location' => 'Mekong Floating Solar, Vietnam',
            'permit_reference' => 'VN-MOIT-MEKONG-2026-11',
            'photo' => 'panorama-mekong.jpg',
            'status' => 'ACTIVE',
            'version' => 1,
        ]);

        // 5. Two ACTIVE Carbon Credit Listings (with Valid Certificates)
        $carbon1 = Listing::create([
            'company_id' => $sellerCompany->id,
            'type' => 'carbon_credit',
            'quantity_tco2e' => 25000,
            'vintage' => 2024,
            'registry_standard' => 'VERRA',
            'serial_number' => 'VCS-ID-2024-001920',
            'price_per_ton' => 18.5000,
            'location' => 'Central Kalimantan, Indonesia',
            'status' => 'ACTIVE',
            'version' => 1,
        ]);

        Certificate::create([
            'listing_id' => $carbon1->id,
            'type' => 'carbon_credit',
            'registry' => 'VERRA',
            'serial_number' => 'VCS-ID-2024-001920',
            'serial_normalized' => Certificate::normalizeSerial('VCS-ID-2024-001920'),
            'status' => 'VALID',
            'reviewed_by' => $admin1->id,
            'reviewed_at' => now()->subDay(),
        ]);

        $carbon2 = Listing::create([
            'company_id' => $sellerCompany->id,
            'type' => 'carbon_credit',
            'quantity_tco2e' => 15000,
            'vintage' => 2023,
            'registry_standard' => 'GOLD_STANDARD',
            'serial_number' => 'GS-VN-2023-004810',
            'price_per_ton' => 22.0000,
            'location' => 'An Giang, Vietnam',
            'status' => 'ACTIVE',
            'version' => 1,
        ]);

        Certificate::create([
            'listing_id' => $carbon2->id,
            'type' => 'carbon_credit',
            'registry' => 'GOLD_STANDARD',
            'serial_number' => 'GS-VN-2023-004810',
            'serial_normalized' => Certificate::normalizeSerial('GS-VN-2023-004810'),
            'status' => 'VALID',
            'reviewed_by' => $admin1->id,
            'reviewed_at' => now()->subDay(),
        ]);

        // 6. One DRAFT Listing
        Listing::create([
            'company_id' => $sellerCompany->id,
            'type' => 'energy',
            'capacity_mw' => 4,
            'volume_available_mw' => 4.00,
            'price_per_kwh' => 0.0695,
            'duration_years' => 15,
            'start_date' => '2027-08-01',
            'location' => 'Aceh Coastal Solar, Indonesia',
            'permit_reference' => 'IUPTL-ACEH-DRAFT-01',
            'photo' => 'pending',
            'status' => 'DRAFT',
            'version' => 1,
        ]);

        // 7. One Pending KYC Submission Company
        $pendingCompany = Company::create([
            'name' => 'Mekong Renewables Co., Ltd',
            'country' => 'VN',
            'registration_number' => 'VN-ERC-09283019',
            'kyc_status' => 'PENDING_VERIFICATION',
            'company_role' => null,
            'version' => 1,
        ]);

        $pendingAdmin = User::create([
            'name' => 'Nguyen Van Minh',
            'email' => 'pending.admin@mekong-solar.test',
            'password' => $defaultPassword,
            'timezone' => 'Asia/Ho_Chi_Minh',
            'is_platform_admin' => false,
            'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code-p1', 'code-p2'])),
            'two_factor_confirmed_at' => now(),
        ]);

        Membership::create([
            'company_id' => $pendingCompany->id,
            'user_id' => $pendingAdmin->id,
            'role' => 'company_admin',
        ]);

        KycSubmission::create([
            'company_id' => $pendingCompany->id,
            'country' => 'VN',
            'requested_role' => 'seller',
            'status' => 'PENDING_VERIFICATION',
            'submitted_by' => $pendingAdmin->id,
            'submitted_at' => now()->subHours(2),
        ]);
    }
}
