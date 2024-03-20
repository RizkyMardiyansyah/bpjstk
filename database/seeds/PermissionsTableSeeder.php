<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // ok let's get all
        $sections_ids = \App\Models\Section::pluck('id')->toArray();
        // apply all permissions to admin
        echo "Found " . count($sections_ids) . " sections\n";
        foreach ($sections_ids as $section_id) {
            \App\Models\Permission::updateOrCreate(
                ['id' => $section_id],
                ['role_id' => 2, 'section_id' => $section_id, 'allow' => 1]);
        }
    }
}
