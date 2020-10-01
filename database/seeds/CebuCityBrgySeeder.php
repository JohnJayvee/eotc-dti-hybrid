<?php

use App\Laravel\Models\Barangay;
use Illuminate\Database\Seeder;

class CebuCityBrgySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Barangay::truncate();

        $north_district = [
            "072217001" => "Adlaon",
            "072217002" => "Agsungot",
            "072217003" => "Apas",
            "072217006" => "Bacayan",
            "072217007" => "Banilad",
            "072217010" => "Binaliw",
            "072217013" => "Budlaan",
            "072217017" => "Busay",
            "072217019" => "Cambinocot",
            "072217020" => "Capitol Site",
            "072217021" => "Carreta",
            "072217023" => "Cogon‑Ramos",
            "072217025" => "Day‑as",
            "072217028" => "Ermita",
            "072217030" => "Guba",
            "072217031" => "Hipodromo",
            "072217033" => "Kalubihan",
            "072217035" => "Kamagayan",
            "072217036" => "Kamputhaw",
            "072217037" => "Kasambagan",
            "072217041" => "Lahug",
            "072217042" => "Lorega‑San Miguel",
            "072217043" => "Lusaran",
            "072217044" => "Luz",
            "072217045" => "Mabini",
            "072217046" => "Mabolo",
            "072217048" => "Malubog",
            "072217050" => "Pahina Central",
            "072217054" => "Parian",
            "072217055" => "Paril",
            "072217057" => "Pit-os",
            "072217059" => "Pulangbato",
            "072217064" => "Sambag I",
            "072217065" => "Sambag II",
            "072217066" => "San Antonio",
            "072217067" => "San Jose",
            "072217069" => "San Roque",
            "072217070" => "Santa Cruz",
            "072217022" => "Santo Niño",
            "072217074" => "Sirao",
            "072217078" => "T. Padilla",
            "072217081" => "Talamban",
            "072217082" => "Taptap",
            "072217083" => "Tejero (Villa Gonzalo)",
            "072217084" => "Tinago",
            "072217087" => "Zapatera"
        ];

        foreach($north_district as $index => $value){
            Barangay::create([
                'psgc_brgy' => $index,
                'name' => $value,
                'district' => "north",
                'status' => "active"
            ]);
        }
        
        
        $south_district = [
            "072217004" => "Babag",
            "072217005" => "Basak Padro",
            "072217008" => "Basak San Nicolas",
            "072217011" => "Bonbon",
            "072217014" => "Buhisan",
            "072217015" => "Bulacao (Bulacao Pardo)",
            "072217016" => "Buot-Taop (Buot‑Taup Pardo)",
            "072217018" => "Calamba",
            "072217024" => "Cogon Padro",
            "072217027" => "Duljo Fatima",
            "072217029" => "Guadalupe",
            "072217032" => "Inayawan (Inayawan Pardo)",
            "072217034" => "Kalunasan",
            "072217038" => "Kinasang‑an (Kinasang‑an Pardo)",
            "072217040" => "Labangon",
            "072217049" => "Mambaling",
            "072217051" => "Pahina San Nicolas",
            "072217052" => "Pamutan",
            "072217056" => "Pasil",
            "072217053" => "Poblacion Pardo",
            "072217060" => "Pung‑ol Sibugay",
            "072217062" => "Punta Princesa",
            "072217063" => "Quiot (Quiot Pardo)",
            "072217068" => "San Nicolas Proper",
            "072217077" => "Sapangdaku",
            "072217071" => "Sawang Calero",
            "072217073" => "Sinsin",
            "072217075" => "Suba (Suba San Nicolas)",
            "072217076" => "Sudlon I",
            "072217088" => "Sudlon II",
            "072217079" => "Tabunan",
            "072217080" => "Tag-bao",
            "072217085" => "Tisa",
            "072217086" => "Toong (To‑ong Pardo)"
        ];

        foreach($south_district as $index => $value){
            Barangay::create([
                'psgc_brgy' => $index,
                'name' => $value,
                'district' => "south",
                'status' => "active"
            ]);
        }

        echo "Successfully seeded Cebu City Barangay.";
    }
}
