<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
            $table->boolean('marked_for_deletion')->default(false)->after('deleted_by');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('deleted_by');
            $table->dropColumn('marked_for_deletion');
        });
    }
};
