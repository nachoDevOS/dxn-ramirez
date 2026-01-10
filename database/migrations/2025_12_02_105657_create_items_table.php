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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('presentation_id')->nullable()->constrained('presentations');
            $table->foreignId('laboratory_id')->nullable()->constrained('laboratories');
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->string('image')->nullable();
            $table->string('name')->nullable();
            // $table->string('nameTrade')->nullable();

            $table->smallInteger('fraction')->nullable();
            $table->foreignId('fractionPresentation_id')->nullable()->constrained('presentations');
            $table->decimal('fractionQuantity', 10, 2)->nullable();


            $table->text('observation')->nullable();

            $table->smallInteger('status')->default(1);

            $table->timestamps();            
            $table->foreignId('registerUser_id')->nullable()->constrained('users');
            $table->string('registerRole')->nullable();

            $table->softDeletes();
            $table->foreignId('deleteUser_id')->nullable()->constrained('users');
            $table->string('deleteRole')->nullable();
            $table->text('deleteObservation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
