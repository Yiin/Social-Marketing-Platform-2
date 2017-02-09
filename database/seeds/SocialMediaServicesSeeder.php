<?php

use Illuminate\Database\Seeder;

class SocialMediaServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Models\SocialMediaService::SERVICES as $socialMediaService) {
            \App\Models\SocialMediaService::updateOrCreate([
                'name' => $socialMediaService['name'],
            ], $socialMediaService);
        }
    }
}
