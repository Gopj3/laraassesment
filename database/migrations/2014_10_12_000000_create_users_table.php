<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('prefixname')->nullable();
            $table->string('firstname')->nullable(false);
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable(false);
            $table->string('suffixname')->nullable();
            $table->string('username')->unique()->nullable(false);
            $table->string('email')->unique();
            $table->string('password');
            $table->text('photo')->nullable();
            $table->string('type')->index()->nullable()->default('user');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
