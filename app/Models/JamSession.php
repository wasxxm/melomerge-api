<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JamSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'name',
        'description',
        'start_time',
        'venue',
        'location',
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
        return $this->belongsToMany(User::class, 'jam_participants', 'jam_session_id', 'user_id')->withPivot('role_id');
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

    public function getLocationAttribute($value): array
    {
        // Assuming the location is stored as a POINT
        $location = unpack('x/x/x/x/corder/Ltype/dlat/dlon', $value);

        return ['lat' => $location['lat'], 'lng' => $location['lon']];
    }

    public function scopeNearby($query, $latitude, $longitude, $distanceInKm): void
    {
        $distanceInMeters = $distanceInKm * 1000;

        // Using raw expressions for spatial calculations
        $query->select('*')
            ->whereRaw('ST_DISTANCE_SPHERE(location, POINT(?, ?)) <= ?', [$longitude, $latitude, $distanceInMeters])
            ->addSelect(DB::raw('(ST_DISTANCE_SPHERE(location, POINT('.$longitude.', '.$latitude.')) / 1000) AS distance'));
    }

}
