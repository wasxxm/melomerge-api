<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class JamParticipant extends Model
{
    use HasFactory;

    protected $table = 'jam_participants';

    protected $fillable = [
        'jam_session_id',
        'user_id',
        'instrument_id',
        'role_id',
        'message',
        'skill_level_id',
    ];

//    protected $with = ['instrument', 'role', 'skill_level'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jamSession(): BelongsTo
    {
        return $this->belongsTo(JamSession::class);
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function skill_level(): BelongsTo
    {
        return $this->belongsTo(SkillLevel::class);
    }
}
