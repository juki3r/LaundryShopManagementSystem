<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'contact_number',
        'address',
        'service_type',
        'weight',
        'laundry_status',
        'claimed',
        'delivered',
        'total',
        'amount_status',
        'order_date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
