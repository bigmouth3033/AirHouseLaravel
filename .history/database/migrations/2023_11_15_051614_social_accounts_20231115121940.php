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
        Schema::create('social_accounts', function (Blueprint $table) {
            // Role: Auto-incrementing primary key representing the unique ID for each record in the table.
            $table->bigIncrements('id');
            // Role: Foreign key linked to the users table (or another table) to identify the user associated with the social media account.
            $table->unsignedBigInteger('user_id');
            $table->string('social_id');
            $table->string('social_provider');
            $table->string('social_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
