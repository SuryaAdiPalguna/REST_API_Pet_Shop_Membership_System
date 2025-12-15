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
        Schema::create('puppy_cares', function (Blueprint $table) {
            $table->ulid('id')->primary()->unique();
            $table->foreignUlid('puppy_id')->constrained('puppies', 'id', 'puppy_cares_puppy_id')->cascadeOnDelete();
            $table->foreignUlid('care_id')->constrained('cares', 'id', 'puppy_cares_care_id')->cascadeOnDelete();
            $table->integer('period');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puppy_cares');
    }
};
