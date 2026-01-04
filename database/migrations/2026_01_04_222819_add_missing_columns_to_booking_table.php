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
        Schema::table('booking', function (Blueprint $table) {
            // Add reservation expiry timestamp
            if (!Schema::hasColumn('booking', 'reservation_expires_at')) {
                $table->timestamp('reservation_expires_at')->nullable()->after('bookingStatus');
            }
            
            // Add deposit amount
            if (!Schema::hasColumn('booking', 'depositAmount')) {
                $table->decimal('depositAmount', 10, 2)->default(0)->after('totalPrice');
            }
            
            // Add promotion ID
            if (!Schema::hasColumn('booking', 'promo_id')) {
                $table->unsignedBigInteger('promo_id')->nullable()->after('depositAmount');
            }
            
            // Add voucher ID
            if (!Schema::hasColumn('booking', 'voucher_id')) {
                $table->unsignedBigInteger('voucher_id')->nullable()->after('promo_id');
            }
            
            // Add destination
            if (!Schema::hasColumn('booking', 'destination')) {
                $table->string('destination')->nullable()->after('voucher_id');
            }
            
            // Add remark
            if (!Schema::hasColumn('booking', 'remark')) {
                $table->text('remark')->nullable()->after('destination');
            }
            
            // Add bank information
            if (!Schema::hasColumn('booking', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('remark');
            }
            
            if (!Schema::hasColumn('booking', 'bank_owner_name')) {
                $table->string('bank_owner_name')->nullable()->after('bank_name');
            }
            
            // Add payment type
            if (!Schema::hasColumn('booking', 'pay_amount_type')) {
                $table->enum('pay_amount_type', ['full', 'deposit'])->default('deposit')->after('bank_owner_name');
            }
            
            // Add payment receipt path
            if (!Schema::hasColumn('booking', 'payment_receipt_path')) {
                $table->string('payment_receipt_path')->nullable()->after('pay_amount_type');
            }
            
            // Add driver information (for booking on behalf of someone else)
            if (!Schema::hasColumn('booking', 'for_someone_else')) {
                $table->boolean('for_someone_else')->default(false)->after('payment_receipt_path');
            }
            
            if (!Schema::hasColumn('booking', 'driver_matric_number')) {
                $table->string('driver_matric_number')->nullable()->after('for_someone_else');
            }
            
            if (!Schema::hasColumn('booking', 'driver_license_number')) {
                $table->string('driver_license_number')->nullable()->after('driver_matric_number');
            }
            
            if (!Schema::hasColumn('booking', 'driver_college')) {
                $table->string('driver_college')->nullable()->after('driver_license_number');
            }
            
            if (!Schema::hasColumn('booking', 'driver_faculty')) {
                $table->string('driver_faculty')->nullable()->after('driver_college');
            }
            
            if (!Schema::hasColumn('booking', 'driver_deposit_balance')) {
                $table->decimal('driver_deposit_balance', 10, 2)->default(0)->after('driver_faculty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $columns = [
                'reservation_expires_at',
                'depositAmount',
                'promo_id',
                'voucher_id',
                'destination',
                'remark',
                'bank_name',
                'bank_owner_name',
                'pay_amount_type',
                'payment_receipt_path',
                'for_someone_else',
                'driver_matric_number',
                'driver_license_number',
                'driver_college',
                'driver_faculty',
                'driver_deposit_balance'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('booking', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};