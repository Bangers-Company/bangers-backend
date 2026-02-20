<?php

namespace Database\Seeders;

use App\Models\Act;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Stage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FestivalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Artists across different genres
        $artistsData = [
            // EDM / Mainstream
            ['name' => 'Dimitri Vegas & Like Mike', 'genre' => 'EDM'],
            ['name' => 'Armin van Buuren', 'genre' => 'Trance'],
            ['name' => 'Martin Garrix', 'genre' => 'EDM'],
            ['name' => 'Tiësto', 'genre' => 'Multi'],
            ['name' => 'Hardwell', 'genre' => 'EDM'],
            ['name' => 'David Guetta', 'genre' => 'EDM'],
            ['name' => 'Calvin Harris', 'genre' => 'EDM'],
            ['name' => 'Steve Aoki', 'genre' => 'EDM'],
            ['name' => 'Afrojack', 'genre' => 'EDM'],
            ['name' => 'Alesso', 'genre' => 'EDM'],
            ['name' => 'Sebastian Ingrosso', 'genre' => 'Progressive House'],
            ['name' => 'Steve Angello', 'genre' => 'Progressive House'],
            ['name' => 'Axwell', 'genre' => 'Progressive House'],

            // Techno / House
            ['name' => 'Charlotte de Witte', 'genre' => 'Techno'],
            ['name' => 'Amelie Lens', 'genre' => 'Techno'],
            ['name' => 'Carl Cox', 'genre' => 'Techno'],
            ['name' => 'Eric Prydz', 'genre' => 'Techno/House'],
            ['name' => 'Boris Brejcha', 'genre' => 'High-Tech Minimal'],
            ['name' => 'Fisher', 'genre' => 'Tech House'],
            ['name' => 'Chris Lake', 'genre' => 'Tech House'],
            ['name' => 'Anyma', 'genre' => 'Melodic Techno'],
            ['name' => 'Tale Of Us', 'genre' => 'Melodic Techno'],
            ['name' => 'Reinier Zonneveld', 'genre' => 'Techno'],
            ['name' => '999999999', 'genre' => 'Techno'],
            ['name' => 'I Hate Models', 'genre' => 'Techno'],

            // Hardstyle / Harder Styles
            ['name' => 'Headhunterz', 'genre' => 'Hardstyle'],
            ['name' => 'Wildstylez', 'genre' => 'Hardstyle'],
            ['name' => 'Sub Zero Project', 'genre' => 'Hardstyle'],
            ['name' => 'Brennan Heart', 'genre' => 'Hardstyle'],
            ['name' => 'Ran-D', 'genre' => 'Hardstyle'],
            ['name' => 'D-Block & S-te-Fan', 'genre' => 'Hardstyle'],
            ['name' => 'Sound Rush', 'genre' => 'Hardstyle'],
            ['name' => 'Vertile', 'genre' => 'Rawstyle'],
            ['name' => 'Rebelion', 'genre' => 'Rawstyle'],
            ['name' => 'Warface', 'genre' => 'Rawstyle'],
            ['name' => 'D-Sturb', 'genre' => 'Rawstyle'],
            ['name' => 'Rooler', 'genre' => 'Rawstyle'],
            ['name' => 'Sickmode', 'genre' => 'Rawstyle'],
            ['name' => 'Sefa', 'genre' => 'Frenchcore'],
            ['name' => 'Dr. Peacock', 'genre' => 'Frenchcore'],
            ['name' => 'Angerfist', 'genre' => 'Hardcore'],
            ['name' => 'Miss K8', 'genre' => 'Hardcore'],
            ['name' => 'N-Vitral', 'genre' => 'Hardcore'],
            ['name' => 'The Prophet', 'genre' => 'Hardstyle'],
        ];

        $artistModels = [];
        foreach ($artistsData as $data) {
            $artistModels[$data['name']] = Artist::create($data);
        }

        // 2. Specialized Acts
        $actsData = [
            ['name' => 'Dimitri Vegas & Like Mike Presents: The Hum', 'artists' => ['Dimitri Vegas & Like Mike']],
            ['name' => 'Armin van Buuren (A State of Trance)', 'artists' => ['Armin van Buuren']],
            ['name' => 'Swedish House Mafia', 'artists' => ['Axwell', 'Sebastian Ingrosso', 'Steve Angello']],
            ['name' => 'Eric Prydz presents HOLO', 'artists' => ['Eric Prydz']],
            ['name' => 'Boris Brejcha (In Concert)', 'artists' => ['Boris Brejcha']],
            ['name' => 'Sub Zero Project: Robot Heritage', 'artists' => ['Sub Zero Project']],
            ['name' => 'Sefa: This is Sefa', 'artists' => ['Sefa']],
            ['name' => 'Rebelion: The Second Dose', 'artists' => ['Rebelion']],
            ['name' => 'Headhunterz vs Wildstylez', 'artists' => ['Headhunterz', 'Wildstylez']],
            ['name' => 'The Gang', 'artists' => ['Rooler', 'Sickmode']],
            ['name' => 'Gunz for Hire', 'artists' => ['Ran-D']],
            ['name' => 'Charlotte de Witte (KNTXT Stage Host)', 'artists' => ['Charlotte de Witte']],
            ['name' => 'Anyma presents Genesys', 'artists' => ['Anyma']],
            ['name' => 'Reinier Zonneveld (Live)', 'artists' => ['Reinier Zonneveld']],
            ['name' => 'Dr. Peacock: Peacock in Concert', 'artists' => ['Dr. Peacock']],
            ['name' => 'D-Sturb: Through My Veins', 'artists' => ['D-Sturb']],
            ['name' => 'Martin Garrix (Closing Set)', 'artists' => ['Martin Garrix']],
            ['name' => 'Angerfist LIVE', 'artists' => ['Angerfist']],
            ['name' => 'The Prophet: The Last Show', 'artists' => ['The Prophet']],
            ['name' => 'D-Block & S-te-Fan (Ghost Stories Live)', 'artists' => ['D-Block & S-te-Fan']],
        ];

        // Add regular acts for each artist
        foreach ($artistModels as $name => $model) {
            $actsData[] = ['name' => $name, 'artists' => [$name]];
        }

        $actModels = [];
        foreach ($actsData as $data) {
            // Check if act name already exists to avoid duplicates from regular acts loop
            if (isset($actModels[$data['name']])) continue;

            $act = Act::create(['name' => $data['name']]);
            foreach ($data['artists'] as $artistName) {
                if (isset($artistModels[$artistName])) {
                    $act->artists()->attach($artistModels[$artistName]->id);
                }
            }
            $actModels[$data['name']] = $act;
        }

        // 3. Global Festivals (Events)
        $eventsData = [
            ['name' => 'Tomorrowland 2024', 'loc' => 'Boom, Belgium', 'start' => '2024-07-19', 'end' => '2024-07-28'],
            ['name' => 'Tomorrowland 2025', 'loc' => 'Boom, Belgium', 'start' => '2025-07-18', 'end' => '2025-07-27'],
            ['name' => 'Defqon.1 2024', 'loc' => 'Biddinghuizen, Netherlands', 'start' => '2024-06-27', 'end' => '2024-06-30'],
            ['name' => 'Defqon.1 2025', 'loc' => 'Biddinghuizen, Netherlands', 'start' => '2025-06-26', 'end' => '2025-06-29'],
            ['name' => 'Intents Festival 2025', 'loc' => 'Oisterwijk, Netherlands', 'start' => '2025-06-06', 'end' => '2025-06-08'],
            ['name' => 'Ultra Music Festival Miami 2024', 'loc' => 'Miami, Florida', 'start' => '2024-03-22', 'end' => '2024-03-24'],
            ['name' => 'Creamfields UK 2024', 'loc' => 'Daresbury, UK', 'start' => '2024-08-22', 'end' => '2024-08-25'],
            ['name' => 'Mysteryland 2024', 'loc' => 'Haarlemmermeer, Netherlands', 'start' => '2024-08-30', 'end' => '2024-09-01'],
            ['name' => 'EDC Las Vegas 2024', 'loc' => 'Motor Speedway, LV', 'start' => '2024-05-17', 'end' => '2024-05-19'],
        ];

        $eventModels = [];
        foreach ($eventsData as $data) {
            $eventModels[$data['name']] = Event::create([
                'name' => $data['name'],
                'location' => $data['loc'],
                'start_date' => Carbon::parse($data['start']),
                'end_date' => Carbon::parse($data['end']),
            ]);
        }

        // 4. Iconic Stages
        $stagesData = [
            'Mainstage' => 'The crown jewel of the festival.',
            'Freedom Stage' => 'Massive indoor arena with LED ceiling.',
            'Atmosphere' => 'Custom-built Techno cathedral.',
            'Core' => 'Deep house magic in the forest.',
            'Library' => 'Giant bookshelves stage.',
            'Rose Garden' => 'Floating dragon stage.',
            'RED Stage' => 'The ultimate Hardstyle outdoor stage.',
            'BLUE Stage' => 'Raw and Hardcore power arena.',
            'BLACK Stage' => 'The temple of Hardcore.',
            'YELLOW Stage' => 'Up-tempo and Frenchcore crazyhouse.',
            'Ultra Mainstage' => 'Futuristic design at Bayfront Park.',
            'MegaStructure' => 'Carl Cox\'s Techno home.',
            'Arcadia Spider' => 'Fire-breathing mechanical spider.',
            'Kinetic FIELD' => 'The heart of EDC.',
            'Circuit GROUNDS' => 'Surrounding LED towers.',
        ];

        $stageModels = [];
        foreach ($stagesData as $name => $desc) {
            $stageModels[$name] = Stage::create(['name' => $name, 'description' => $desc]);
        }

        // 5. Associating Stages to Events
        $eventStages = [
            'Tomorrowland 2024' => ['Mainstage', 'Freedom Stage', 'Atmosphere', 'Core', 'Library'],
            'Tomorrowland 2025' => ['Mainstage', 'Freedom Stage', 'Atmosphere', 'Library', 'Rose Garden'],
            'Defqon.1 2024' => ['RED Stage', 'BLUE Stage', 'BLACK Stage', 'YELLOW Stage'],
            'Defqon.1 2025' => ['RED Stage', 'BLUE Stage', 'BLACK Stage'],
            'Intents Festival 2025' => ['Mainstage', 'RED Stage', 'YELLOW Stage'],
            'Ultra Music Festival Miami 2024' => ['Ultra Mainstage', 'MegaStructure', 'Arcadia Spider'],
            'Creamfields UK 2024' => ['Mainstage', 'MegaStructure', 'Circuit GROUNDS'],
            'Mysteryland 2024' => ['Mainstage', 'Library', 'Atmosphere'],
            'EDC Las Vegas 2024' => ['Kinetic FIELD', 'Circuit GROUNDS', 'MegaStructure'],
        ];

        foreach ($eventStages as $eventName => $stageNames) {
            $event = $eventModels[$eventName];
            foreach ($stageNames as $sName) {
                if (isset($stageModels[$sName])) {
                    $event->stages()->attach($stageModels[$sName]->id);
                }
            }
        }

        // 6. Massive Lineup (Edition specific, reusing acts)
        $lineups = [
            'Tomorrowland 2024' => [
                'Mainstage' => ['Dimitri Vegas & Like Mike Presents: The Hum', 'Armin van Buuren', 'Martin Garrix (Closing Set)', 'David Guetta', 'Tiësto'],
                'Freedom Stage' => ['Eric Prydz presents HOLO', 'Sebastian Ingrosso', 'Steve Angello'],
                'Atmosphere' => ['Charlotte de Witte (KNTXT Stage Host)', 'Amelie Lens', 'Carl Cox', 'Reinier Zonneveld (Live)'],
                'Core' => ['Tale Of Us', 'Anyma presents Genesys'],
            ],
            'Tomorrowland 2025' => [
                'Mainstage' => ['Swedish House Mafia', 'Martin Garrix', 'Hardwell', 'Armin van Buuren'],
                'Freedom Stage' => ['Boris Brejcha (In Concert)', 'Anyma'],
                'Library' => ['D-Block & S-te-Fan', 'Sub Zero Project', 'Brennan Heart'],
            ],
            'Defqon.1 2024' => [
                'RED Stage' => ['Headhunterz vs Wildstylez', 'Sub Zero Project: Robot Heritage', 'Ran-D: Illuminate', 'The Prophet: The Last Show'],
                'BLUE Stage' => ['Rebelion: The Second Dose', 'Warface: Rest in Pieces', 'D-Sturb: Through My Veins', 'Vertile'],
                'BLACK Stage' => ['Angerfist LIVE', 'Miss K8', 'Sefa: This is Sefa'],
                'YELLOW Stage' => ['Dr. Peacock: Peacock in Concert', 'N-Vitral'],
            ],
            'Ultra Music Festival Miami 2024' => [
                'Ultra Mainstage' => ['Hardwell', 'Martin Garrix', 'David Guetta', 'Armin van Buuren'],
                'MegaStructure' => ['Carl Cox', 'Eric Prydz', 'Anyma'],
            ],
            'Creamfields UK 2024' => [
                'MegaStructure' => ['Eric Prydz presents HOLO', 'Carl Cox'],
                'Circuit GROUNDS' => ['Martin Garrix', 'Armin van Buuren'],
            ],
            'Intents Festival 2025' => [
                'Mainstage' => ['The Gang', 'Sub Zero Project', 'Rebelion'],
                'RED Stage' => ['D-Block & S-te-Fan (Ghost Stories Live)', 'Sound Rush'],
            ],
        ];

        foreach ($lineups as $eventName => $stages) {
            $event = $eventModels[$eventName];
            foreach ($stages as $stageName => $actNames) {
                $stage = $stageModels[$stageName];
                foreach ($actNames as $aName) {
                    if (isset($actModels[$aName])) {
                        $event->acts()->attach($actModels[$aName]->id, ['stage_id' => $stage->id]);
                    }
                }
            }
        }
    }
}
