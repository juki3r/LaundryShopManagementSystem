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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // link order to user
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('customer_name');
            $table->string('contact_number');
            $table->text('address');

            $table->enum('service_type', ['Delivery', 'Pick-up']);
            $table->decimal('weight', 8, 2);

            $table->enum('laundry_status', ['Waiting', 'Approved', 'Denied'])->default('Waiting');
            $table->enum('claimed', ['Yes', 'No'])->default('No');
            $table->enum('delivered', ['Yes', 'No'])->default('No');

            $table->decimal('total', 10, 2);
            $table->enum('amount_status', ['Pending', 'Paid'])->default('Pending');

            $table->timestamp('order_date')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
