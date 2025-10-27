<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subjects')->delete();
        DB::table('subjects')->insert([
            [
                'name' => 'CALCULO I',
                'code' => 'MAT101',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FISICA II',
                'code' => 'FIS102',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CALCULO II',
                'code' => 'MAT102',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'METODOS NUMERICOS',
                'code' => 'MAT205',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FISICA I',
                'code' => 'FIS100',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INTRODUCCION A LA INFORMATICA',
                'code' => 'INF110',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ESTRUCTURAS DISCRETAS',
                'code' => 'INF119',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INGLES TECNICO I',
                'code' => 'LIN100',
                'credits' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PROGRAMACION I',
                'code' => 'INF120',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INGLES TECNICO II',
                'code' => 'LIN101',
                'credits' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ALGEBRA LINEAL',
                'code' => 'MAT103',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ADMINISTRACION',
                'code' => 'ADM100',
                'credits' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PROGRAMACION II',
                'code' => 'INF210',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ARQUITECTURA DE COMPUTADORAS',
                'code' => 'INF211',
                'credits' => 6, // Informática/Tecnología: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ECUACIONES DIFERENCIALES',
                'code' => 'MAT207',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CONTABILIDAD',
                'code' => 'ADM200',
                'credits' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ESTRUCTURA DE DATOS I',
                'code' => 'INF220',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PROGRAMACION ENSAMBLADOR',
                'code' => 'INF221',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PROBABILIDADES Y ESTADIST.I',
                'code' => 'MAT202',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ESTRUCTURAS DE DATOS II',
                'code' => 'INF310',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BASE DE DATOS I',
                'code' => 'INF312',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PROGRAMAC.LOGICA Y FUNCIONAL',
                'code' => 'INF318',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LENGUAJES FORMALES',
                'code' => 'INF319',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PROBABILIDADES Y ESTADISTICAS II',
                'code' => 'MAT302',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BASES DE DATOS II',
                'code' => 'INF322',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SISTEMAS OPERATIVOS I',
                'code' => 'INF323',
                'credits' => 6, // Informática/Tecnología: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'COMPILADORES',
                'code' => 'INF329',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SISTEMAS DE INFORMACION I',
                'code' => 'INF342',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INVESTIG. OPERATIVA I',
                'code' => 'MAT329',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SISTEMAS DE INFORMACION II',
                'code' => 'INF412',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SISTEMAS OPERATIVOS II',
                'code' => 'INF413',
                'credits' => 6, // Informática/Tecnología: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INTELIGENCIA ARTIFICIAL',
                'code' => 'INF418',
                'credits' => 6, // Informática/Tecnología: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REDES I',
                'code' => 'INF433',
                'credits' => 6, // Informática/Tecnología: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INVESTIGAC.OPERATIVA II',
                'code' => 'MAT419',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PREPARAC.Y EVALUAC.DE PROYECTOS',
                'code' => 'ECO449',
                'credits' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INGENIERIA DE SOFTWARE I',
                'code' => 'INF422',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SISTEMAS EXPERTOS',
                'code' => 'INF428',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SISTEMAS DE INFORM.GEOGRAFICA',
                'code' => 'INF442',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TALLER DE GRADO I',
                'code' => 'INF511',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INGENIERIA DE SOFTWARE II',
                'code' => 'INF512',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TECNOLOGIA WEB',
                'code' => 'INF513',
                'credits' => 6, // Informática/Tecnología: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ARQUITECTURA DEL SOFTWARE',
                'code' => 'INF552',
                'credits' => 6, // Informática: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PROGRAMACION GRAFICA',
                'code' => 'ELC102',
                'credits' => 6, // Tecnología: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TOPIC.AVANZ.DE PROGRAMAC.(ALGORIT.G',
                'code' => 'ELC103',
                'credits' => 6, // Tecnología: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INTERACCION HOMBRE-COMPUTADOR',
                'code' => 'ELC106',
                'credits' => 6, // Tecnología: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CRIPTOGRAFIA Y SEGURIDAD',
                'code' => 'ELC107',
                'credits' => 6, // Tecnología: Mayor puntaje
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FISICA III',
                'code' => 'FIS200',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'METODOLOGÍA DE LA INVESTIGACIÓN',
                'code' => 'MET100',
                'credits' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INTRODUCCIÓN A LA ROBÓTICA',
                'code' => 'ROB101',
                'credits' => 5, // Robótica es tecnología, se asigna un puntaje medio a alto
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DIBUJO MECÁNICO EN CAD',
                'code' => 'ROB102',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ESTÁTICA',
                'code' => 'ROB103',
                'credits' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PENSAMIENTO CRÍTICO Y CREATIVO',
                'code' => 'ROB104',
                'credits' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ELECTRICIDAD Y MAGNETISMO',
                'code' => 'ROB201',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TECNOLOGÍAS DE LA MANUFACTURA',
                'code' => 'ROB202',
                'credits' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DINÁMICA',
                'code' => 'ROB203',
                'credits' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
