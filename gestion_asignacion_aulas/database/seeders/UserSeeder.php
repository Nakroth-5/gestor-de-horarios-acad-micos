<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $docentes = [
            "ADMIN ADMIN",
            "FLORES FLORES MARCOS OSCAR", "CABELLO MERIDA JUAN RUBEN", "CORTEZ UZEDA JULIO MARTIN",
            "GUTIERREZ BRUNO KATIME ESTHER", "GIANELLA PEREDO EDUARDO", "MONRROY DIPP VICTOR FERNANDO",
            "CARVAJAL CORDERO MARCIO", "HINOJOSA SAAVEDRA JOSE SAID", "VEIZAGA GONZALES JOSUE OBED",
            "VARGAS PEÑA LEONARDO", "PEREZ FERREIRA UBALDO", "AVENDAÑO GONZALES EUDAL",
            "JUSTINIANO VACA JUAN TOMAS", "TEJERINA GUERRA JULIO", "JUSTINIANO ROCA RONALD",
            "MORALES MENDEZ MAGALY", "CALIZAYA AJHUACHO MAGNO EDWIN", "OROPEZA CLAURE GUSTAVO ADOLFO",
            "DURAN CESPEDES BERTHY RONALD", "SILES MUÑOZ FELIX", "ABARCA SOTA NANCY",
            "MIRANDA CARRASCO CARLOS", "VALDELOMAR ORELLANA TOMAS", "ACOSTA CABEZAS BARTOLO JAVIER",
            "CARRENO PEREIRA ANDRES", "LAZO ARTEAGA CARLOS ROBERTO", "GUARACHI SOLANO JONATHAN FELIX",
            /*"GRIMALDO BRAVO PAUL", "VELASCO GUAMAN ANGEL", "POR DESIGNAR", "BARROSO VIRUEZ GINO",
            "OROSCO GOMEZ RUBEN", "CHAHIN AVICHACRA JUAN MANUEL", "PEREZ DELGADILLO SHIRLEY EULAL",
            "TERRAZAS SOTO RICARDO", "GRAGEDA ESCUDERO MARIO WILSON", "PEINADO PEREIRA JUAN CARLOS",
            "LOPEZ WINNIPEG MARIO MILTON", "CAMPOS BARRERA MARIO", "VACA PINTO CESPEDES ROBERTO CA",
            "VARGAS YAPURA EDWIN", "VARGAS CASTILLO CIRO EDGAR", "MARTINEZ CARDONA SARAH MIRNA",
            "CACERES CHACON BRAULIO", "ARANIBAR QUIROZ M. MONICA", "PINTO VARGAS EDUARDO",
            "LAMAS RODRIGUEZ MARCOS", "SALVATIERRA MERCADO ROLANDO", "CAYOJA LUCANA VICTOR MILTON",
            "SANCHEZ VELASCO ENRIQUE", "CANO CESPEDES JORGE", "ORTIZ ARTEAGA VICTOR HUGO",
            "ROMAN ROCA RUFINO WILBERTO", "CHAU WONG JORGE", "CALDERON FLORES MODESTO FRANKL",
            "ZEBALLOS PAREDES DANIEL LUIS", "ROCHA ARGOTE FERNANDO", "OQUENDO HEREDIA FREDDY MIGUEL",
            "ARGOTE CLAROS IRMA ISABEL", "ROSALES FUENTES JORGE MARCELO", "ZUNA VILLAGOMEZ JULIO",
            "AGUILAR MARTINEZ DOMINGO", "CALLE TERRAZAS EDWIN", "MARTINEZ CANEDO ROLANDO ANTONI",
            "CLAURE MEDRANO DE OROPEZA ELIZ", "ALPIRE RIVERO GERMAN", "ZUÑIGA RUIZ WILMA",
            "EVELYN VANESA SORIA AVILA", "PEINADO PEREIRA MIGUEL JESUS", "SERISSIOTTI VELASQUEZ EDGAR ZACA",
            "ZUNA VILLAGOMEZ RICARDO", "MOLLO MAMANI ALBERTO", "GARZON CUELLAR ANGELICA",
            "TAPIA FLORES LUIS PERCY", "ALIAGA HOWARD SHARON KENNY", "CONTRERAS VILLEGAS JUAN CARLOS",
            "TORREZ CAMACHO LUZ DIANA", "SUAREZ CESPEDES MELBY", "DAVALOS SANCHEZ DE MANCILLA PI",
            "JUSTINIANO FLORES CARMEN LILIA", "LAMAS RODRIGUEZ MARCOS RODRIGO", "GIANELLA PEREDO LUIS ANTONIO",
            "MORENO SUAREZ ENRIQUE", "CASTRO MARISCAL JHONNY", "SANCHEZ RIOJA EDWIN ALEJANDRO",
            "GUTHRIE PACHECO MIGUEL ANGEL", "ATILA LIJERON JHONNY DAVID", "VALLET VALLET CORRADO",
            "FLORES GUZMAN VALENTIN VICTOR", "BALCAZAR VEIZAGA EVANS", "LOPEZ NEGRETTY MARY DUNNIA",
            "VILLAGOMEZ MELGAR JOSE JUNIOR", "GONZALES SANDOVAL JORGE ANTONI", "PAZ MENDOZA ROBERTO CARLOS",
            "CABALLERO RUA MAURICIO CHRISTI", "LAZO QUISPE SEBASTIAN", "SEVERICHE TOLEDO SAUL",
            "SELAYA GARVIZU IVAN VLADISHLAV", "FLORES CUELLAD DAVID LUIS", "LOBO LIMPIAS VICTOR HUGO",*/
        ];

        foreach (array_unique($docentes) as $fullName) {
            $names = $this->splitName($fullName);
            $email = Str::slug($names['name'] . ' ' . $names['last_name'], '.') . '@example.com';

            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $names['name'],
                    'code' => (string)rand(100000, 999999),
                    'last_name' => $names['last_name'],
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'document_number' => (string)rand(1000000, 9999999),
                ]
            );
        }
    }

    /**
     * Splits a full name into last_name and name.
     * Assumes first two words are last_name, rest is name.
     */
    private function splitName(string $fullName): array
    {
        if ($fullName === 'POR DESIGNAR') {
            return ['last_name' => 'POR', 'name' => 'DESIGNAR'];
        }

        $parts = explode(' ', $fullName);
        if (count($parts) <= 2) {
            return ['last_name' => $parts[0], 'name' => $parts[1] ?? ''];
        }

        return [
            'last_name' => $parts[0] . ' ' . $parts[1],
            'name' => implode(' ', array_slice($parts, 2)),
        ];
    }
}
