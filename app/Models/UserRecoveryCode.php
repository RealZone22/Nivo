<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class UserRecoveryCode extends Model
{
    public const UPDATED_AT = null;

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'code',
    ];

    protected $hidden = [
        'code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
