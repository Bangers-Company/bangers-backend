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
            ['name' => 'EZG', 'genre' => 'Hardcore/Rap'],
            ['name' => 'Outsiders', 'genre' => 'EDM/Hardstyle'],
            ['name' => 'Refold', 'genre' => 'Rawstyle'],
            ['name' => 'Vexxed', 'genre' => 'Rawstyle'],
            ['name' => 'Amigo', 'genre' => 'Uptempo'],
            ['name' => 'Namara', 'genre' => 'Uptempo'],
            ['name' => 'Silvio Aquila', 'genre' => 'Hardstyle'],
            ['name' => 'The Pitcher', 'genre' => 'Hardstyle'],
            ['name' => 'Hyperverb', 'genre' => 'Hardcore'],
            ['name' => 'Chaos Project', 'genre' => 'Hardcore'],
            ['name' => 'Karun', 'genre' => 'Hardcore'],
            ['name' => 'Unfused', 'genre' => 'Hardcore'],
            ['name' => 'Furyan', 'genre' => 'Hardcore'],
            ['name' => 'Boogshe', 'genre' => 'MC'],
            ['name' => 'D-Fence', 'genre' => 'Hardcore'],
            ['name' => 'Never Surrender', 'genre' => 'Hardcore'],
            ['name' => 'Gabber Syndrome', 'genre' => 'Hardcore'],
            ['name' => 'Noxa', 'genre' => 'Hardcore'],
            ['name' => 'Kasparov', 'genre' => 'Hardcore'],
            ['name' => 'Mad Dog', 'genre' => 'Hardcore'],
            ['name' => 'Art of Fighters', 'genre' => 'Hardcore'],
            ['name' => 'Korsakoff', 'genre' => 'Hardcore'],
            ['name' => 'Neophyte', 'genre' => 'Hardcore'],
            ['name' => 'Drokz', 'genre' => 'Hardcore/Terror'],
            ['name' => 'Dâvinø', 'genre' => 'Freestyle'],
            ['name' => 'Patjoo', 'genre' => 'Freestyle'],
            ['name' => 'Hans Glock', 'genre' => 'Freestyle'],
            ['name' => 'Dr. Rude', 'genre' => 'Hardstyle/Freestyle'],
            ['name' => 'DJ Jantje', 'genre' => 'Freestyle'],
            ['name' => 'DIKKE BAAP', 'genre' => 'Hardcore/Party'],
            ['name' => 'Pat B', 'genre' => 'Jumpstyle/Freestyle'],
            ['name' => 'Potato', 'genre' => 'Freestyle'],
            ['name' => 'Synergy', 'genre' => 'Host'],
            ['name' => 'FLO', 'genre' => 'Host'],
            ['name' => 'Robs', 'genre' => 'Host'],
            ['name' => 'DV8', 'genre' => 'Host'],
            ['name' => 'Tha Watcher', 'genre' => 'Host'],
            ['name' => 'Alee', 'genre' => 'Host'],
            ['name' => 'DL', 'genre' => 'Host'],

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
            
            // Rebirth Festival Artists
            ['name' => 'D-Charged', 'genre' => 'Hardstyle'],
            ['name' => 'Serzo', 'genre' => 'Hardstyle'],
            ['name' => 'Latinity', 'genre' => 'Hardstyle'],
            ['name' => 'Luxo', 'genre' => 'Hardstyle'],
            ['name' => 'Maistro', 'genre' => 'Hardstyle'],
            ['name' => 'Tharoza', 'genre' => 'Uptempo'],
            ['name' => 'Revellers', 'genre' => 'Uptempo'],
            ['name' => 'The Dope Doctor', 'genre' => 'Uptempo'],
            ['name' => 'Bössels', 'genre' => 'Uptempo'],
            ['name' => 'Missy', 'genre' => 'Hardstyle'],
            ['name' => 'Dimma', 'genre' => 'Hardstyle'],
            ['name' => 'Deviation', 'genre' => 'Hardstyle'],
            ['name' => 'Eraized', 'genre' => 'Hardstyle'],
            ['name' => 'Udow', 'genre' => 'Hardstyle'],
            ['name' => 'Samynator', 'genre' => 'Hardstyle'],
            ['name' => 'Pinotello', 'genre' => 'Hardstyle'],
            ['name' => 'Aalst', 'genre' => 'Hardstyle'],
            ['name' => 'Roosterz', 'genre' => 'Hardstyle'],
            ['name' => 'Lekkerfaces', 'genre' => 'Hardstyle'],
            ['name' => 'Toza', 'genre' => 'Hardstyle'],
            ['name' => 'Fracture', 'genre' => 'Rawstyle'],
            ['name' => 'Madmize', 'genre' => 'Rawstyle'],
            ['name' => 'Dark Entities', 'genre' => 'Rawstyle'],
            ['name' => 'Phantom', 'genre' => 'Rawstyle'],
            ['name' => 'Spitfire', 'genre' => 'Rawstyle'],
            ['name' => 'Unique', 'genre' => 'Rawstyle'],
            ['name' => 'Coldax', 'genre' => 'Rawstyle'],
            ['name' => 'Big K', 'genre' => 'Rawstyle'],
            ['name' => 'Detailed', 'genre' => 'Rawstyle'],
            ['name' => 'Chapter V', 'genre' => 'Rawstyle'],
            ['name' => 'Faceless', 'genre' => 'Rawstyle'],
            ['name' => 'Damaxy', 'genre' => 'Rawstyle'],
            ['name' => 'Vasto', 'genre' => 'Rawstyle'],
            ['name' => 'Amduscias', 'genre' => 'Rawstyle'],
            ['name' => 'Mortis', 'genre' => 'Rawstyle'],
            ['name' => 'Exproz', 'genre' => 'Rawstyle'],
            ['name' => 'So Juice', 'genre' => 'Rawstyle'],
            ['name' => 'Restrictless', 'genre' => 'Rawstyle'],
            ['name' => 'Amayze', 'genre' => 'Rawstyle'],
            ['name' => 'Untold Stories', 'genre' => 'Rawstyle'],
            ['name' => 'Kideast', 'genre' => 'Rawstyle'],
            ['name' => 'B-Struct', 'genre' => 'Rawstyle'],
            ['name' => 'Oblivion', 'genre' => 'Rawstyle'],
            ['name' => 'Vibetalgic', 'genre' => 'Rawstyle'],
            ['name' => 'Dejection', 'genre' => 'Rawstyle'],
            ['name' => 'Nside', 'genre' => 'Rawstyle'],
            ['name' => 'Zelecter', 'genre' => 'Hardstyle'],
            ['name' => 'Nightcraft', 'genre' => 'Hardstyle'],
            ['name' => 'The Saints', 'genre' => 'Rawstyle'],
            ['name' => 'The Straikerz', 'genre' => 'Rawstyle'],
            ['name' => 'Awake Asleep', 'genre' => 'Rawstyle'],
            ['name' => 'Noxiouz', 'genre' => 'Uptempo'],
            ['name' => 'Disaster', 'genre' => 'Rawstyle'],
            ['name' => 'Hard Destiny', 'genre' => 'Rawstyle'],
            ['name' => 'Sanctuary', 'genre' => 'Rawstyle'],
            ['name' => 'Infliction', 'genre' => 'Rawstyle'],
            ['name' => 'Incult', 'genre' => 'Rawstyle'],
            ['name' => 'Slvl', 'genre' => 'Rawstyle'],
            ['name' => 'Anderex', 'genre' => 'Rawstyle'],
            ['name' => 'The Smiler', 'genre' => 'Rawstyle'],
            ['name' => 'Undivided', 'genre' => 'Hardstyle'],
            ['name' => 'Victus', 'genre' => 'Hardstyle'],
            ['name' => 'Noise of Aggression', 'genre' => 'Hardstyle'],
            ['name' => 'Noiseflow', 'genre' => 'Hardstyle'],
            ['name' => 'T.M.O.', 'genre' => 'Hardstyle'],
            ['name' => 'DMRC', 'genre' => 'Hardstyle'],
            ['name' => 'Manifest Destiny', 'genre' => 'Hardstyle'],
            ['name' => 'Unproven', 'genre' => 'Hardstyle'],
            ['name' => 'Barber', 'genre' => 'Uptempo'],
            ['name' => 'Complex', 'genre' => 'Uptempo'],
            ['name' => 'Angst voor Aalst', 'genre' => 'Uptempo'],
            ['name' => 'The Dark Horror', 'genre' => 'Uptempo'],
            ['name' => 'Kili', 'genre' => 'Uptempo'],
            ['name' => 'Abaddon', 'genre' => 'Uptempo'],
            ['name' => 'Dark Individual', 'genre' => 'Uptempo'],
            ['name' => 'Rosbeek', 'genre' => 'Uptempo'],
            ['name' => 'Screecher', 'genre' => 'Uptempo'],
            ['name' => 'Neilio', 'genre' => 'Hardstyle'],
            ['name' => 'Noisecult', 'genre' => 'Hardstyle'],
            ['name' => 'Digital Punk', 'genre' => 'Rawstyle'],
            ['name' => 'Max Enforcer', 'genre' => 'Hardstyle'],
            ['name' => 'Psyko Punkz', 'genre' => 'Hardstyle'],
            ['name' => 'Deepack', 'genre' => 'Hardstyle'],
            ['name' => 'Sub Sonik', 'genre' => 'Hardstyle'],
            ['name' => 'Degos & Re-Done', 'genre' => 'Hardstyle'],
            ['name' => 'DJ Thera', 'genre' => 'Hardstyle'],
            ['name' => 'Re-Vane', 'genre' => 'Hardstyle'],
            ['name' => 'More Kords', 'genre' => 'Hardstyle'],
            ['name' => 'Avi8', 'genre' => 'Hardstyle'],
            ['name' => 'Audiotricz', 'genre' => 'Hardstyle'],
            ['name' => 'Wasted Penguinz', 'genre' => 'Hardstyle'],
            ['name' => 'Adrenalize', 'genre' => 'Hardstyle'],
            ['name' => 'Galactixx', 'genre' => 'Hardstyle'],
            ['name' => 'Willem de Wijs', 'genre' => 'Party'],
            ['name' => 'Kruzo', 'genre' => 'Party'],
            ['name' => 'Nelis Leeman', 'genre' => 'Party'],
            ['name' => 'Jeffrey Heesen', 'genre' => 'Party'],
            ['name' => 'Sven Versteeg', 'genre' => 'Party'],
            ['name' => 'Mart Hoogkamer', 'genre' => 'Party'],
            ['name' => 'Effe Serieus', 'genre' => 'Party'],
            ['name' => 'Mutant', 'genre' => 'Hardstyle'],
            ['name' => 'LuckyNoise', 'genre' => 'Hardstyle'],
            ['name' => 'Josha', 'genre' => 'Hardstyle'],
            ['name' => 'Conspirator', 'genre' => 'Hardstyle'],
            ['name' => 'Sickdog', 'genre' => 'Hardstyle'],
            ['name' => 'Tob-E', 'genre' => 'Hardstyle'],
            ['name' => 'Invicious', 'genre' => 'Hardstyle'],
            ['name' => 'Illuszion', 'genre' => 'Hardstyle'],
            ['name' => 'Ijgenweis', 'genre' => 'Hardstyle'],
            ['name' => 'Savellix', 'genre' => 'Hardstyle'],
            ['name' => 'Cryex', 'genre' => 'Rawstyle'],
            ['name' => 'Bloodlust', 'genre' => 'Rawstyle'],
            ['name' => 'Resilience', 'genre' => 'Rawstyle'],
            ['name' => 'Deluzion', 'genre' => 'Rawstyle'],
            ['name' => 'Spoontech', 'genre' => 'Rawstyle'],
            ['name' => 'Rejectofrage', 'genre' => 'Rawstyle'],
            ['name' => 'D-Stroy', 'genre' => 'Rawstyle'],
            ['name' => 'Guiberz', 'genre' => 'Hardstyle'],
            ['name' => 'Valhalla', 'genre' => 'Hardstyle'],
            ['name' => 'Revealer', 'genre' => 'Hardstyle'],
            ['name' => 'Guizcore', 'genre' => 'Hardstyle'],
            ['name' => 'Spiady', 'genre' => 'Hardstyle'],
            ['name' => 'Deadly Guns', 'genre' => 'Hardcore'],
            ['name' => 'Satirized', 'genre' => 'Uptempo'],
            ['name' => 'Akimbo', 'genre' => 'Uptempo'],
            ['name' => 'Harde Kwark', 'genre' => 'Rawstyle'],
            ['name' => 'Elevation', 'genre' => 'Rawstyle'],
            ['name' => 'Unmute', 'genre' => 'Rawstyle'],
            ['name' => 'Heavy Resistance', 'genre' => 'Rawstyle'],
            ['name' => 'Captivator', 'genre' => 'Rawstyle'],
            ['name' => 'Kemal', 'genre' => 'Rawstyle'],
            ['name' => 'Rave Generators', 'genre' => 'Hardcore'],
            ['name' => 'GA-OSZ', 'genre' => 'Hardcore'],
            ['name' => 'Exertion', 'genre' => 'Hardcore'],
            ['name' => 'Panic', 'genre' => 'Hardcore'],
            ['name' => 'The Darkraver', 'genre' => 'Hardcore'],
            ['name' => 'The Viper', 'genre' => 'Hardcore'],
            ['name' => 'Endymion', 'genre' => 'Hardcore'],
            ['name' => 'Evil Activities', 'genre' => 'Hardcore'],
            ['name' => 'Nosferatu', 'genre' => 'Hardcore'],
            ['name' => 'Tha Playah', 'genre' => 'Hardcore'],
            ['name' => 'Noize Suppressor', 'genre' => 'Hardcore'],
            ['name' => 'Promo', 'genre' => 'Hardcore'],
            ['name' => 'Coone', 'genre' => 'Hardstyle'],
            ['name' => 'Josh & Wesz', 'genre' => 'Hardstyle'],
            ['name' => 'EMS: The Hardstyle Family', 'genre' => 'Hardstyle'],
            ['name' => 'Digital Madness', 'genre' => 'Hardstyle'],
            ['name' => 'Refuzion', 'genre' => 'Hardstyle'],
            ['name' => 'Cyber', 'genre' => 'Hardstyle'],
            ['name' => 'Demi Kanon', 'genre' => 'Hardstyle'],
            ['name' => 'Noisecontrollers', 'genre' => 'Hardstyle'],
            ['name' => 'Unbreakable', 'genre' => 'Party'],
            ['name' => 'Special Krew', 'genre' => 'Party'],
            ['name' => 'Kreated', 'genre' => 'Party'],
            ['name' => 'Tomme-C', 'genre' => 'Party'],
            ['name' => 'Daani', 'genre' => 'Party'],
            ['name' => 'Arabiercantus', 'genre' => 'Party'],
            ['name' => 'De Quarantaine Boys', 'genre' => 'Party'],
            ['name' => 'Sjans Paul', 'genre' => 'Party'],
            ['name' => 'Blvckprint', 'genre' => 'Party'],
            ['name' => 'Rave Krew', 'genre' => 'Party'],
        ];

        $artistModels = [];
        foreach (collect($artistsData)->unique('name')->all() as $artistData) {
            $artistModels[$artistData['name']] = Artist::updateOrCreate(['name' => $artistData['name']], $artistData);
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
            
            // Rebirth Festival 2026 Specialized Acts
            ['name' => 'ACT OF RAGE (UNCHARTED LIVE)', 'artists' => ['Act of Rage'], 'is_live' => true],
            ['name' => 'THE STRAIKERZ (777 LIVE)', 'artists' => ['The Straikerz'], 'is_live' => true],
            ['name' => 'THAROZA (LIVE OR DIE LIVE)', 'artists' => ['Tharoza'], 'is_live' => true],
            ['name' => 'REVELLERS (LIVE)', 'artists' => ['Revellers'], 'is_live' => true],
            ['name' => 'BÖSSELS (LIVE)', 'artists' => ['Bössels'], 'is_live' => true],
            ['name' => 'PREMIERE MISSY & DIMMA (SLOPEN DE TENT LIVE)', 'artists' => ['Missy', 'Dimma'], 'is_live' => true],
            ['name' => 'PREMIERE PINOTELLO (NO PINO NO PARTY LIVE)', 'artists' => ['Pinotello'], 'is_live' => true],
            ['name' => 'DETAILED (LIVE)', 'artists' => ['Detailed'], 'is_live' => true],
            ['name' => 'NOXIOUZ (THE CATALYST LIVE)', 'artists' => ['Noxiouz'], 'is_live' => true],
            ['name' => 'CHAPTER V vs INFLICTION (LIVE)', 'artists' => ['Chapter V', 'Infliction'], 'is_live' => true],
            ['name' => 'TOZA (LIVE)', 'artists' => ['Toza'], 'is_live' => true],
            ['name' => 'NOISEFLOW (LIVE)', 'artists' => ['Noiseflow'], 'is_live' => true],
            ['name' => 'DMRC (HEIST NIGHT LIVE)', 'artists' => ['DMRC'], 'is_live' => true],
            ['name' => 'ROOSTERZ (GOOSEBUMPS LIVE)', 'artists' => ['Roosterz'], 'is_live' => true],
            ['name' => 'COMPLEX (RAMMERONI LIVE)', 'artists' => ['Complex'], 'is_live' => true],
            ['name' => 'PREMIERE ANGST VOOR AALST (LIVE)', 'artists' => ['Angst voor Aalst'], 'is_live' => true],
            ['name' => 'THE SMILER (LIVE)', 'artists' => ['The Smiler'], 'is_live' => true],
            ['name' => 'UNRESOLVED (WARRIOR LIVE)', 'artists' => ['Unresolved'], 'is_live' => true],
            ['name' => 'REJECTA (PATIENT ZERO LIVE)', 'artists' => ['Rejecta'], 'is_live' => true],
            ['name' => 'T.M.O. (LIVE)', 'artists' => ['T.M.O.'], 'is_live' => true],
            ['name' => 'UDOW (LIVE)', 'artists' => ['Udow'], 'is_live' => true],
            ['name' => 'REVEALER (THE ANGER IN US LIVE)', 'artists' => ['Revealer'], 'is_live' => true],
            ['name' => 'SATIRIZED (NEON FUNFAIR LIVE)', 'artists' => ['Satirized'], 'is_live' => true],
            ['name' => 'HEAVY RESISTANCE (LIVE)', 'artists' => ['Heavy Resistance'], 'is_live' => true],
            ['name' => 'SANCTUARY (LIVE)', 'artists' => ['Sanctuary'], 'is_live' => true],
            ['name' => 'INCULT (LIVE)', 'artists' => ['Incult'], 'is_live' => true],
            ['name' => 'REVELATION (LIVE)', 'artists' => ['Revelation'], 'is_live' => true],
            ['name' => 'DARK ENTITIES (LIVE)', 'artists' => ['Dark Entities'], 'is_live' => true],
            ['name' => 'CAPTIVATOR vs SPITFIRE (LIVE)', 'artists' => ['Captivator', 'Spitfire'], 'is_live' => true],
            
            ['name' => 'JAY REEVE (MELODIC MADNESS)', 'artists' => ['Jay Reeve']],
            ['name' => 'B-FRONT F2F PHUTURE NOIZE', 'artists' => ['B-Front', 'Phuture Noize']],
            ['name' => 'SAMYNATOR (MUTATION)', 'artists' => ['Samynator']],
            ['name' => 'REGAIN: POLISH PUNISHER (ALBUM SHOWCASE)', 'artists' => ['Regain']],
            ['name' => 'WARFACE (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Warface']],
            ['name' => 'D-BLOCK & S-TE-FAN vs PHUTURE NOIZE (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['D-Block & S-te-Fan', 'Phuture Noize']],
            ['name' => 'AWAKE ASLEEP (ADJUZT)', 'artists' => ['Adjuzt']],
            ['name' => 'ELEMENT (ODE TO THE PAST)', 'artists' => ['Element']],
            ['name' => 'BMBERJCK (TEMPLE OF RAW)', 'artists' => ['BMBERJCK']],
            ['name' => 'KILI (LOCKED & LOADED)', 'artists' => ['Kili']],
            ['name' => 'DIGITAL PUNK vs MAX ENFORCER (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Digital Punk', 'Max Enforcer']],
            ['name' => 'AUDIOTRICZ (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Audiotricz']],
            ['name' => 'WASTED PENGUINZ (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Wasted Penguinz']],
            ['name' => 'ADARO (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Adaro']],
            ['name' => 'DEMI KANON (DECADE)', 'artists' => ['Demi Kanon']],
            ['name' => 'ATMOZFEARS (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Atmozfears']],
            ['name' => '120 MINUTES: 20 YEARS OF NOISECONTROLLERS (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Noisecontrollers']],
            ['name' => 'AUDIOTRICZ & ECSTATIC (PROGRESSIVE HARDSTYLE)', 'artists' => ['Audiotricz', 'Ecstatic']],
            ['name' => 'ADRENALIZE vs B-FRONT (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Adrenalize', 'B-Front']],
            ['name' => 'THE SAINTS (HOLY DREAMS)', 'artists' => ['The Saints']],
            ['name' => 'D-STURB (UNIVERSE)', 'artists' => ['D-Sturb']],
            ['name' => 'SUB ZERO PROJECT (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Sub Zero Project']],
            ['name' => 'DUAL DAMAGE (BUILT 2 BREAK)', 'artists' => ['Dual Damage']],
            ['name' => 'PHUTURE NOIZE (OPEN HEART SURGERY)', 'artists' => ['Phuture Noize']],
            ['name' => 'REBELION (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Rebelion']],
            ['name' => '15 YEARS OF RADICAL REDEMPTION (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Radical Redemption']],
            ['name' => 'SPOONTECH (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Wait', 'Genius']], // Spoontech is a label
            ['name' => 'CRYEX (PREMIERE NEW UNLIKE EXPERIENCE)', 'artists' => ['Cryex']],
            ['name' => 'THE PURGE (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['The Purge']],
            ['name' => 'DEEZL (AEON)', 'artists' => ['Deezl']],
            ['name' => 'REJECTOFRAGE (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Rejecta', 'Act of Rage']],
            ['name' => 'ROOLER (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Rooler']],
            ['name' => 'KROWDEXX (WE THE LOUDEST)', 'artists' => ['Krowdexx']],
            ['name' => 'OMNYA (CARNAGE: FROZEN VEINS)', 'artists' => ['Omnya']],
            ['name' => 'MUTILATOR (RAVE REACTOR)', 'artists' => ['Mutilator']],
            ['name' => 'EXPROZ (THE JAILBREAKER)', 'artists' => ['Exproz']],
            ['name' => 'DEADLY GUNS (HARDCORE NATION)', 'artists' => ['Deadly Guns']],
            ['name' => 'LEKKERFACES (HYPER)', 'artists' => ['Lekkerfaces']],
            ['name' => 'GEZELLIGE UPTEMPO (SHIT HAPPENS)', 'artists' => ['Gezellige Uptempo']],
            ['name' => 'SPITNOISE (BOUNCE OF STEEL)', 'artists' => ['Spitnoise']],
            ['name' => 'AKIMBO (GAME OVER)', 'artists' => ['Akimbo']],
            ['name' => 'ENDYMION vs EVIL ACTIVITIES (MILLENNIUM SET)', 'artists' => ['Endymion', 'Evil Activities']],
            ['name' => 'NOSFERATU & THA PLAYAH (COMBINED FORCES)', 'artists' => ['Nosferatu', 'Tha Playah']],
            ['name' => 'MISS K8 (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Miss K8']],
            ['name' => 'THE BEST OF PARTYRAISER (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Partyraiser']],
            ['name' => '20 YEARS OF DIRTY WORKZ: COONE (REBIRTH EVOLUTION SPECIAL)', 'artists' => ['Coone']],
            ['name' => 'JASON PAYNE (GOLDSCHOOL)', 'artists' => ['Jason Payne']],
            ['name' => 'MAJOR CONSPIRACY (VOL BASIS 2ND GEAR)', 'artists' => ['Major Conspiracy']],
            ['name' => 'OVERDRIVE SHOWCASE: ABADDON, DARK INDIVIDUAL, ROSBEEK, SCREECHER & THAROZA', 'artists' => ['Abaddon', 'Dark Individual', 'Rosbeek', 'Screecher', 'Tharoza']],
            ['name' => 'THERACORDS SPECIAL: DEGOS & RE-DONE, DJ THERA & GECK-O', 'artists' => ['Degos & Re-Done', 'DJ Thera', 'Geck-O']],
            ['name' => 'CLASSIFIED RECORDS SHOWCASE: COLDAX, DAMAXY, DETAILED & UNIQUE', 'artists' => ['Coldax', 'Damaxy', 'Detailed', 'Unique']],
            
            // Supersized Kingsday 2026 Specialized Acts
            ['name' => 'Rejecta : SUPERSIZED', 'artists' => ['Rejecta']],
            ['name' => 'Brennan Heart pres. Evolution of Style', 'artists' => ['Brennan Heart']],
            ['name' => 'Paul Elstak ft. Boogshe', 'artists' => ['Paul Elstak', 'Boogshe']],
            ['name' => 'Dr. Rude pres Jump Classics', 'artists' => ['Dr. Rude']],
            ['name' => 'Hans Glock pres. Back2Basics', 'artists' => ['Hans Glock']],
            ['name' => 'The Speed Team: Akimbo, Kili, Samynator, Revealer & Roosterz', 'artists' => ['Akimbo', 'Kili', 'Samynator', 'Revealer', 'Roosterz']],
            ['name' => 'Gezellige Uptempo : SUPERSIZED', 'artists' => ['Gezellige Uptempo']],
            ['name' => 'Nosferatu : SUPERSIZED', 'artists' => ['Nosferatu']],
            ['name' => 'Coldax vs Omnya : SUPERSIZED', 'artists' => ['Coldax', 'Omnya']],
            ['name' => 'Ran-D & Adaro : SUPERSIZED', 'artists' => ['Ran-D', 'Adaro']],
            ['name' => 'Fantastic Four : SUPERSIZED', 'artists' => []],
            ['name' => 'Larstig & Gasdrop : SUPERSIZED', 'artists' => ['Larstig', 'Gasdrop']],
            ['name' => 'Drokz : Gabber Set', 'artists' => ['Drokz']],
            ['name' => 'Patjoo\'s Royal Rave', 'artists' => ['Patjoo']],
            ['name' => 'B-Front & Phuture Noize', 'artists' => ['B-Front', 'Phuture Noize']],
            ['name' => 'Refold vs Re-Vane', 'artists' => ['Refold', 'Re-Vane']],
            ['name' => 'Level One vs Nightcraft', 'artists' => ['Level One', 'Nightcraft']],
            ['name' => 'Sanctuary vs Spitfire', 'artists' => ['Sanctuary', 'Spitfire']],
            ['name' => 'Chapter V & Revelation', 'artists' => ['Chapter V', 'Revelation']],
            ['name' => 'Dark Entities vs Unmute', 'artists' => ['Dark Entities', 'Unmute']],
            ['name' => 'T.M.O. vs Amigo', 'artists' => ['T.M.O.', 'Amigo']],
            ['name' => 'Complex vs Udow', 'artists' => ['Complex', 'Udow']],
            ['name' => 'Abaddon vs Rosbeek', 'artists' => ['Abaddon', 'Rosbeek']],
            ['name' => 'Zany & The Pitcher', 'artists' => ['Zany', 'The Pitcher']],
            ['name' => 'Karun vs Unfused', 'artists' => ['Karun', 'Unfused']],
            ['name' => 'D-Fence vs Never Surrender', 'artists' => ['D-Fence', 'Never Surrender']],
        ];

        // Add regular acts for each artist
        foreach ($artistModels as $name => $model) {
            $actsData[] = ['name' => $name, 'artists' => [$name]];
        }

        $actModels = [];
        foreach (collect($actsData)->unique('name')->all() as $actData) {
            $isLive = $actData['is_live'] ?? false;

            $act = Act::updateOrCreate(
                ['name' => $actData['name']],
                ['is_live' => $isLive]
            );
            foreach ($actData['artists'] as $artistName) {
                if (isset($artistModels[$artistName])) {
                    $act->artists()->syncWithoutDetaching([$artistModels[$artistName]->id]);
                }
            }
            if (isset($actData['is_special_act'])) {
                $act->is_special = $actData['is_special_act'];
                $act->save();
            }
            $actModels[$actData['name']] = $act;
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
            
            // 5 Future Events (relative to 2026-03-10)
            ['name' => 'Tomorrowland 2026', 'loc' => 'Boom, Belgium', 'start' => '2026-07-17', 'end' => '2026-07-26'],
            ['name' => 'Defqon.1 2026', 'loc' => 'Biddinghuizen, Netherlands', 'start' => '2026-06-25', 'end' => '2026-06-28'],
            ['name' => 'Intents Festival 2026', 'loc' => 'Oisterwijk, Netherlands', 'start' => '2026-06-05', 'end' => '2026-06-07'],
            ['name' => 'EDC Las Vegas 2026', 'loc' => 'Motor Speedway, LV', 'start' => '2026-05-15', 'end' => '2026-05-17'],
            ['name' => 'Mysteryland 2026', 'loc' => 'Haarlemmermeer, Netherlands', 'start' => '2026-08-28', 'end' => '2026-08-30'],
            ['name' => 'Rebirth Festival 2026', 'loc' => 'Haaren, Netherlands', 'start' => '2026-04-10', 'end' => '2026-04-12'],
            ['name' => 'Supersized Kingsday 2026', 'loc' => 'Aquabest, Best', 'start' => '2026-04-27', 'end' => '2026-04-27'],
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
            'REBIRTH' => 'Rebirth Mainstage.',
            'REBELLION' => 'Rawstyle sanctuary.',
            'RESIST' => 'Uptempo and Hardcore arena.',
            'REBORN RAW' => 'Fresh raw talents.',
            'ROAD TO REBIRTH' => 'The path to the mainstage.',
            'RESET x REVENGE' => 'Classics and Millennium hardcore.',
            'REACTIVATE' => 'Euphoric and Classics.',
            'REVELATION' => 'Progressive and Euphoric hardstyle.',
            'MADNESS SQUARE' => 'Pure party vibes.',
            'Beetje Dansen' => 'Cosy party stage.',
            'Raw' => 'Rawstyle stage.',
            'Uptempo' => 'Uptempo stage.',
            'Hardstyle Classics' => 'Hardstyle Classics stage.',
            'Hardcore' => 'Hardcore stage.',
            'Hardcore Classics' => 'Hardcore Classics stage.',
            'Dutch Style' => 'Dutch Style stage.',
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
            'Tomorrowland 2026' => ['Mainstage', 'Freedom Stage', 'Atmosphere'],
            'Defqon.1 2026' => ['RED Stage', 'BLUE Stage', 'BLACK Stage'],
            'Intents Festival 2026' => ['Mainstage', 'RED Stage'],
            'EDC Las Vegas 2026' => ['Kinetic FIELD', 'Circuit GROUNDS'],
            'Mysteryland 2026' => ['Mainstage', 'Library'],
            'Rebirth Festival 2026' => ['REBIRTH', 'REBELLION', 'RESIST', 'REBORN RAW', 'RESET x REVENGE', 'REACTIVATE', 'REVELATION', 'MADNESS SQUARE', 'ROAD TO REBIRTH', 'Beetje Dansen'],
            'Supersized Kingsday 2026' => ['Mainstage', 'Raw', 'Uptempo', 'Hardstyle Classics', 'Hardcore', 'Hardcore Classics', 'Dutch Style'],
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
            'Tomorrowland 2026' => [
                'Mainstage' => ['Dimitri Vegas & Like Mike', 'Martin Garrix', 'Armin van Buuren'],
                'Freedom Stage' => ['Eric Prydz presents HOLO', 'Boris Brejcha'],
                'Atmosphere' => ['Charlotte de Witte', 'Amelie Lens'],
            ],
            'Defqon.1 2026' => [
                'RED Stage' => ['Sub Zero Project', 'Rebelion', 'Sefa'],
                'BLUE Stage' => ['Warface', 'D-Sturb'],
                'BLACK Stage' => ['Angerfist', 'Miss K8'],
            ],
            'Intents Festival 2026' => [
                'Mainstage' => ['The Gang', 'Hard Driver'],
                'RED Stage' => ['Act of Rage', 'Rejecta'],
            ],
            'EDC Las Vegas 2026' => [
                'Kinetic FIELD' => ['Tiësto', 'David Guetta'],
                'Circuit GROUNDS' => ['Martin Garrix', 'Alesso'],
            ],
            'Mysteryland 2026' => [
                'Mainstage' => ['Calvin Harris', 'Steve Aoki'],
                'Library' => ['Headhunterz', 'Wildstylez'],
            ],
            'Rebirth Festival 2026' => [
                '2026-04-10' => [
                    'REBIRTH' => [
                        'D-Charged vs Serzo', 'JAY REEVE (MELODIC MADNESS)', 'Aversion', 'ACT OF RAGE (UNCHARTED LIVE)',
                        'B-FRONT F2F PHUTURE NOIZE', 'Sickmode', 'THE STRAIKERZ (777 LIVE)', 'Warface',
                        'Mutilator vs Revelation', 'D-Sturb vs Mish', 'Gezellige Uptempo vs Satirized'
                    ],
                    'RESIST' => [
                        'Histofz', 'Latinity vs Luxo vs Maistro', 'THAROZA (LIVE OR DIE LIVE)', 'REVELLERS (LIVE)',
                        'The Dope Doctor', 'BÖSSELS (LIVE)', 'PREMIERE MISSY & DIMMA (SLOPEN DE TENT LIVE)',
                        'Deviation vs Eraized vs Udow', 'SAMYNATOR (MUTATION)', 'PREMIERE PINOTELLO (NO PINO NO PARTY LIVE)',
                        'MAJOR CONSPIRACY (VOL BASIS 2ND GEAR)', 'Aalst vs Roosterz', 'Lekkerfaces vs Toza'
                    ],
                    'REBORN RAW' => [
                        'Fracture vs Madmize', 'Dark Entities vs Phantom', 'Spitfire vs Unique', 'Coldax', 'Big K',
                        'DETAILED (LIVE)', 'Chapter V vs Faceless vs Omnya', 'Damaxy vs Vasto',
                        'Amduscias vs Mortis', 'Exproz vs So Juice'
                    ],
                    'ROAD TO REBIRTH' => [
                        'Restrictless', 'Amayze', 'Untold Stories', 'Kideast', 'B-Struct', 'Oblivion',
                        'Vibetalgic', 'Dejection', 'Nside'
                    ],
                ],
                '2026-04-11' => [
                    'REBIRTH' => [
                        'SAVELLIX (WINNER ROAD TO REBIRTH)', 'Sound Rush', 'ADRENALIZE vs B-FRONT (REBIRTH EVOLUTION SPECIAL)',
                        'THE SAINTS (HOLY DREAMS)', 'D-STURB (UNIVERSE)', 'SUB ZERO PROJECT (REBIRTH EVOLUTION SPECIAL)',
                        'Kick Off Show', 'Sickmode', 'UNRESOLVED (WARRIOR LIVE)', 'DUAL DAMAGE (BUILT 2 BREAK)',
                        'Anthem Show', 'PHUTURE NOIZE (OPEN HEART SURGERY)', '4444 Four of a Kind',
                        'REJECTA (PATIENT ZERO LIVE)', 'REBELION (REBIRTH EVOLUTION SPECIAL)',
                        '15 YEARS OF RADICAL REDEMPTION (REBIRTH EVOLUTION SPECIAL)'
                    ],
                    'REBELLION' => [
                        'Resilience', 'Deluzion', 'SPOONTECH (REBIRTH EVOLUTION SPECIAL)',
                        'CRYEX (PREMIERE NEW UNLIKE EXPERIENCE)', 'Nightcraft vs Regain', 'Bloodlust',
                        'THE PURGE (REBIRTH EVOLUTION SPECIAL)', 'DEEZL (AEON)', 'REJECTOFRAGE (REBIRTH EVOLUTION SPECIAL)',
                        'ROOLER (REBIRTH EVOLUTION SPECIAL)', 'KROWDEXX (WE THE LOUDEST)', 'Adjuzt vs BMBERJCK',
                        'OMNYA (CARNAGE: FROZEN VEINS)', 'MUTILATOR (RAVE REACTOR)', 'EXPROZ (THE JAILBREAKER)', 'Kruelty'
                    ],
                    'RESIST' => [
                        'D-STROY (WINNER ROAD TO REBIRTH)', 'Dimma vs Guiberz vs Valhalla', 'T.M.O. (LIVE)', 'UDOW (LIVE)',
                        'Maintrex vs MT', 'REVEALER (THE ANGER IN US LIVE)', 'Guizcore vs Spiady',
                        'DEADLY GUNS (HARDCORE NATION)', 'Complex vs Kili vs Missy', 'LEKKERFACES (HYPER)',
                        'Manifest Destiny', 'GEZELLIGE UPTEMPO (SHIT HAPPENS)', 'SPITNOISE (BOUNCE OF STEEL)',
                        'Dimitri K', 'SATIRIZED (NEON FUNFAIR LIVE)', 'The Dark Horror vs Noxiouz', 'AKIMBO (GAME OVER)'
                    ],
                    'REBORN RAW' => [
                        'HARDE KWARK (WINNER ROAD TO REBIRTH)', 'Elevation vs Unmute', 'HEAVY RESISTANCE (LIVE)',
                        'SANCTUARY (LIVE)', 'Disaster', 'INCULT (LIVE)', 'CLASSIFIED RECORDS SHOWCASE: COLDAX, DAMAXY, DETAILED & UNIQUE',
                        'Infliction vs Sparkz', 'REVELATION (LIVE)', 'Anderex vs Titi', 'DARK ENTITIES (LIVE)',
                        'Unload', 'Faceless', 'CAPTIVATOR vs SPITFIRE (LIVE)', 'Kemal'
                    ],
                    'RESET x REVENGE' => [
                        'Rave Generators', 'GA-OSZ', 'Exertion vs Panic', 'The Darkraver vs The Viper',
                        'ENDYMION vs EVIL ACTIVITIES (MILLENNIUM SET)', 'NOSFERATU & THA PLAYAH (COMBINED FORCES)',
                        'Noize Suppressor', 'Promo', 'MISS K8 (REBIRTH EVOLUTION SPECIAL)',
                        'THE BEST OF PARTYRAISER (REBIRTH EVOLUTION SPECIAL)'
                    ],
                    'REACTIVATE' => [
                        'The Echoes of Time', 'Zelecter', 'Jones', '20 YEARS OF DIRTY WORKZ: COONE (REBIRTH EVOLUTION SPECIAL)',
                        'Josh & Wesz', 'Wildstylez', 'E-Force', 'Zany', 'B-Front vs Zany', 'B-Front', 'Adaro', 'Jason Payne (GOLDSCHOOL)'
                    ],
                    'REVELATION' => [
                        'EMS: The Hardstyle Family', 'Digital Madness', 'D-Charged', 'Solstice', 'Refuzion', 'Cyber',
                        'DEMI KANON (DECADE)', 'Jay Reeve', 'ATMOZFEARS (REBIRTH EVOLUTION SPECIAL)',
                        '120 MINUTES: 20 YEARS OF NOISECONTROLLERS (REBIRTH EVOLUTION SPECIAL)',
                        'Audiotricz & Ecstatic (Progressive Hardstyle)', 'Galactixx'
                    ],
                    'MADNESS SQUARE' => [
                        'Vette Openingsshow', 'Unbreakable x Special Krew', 'Kreated (Wheel of Madness)',
                        'Special Krew x Tomme-C', 'Daani (Party with Style)', 'Special Krew B2B Daani',
                        'Arabiercantus', 'De Quarantaine Boys', 'Sjans Paul', 'Blvckprint', 'Rave Krew'
                    ],
                ],
                '2026-04-12' => [
                    'REBIRTH' => [
                        'Jones vs Zelecter', 'Nightcraft', 'REGAIN: POLISH PUNISHER (ALBUM SHOWCASE)', 'Mish',
                        'WARFACE (REBIRTH EVOLUTION SPECIAL)', 'Dual Damage vs The Saints',
                        'D-BLOCK & S-TE-FAN vs PHUTURE NOIZE (REBIRTH EVOLUTION SPECIAL)', 'Rebelion vs The Straikerz',
                        'AWAKE ASLEEP (ADJUZT)', 'Vertile', 'Rooler', 'NOXIOUZ (THE CATALYST LIVE)'
                    ],
                    'REBELLION' => [
                        'Disaster vs Unique', 'Hard Destiny vs Sanctuary', 'Vasto (The Protocol)',
                        'CHAPTER V vs INFLICTION (LIVE)', 'ELEMENT (ODE TO THE PAST)', 'TOZA (LIVE)',
                        'Detailed vs Sparkz vs Unload', 'Toxic Machinery vs Ush', 'BMBERJCK (TEMPLE OF RAW)',
                        'Damaxy vs Incult vs Revelation', 'Kruelty vs Slvl', 'ANDEREX (THE SIMULATION)', 'THE SMILER (LIVE)'
                    ],
                    'RESIST' => [
                        'Undivided vs Victus', 'Noise of Aggression', 'NOISEFLOW (LIVE)', 'Bössels vs T.M.O.',
                        'DMRC (HEIST NIGHT LIVE)', 'Manifest Destiny vs Unproven', 'ROOSTERZ (GOOSEBUMPS LIVE)',
                        'Barber vs Partyraiser', 'COMPLEX (RAMMERONI LIVE)', 'PREMIERE ANGST VOOR AALST (LIVE)',
                        'The Dark Horror', 'Kili (Locked & Loaded)', 'OVERDRIVE SHOWCASE: ABADDON, DARK INDIVIDUAL, ROSBEEK, SCREECHER & THAROZA'
                    ],
                    'REACTIVATE' => [
                        'Neilio', 'Noisecult', 'DIGITAL PUNK vs MAX ENFORCER (REBIRTH EVOLUTION SPECIAL)',
                        'Psyko Punkz', 'Deepack', 'Crypsis', 'Sub Sonik', 'Regain vs Unresolved', 'THERACORDS SPECIAL: DEGOS & RE-DONE, DJ THERA & GECK-O'
                    ],
                    'REVELATION' => [
                        'Re-Vane', 'MORE KORDS (ZAAGPHORIC)', 'Avi8 (Higher)', 'AUDIOTRICZ (REBIRTH EVOLUTION SPECIAL)',
                        'WASTED PENGUINZ (REBIRTH EVOLUTION SPECIAL)', 'ECSTATIC (THE ESSENCE)', 'Adrenalize vs Deezl',
                        'ADARO (REBIRTH EVOLUTION SPECIAL)', 'Galactixx vs Rejecta'
                    ],
                    'Beetje Dansen' => [
                        'Willem de Wijs', 'Kruzo', 'Nelis Leeman', 'Jeffrey Heesen', 'Sven Versteeg', 'Mart Hoogkamer', 'Effe Serieus'
                    ],
                    'ROAD TO REBIRTH' => [
                        'Mutant', 'LuckyNoise', 'Josha', 'Conspirator', 'Sickdog', 'Tob-E', 'Invicious', 'Illuszion', 'Ijgenweis'
                    ],
                ],
            ],
            'Supersized Kingsday 2026' => [
                'Mainstage' => ['Ecstatic', 'EZG', 'Atmozfears', 'Wildstylez', 'Outsiders', 'B-Front & Phuture Noize', 'D-Sturb', 'Brennan Heart', 'Rejecta : SUPERSIZED', 'Rebelion', 'Radical Redemption', 'Synergy'],
                'Raw' => ['Refold vs Re-Vane', 'Coldax vs Omnya : SUPERSIZED', 'Vexxed', 'Infliction', 'Level One vs Nightcraft', 'Sanctuary vs Spitfire', 'Cryex', 'BMBERJCK', 'Chapter V & Revelation', 'Dark Entities vs Unmute', 'FLO'],
                'Uptempo' => ['Josha', 'T.M.O. vs Amigo', 'Namara', 'Spitnoise', 'Satirized', 'Lekkerfaces', 'Gezellige Uptempo : SUPERSIZED', 'Complex vs Udow', 'Abaddon vs Rosbeek', 'Partyraiser', 'The Speed Team: Akimbo, Kili, Samynator, Revealer & Roosterz', 'Robs'],
                'Hardstyle Classics' => ['Silvio Aquila', 'Josh & Wesz', 'Jones', 'Zany & The Pitcher', 'Brennan Heart pres. Evolution of Style', 'Coone', 'Noisecontrollers', 'Psyko Punkz', 'Ran-D & Adaro : SUPERSIZED', 'E-Force', 'DV8'],
                'Hardcore' => ['Hyperverb', 'Chaos Project', 'Karun vs Unfused', 'Furyan', 'Promo', 'Nosferatu : SUPERSIZED', 'Miss K8', 'Paul Elstak ft. Boogshe', 'Endymion', 'D-Fence vs Never Surrender', 'Tha Watcher'],
                'Hardcore Classics' => ['Gabber Syndrome', 'Noxa', 'Fantastic Four : SUPERSIZED', 'Kasparov', 'Mad Dog', 'Tha Playah', 'Art of Fighters', 'Korsakoff', 'Neophyte', 'Drokz : Gabber Set', 'Alee'],
                'Dutch Style' => ['Dâvinø', 'Patjoo\'s Royal Rave', 'More Kords', 'Hans Glock pres. Back2Basics', 'Dr. Rude pres Jump Classics', 'DJ Jantje', 'Larstig & Gasdrop : SUPERSIZED', 'DIKKE BAAP', 'Pat B', 'Potato', 'DL'],
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

        // 10. Official Timetable for Rebirth Festival 2026
        $rebirthEvent = $eventModels['Rebirth Festival 2026'];
        $rebirthTimetable = EventTimetable::updateOrCreate(
            ['event_id' => $rebirthEvent->id, 'is_official' => true],
            ['name' => 'Official Timetable', 'is_public' => true]
        );
        $rebirthTimetable->entries()->delete();

        $rebirthEntries = [
            // Day 1
            '2026-04-10' => [
                'REBIRTH' => [
                    ['D-Charged vs Serzo', '14:00', '15:00'],
                    ['JAY REEVE (MELODIC MADNESS)', '15:00', '16:00'],
                    ['Aversion', '16:00', '17:00'],
                    ['ACT OF RAGE (UNCHARTED LIVE)', '17:00', '17:30'],
                    ['B-FRONT F2F PHUTURE NOIZE', '17:30', '18:45'],
                    ['Sickmode', '18:45', '19:30'],
                    ['THE STRAIKERZ (777 LIVE)', '19:30', '20:00'],
                    ['Warface', '20:00', '21:00'],
                    ['Mutilator vs Revelation', '21:00', '22:00'],
                    ['D-Sturb vs Mish', '22:00', '23:00'],
                    ['Gezellige Uptempo vs Satirized', '23:00', '00:00'],
                ],
                'RESIST' => [
                    ['Histofz', '14:00', '14:45'],
                    ['Latinity vs Luxo vs Maistro', '14:45', '15:45'],
                    ['THAROZA (LIVE OR DIE LIVE)', '15:45', '16:15'],
                    ['REVELLERS (LIVE)', '16:15', '16:45'],
                    ['The Dope Doctor', '16:45', '17:30'],
                    ['BÖSSELS (LIVE)', '17:30', '18:00'],
                    ['PREMIERE MISSY & DIMMA (SLOPEN DE TENT LIVE)', '18:00', '18:30'],
                    ['Deviation vs Eraized vs Udow', '18:30', '19:30'],
                    ['SAMYNATOR (MUTATION)', '19:30', '20:00'],
                    ['PREMIERE PINOTELLO (NO PINO NO PARTY LIVE)', '20:00', '20:30'],
                    ['MAJOR CONSPIRACY (VOL BASIS 2ND GEAR)', '20:30', '21:00'],
                    ['Aalst vs Roosterz', '21:00', '22:00'],
                    ['Lekkerfaces vs Toza', '22:00', '23:00'],
                ],
                'REBORN RAW' => [
                    ['Fracture vs Madmize', '14:00', '15:00'],
                    ['Dark Entities vs Phantom', '15:00', '16:00'],
                    ['Spitfire vs Unique', '16:00', '17:00'],
                    ['Coldax', '17:00', '18:00'],
                    ['Big K', '18:00', '18:30'],
                    ['DETAILED (LIVE)', '18:30', '19:00'],
                    ['Chapter V vs Faceless vs Omnya', '19:00', '20:00'],
                    ['Damaxy vs Vasto', '20:00', '21:00'],
                    ['Amduscias vs Mortis', '21:00', '22:00'],
                    ['Exproz vs So Juice', '22:00', '23:00'],
                ],
                'ROAD TO REBIRTH' => [
                    ['Restrictless', '14:00', '15:00'],
                    ['Amayze', '15:00', '16:00'],
                    ['Untold Stories', '16:00', '17:00'],
                    ['Kideast', '17:00', '18:00'],
                    ['B-Struct', '18:00', '19:00'],
                    ['Oblivion', '19:00', '20:00'],
                    ['Vibetalgic', '20:00', '21:00'],
                    ['Dejection', '21:00', '22:00'],
                    ['Nside', '22:00', '23:00'],
                ],
            ],
            // Day 2
            '2026-04-11' => [
                'REBIRTH' => [
                    ['SAVELLIX (WINNER ROAD TO REBIRTH)', '12:00', '13:15'],
                    ['Sound Rush', '13:15', '14:15'],
                    ['ADRENALIZE vs B-FRONT (REBIRTH EVOLUTION SPECIAL)', '14:15', '15:30'],
                    ['THE SAINTS (HOLY DREAMS)', '15:30', '16:00'],
                    ['D-STURB (UNIVERSE)', '16:00', '16:45'],
                    ['SUB ZERO PROJECT (REBIRTH EVOLUTION SPECIAL)', '16:45', '17:45'],
                    ['Kick Off Show', '17:45', '18:00'],
                    ['Sickmode', '18:00', '18:45'],
                    ['UNRESOLVED (WARRIOR LIVE)', '18:45', '19:15'],
                    ['DUAL DAMAGE (BUILT 2 BREAK)', '19:15', '20:00'],
                    ['Anthem Show', '20:00', '20:05'],
                    ['PHUTURE NOIZE (OPEN HEART SURGERY)', '20:05', '20:45'],
                    ['4444 Four of a Kind', '20:45', '21:45'],
                    ['REJECTA (PATIENT ZERO LIVE)', '21:45', '22:15'],
                    ['REBELION (REBIRTH EVOLUTION SPECIAL)', '22:15', '23:15'],
                    ['15 YEARS OF RADICAL REDEMPTION (REBIRTH EVOLUTION SPECIAL)', '23:15', '00:00'],
                ],
                'REBELLION' => [
                    ['Resilience', '12:00', '12:45'],
                    ['Deluzion', '12:45', '13:30'],
                    ['SPOONTECH (REBIRTH EVOLUTION SPECIAL)', '13:30', '14:30'],
                    ['CRYEX (PREMIERE NEW UNLIKE EXPERIENCE)', '14:30', '15:00'],
                    ['Nightcraft vs Regain', '15:00', '15:45'],
                    ['Bloodlust', '15:45', '16:45'],
                    ['THE PURGE (REBIRTH EVOLUTION SPECIAL)', '16:45', '17:45'],
                    ['DEEZL (AEON)', '17:45', '18:15'],
                    ['REJECTOFRAGE (REBIRTH EVOLUTION SPECIAL)', '18:15', '19:15'],
                    ['ROOLER (REBIRTH EVOLUTION SPECIAL)', '19:15', '20:15'],
                    ['KROWDEXX (WE THE LOUDEST)', '20:15', '20:45'],
                    ['Adjuzt vs BMBERJCK', '20:45', '21:45'],
                    ['OMNYA (CARNAGE: FROZEN VEINS)', '21:45', '22:15'],
                    ['MUTILATOR (RAVE REACTOR)', '22:15', '22:45'],
                    ['EXPROZ (THE JAILBREAKER)', '22:45', '23:15'],
                    ['Kruelty', '23:15', '00:00'],
                ],
                'RESIST' => [
                    ['D-STROY (WINNER ROAD TO REBIRTH)', '12:00', '12:45'],
                    ['Dimma vs Guiberz vs Valhalla', '12:45', '13:45'],
                    ['T.M.O. (LIVE)', '13:45', '14:15'],
                    ['UDOW (LIVE)', '14:15', '14:45'],
                    ['Maintrex vs MT', '14:45', '15:30'],
                    ['REVEALER (THE ANGER IN US LIVE)', '15:30', '16:00'],
                    ['Guizcore vs Spiady', '16:00', '17:00'],
                    ['DEADLY GUNS (HARDCORE NATION)', '17:00', '17:30'],
                    ['Complex vs Kili vs Missy', '17:30', '18:30'],
                    ['LEKKERFACES (HYPER)', '18:30', '19:00'],
                    ['Manifest Destiny', '19:00', '20:00'],
                    ['GEZELLIGE UPTEMPO (SHIT HAPPENS)', '20:00', '20:30'],
                    ['SPITNOISE (BOUNCE OF STEEL)', '20:30', '21:00'],
                    ['Dimitri K', '21:00', '22:00'],
                    ['SATIRIZED (NEON FUNFAIR LIVE)', '22:00', '22:30'],
                    ['The Dark Horror vs Noxiouz', '22:30', '23:30'],
                    ['AKIMBO (GAME OVER)', '23:30', '00:00'],
                ],
                'REBORN RAW' => [
                    ['HARDE KWARK (WINNER ROAD TO REBIRTH)', '12:00', '12:45'],
                    ['Elevation vs Unmute', '12:45', '13:45'],
                    ['HEAVY RESISTANCE (LIVE)', '13:45', '14:15'],
                    ['SANCTUARY (LIVE)', '14:15', '14:45'],
                    ['Disaster', '14:45', '15:30'],
                    ['INCULT (LIVE)', '15:30', '16:00'],
                    ['CLASSIFIED RECORDS SHOWCASE: COLDAX, DAMAXY, DETAILED & UNIQUE', '16:00', '17:00'],
                    ['Infliction vs Sparkz', '17:00', '18:00'],
                    ['REVELATION (LIVE)', '18:00', '18:30'],
                    ['Anderex vs Titi', '18:30', '19:30'],
                    ['DARK ENTITIES (LIVE)', '19:30', '20:00'],
                    ['Unload', '20:00', '20:45'],
                    ['Faceless', '20:45', '21:30'],
                    ['CAPTIVATOR vs SPITFIRE (LIVE)', '21:30', '22:00'],
                    ['Kemal', '22:00', '22:30'],
                ],
                'RESET x REVENGE' => [
                    ['Rave Generators', '12:00', '13:00'],
                    ['GA-OSZ', '13:00', '14:00'],
                    ['Exertion vs Panic', '14:00', '15:00'],
                    ['The Darkraver vs The Viper', '15:00', '16:00'],
                    ['ENDYMION vs EVIL ACTIVITIES (MILLENNIUM SET)', '16:00', '17:00'],
                    ['NOSFERATU & THA PLAYAH (COMBINED FORCES)', '17:00', '18:00'],
                    ['Noize Suppressor', '18:00', '19:00'],
                    ['Promo', '19:00', '20:30'],
                    ['MISS K8 (REBIRTH EVOLUTION SPECIAL)', '20:30', '22:00'],
                    ['THE BEST OF PARTYRAISER (REBIRTH EVOLUTION SPECIAL)', '22:00', '23:00'],
                ],
                'REACTIVATE' => [
                    ['The Echoes of Time', '12:00', '13:00'],
                    ['Zelecter', '13:00', '14:00'],
                    ['Jones', '14:00', '15:00'],
                    ['20 YEARS OF DIRTY WORKZ: COONE (REBIRTH EVOLUTION SPECIAL)', '15:00', '16:30'],
                    ['Josh & Wesz', '16:30', '17:30'],
                    ['Wildstylez', '17:30', '18:30'],
                    ['E-Force', '18:30', '19:30'],
                    ['Zany', '19:30', '20:15'],
                    ['B-Front vs Zany', '20:15', '20:45'],
                    ['B-Front', '20:45', '21:15'],
                    ['Adaro', '21:15', '22:30'],
                    ['Jason Payne (GOLDSCHOOL)', '22:30', '23:30'],
                ],
                'REVELATION' => [
                    ['EMS: The Hardstyle Family', '12:00', '13:00'],
                    ['Digital Madness', '13:00', '14:00'],
                    ['D-Charged', '14:00', '15:00'],
                    ['Solstice', '15:00', '16:00'],
                    ['Refuzion', '16:00', '17:00'],
                    ['Cyber', '17:00', '18:00'],
                    ['DEMI KANON (DECADE)', '18:00', '18:30'],
                    ['Jay Reeve', '18:30', '19:30'],
                    ['ATMOZFEARS (REBIRTH EVOLUTION SPECIAL)', '19:30', '20:30'],
                    ['120 MINUTES: 20 YEARS OF NOISECONTROLLERS (REBIRTH EVOLUTION SPECIAL)', '20:30', '22:30'],
                    ['Audiotricz & Ecstatic (Progressive Hardstyle)', '22:30', '23:00'],
                    ['Galactixx', '23:00', '00:00'],
                ],
                'MADNESS SQUARE' => [
                    ['Vette Openingsshow', '14:00', '15:00'],
                    ['Unbreakable x Special Krew', '15:00', '15:30'],
                    ['Kreated (Wheel of Madness)', '15:30', '16:30'],
                    ['Special Krew x Tomme-C', '16:30', '17:15'],
                    ['Daani (Party with Style)', '17:15', '17:45'],
                    ['Special Krew B2B Daani', '17:45', '18:30'],
                    ['Arabiercantus', '18:30', '19:00'],
                    ['De Quarantaine Boys', '19:00', '19:30'],
                    ['Sjans Paul', '19:30', '20:00'],
                    ['Blvckprint', '20:00', '21:00'],
                    ['Rave Krew', '21:00', '22:00'],
                ],
            ],
            // Day 3
            '2026-04-12' => [
                'REBIRTH' => [
                    ['Jones vs Zelecter', '13:00', '14:00'],
                    ['Nightcraft', '14:00', '14:45'],
                    ['REGAIN: POLISH PUNISHER (ALBUM SHOWCASE)', '14:45', '15:15'],
                    ['Mish', '15:15', '16:00'],
                    ['WARFACE (REBIRTH EVOLUTION SPECIAL)', '16:00', '17:00'],
                    ['Dual Damage vs The Saints', '17:00', '18:00'],
                    ['D-BLOCK & S-TE-FAN vs PHUTURE NOIZE (REBIRTH EVOLUTION SPECIAL)', '18:00', '19:30'],
                    ['Rebelion vs The Straikerz', '19:30', '20:30'],
                    ['AWAKE ASLEEP (ADJUZT)', '20:30', '21:00'],
                    ['Vertile', '21:00', '21:45'],
                    ['Rooler', '21:45', '22:30'],
                    ['NOXIOUZ (THE CATALYST LIVE)', '22:30', '23:00'],
                ],
                'REBELLION' => [
                    ['Disaster vs Unique', '13:00', '13:45'],
                    ['Hard Destiny vs Sanctuary', '13:45', '14:30'],
                    ['Vasto (The Protocol)', '14:30', '15:00'],
                    ['CHAPTER V vs INFLICTION (LIVE)', '15:00', '15:30'],
                    ['ELEMENT (ODE TO THE PAST)', '15:30', '16:00'],
                    ['TOZA (LIVE)', '16:00', '16:30'],
                    ['Detailed vs Sparkz vs Unload', '16:30', '17:30'],
                    ['Toxic Machinery vs Ush', '17:30', '18:30'],
                    ['BMBERJCK (TEMPLE OF RAW)', '18:30', '19:00'],
                    ['Damaxy vs Incult vs Revelation', '19:00', '20:00'],
                    ['Kruelty vs Slvl', '20:00', '21:00'],
                    ['ANDEREX (THE SIMULATION)', '21:00', '21:30'],
                    ['THE SMILER (LIVE)', '21:30', '22:00'],
                ],
                'RESIST' => [
                    ['Undivided vs Victus', '13:00', '14:00'],
                    ['Noise of Aggression', '14:00', '14:30'],
                    ['NOISEFLOW (LIVE)', '14:30', '15:00'],
                    ['Bössels vs T.M.O.', '15:00', '16:00'],
                    ['DMRC (HEIST NIGHT LIVE)', '16:00', '16:30'],
                    ['Manifest Destiny vs Unproven', '16:30', '17:30'],
                    ['ROOSTERZ (GOOSEBUMPS LIVE)', '17:30', '18:00'],
                    ['Barber vs Partyraiser', '18:00', '19:00'],
                    ['COMPLEX (RAMMERONI LIVE)', '19:00', '19:30'],
                    ['PREMIERE ANGST VOOR AALST (LIVE)', '19:30', '20:00'],
                    ['The Dark Horror', '20:00', '21:00'],
                    ['Kili (Locked & Loaded)', '21:00', '21:30'],
                    ['OVERDRIVE SHOWCASE: ABADDON, DARK INDIVIDUAL, ROSBEEK, SCREECHER & THAROZA', '21:30', '22:30'],
                ],
                'REACTIVATE' => [
                    ['Neilio', '14:00', '15:00'],
                    ['Noisecult', '15:00', '16:00'],
                    ['DIGITAL PUNK vs MAX ENFORCER (REBIRTH EVOLUTION SPECIAL)', '16:00', '17:00'],
                    ['Psyko Punkz', '17:00', '18:00'],
                    ['Deepack', '18:00', '19:00'],
                    ['Crypsis', '19:00', '20:00'],
                    ['Sub Sonik', '20:00', '21:00'],
                    ['Regain vs Unresolved', '21:00', '22:00'],
                    ['THERACORDS SPECIAL: DEGOS & RE-DONE, DJ THERA & GECK-O', '22:00', '23:00'],
                ],
                'REVELATION' => [
                    ['Re-Vane', '13:00', '14:00'],
                    ['MORE KORDS (ZAAGPHORIC)', '14:00', '15:00'],
                    ['Avi8 (Higher)', '15:00', '16:00'],
                    ['AUDIOTRICZ (REBIRTH EVOLUTION SPECIAL)', '16:00', '17:00'],
                    ['WASTED PENGUINZ (REBIRTH EVOLUTION SPECIAL)', '17:00', '18:00'],
                    ['ECSTATIC (THE ESSENCE)', '18:00', '19:00'],
                    ['Adrenalize vs Deezl', '19:00', '20:00'],
                    ['ADARO (REBIRTH EVOLUTION SPECIAL)', '20:00', '21:00'],
                    ['Galactixx vs Rejecta', '21:00', '22:00'],
                ],
                'Beetje Dansen' => [
                    ['Willem de Wijs', '14:30', '16:00'],
                    ['Kruzo', '16:00', '17:00'],
                    ['Nelis Leeman', '17:00', '17:30'],
                    ['Willem de Wijs', '17:30', '18:00'],
                    ['Jeffrey Heesen', '18:00', '18:30'],
                    ['Willem de Wijs', '18:30', '19:00'],
                    ['Sven Versteeg', '19:00', '19:30'],
                    ['Willem de Wijs', '19:30', '20:45'],
                    ['Mart Hoogkamer', '20:45', '21:15'],
                    ['Willem de Wijs', '21:15', '22:00'],
                    ['Effe Serieus', '22:00', '23:00'],
                ],
                'ROAD TO REBIRTH' => [
                    ['Mutant', '13:00', '14:00'],
                    ['LuckyNoise', '14:00', '15:00'],
                    ['Josha', '15:00', '16:00'],
                    ['Conspirator', '16:00', '17:00'],
                    ['Sickdog', '17:00', '18:00'],
                    ['Tob-E', '18:00', '19:00'],
                    ['Invicious', '19:00', '20:00'],
                    ['Illuszion', '20:00', '21:00'],
                    ['Ijgenweis', '21:00', '22:00'],
                ],
            ],
        ];

        foreach ($rebirthEntries as $date => $stages) {
            foreach ($stages as $stageName => $entries) {
                if (!isset($stageModels[$stageName])) continue;
                $stage = $stageModels[$stageName];

                foreach ($entries as $entry) {
                    $aName = $entry[0];
                    $act = $actModels[$aName] ?? null;
                    if (!$act) {
                        $act = Act::firstOrCreate(['name' => $aName], ['is_live' => false]);
                        $actModels[$aName] = $act;
                    }

                    $start = Carbon::parse($date . ' ' . $entry[1], 'Europe/Amsterdam')->utc();
                    $end = Carbon::parse($date . ' ' . $entry[2], 'Europe/Amsterdam')->utc();

                    // If end time is before start time, it probably spans to the next day
                    if ($end->lt($start)) {
                        $end->addDay();
                    }

                    TimetableEntry::create([
                        'timetable_id' => $rebirthTimetable->id,
                        'stage_id' => $stage->id,
                        'act_id' => $act->id,
                        'start_time' => $start,
                        'end_time' => $end,
                    ]);
                }
            }
        }

        // 11. Official Timetable for Supersized Kingsday 2026
        $supersizedEvent = $eventModels['Supersized Kingsday 2026'];
        $supersizedTimetable = EventTimetable::updateOrCreate(
            ['event_id' => $supersizedEvent->id, 'is_official' => true],
            ['name' => 'Official Timetable', 'is_public' => true]
        );
        $supersizedTimetable->entries()->delete();

        $supersizedEntries = [
            '2026-04-27' => [
                'Mainstage' => [
                    ['Ecstatic', '12:00', '13:30'],
                    ['EZG', '13:30', '14:00'],
                    ['Atmozfears', '14:00', '15:00'],
                    ['Wildstylez', '15:00', '16:00'],
                    ['Outsiders', '16:00', '17:00'],
                    ['B-Front & Phuture Noize', '17:00', '18:00'],
                    ['D-Sturb', '18:00', '19:00'],
                    ['Brennan Heart', '19:00', '20:00'],
                    ['Rejecta : SUPERSIZED', '20:00', '21:00'],
                    ['Rebelion', '21:00', '22:00'],
                    ['Radical Redemption', '22:00', '23:00'],
                    ['Synergy', '23:00', '23:01'],
                ],
                'Raw' => [
                    ['Refold vs Re-Vane', '12:00', '13:00'],
                    ['Coldax vs Omnya : SUPERSIZED', '13:00', '14:00'],
                    ['Vexxed', '14:00', '15:15'],
                    ['Infliction', '15:15', '16:15'],
                    ['Level One vs Nightcraft', '16:15', '17:30'],
                    ['Sanctuary vs Spitfire', '17:30', '19:00'],
                    ['Cryex', '19:00', '20:00'],
                    ['BMBERJCK', '20:00', '21:00'],
                    ['Chapter V & Revelation', '21:00', '22:00'],
                    ['Dark Entities vs Unmute', '22:00', '23:00'],
                    ['FLO', '23:00', '23:01'],
                ],
                'Uptempo' => [
                    ['Josha', '12:00', '13:00'],
                    ['T.M.O. vs Amigo', '13:00', '14:00'],
                    ['Namara', '14:00', '15:00'],
                    ['Spitnoise', '15:00', '16:00'],
                    ['Satirized', '16:00', '17:00'],
                    ['Lekkerfaces', '17:00', '18:00'],
                    ['Gezellige Uptempo : SUPERSIZED', '18:00', '19:00'],
                    ['Complex vs Udow', '19:00', '20:00'],
                    ['Abaddon vs Rosbeek', '20:00', '21:00'],
                    ['Partyraiser', '21:00', '22:00'],
                    ['The Speed Team: Akimbo, Kili, Samynator, Revealer & Roosterz', '22:00', '23:00'],
                    ['Robs', '23:00', '23:01'],
                ],
                'Hardstyle Classics' => [
                    ['Silvio Aquila', '12:00', '13:00'],
                    ['Josh & Wesz', '13:00', '14:00'],
                    ['Jones', '14:00', '15:00'],
                    ['Zany & The Pitcher', '15:00', '16:30'],
                    ['Brennan Heart pres. Evolution of Style', '16:30', '17:15'],
                    ['Coone', '17:15', '18:15'],
                    ['Noisecontrollers', '18:15', '19:30'],
                    ['Psyko Punkz', '19:30', '20:30'],
                    ['Ran-D & Adaro : SUPERSIZED', '20:30', '22:00'],
                    ['E-Force', '22:00', '23:00'],
                    ['DV8', '23:00', '23:01'],
                ],
                'Hardcore' => [
                    ['Hyperverb', '12:00', '13:00'],
                    ['Chaos Project', '13:00', '14:00'],
                    ['Karun vs Unfused', '14:00', '15:00'],
                    ['Furyan', '15:00', '16:00'],
                    ['Promo', '16:00', '17:00'],
                    ['Nosferatu : SUPERSIZED', '17:00', '18:00'],
                    ['Miss K8', '18:00', '19:30'],
                    ['Paul Elstak ft. Boogshe', '19:30', '20:30'],
                    ['Endymion', '20:30', '21:45'],
                    ['D-Fence vs Never Surrender', '21:45', '23:00'],
                    ['Tha Watcher', '23:00', '23:01'],
                ],
                'Hardcore Classics' => [
                    ['Gabber Syndrome', '12:00', '13:30'],
                    ['Noxa', '13:30', '14:30'],
                    ['Fantastic Four : SUPERSIZED', '14:30', '16:00'],
                    ['Kasparov', '16:00', '17:00'],
                    ['Mad Dog', '17:00', '18:00'],
                    ['Tha Playah', '18:00', '19:00'],
                    ['Art of Fighters', '19:00', '20:00'],
                    ['Korsakoff', '20:00', '21:00'],
                    ['Neophyte', '21:00', '22:00'],
                    ['Drokz : Gabber Set', '22:00', '23:00'],
                    ['Alee', '23:00', '23:01'],
                ],
                'Dutch Style' => [
                    ['Dâvinø', '12:00', '13:00'],
                    ['Patjoo\'s Royal Rave', '13:00', '14:30'],
                    ['More Kords', '14:30', '15:15'],
                    ['Hans Glock pres. Back2Basics', '15:15', '16:15'],
                    ['Dr. Rude pres Jump Classics', '16:15', '17:30'],
                    ['DJ Jantje', '17:30', '19:00'],
                    ['Larstig & Gasdrop : SUPERSIZED', '19:00', '20:00'],
                    ['DIKKE BAAP', '20:00', '21:00'],
                    ['Pat B', '21:00', '22:00'],
                    ['Potato', '22:00', '23:00'],
                    ['DL', '23:00', '23:01'],
                ],
            ],
        ];

        foreach ($supersizedEntries as $date => $stages) {
            foreach ($stages as $stageName => $entries) {
                if (!isset($stageModels[$stageName])) continue;
                $stage = $stageModels[$stageName];

                foreach ($entries as $entry) {
                    $aName = $entry[0];
                    $act = $actModels[$aName] ?? null;
                    if (!$act) {
                        $act = Act::firstOrCreate(['name' => $aName], ['is_live' => false]);
                        $actModels[$aName] = $act;
                    }

                    $start = Carbon::parse($date . ' ' . $entry[1], 'Europe/Amsterdam')->utc();
                    $end = Carbon::parse($date . ' ' . $entry[2], 'Europe/Amsterdam')->utc();

                    if ($end->lt($start)) {
                        $end->addDay();
                    }

                    TimetableEntry::create([
                        'timetable_id' => $supersizedTimetable->id,
                        'stage_id' => $stage->id,
                        'act_id' => $act->id,
                        'start_time' => $start,
                        'end_time' => $end,
                    ]);
                }
            }
        }

        // 12. Assign events to admin user
        $admin = \App\Models\User::where('email', 'admin@bangers.nl')->first();
        if ($admin) {
            $now = Carbon::now();
            $allEvents = Event::all();

            $pastEvents = $allEvents->where('end_date', '<', $now)->take(10);
            $futureEvents = $allEvents->where('start_date', '>=', $now)->take(5);

            foreach ($pastEvents->concat($futureEvents) as $event) {
                $admin->attendedEvents()->syncWithoutDetaching([
                    $event->id => ['status' => 'going']
                ]);
            }
        }
    }
}

