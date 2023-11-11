<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JamSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'name',
        'description',
        'start_time',
        'venue',
        'genre_id',
        'is_public',
        'image_uri',
    ];

    protected $appends = ['participants_count'];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'jam_participants', 'jam_session_id', 'user_id')->withPivot('role');
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    // get only the count of participants
    public function getParticipantsCountAttribute(): int
    {
        return $this->participants()->count();
    }
}
