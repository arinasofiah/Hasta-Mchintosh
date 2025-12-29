<?

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'matricNumber'; 
    protected $keyType = 'string';          
    public $incrementing = false;         
    
    protected $fillable = [
        'matricNumber', 
        'userID', 
        'licenseNumber', 
        'college', 
        'faculty', 
        'depoBalance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}