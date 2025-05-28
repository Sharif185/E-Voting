<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Only run if the elections table exists and has data
        if (Schema::hasTable('elections') && DB::table('elections')->exists()) {
            // Convert existing times assuming they were stored in local time
            DB::table('elections')->update([
                'start_date' => DB::raw("CONVERT_TZ(start_date, '+03:00', '+00:00')"), // Adjust +03:00 to your local offset
                'end_date' => DB::raw("CONVERT_TZ(end_date, '+03:00', '+00:00')")
            ]);
        }
    }

    public function down()
    {
        // Optional: Add logic to reverse the conversion if needed
    }
};
