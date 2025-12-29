<?

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $primaryKey = 'userID'; 
    public $incrementing = false;   

    protected $fillable = [
        'userID', 
        'matricNumber', 
        'licenseNumber', 
        'college', 
        'faculty', 
        'depoBalance',
        'isBlacklisted',
        'blacklistReason'
    ];

    // Link back to the User supertype
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}