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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('id_card_number')->nullable();
            $table->date('join_date');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->decimal('share_capital', 15, 2)->default(0);
            $table->decimal('mandatory_savings', 15, 2)->default(0);
            $table->decimal('voluntary_savings', 15, 2)->default(0);
            $table->integer('points')->default(0);
            $table->timestamps();
            
            // Indexes for performance
            $table->index('member_id');
            $table->index('email');
            $table->index('status');
            $table->index(['status', 'join_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};