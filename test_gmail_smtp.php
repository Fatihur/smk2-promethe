<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

echo "Testing Gmail SMTP Configuration for Forgot Password...\n";
echo "=======================================================\n";

// Check mail configuration
echo "\n=== Mail Configuration ===\n";
echo "MAIL_MAILER: " . config('mail.default') . "\n";
echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "MAIL_ENCRYPTION: " . (config('mail.mailers.smtp.encryption') ?? 'none') . "\n";
echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "MAIL_FROM_NAME: " . config('mail.from.name') . "\n";

// Get first user
$user = User::first();
if (!$user) {
    echo "\n❌ No user found in database\n";
    exit(1);
}

echo "\n=== User Information ===\n";
echo "User: {$user->name}\n";
echo "Email: {$user->email}\n";

// Test 1: Simple mail test
echo "\n=== Test 1: Simple Mail Test ===\n";
try {
    Mail::raw('Test email dari SMK2 PROMETHEE system untuk memastikan konfigurasi Gmail SMTP berfungsi.', function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Test Email - SMK2 PROMETHEE');
    });
    echo "✅ Simple mail test sent successfully!\n";
} catch (\Exception $e) {
    echo "❌ Simple mail test failed: " . $e->getMessage() . "\n";
    echo "   Check your Gmail SMTP configuration.\n";
}

// Test 2: Password reset email
echo "\n=== Test 2: Password Reset Email ===\n";
try {
    $status = Password::sendResetLink(['email' => $user->email]);
    
    switch ($status) {
        case Password::RESET_LINK_SENT:
            echo "✅ Password reset email sent successfully!\n";
            echo "   Check the email: {$user->email}\n";
            break;
        case Password::INVALID_USER:
            echo "❌ Invalid user email\n";
            break;
        case Password::RESET_THROTTLED:
            echo "❌ Reset throttled (too many attempts)\n";
            break;
        default:
            echo "❌ Unknown status: {$status}\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Password reset email failed: " . $e->getMessage() . "\n";
}

// Check database
echo "\n=== Database Check ===\n";
$resetRecord = \DB::table('password_reset_tokens')->where('email', $user->email)->first();

if ($resetRecord) {
    echo "✅ Password reset token created in database\n";
    echo "   Email: {$resetRecord->email}\n";
    echo "   Created: {$resetRecord->created_at}\n";
} else {
    echo "❌ No password reset token found in database\n";
}

echo "\n=== Gmail SMTP Troubleshooting Tips ===\n";
echo "1. Pastikan 'Less secure app access' diaktifkan di Gmail (jika menggunakan password biasa)\n";
echo "2. Atau gunakan 'App Password' jika 2FA diaktifkan\n";
echo "3. Pastikan MAIL_MAILER=smtp (bukan log)\n";
echo "4. Pastikan MAIL_ENCRYPTION=tls\n";
echo "5. Pastikan MAIL_FROM_ADDRESS sama dengan MAIL_USERNAME\n";

echo "\n=== Next Steps ===\n";
echo "1. Cek email di: {$user->email}\n";
echo "2. Jika tidak ada email, cek spam folder\n";
echo "3. Jika masih tidak ada, periksa konfigurasi Gmail\n";
echo "4. Test fitur lupa password di browser\n";

echo "\n✅ Test completed!\n";
