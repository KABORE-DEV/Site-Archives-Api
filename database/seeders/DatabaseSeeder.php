<?php

namespace Database\Seeders;

use App\Models\AnneeAcademique;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Niveau;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Exécute les graines de la base de données (Seeders).
     */
    public function run(): void
    {
        // ---------------------------------------------------------------------
        // 1. CRÉATION DES NIVEAUX D'ÉTUDE
        // ---------------------------------------------------------------------
        $l1 = Niveau::firstOrCreate(['code' => 'L1'], ['intitule' => 'Licence 1']);
        $l2 = Niveau::firstOrCreate(['code' => 'L2'], ['intitule' => 'Licence 2']);
        $l3 = Niveau::firstOrCreate(['code' => 'L3'], ['intitule' => 'Licence 3']);
        $m1 = Niveau::firstOrCreate(['code' => 'M1'], ['intitule' => 'Master 1']);
        $m2 = Niveau::firstOrCreate(['code' => 'M2'], ['intitule' => 'Master 2']);

        // ---------------------------------------------------------------------
        // 2. CRÉATION DES ANNÉES ACADÉMIQUES
        // ---------------------------------------------------------------------
        AnneeAcademique::firstOrCreate(
            ['annee' => '2023-2024'],
            ['active' => false]
        );
        $anneeActive = AnneeAcademique::firstOrCreate(
            ['annee' => '2024-2025'],
            ['active' => true]
        );
        AnneeAcademique::firstOrCreate(
            ['annee' => '2025-2026'],
            ['active' => false]
        );

        // ---------------------------------------------------------------------
        // 3. CRÉATION DES FILIÈRES DE U-AUBEN
        // ---------------------------------------------------------------------
        $filiereGL = Filiere::firstOrCreate(
            ['code' => 'GL'],
            [
                'nom' => 'Génie Logiciel',
                'description' => 'Conception, développement et maintenance des logiciels et applications.',
            ]
        );

        $filiereRIT = Filiere::firstOrCreate(
            ['code' => 'RIT'],
            [
                'nom' => 'Réseaux Informatiques et Télécommunications',
                'description' => 'Administration des réseaux, infrastructures cloud et télécoms.',
            ]
        );

        $filiereSEG = Filiere::firstOrCreate(
            ['code' => 'SEG'],
            [
                'nom' => 'Sciences Économiques et de Gestion',
                'description' => 'Management, comptabilité, finance et administration des entreprises.',
            ]
        );

        // ---------------------------------------------------------------------
        // 4. CRÉATION DES MODULES (MATIÈRES)
        // ---------------------------------------------------------------------
        // Modules pour Génie Logiciel
        Module::firstOrCreate(
            ['code' => 'GL101'],
            [
                'nom' => 'Algorithmique & Structures de Données',
                'filiere_id' => $filiereGL->id,
                'niveau_id' => $l1->id,
            ]
        );

        Module::firstOrCreate(
            ['code' => 'GL201'],
            [
                'nom' => 'Développement Web Avancé (PHP/Laravel)',
                'filiere_id' => $filiereGL->id,
                'niveau_id' => $l2->id,
            ]
        );

        Module::firstOrCreate(
            ['code' => 'GL202'],
            [
                'nom' => 'Bases de Données Relationnelles & SQL',
                'filiere_id' => $filiereGL->id,
                'niveau_id' => $l2->id,
            ]
        );

        Module::firstOrCreate(
            ['code' => 'GL301'],
            [
                'nom' => 'Génie Logiciel & Modélisation UML',
                'filiere_id' => $filiereGL->id,
                'niveau_id' => $l3->id,
            ]
        );

        // Module pour Réseaux
        Module::firstOrCreate(
            ['code' => 'RIT201'],
            [
                'nom' => 'Architecture des Réseaux & Routage IP',
                'filiere_id' => $filiereRIT->id,
                'niveau_id' => $l2->id,
            ]
        );

        // ---------------------------------------------------------------------
        // 5. CRÉATION DES COMPTES UTILISATEURS (MOT DE PASSE COMMUN : password123)
        // ---------------------------------------------------------------------
        $motDePasseCommun = Hash::make('password123');

        // Compte Administrateur général
        User::firstOrCreate(
            ['email' => 'admin@u-auben.bf'],
            [
                'name' => 'Administrateur Général',
                'password' => $motDePasseCommun,
                'role' => 'admin',
                'filiere_id' => null, // L'admin a accès à toutes les filières
            ]
        );

        // Chef de filière : Génie Logiciel
        User::firstOrCreate(
            ['email' => 'chef.gl@u-auben.bf'],
            [
                'name' => 'Chef Filière GL',
                'password' => $motDePasseCommun,
                'role' => 'chef_filiere',
                'filiere_id' => $filiereGL->id, // Rattaché à GL
            ]
        );

        // Chef de filière : Réseaux (RIT)
        User::firstOrCreate(
            ['email' => 'chef.rit@u-auben.bf'],
            [
                'name' => 'Chef Filière RIT',
                'password' => $motDePasseCommun,
                'role' => 'chef_filiere',
                'filiere_id' => $filiereRIT->id, // Rattaché à RIT
            ]
        );

        // Étudiant test
        User::firstOrCreate(
            ['email' => 'etudiant@u-auben.bf'],
            [
                'name' => 'Étudiant Test',
                'password' => $motDePasseCommun,
                'role' => 'etudiant',
                'filiere_id' => null,
            ]
        );
    }
}
