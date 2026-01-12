<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data from webist.sql - students table
     */
    public function run(): void
    {
        $students = [
            ['matricule' => 2022002, 'nom' => 'Faris Ibrahim', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-21 22:38:31'],
            ['matricule' => 2022003, 'nom' => 'Youssef Darwish', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-07-17 20:54:19'],
            ['matricule' => 2022004, 'nom' => 'Walid Yazbek', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => 'ven', 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-05-30 15:58:40'],
            ['matricule' => 2022005, 'nom' => 'Youssef Qasem', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-21 02:54:36'],
            ['matricule' => 2022006, 'nom' => 'Ali Darwish', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-21 02:54:50'],
            ['matricule' => 2022007, 'nom' => 'Youssef Ibrahim', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022008, 'nom' => 'Youssef Zahran', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022009, 'nom' => 'Faris Darwish', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022010, 'nom' => 'Omar Nasser', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022011, 'nom' => 'Ahmed Al-Masri', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-05-23 00:23:41'],
            ['matricule' => 2022012, 'nom' => 'Ziad Nasser', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-05-30 15:09:01'],
            ['matricule' => 2022013, 'nom' => 'Ahmed Ibrahim', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-05-30 15:09:00'],
            ['matricule' => 2022014, 'nom' => 'Hassan Bakr', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-05-30 15:08:59'],
            ['matricule' => 2022015, 'nom' => 'Khaled Yazbek', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022016, 'nom' => 'Amr Yazbek', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022017, 'nom' => 'Amr Bakr', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 0, 'choix' => 'ven', 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-05-30 15:58:28'],
            ['matricule' => 2022018, 'nom' => 'Faris Ibrahim', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022019, 'nom' => 'Amr Ibrahim', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022020, 'nom' => 'Walid Ibrahim', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-02-26 02:52:01', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022041, 'nom' => 'Ali Al-Masri', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:25:07', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022054, 'nom' => 'Walid Ibrahim', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:25:06', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022055, 'nom' => 'Walid Zahran', 'prenom' => '', 'grade' => '1', 'section_id' => 342, 'consigned' => 0, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:21', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022056, 'nom' => 'Hassan Haddad', 'prenom' => '', 'grade' => '1', 'section_id' => 351, 'consigned' => 1, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:57', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022064, 'nom' => 'Youssef Qasem', 'prenom' => '', 'grade' => '1', 'section_id' => 352, 'consigned' => 1, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:48', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022077, 'nom' => 'Amr Yazbek', 'prenom' => '', 'grade' => '1', 'section_id' => 351, 'consigned' => 1, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:59', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022078, 'nom' => 'Omar Qasem', 'prenom' => '', 'grade' => '3', 'section_id' => 362, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:24:54', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022096, 'nom' => 'Ziad Nasser', 'prenom' => '', 'grade' => '2', 'section_id' => 362, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:51', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022099, 'nom' => 'Ziad Darwish', 'prenom' => '', 'grade' => '3', 'section_id' => 362, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:24:53', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022104, 'nom' => 'Youssef Yazbek', 'prenom' => '', 'grade' => '1', 'section_id' => 342, 'consigned' => 1, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:21', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022120, 'nom' => 'Ziad Darwish', 'prenom' => '', 'grade' => '2', 'section_id' => 352, 'consigned' => 1, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:44', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022131, 'nom' => 'Faris Qasem', 'prenom' => '', 'grade' => '2', 'section_id' => 352, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:46', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022168, 'nom' => 'Amr Al-Farouq', 'prenom' => '', 'grade' => '1', 'section_id' => 342, 'consigned' => 0, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:21', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022169, 'nom' => 'Khaled Zahran', 'prenom' => '', 'grade' => '2', 'section_id' => 341, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:39', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022185, 'nom' => 'Ahmed Bakr', 'prenom' => '', 'grade' => '1', 'section_id' => 353, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:27', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022194, 'nom' => 'Ziad Al-Farouq', 'prenom' => '', 'grade' => '1', 'section_id' => 342, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:20', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022220, 'nom' => 'Omar Al-Farouq', 'prenom' => '', 'grade' => '2', 'section_id' => 351, 'consigned' => 1, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:58', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022259, 'nom' => 'Ziad Bakr', 'prenom' => '', 'grade' => '3', 'section_id' => 352, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:24:45', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022276, 'nom' => 'Youssef Bakr', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-03-19 18:25:03', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022285, 'nom' => 'Amr Al-Masri', 'prenom' => '', 'grade' => '2', 'section_id' => 343, 'consigned' => 1, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:32', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022321, 'nom' => 'Hassan Al-Masri', 'prenom' => '', 'grade' => '2', 'section_id' => 342, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:24', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022322, 'nom' => 'Ali Al-Masri', 'prenom' => '', 'grade' => '3', 'section_id' => 343, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:24:34', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022332, 'nom' => 'Omar Ibrahim', 'prenom' => '', 'grade' => '3', 'section_id' => 353, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-03-19 18:24:29', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022343, 'nom' => 'Walid Haddad', 'prenom' => '', 'grade' => '1', 'section_id' => 353, 'consigned' => 0, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:26', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022358, 'nom' => 'Ali Haddad', 'prenom' => '', 'grade' => '2', 'section_id' => 341, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:41', 'updated_at' => '2025-04-19 15:46:11'],
            ['matricule' => 2022359, 'nom' => 'Walid Bakr', 'prenom' => '', 'grade' => '1', 'section_id' => 362, 'consigned' => 0, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:55', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022360, 'nom' => 'Ziad Darwish', 'prenom' => '', 'grade' => '2', 'section_id' => 352, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:48', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022364, 'nom' => 'Ali Haddad', 'prenom' => '', 'grade' => '2', 'section_id' => 352, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:47', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022375, 'nom' => 'Omar Yazbek', 'prenom' => '', 'grade' => '1', 'section_id' => 341, 'consigned' => 1, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:38', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022387, 'nom' => 'Amr Bakr', 'prenom' => '', 'grade' => '2', 'section_id' => 353, 'consigned' => 1, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:29', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022397, 'nom' => 'Ziad Qasem', 'prenom' => '', 'grade' => '2', 'section_id' => 351, 'consigned' => 1, 'choix' => 'sam', 'created_at' => '2025-03-19 18:25:02', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022414, 'nom' => 'Faris Zahran', 'prenom' => '', 'grade' => '1', 'section_id' => 353, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:30', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022424, 'nom' => 'Amr Yazbek', 'prenom' => '', 'grade' => '2', 'section_id' => 342, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:23', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022427, 'nom' => 'Walid Nasser', 'prenom' => '', 'grade' => '2', 'section_id' => 361, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:25:08', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022440, 'nom' => 'Ziad Ibrahim', 'prenom' => '', 'grade' => '2', 'section_id' => 343, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:31', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022456, 'nom' => 'Ziad Yazbek', 'prenom' => '', 'grade' => '2', 'section_id' => 353, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:26', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022460, 'nom' => 'Faris Yazbek', 'prenom' => '', 'grade' => '1', 'section_id' => 352, 'consigned' => 1, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:46', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022468, 'nom' => 'Amr Darwish', 'prenom' => '', 'grade' => '3', 'section_id' => 341, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:42', 'updated_at' => '2025-05-30 13:59:14'],
            ['matricule' => 2022471, 'nom' => 'Youssef Bakr', 'prenom' => '', 'grade' => '3', 'section_id' => 343, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:24:37', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022478, 'nom' => 'Ziad Al-Masri', 'prenom' => '', 'grade' => '2', 'section_id' => 351, 'consigned' => 1, 'choix' => 'sam', 'created_at' => '2025-03-19 18:25:00', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022479, 'nom' => 'Ahmed Darwish', 'prenom' => '', 'grade' => '2', 'section_id' => 362, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:55', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022497, 'nom' => 'Omar Al-Farouq', 'prenom' => '', 'grade' => '1', 'section_id' => 362, 'consigned' => 0, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:50', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022513, 'nom' => 'Ahmed Al-Farouq', 'prenom' => '', 'grade' => '3', 'section_id' => 343, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-03-19 18:24:36', 'updated_at' => '2025-04-21 23:47:08'],
            ['matricule' => 2022519, 'nom' => 'Khaled Qasem', 'prenom' => '', 'grade' => '3', 'section_id' => 351, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:25:01', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022525, 'nom' => 'Amr Haddad', 'prenom' => '', 'grade' => '2', 'section_id' => 351, 'consigned' => 0, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:58', 'updated_at' => '2025-05-30 15:58:35'],
            ['matricule' => 2022527, 'nom' => 'Ziad Al-Masri', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => 'ven', 'created_at' => '2025-03-19 18:25:05', 'updated_at' => '2025-07-17 20:54:52'],
            ['matricule' => 2022545, 'nom' => 'Ahmed Qasem', 'prenom' => '', 'grade' => '3', 'section_id' => 341, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:24:43', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022549, 'nom' => 'Khaled Nasser', 'prenom' => '', 'grade' => '2', 'section_id' => 341, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:41', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022554, 'nom' => 'Ahmed Yazbek', 'prenom' => '', 'grade' => '1', 'section_id' => 352, 'consigned' => 1, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:45', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022567, 'nom' => 'Ali Nasser', 'prenom' => '', 'grade' => '3', 'section_id' => 342, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-03-19 18:24:20', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022568, 'nom' => 'Faris Haddad', 'prenom' => '', 'grade' => '1', 'section_id' => 361, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:25:04', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022580, 'nom' => 'Walid Al-Farouq', 'prenom' => '', 'grade' => '3', 'section_id' => 341, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:24:43', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022598, 'nom' => 'Walid Darwish', 'prenom' => '', 'grade' => '1', 'section_id' => 362, 'consigned' => 0, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:53', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022604, 'nom' => 'Amr Al-Masri', 'prenom' => '', 'grade' => '3', 'section_id' => 343, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:24:34', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022606, 'nom' => 'Omar Darwish', 'prenom' => '', 'grade' => '1', 'section_id' => 351, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:57', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022609, 'nom' => 'Hassan Al-Masri', 'prenom' => '', 'grade' => '2', 'section_id' => 352, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:49', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022652, 'nom' => 'Ahmed Ibrahim', 'prenom' => '', 'grade' => '2', 'section_id' => 351, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:25:00', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022681, 'nom' => 'Faris Qasem', 'prenom' => '', 'grade' => '2', 'section_id' => 342, 'consigned' => 0, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:23', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022686, 'nom' => 'Hassan Nasser', 'prenom' => '', 'grade' => '2', 'section_id' => 343, 'consigned' => 0, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:33', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022701, 'nom' => 'Hassan Qasem', 'prenom' => '', 'grade' => '2', 'section_id' => 351, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:25:02', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022705, 'nom' => 'Khaled Al-Farouq', 'prenom' => '', 'grade' => '2', 'section_id' => 341, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:39', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022719, 'nom' => 'Faris Yazbek', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:25:05', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022726, 'nom' => 'Walid Ibrahim', 'prenom' => '', 'grade' => '2', 'section_id' => 353, 'consigned' => 0, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:28', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022730, 'nom' => 'Omar Al-Farouq', 'prenom' => '', 'grade' => '2', 'section_id' => 362, 'consigned' => 0, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:52', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022733, 'nom' => 'Youssef Darwish', 'prenom' => '', 'grade' => '3', 'section_id' => 341, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-03-19 18:24:38', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022740, 'nom' => 'Faris Darwish', 'prenom' => '', 'grade' => '2', 'section_id' => 361, 'consigned' => 1, 'choix' => 'sam', 'created_at' => '2025-03-19 18:25:06', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022794, 'nom' => 'Walid Bakr', 'prenom' => '', 'grade' => '1', 'section_id' => 342, 'consigned' => 0, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:22', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022800, 'nom' => 'Walid Yazbek', 'prenom' => '', 'grade' => '2', 'section_id' => 343, 'consigned' => 1, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:32', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022812, 'nom' => 'Amr Al-Farouq', 'prenom' => '', 'grade' => '3', 'section_id' => 361, 'consigned' => 0, 'choix' => null, 'created_at' => '2025-03-19 18:25:07', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022813, 'nom' => 'Ahmed Zahran', 'prenom' => '', 'grade' => '2', 'section_id' => 343, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:35', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022819, 'nom' => 'Omar Ibrahim', 'prenom' => '', 'grade' => '1', 'section_id' => 353, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:30', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022830, 'nom' => 'Youssef Al-Farouq', 'prenom' => '', 'grade' => '3', 'section_id' => 362, 'consigned' => 1, 'choix' => null, 'created_at' => '2025-03-19 18:24:51', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022859, 'nom' => 'Hassan Al-Masri', 'prenom' => '', 'grade' => '2', 'section_id' => 342, 'consigned' => 0, 'choix' => '48h', 'created_at' => '2025-03-19 18:24:24', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022883, 'nom' => 'Hassan Bakr', 'prenom' => '', 'grade' => '2', 'section_id' => 343, 'consigned' => 1, 'choix' => 'ven', 'created_at' => '2025-03-19 18:24:36', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022886, 'nom' => 'Youssef Zahran', 'prenom' => '', 'grade' => '1', 'section_id' => 361, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:25:04', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022899, 'nom' => 'Youssef Ibrahim', 'prenom' => '', 'grade' => '2', 'section_id' => 362, 'consigned' => 1, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:56', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022956, 'nom' => 'Faris Zahran', 'prenom' => '', 'grade' => '2', 'section_id' => 341, 'consigned' => 1, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:40', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022958, 'nom' => 'Youssef Zahran', 'prenom' => '', 'grade' => '2', 'section_id' => 352, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:50', 'updated_at' => '2025-04-19 15:46:12'],
            ['matricule' => 2022962, 'nom' => 'Faris Nasser', 'prenom' => '', 'grade' => '2', 'section_id' => 353, 'consigned' => 0, 'choix' => 'sam', 'created_at' => '2025-03-19 18:24:27', 'updated_at' => '2025-04-19 15:46:12'],
        ];

        // Use DB::table to bypass model events
        foreach ($students as $student) {
            DB::table('students')->updateOrInsert(
                ['matricule' => $student['matricule']],
                $student
            );
        }
    }
}
