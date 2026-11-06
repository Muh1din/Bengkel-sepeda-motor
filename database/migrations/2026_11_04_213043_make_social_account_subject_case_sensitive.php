<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $accounts = DB::table('social_accounts')
            ->select(['id', 'provider', 'provider_user_id'])
            ->get();
        $normalizedIdentities = [];

        foreach ($accounts as $account) {
            $identityKey = strtolower($account->provider)."\0".$account->provider_user_id;

            if (isset($normalizedIdentities[$identityKey])) {
                throw new RuntimeException(
                    'Cannot lowercase social account providers because duplicate provider identities exist.'
                );
            }

            $normalizedIdentities[$identityKey] = $account->id;
        }

        DB::table('social_accounts')->update([
            'provider' => DB::raw('LOWER(provider)'),
        ]);

        if (DB::connection()->getDriverName() === 'mysql') {
            Schema::table('social_accounts', function (Blueprint $table) {
                $table->string('provider_user_id', 255)
                    ->collation('utf8mb4_bin')
                    ->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            Schema::table('social_accounts', function (Blueprint $table) {
                $table->string('provider_user_id', 255)->change();
            });
        }
    }
};
