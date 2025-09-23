<?

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailOtp extends Model {
  protected $fillable = ['user_id','code_hash','expires_at','attempts','last_sent_at'];
  protected $dates = ['expires_at','last_sent_at'];
  public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
