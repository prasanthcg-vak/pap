<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaign_staff', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('staff_id')->unsigned();
            $table->timestamps(); // Adds created_at & updated_at
            $table->softDeletes(); // Adds deleted_at for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_staff');
    }
};
