<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data from webist.sql - notifications table
     */
    public function run(): void
    {
        $notifications = [
            [
                'id' => '0cb64501-804b-47ae-9c04-37af9c37de45',
                'type' => 'App\\Notifications\\ReportArival',
                'notifiable_type' => 'App\\Models\\Officer',
                'notifiable_id' => 4,
                'data' => '{"report_id":29,"title":"hiii3","message":"New report: hiii3","url":"http:\\/\\/localhost:8000\\/1\\/show\\/29"}',
                'read_at' => null,
                'created_at' => '2025-12-30 02:41:03',
                'updated_at' => '2025-12-30 02:41:03'
            ],
            [
                'id' => '22e8bed2-5cc8-4062-9747-1a3780e71773',
                'type' => 'App\\Notifications\\ReportArival',
                'notifiable_type' => 'App\\Models\\Officer',
                'notifiable_id' => 3,
                'data' => '{"report_id":29,"title":"hiii3","message":"New report: hiii3","url":"http:\\/\\/localhost:8000\\/1\\/show\\/29"}',
                'read_at' => null,
                'created_at' => '2025-12-30 02:40:02',
                'updated_at' => '2025-12-30 02:40:02'
            ],
            [
                'id' => '5fa64a2c-2afb-496e-be15-bd7c355a27b9',
                'type' => 'App\\Notifications\\DesitionMade',
                'notifiable_type' => 'App\\Models\\Officer',
                'notifiable_id' => 9,
                'data' => '{"report_id":28,"title":"SFK\\/JNER","message":"New report: SFK\\/JNER","url":"http:\\/\\/localhost:8000\\/9\\/show\\/28"}',
                'read_at' => null,
                'created_at' => '2025-12-30 02:28:03',
                'updated_at' => '2025-12-30 02:28:03'
            ],
            [
                'id' => '66cbecdd-fbc6-49c8-8d24-db431c5213ae',
                'type' => 'App\\Notifications\\DesitionMade',
                'notifiable_type' => 'App\\Models\\Officer',
                'notifiable_id' => 9,
                'data' => '{"report_id":28,"title":"SFK\\/JNER","message":"New report: SFK\\/JNER","url":"http:\\/\\/localhost:8000\\/9\\/show\\/28"}',
                'read_at' => null,
                'created_at' => '2025-12-29 13:49:44',
                'updated_at' => '2025-12-29 13:49:44'
            ],
            [
                'id' => 'a5a78306-0e99-44be-addc-e53fc9c73747',
                'type' => 'App\\Notifications\\ReportArival',
                'notifiable_type' => 'App\\Models\\Officer',
                'notifiable_id' => 9,
                'data' => '{"report_id":29,"title":"hiii3","message":"New report: hiii3","url":"http:\\/\\/localhost:8000\\/1\\/show\\/29"}',
                'read_at' => null,
                'created_at' => '2025-12-30 02:42:34',
                'updated_at' => '2025-12-30 02:42:34'
            ],
            [
                'id' => 'a6ce7dcd-f507-4744-926b-5037027339d2',
                'type' => 'App\\Notifications\\DesitionMade',
                'notifiable_type' => 'App\\Models\\Officer',
                'notifiable_id' => 1,
                'data' => '{"report_id":29,"title":"hiii3","message":"New report: hiii3","url":"http:\\/\\/localhost:8000\\/1\\/show\\/29"}',
                'read_at' => null,
                'created_at' => '2025-12-30 02:43:51',
                'updated_at' => '2025-12-30 02:43:51'
            ],
            [
                'id' => 'd091571e-6fc4-4b24-b163-cc8ad7720653',
                'type' => 'App\\Notifications\\ReportArival',
                'notifiable_type' => 'App\\Models\\Officer',
                'notifiable_id' => 3,
                'data' => '{"report_id":29,"title":"hiii3","message":"New report: hiii3","url":"http:\\/\\/localhost:8000\\/1\\/show\\/29"}',
                'read_at' => null,
                'created_at' => '2025-12-30 02:39:42',
                'updated_at' => '2025-12-30 02:39:42'
            ],
            [
                'id' => 'f186b0e8-581d-47ae-a8a6-b491337811a1',
                'type' => 'App\\Notifications\\ReportArival',
                'notifiable_type' => 'App\\Models\\Officer',
                'notifiable_id' => 3,
                'data' => '{"report_id":27,"title":"DJDJD","message":"New report: DJDJD","url":"http:\\/\\/localhost:8000\\/1\\/show\\/27"}',
                'read_at' => null,
                'created_at' => '2025-12-29 13:46:47',
                'updated_at' => '2025-12-29 13:46:47'
            ],
            [
                'id' => 'fd8d652e-9ba5-4b01-a7a8-422a8fb41e49',
                'type' => 'App\\Notifications\\ReportArival',
                'notifiable_type' => 'App\\Models\\Officer',
                'notifiable_id' => 6,
                'data' => '{"report_id":29,"title":"hiii3","message":"New report: hiii3","url":"http:\\/\\/localhost:8000\\/1\\/show\\/29"}',
                'read_at' => null,
                'created_at' => '2025-12-30 02:41:48',
                'updated_at' => '2025-12-30 02:41:48'
            ],
        ];

        foreach ($notifications as $notification) {
            DB::table('notifications')->updateOrInsert(
                ['id' => $notification['id']],
                $notification
            );
        }
    }
}
