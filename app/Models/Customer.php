<?

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    // Tell Laravel to use your singular table name
    protected $table = 'customer'; 
    
    protected $primaryKey = 'userID';
    public $incrementing = false; // Linked to User ID

    protected $fillable = [
        'userID',
        'matricNumber',
        'licenseNumber',
        'college',
        'faculty',
        'depoBalance',
    ];

    // 3NF Relationship: Customer belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    }


