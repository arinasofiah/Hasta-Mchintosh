<?

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'matricNumber'; // Set matricNumber as PK
    public $incrementing = false;          // PK is not an auto-incrementing integer
    protected $keyType = 'string';          // PK is a string

    protected $fillable = [
        'matricNumber', 
        'userID', 
        'depoBalance'
    ];

    // Link back to the User supertype
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}

