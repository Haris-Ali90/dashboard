<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'core.application' => 'ValuationApp',
            'core.version' => '1.0',
            'email.support' => 'support@valuationapp.com',
            'email.contact' => 'contact@valuationapp.com',
            'app.link.tutorial_video' => 'http://www.example.com/dummy_video.mp4',
            'app.link.guide_book' => 'http://www.example.com/dummy_video.mp4',
        ];

        foreach ($settings as $key => $value) {

            DB::table('settings')->insert([
                'config_key' => $key,
                'config_value' => is_array($value) ? json_encode($value) : $value,
                'is_encoded' => is_array($value) ? 1 : 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }
    }
}
