<?php

namespace App\Models;

use App\Enums\PhoneCallStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $caller_user_id
 * @property int $receiver_user_id
 * @property string $status
 * @property Carbon $called_at
 * @property Carbon $talk_started_at
 * @property Carbon $finished_at
 * @property int $call_charge
 * @property-read User $caller
 * @property-read User $receiver
 */
class PhoneCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'caller_user_id',
        'receiver_user_id',
        'status',
        'called_at',
        'talk_started_at',
        'finished_at',
        'call_charge',
    ];

    protected $casts = [
        'status' => PhoneCallStatus::class,
        'called_at' => 'immutable_datetime',
        'talk_started_at' => 'immutable_datetime',
        'finished_at' => 'immutable_datetime',
    ];

    /**
     * @return BelongsTo<User, PhoneCall>
     */
    public function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_user_id');
    }

    /**
     * @return BelongsTo<User, PhoneCall>
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }
}
