<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class JamParticipant extends Pivot
{
    use HasFactory;

    protected $table = 'jam_participants';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jamSession(): BelongsTo
    {
        return $this->belongsTo(JamSession::class);
    }
}
