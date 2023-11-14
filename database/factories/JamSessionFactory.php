<?php

namespace Database\Factories;

use App\Models\Genre;
use App\Models\JamSession;
use App\Models\User;
use App\Utilities\Geocoder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<JamSession>
 */
class JamSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function definition(): array
    {
        static $names = [
            'Groove Junction',
            'Melody Meetup',
            'Harmony Hub',
            'Rhythm Rendezvous',
            'Bassline Bash',
            'Chord Conclave',
            'Beat Boutique',
            'Tune Town',
            'Vibe Village',
            'Sound Soirée',
            // New names added below
            'Echo Alley',
            'Tempo Terrace',
            'Lyric Lounge',
            'Pitch Plaza',
            'Sonic Session',
            'Cadence Carnival',
            'Melodic Mingle',
            'Syncopation Station',
            'Tune Trek',
            'Riff Realm',
            'Harmony Haven',
            'Note Nexus',
            'Scale Symposium',
            'Resonance Retreat',
            'Rhythm Realm',
            'Melody Mashup',
            'Beat Bazaar',
            'Chorus Corner',
            'Tempo Tribe',
            'Vibrato Valley',
        ];

        static $descriptions = [
            'A crossroads of rhythm and style where musicians gather to blend genres and create new sounds.',
            'Join fellow melody makers in a collaborative session to weave musical tales.',
            'A gathering for those who seek to create the perfect blend of voices and instruments in unison.',
            'A rhythmic escapade for percussionists and beat lovers to sync up and jam out.',
            'All about that bass! A session dedicated to the groove and depth of basslines.',
            'Guitarists and keyboardists unite to explore the world of chords and progressions.',
            'A chic assembly of beat-makers and producers crafting the pulse of tomorrow\'s hits.',
            'A playful jam for musicians of all levels to come together and share catchy tunes.',
            'An immersive musical experience where the vibe is just right for collaboration.',
            'An elegant evening of diverse sounds, from jazz to electronica, where experimentation is the guest of honor.',
            // New descriptions added below
            'Echo Alley is a haven for echo enthusiasts and reverb rebels looking to create expansive soundscapes.',
            'Step onto Tempo Terrace, where timekeepers and tempo tamers converge to create precise rhythmic patterns.',
            'Lyric Lounge, a cozy corner for songwriters and poets to meld words with melodies.',
            'Pitch Plaza invites tuneful talents to pitch in and harmonize in a symphony of collaboration.',
            'Sonic Session is the ultimate gathering for sound smiths and audio architects.',
            'Cadence Carnival, a festival of rhythm where drummers and dancers create a tapestry of beats.',
            'Melodic Mingle is where melodies meet and musicians mingle in a symphony of collaboration.',
            'Syncopation Station: a syncopated symposium for those who love off-beat rhythms and unexpected accents.',
            'Tune Trek, a musical journey for adventurers looking to discover new tunes and sounds.',
            'Riff Realm, where guitarists and bassists come to trade riffs and create grooves.',
            'Harmony Haven, a sanctuary for those seeking the serene sounds of perfect harmony.',
            'Note Nexus, the central hub for note-worthy collaborations and musical exchanges.',
            'Scale Symposium, a gathering of scale enthusiasts looking to explore new tonal territories.',
            'Resonance Retreat, a getaway for those looking to resonate with like-minded sound seekers.',
            'Rhythm Realm, a kingdom where the beat is king and rhythm rules all.',
            'Melody Mashup, where melodies from around the world come together in a beautiful blend.',
            'Beat Bazaar, a marketplace of rhythms where drummers and percussionists exchange beats.',
            'Chorus Corner, a gathering place for choirs and ensembles to create powerful collective vocals.',
            'Tempo Tribe, a community where tempo is treasured and rhythm is revered.',
            'Vibrato Valley, a vibrant venue for those who vibrate with the pulse of music.',
        ];

        $venues = [
            'Main St, Springfield, IL 62701',
            'Elm St, New York, NY 10001',
            'Oak St, Los Angeles, CA 90001',
            'Pine St, Chicago, IL 60601',
            'Maple St, Houston, TX 77001',
            'Cedar St, San Francisco, CA 94101',
            'Birch St, Miami, FL 33101',
            'Redwood St, Dallas, TX 75201',
            'Willow St, Phoenix, AZ 85001',
            'Cherry St, Philadelphia, PA 19101',
            'Walnut St, Denver, CO 80201',
            'Spruce St, Seattle, WA 98101',
            'Laurel St, Atlanta, GA 30301',
            'Sycamore St, Boston, MA 02101',
            'Cedar St, San Diego, CA 92101',
            'Pine St, Las Vegas, NV 89101',
            'Oak St, Austin, TX 78701',
            'Maple St, Portland, OR 97201',
            'Elm St, Baltimore, MD 21201',
            'Birch St, Nashville, TN 37201',
            'Redwood St, Detroit, MI 48201',
            'Willow St, Minneapolis, MN 55401',
            'Cherry St, Orlando, FL 32801',
            'Walnut St, Charlotte, NC 28201',
            'Spruce St, San Antonio, TX 78201',
            'Sycamore St, Raleigh, NC 27601',
            'Cedar St, Indianapolis, IN 46201',
            'Pine St, Columbus, OH 43201',
            'Oak St, Kansas City, MO 64101',
            'Maple St, New Orleans, LA 70101',
        ];


        // Ensure we don't run out of unique names/descriptions
        if (empty($names) || empty($descriptions)) {
            throw new \Exception('Not enough unique names or descriptions for Jam Sessions');
        }

        // Pick a unique name and description, then remove them from the arrays
        $nameKey = array_rand($names);
        $descriptionKey = array_rand($descriptions);

        $name = $names[$nameKey];
        $description = $descriptions[$descriptionKey];

        unset($names[$nameKey]);
        unset($descriptions[$descriptionKey]);

        $randVenue = $venues[array_rand($venues)];

        $coordinates = Geocoder::geocodeAddress($randVenue);

        return [
            'organizer_id' => User::inRandomOrder()->first()->id, // Select a random user as the organizer
            'name' => $name,
            'description' => $description,
            'start_time' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'venue' => $randVenue,
            'location' => DB::raw("ST_GeomFromText('POINT({$coordinates['lng']} {$coordinates['lat']})')"),
            'genre_id' => Genre::inRandomOrder()->first()->id,
            'is_public' => $this->faker->boolean,
            'image_uri' => 'https://picsum.photos/seed/' . $this->faker->word . '/720/400',
            // Add other fields as necessary
        ];
    }
}
