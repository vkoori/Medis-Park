<?php

namespace Modules\Notification\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Notification\Enums\SmsTemplateEnum;
use Modules\Notification\Models\SmsTemplate;

class NotificationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            SmsTemplateEnum::OTP->value => 'خوش آمدید\nکد ورود :code'
        ];

        foreach ($rows as $template => $content) {
            SmsTemplate::query()->firstOrCreate(
                attributes: [
                    'template' => $template
                ],
                values: [
                    'content' => $content
                ]
            );
        }
    }
}
