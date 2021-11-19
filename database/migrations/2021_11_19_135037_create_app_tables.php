<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
        });
        
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
        });

        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
        });

        Schema::table('user', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('user');
            $table->foreign('role_id')->references('id')->on('role');
            $table->foreign('company_id')->references('id')->on('company');
        });

        Schema::table('company', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
        Schema::dropIfExists('company');
        Schema::dropIfExists('role');
    }
}
