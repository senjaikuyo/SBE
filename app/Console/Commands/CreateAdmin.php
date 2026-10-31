<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdmin extends Command
{
    protected $signature = 'greengrid:create-admin {email}';

    protected $description = 'Create a platform admin user with a password and mandatory two-factor setup';

    public function handle(AuditLogger $audit): int
    {
        $email = (string) $this->argument('email');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('A valid email address is required.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Password for the platform admin (min 12 characters):');

        if (strlen($password) < 12) {
            $this->error('Password must be at least 12 characters.');

            return self::FAILURE;
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Platform Admin',
                'password' => Hash::make($password),
                'is_platform_admin' => true,
            ],
        );

        $user->forceFill([
            'two_factor_secret' => encrypt(Str::random(16)),
            'two_factor_recovery_codes' => encrypt(json_encode(['K7Y3-2Q9W-8E4R-5T6Y'])),
            'two_factor_confirmed_at' => null,
        ])->save();

        $audit->record(
            action: 'admin.created',
            entity: 'user',
            entityId: (string) $user->id,
            actor: null,
        );

        $this->info('Platform admin created. Ask the user to set up two-factor authentication on first login.');

        return self::SUCCESS;
    }
}
