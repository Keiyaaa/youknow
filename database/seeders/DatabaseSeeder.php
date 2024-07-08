<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Member::factory(10)->create();
        $this->call(AdminMenuTableSeeder::class);
        // $this->call(MembersTableSeeder::class);
        // $this->call(QuestionsTableSeeder::class);
        $this->call(SearchTagsTableSeeder::class);
    }
}
