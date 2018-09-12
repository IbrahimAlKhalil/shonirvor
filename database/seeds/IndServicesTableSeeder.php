<?php

use App\Models\IndCategory;
use App\Models\IndService;
use App\Models\PendingIndService;
use App\Models\User;
use Illuminate\Database\Seeder;

class IndServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $users->each(function ($user) {
            $isIndService = rand(1, 10);
            $isPending = rand(1, 10) >= 6;

            if ($isIndService >= 6) {

                switch ($isPending) {
                    case false:
                        factory(IndCategory::class)->create()->each(function ($indCategory) use ($user) {
                            $indCategory->indServices()->saveMany(factory(IndService::class, rand(1, 5))->make(['user_id' => $user->id]));
                        });

                        $user->roles()->attach(3);
                        break;
                    default:
                        $user->pendingIndServices()->saveMany(factory(PendingIndService::class, rand(1, 3))->make());
                }
            }
        });

    }
}
