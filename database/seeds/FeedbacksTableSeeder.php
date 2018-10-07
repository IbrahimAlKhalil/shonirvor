<?php

use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbacksTableSeeder extends Seeder
{
    public function run()
    {
        factory(Feedback::class, 200)->create();
    }
}
