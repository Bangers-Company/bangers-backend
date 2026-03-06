<?php

namespace Database\Seeders;

use App\Models\Act;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Stage;
use App\Models\EventTimetable;
use App\Models\TimetableEntry;
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
            ['name' => 'Da Tweekaz', 'genre' => 'Hardstyle'],
            ['name' => 'The Purge', 'genre' => 'Rawstyle'],
            ['name' => 'Mutilator', 'genre' => 'Rawstyle'],
            ['name' => 'Unresolved', 'genre' => 'Rawstyle'],
            ['name' => 'Regain', 'genre' => 'Rawstyle'],
            ['name' => 'Radical Redemption', 'genre' => 'Rawstyle'],
            ['name' => 'Dual Damage', 'genre' => 'Rawstyle'],
            ['name' => 'Mish', 'genre' => 'Rawstyle'],
            ['name' => 'Adjuzt', 'genre' => 'Rawstyle'],
            ['name' => 'Paul Elstak', 'genre' => 'Hardcore/Classics'],
            ['name' => 'Partyraiser', 'genre' => 'Uptempo'],
            ['name' => 'Dimitri K', 'genre' => 'Uptempo'],
            ['name' => 'Spitnoise', 'genre' => 'Uptempo'],
            ['name' => 'Major Conspiracy', 'genre' => 'Uptempo'],
            ['name' => 'Aversion', 'genre' => 'Rawstyle'],
            ['name' => 'Krowdexx', 'genre' => 'Rawstyle'],
            ['name' => 'Rejecta', 'genre' => 'Rawstyle'],
            ['name' => 'Act of Rage', 'genre' => 'Rawstyle'],
            ['name' => 'Hard Driver', 'genre' => 'Hardstyle'],
            ['name' => 'Adaro', 'genre' => 'Hardstyle'],
            ['name' => 'Crypsis', 'genre' => 'Rawstyle/Classics'],
            ['name' => 'Frequencerz', 'genre' => 'Hardstyle'],
            ['name' => 'Zany', 'genre' => 'Hardstyle/Classics'],
            ['name' => 'Jones', 'genre' => 'Hardstyle'],
            ['name' => 'Thera', 'genre' => 'Hardstyle'],
            ['name' => 'Geck-O', 'genre' => 'Hardstyle'],
            ['name' => 'B-Front', 'genre' => 'Hardstyle'],
            ['name' => 'Phuture Noize', 'genre' => 'Hardstyle'],
            ['name' => 'Ecstatic', 'genre' => 'Hardstyle'],
            ['name' => 'Jay Reeve', 'genre' => 'Hardstyle'],
            ['name' => 'Solstice', 'genre' => 'Hardstyle'],
            ['name' => 'Deezl', 'genre' => 'Rawstyle'],
            ['name' => 'Sparkz', 'genre' => 'Rawstyle'],
            ['name' => 'Kruelty', 'genre' => 'Rawstyle'],
            ['name' => 'Omnya', 'genre' => 'Rawstyle'],
            ['name' => 'Element', 'genre' => 'Rawstyle'],
            ['name' => 'BMBERJCK', 'genre' => 'Rawstyle'],

            // Rock & Metal
            ['name' => 'Metallica', 'genre' => 'Heavy Metal'],
            ['name' => 'Iron Maiden', 'genre' => 'Heavy Metal'],
            ['name' => 'Slipknot', 'genre' => 'Nu Metal'],
            ['name' => 'Rammstein', 'genre' => 'Industrial Metal'],
            ['name' => 'Bring Me The Horizon', 'genre' => 'Metalcore'],
            ['name' => 'Architects', 'genre' => 'Metalcore'],
            ['name' => 'Parkway Drive', 'genre' => 'Metalcore'],
            ['name' => 'Lorna Shore', 'genre' => 'Deathcore'],
            ['name' => 'Sleep Token', 'genre' => 'Alternative Metal'],
        ];

        $artistModels = [];
        foreach ($artistsData as $data) {
            $artistModels[$data['name']] = Artist::updateOrCreate(['name' => $data['name']], $data);
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
            ['name' => 'Reinier Zonneveld', 'artists' => ['Reinier Zonneveld'], 'is_live' => true],
            ['name' => 'Dr. Peacock: Peacock in Concert', 'artists' => ['Dr. Peacock']],
            ['name' => 'D-Sturb: Through My Veins', 'artists' => ['D-Sturb']],
            ['name' => 'Martin Garrix (Closing Set)', 'artists' => ['Martin Garrix']],
            ['name' => 'Angerfist', 'artists' => ['Angerfist'], 'is_live' => true],
            ['name' => 'The Prophet: The Last Show', 'artists' => ['The Prophet']],
            ['name' => 'D-Block & S-te-Fan (Ghost Stories)', 'artists' => ['D-Block & S-te-Fan'], 'is_live' => true],
            ['name' => 'Rammstein (Pyrotechnics Mix)', 'artists' => ['Rammstein']],
            ['name' => 'Slipknot (Masked Up)', 'artists' => ['Slipknot']],
            
            // Additional acts explicitly marked as live
            ['name' => 'Heavy Resistance', 'artists' => [], 'is_live' => true],
            ['name' => 'Hard Destiny', 'artists' => [], 'is_live' => true],
            ['name' => 'BMBERJCK', 'artists' => [], 'is_live' => true],
            ['name' => 'Act of Rage vs Rejecta', 'artists' => ['Act of Rage', 'Rejecta'], 'is_live' => true],
            ['name' => 'D-Sturb vs E-Force', 'artists' => ['D-Sturb', 'E-Force'], 'is_live' => true],
            ['name' => 'Sickmode & Krowdexx New Act', 'artists' => ['Sickmode', 'Krowdexx'], 'is_live' => true],
            ['name' => 'Mish vs The Straikerz', 'artists' => ['Mish'], 'is_live' => true],
            ['name' => 'Adjuzt vs Mutilator', 'artists' => ['Adjuzt', 'Mutilator'], 'is_live' => true],
            ['name' => 'Marshals of Mayhem', 'artists' => [], 'is_live' => true],
        ];

        // Add regular acts for each artist
        foreach ($artistModels as $name => $model) {
            $actsData[] = ['name' => $name, 'artists' => [$name]];
        }

        $actModels = [];
        foreach ($actsData as $data) {
            // Check if act name already exists to avoid duplicates from regular acts loop
            if (isset($actModels[$data['name']])) continue;

            $isLive = $data['is_live'] ?? false;

            $act = Act::updateOrCreate(
                ['name' => $data['name']],
                ['is_live' => $isLive]
            );
            foreach ($data['artists'] as $artistName) {
                if (isset($artistModels[$artistName])) {
                    $act->artists()->syncWithoutDetaching([$artistModels[$artistName]->id]);
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
            ['name' => 'Spectacular Festival', 'loc' => 'Oisterwijk, Netherlands', 'start' => '2025-05-30', 'end' => '2025-06-01'],
            ['name' => 'Coachella 2025', 'loc' => 'Indio, California', 'start' => '2025-04-11', 'end' => '2025-04-20'],
            ['name' => 'EDC Las Vegas 2025', 'loc' => 'Motor Speedway, LV', 'start' => '2025-05-16', 'end' => '2025-05-18'],
            ['name' => 'Wacken Open Air 2025', 'loc' => 'Wacken, Germany', 'start' => '2025-07-30', 'end' => '2025-08-02'],
            ['name' => 'Download Festival UK 2025', 'loc' => 'Donington Park, UK', 'start' => '2025-06-13', 'end' => '2025-06-15'],
        ];

        $eventModels = [];
        foreach ($eventsData as $data) {
            $eventModels[$data['name']] = Event::updateOrCreate(
                ['name' => $data['name']],
                [
                    'location' => $data['loc'],
                    'start_date' => Carbon::parse($data['start']),
                    'end_date' => Carbon::parse($data['end']),
                ]
            );
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
            'DYNAMITE' => 'High energy uptempo and hardcore.',
            'FANATICZ' => 'The rawstyle sanctuary.',
            'REVIVE' => 'Classic hardstyle and raw energy.',
            'BOOMBOX' => 'Fresh talent and experimental sounds.',
            'RELIVE' => 'Nostalgic classics and oldschool gems.',
            'INDOOR MAINSTAGE' => 'Massive indoor experience.',
            'OUTRAGEOUS!' => 'Pure party madness.',
            'KARNAVAL FESTIVAL' => 'The fun side of the festival.',
            'UITJE' => 'A small but crazy stage.',
            'INTENTSCITY' => 'The heart of the campsite.',
            'Faster Stage' => 'The holy ground of Wacken.',
            'Harder Stage' => 'Heavy riffs and hard hits.',
            'Louder Stage' => 'Where it truly gets loud.',
            'Apex Stage' => 'Download Festival main arena.',
            'Opus Stage' => 'Alternative and extreme metal heaven.',
        ];

        $stageModels = [];
        foreach ($stagesData as $name => $desc) {
            $stageModels[$name] = Stage::updateOrCreate(['name' => $name], ['description' => $desc]);
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
            'Spectacular Festival' => ['Mainstage', 'DYNAMITE', 'FANATICZ', 'REVIVE', 'BOOMBOX'],
            'Wacken Open Air 2025' => ['Faster Stage', 'Harder Stage', 'Louder Stage'],
            'Download Festival UK 2025' => ['Apex Stage', 'Opus Stage'],
        ];

        foreach ($eventStages as $eventName => $stageNames) {
            $event = $eventModels[$eventName];
            $event->stages()->detach(); // Clear old stages for this event
            $stageIds = [];
            foreach ($stageNames as $sName) {
                if (isset($stageModels[$sName])) {
                    $stageIds[] = $stageModels[$sName]->id;
                }
            }
            $event->stages()->sync($stageIds);
        }

        // 6. Massive Lineup (Edition specific, reusing acts)
        $lineups = [
            'Tomorrowland 2024' => [
                'Mainstage' => ['Dimitri Vegas & Like Mike Presents: The Hum', 'Armin van Buuren', 'Martin Garrix (Closing Set)', 'David Guetta', 'Tiësto'],
                'Freedom Stage' => ['Eric Prydz presents HOLO', 'Sebastian Ingrosso', 'Steve Angello'],
                'Atmosphere' => ['Charlotte de Witte (KNTXT Stage Host)', 'Amelie Lens', 'Carl Cox', 'Reinier Zonneveld'],
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
                'BLACK Stage' => ['Angerfist', 'Miss K8', 'Sefa: This is Sefa'],
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
                'RED Stage' => ['D-Block & S-te-Fan (Ghost Stories)', 'Sound Rush'],
            ],
            'Spectacular Festival' => [
                '2025-05-30' => [ // Friday
                    'Mainstage' => [
                        'Doors to Mainstage open',
                        'The Opening Ceremony',
                        'D-Block & S-te-Fan',
                        'Da Tweekaz',
                        'Rooler',
                        'Vertile',
                        'The Purge presents HYTRIP',
                        'Warface vs Mutilator',
                        'Unresolved vs Regain',
                        'Radical Redemption',
                    ],
                    'DYNAMITE' => [
                        'UDOW vs Missy vs Svenergy',
                        'Aalst vs Tharoza vs Screecher',
                        'Unproven - 10 years',
                        'Soulblast vs Abaddon',
                        'Odium',
                        'Gezellige Uptempo pres: Crapital of Craziness openingsshow',
                        'G3Z3LLIG3 BOUNC3 by Rosbeek & Kili & Akimbo',
                        'Gezellige Uptempo pres: The Ass-Assins',
                        'Major Conspiracy Uptempo Karaoke',
                        'Spitnoise vs N-Vitral',
                        'Gezellige Uptempo vs Dimitri K vs Partyraiser',
                    ],
                    'FANATICZ' => [
                        'D-Venn vs Incult',
                        'Faceless vs Infliction',
                        'Dual Damage - Opening',
                        'Dual Damage vs Cardination',
                        'Collusion vs Revelation',
                        'Heavy Resistance',
                        'Hard Destiny',
                        'Element vs Unload',
                        'Dual Damage vs The Straikerz',
                        'BMBERJCK',
                        'Deezl vs Sparkz',
                        'Kruelty vs Omnya',
                    ],
                    'REVIVE' => [
                        'Jones - The Mind of A Lunatick',
                        'Nightcraft',
                        'Thera vs Geck-O Classics',
                        'Sub Sonik',
                        'Wolv',
                        'Rejecta',
                        'E-Force',
                        'Crypsis - 15 years',
                        'Adaro vs Deluzion',
                        'D-Verze vs Main Concern - Classics',
                        'Jason Payne - Goldschool',
                    ],
                    'BOOMBOX' => [
                        'Miss Isa',
                        'SVANE',
                        'Josha',
                        'Repeller vs Amduscias',
                        'Royalistiq',
                        'Dark Individual',
                        'Break the Rules Showcase',
                        'Nexor',
                        'Wheelhatz vs Disphaze',
                        'DJ Contest',
                    ],
                ],
                '2025-05-31' => [ // Saturday
                    'Mainstage' => [
                        'Wildstylez', 'Headhunterz', 'D-Block & S-te-Fan', 'Sound Rush',
                        'Da Tweekaz', 'Sub Zero Project', 'Rebelion', 'Vertile', 'D-Sturb', 'Warface'
                    ],
                    'DYNAMITE' => [
                        'Angerfist', 'Miss K8', 'N-Vitral', 'Partyraiser',
                        'Dimitri K', 'Spitnoise', 'Major Conspiracy', 'Paul Elstak'
                    ],
                    'FANATICZ' => [
                        'Aversion', 'Sickmode', 'Rooler', 'Mutilator', 'The Purge',
                        'Adjuzt', 'Mish', 'Dual Damage', 'Deezl', 'Sparkz'
                    ],
                    'REVIVE' => [
                        'Crypsis', 'Adaro', 'Zany', 'Brennan Heart',
                        'B-Front', 'Frequencerz', 'Thera', 'Geck-O'
                    ],
                    'BOOMBOX' => [
                        'Ecstatic', 'Jay Reeve', 'Solstice', 'Rejecta', 'Act of Rage'
                    ],
                    'INDOOR MAINSTAGE' => [
                        'Hard Driver', 'Phuture Noize', 'Krowdexx', 'Kruelty',
                        'Omnya', 'Element', 'BMBERJCK'
                    ],
                ],
                '2025-06-01' => [ // Sunday
                    'Mainstage' => [
                        'DJ Contest',
                        'Ecstatic vs Jay Reeve vs Solstice',
                        'Hard Driver vs Sound Rush',
                        'B-Front vs Phuture Noize',
                        'Act of Rage vs Rejecta',
                        'D-Sturb vs E-Force',
                        'Rebelion vs Aversion',
                        'Sickmode & Krowdexx New Act',
                        'Mish vs The Straikerz',
                        'Adjuzt vs Mutilator',
                        'Marshals of Mayhem',
                        'Element vs BMBERJCK vs The Saints',
                        'Warface - Electric Dreams',
                        'Paul Elstak',
                        'Sunday Endshow',
                    ],
                    'INDOOR MAINSTAGE' => [
                        'Lunaticz',
                        'Bass Chaserz vs Ginia',
                        'Outsiders vs Pat B',
                        'John West',
                        'Altijd Larstig & Rob Gasd\'rop',
                        'Gezellige Uptempo & Unlocked',
                        'The Darkraver & Freddy Moreira',
                        'Outsiders',
                        'Django Wagner',
                        'Mental Theo',
                        'Outsiders & Partyraiser',
                    ],
                    'RELIVE' => [
                        'Zany vs Jones',
                        'Adrenalize vs Atmozfears',
                        'Brennan Heart',
                        'Frequencerz',
                        'B-Front',
                        'Regain Classics',
                        'E-Force vs Unresolved',
                    ],
                ],
            ],
            'Coachella 2025' => [
                'Mainstage' => ['Calvin Harris', 'Charlotte de Witte', 'Tiësto'],
                'Sahara' => ['Anyma'],
            ],
            'EDC Las Vegas 2025' => [
                'Kinetic FIELD' => ['Martin Garrix', 'David Guetta', 'Armin van Buuren'],
                'Circuit GROUNDS' => ['Sub Zero Project', 'Hardwell'],
            ],
            'Wacken Open Air 2025' => [
                'Faster Stage' => ['Metallica', 'Iron Maiden', 'Slipknot'],
                'Harder Stage' => ['Rammstein', 'Parkway Drive'],
                'Louder Stage' => ['Lorna Shore', 'Sleep Token'],
            ],
            'Download Festival UK 2025' => [
                'Apex Stage' => ['Bring Me The Horizon', 'Architects'],
                'Opus Stage' => ['Slipknot (Masked Up)', 'Lorna Shore'],
            ],
        ];

        foreach ($lineups as $eventName => $stagesOrDates) {
            $event = $eventModels[$eventName];
            $event->acts()->detach(); // Clear old lineup for this event

            // Check if this is a multi-day structure
            reset($stagesOrDates);
            $firstKey = key($stagesOrDates);

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $firstKey)) {
                // Multi-day structure: [date => [stage => [act, ...]]]
                foreach ($stagesOrDates as $date => $stages) {
                    foreach ($stages as $stageName => $actNames) {
                        $stage = $stageModels[$stageName] ?? Stage::firstOrCreate(['name' => $stageName]);
                        foreach ($actNames as $aName) {
                            if (!isset($actModels[$aName])) {
                                $act = Act::firstOrCreate(['name' => $aName], ['is_live' => false]);
                                $actModels[$aName] = $act;
                            } else {
                                $act = $actModels[$aName];
                            }
                            $event->acts()->attach($act->id, ['stage_id' => $stage->id, 'date' => $date]);
                        }
                    }
                }
            } else {
                // Regular structure: [stage => [act, ...]]
                foreach ($stagesOrDates as $stageName => $actNames) {
                    $stage = $stageModels[$stageName] ?? Stage::firstOrCreate(['name' => $stageName]);
                    foreach ($actNames as $aName) {
                        if (!isset($actModels[$aName])) {
                            $act = Act::firstOrCreate(['name' => $aName], ['is_live' => false]);
                            $actModels[$aName] = $act;
                        } else {
                            $act = $actModels[$aName];
                        }

                        $pivotData = ['stage_id' => $stage->id];
                        // Assign a random date between start and end if it's not the Spectacular Festival
                        if ($eventName !== 'Spectacular Festival') {
                            $start = Carbon::parse($event->start_date);
                            $end = Carbon::parse($event->end_date);
                            $diff = $start->diffInDays($end);
                            $randomDate = $start->copy()->addDays(rand(0, $diff))->format('Y-m-d');
                            $pivotData['date'] = $randomDate;
                        }

                        $event->acts()->attach($act->id, $pivotData);
                        $actModels[$aName] = $act;
                    }
                }
            }
        }

        // 7. Uncategorized Acts (attached to event but no stage)
        $eventModels['Tomorrowland 2024']->acts()->attach([
            $actModels['Alesso']->id => ['stage_id' => null],
            $actModels['Steve Aoki']->id => ['stage_id' => null],
        ]);

        // 8. Official Timetable for Spectacular Festival (3 Days)
        $spectacularEvent = $eventModels['Spectacular Festival'];
        $officialTimetable = EventTimetable::updateOrCreate(
            [
                'event_id' => $spectacularEvent->id,
                'is_official' => true,
            ],
            [
                'name' => 'Official Timetable',
                'is_public' => true,
            ]
        );

        // Clear existing entries to avoid duplicates/overlaps on re-seed
        $officialTimetable->entries()->delete();

        foreach ($lineups['Spectacular Festival'] as $date => $stages) {
            foreach ($stages as $stageName => $actNames) {
                if (!isset($stageModels[$stageName])) continue;

                $stage = $stageModels[$stageName];
                // Starts at 09:00
                $startTime = Carbon::parse($date)->setHour(9)->setMinute(0);

                foreach ($actNames as $aName) {
                    $act = $actModels[$aName] ?? null;
                    if (!$act) continue;

                    $duration = 60; // Default 60 minutes

                    // Special cases for longer sets or specific names
                    if (str_contains($aName, 'Closing Set')) $duration = 90;
                    if (str_contains($aName, 'Endshow')) $duration = 30;
                    if (str_contains($aName, 'Opening Ceremony')) $duration = 30;
                    if (str_contains($aName, 'Doors to Mainstage')) $duration = 30;

                    // Ensure we don't exceed 2:00 AM next day (1020 minutes from 9 AM)
                    $baseTime = Carbon::parse($date)->setHour(9)->setMinute(0);
                    $maxEndTime = $baseTime->copy()->addHours(17); // 9:00 + 17h = 02:00 next day

                    $endTime = $startTime->copy()->addMinutes($duration);

                    if ($endTime->gt($maxEndTime)) {
                        $duration = $startTime->diffInMinutes($maxEndTime);
                        if ($duration < 15) break; // Skip if no time left
                        $endTime = $maxEndTime->copy();
                    }

                    TimetableEntry::create([
                        'timetable_id' => $officialTimetable->id,
                        'stage_id' => $stage->id,
                        'act_id' => $act->id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ]);

                    $startTime = $endTime->copy()->addMinutes(15); // 15 min break between sets
                }
            }
        }
    }
}
