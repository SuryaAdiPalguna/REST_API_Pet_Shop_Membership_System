<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adopts', function (Blueprint $table) {
            $table->ulid('id')->primary()->unique();
            $table->foreignUlid('member_id')->constrained('members', 'id', 'adopts_member_id')->cascadeOnDelete();
            $table->foreignUlid('puppy_id')->unique()->constrained('puppies', 'id', 'adopts_puppy_id')->cascadeOnDelete();
            $table->date('date');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adopts');
    }
};
