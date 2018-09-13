<?php

use App\Models\User;
use App\Models\IndService;
use App\Models\OrgService;
use Illuminate\Database\Seeder;
use App\Models\PendingIndService;
use App\Models\PendingOrgService;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $customer = new User;
        $customer->name = "Hujaifa Islam";
        $customer->mobile = '0';
        $customer->password = '$2y$10$mBXIXfLULn4Vc7bJtVRk3.ZQ0S3Zb02x1xC/wmxsP.4H5TMGKIkHC';
        $customer->save();

        factory(User::class, 80)->create()->each(function ($user) {
            $isIndService = rand(1, 10);
            $isOrgService = rand(1, 10);

            if ($isIndService >= 6) {
                $isPending = rand(1, 10) >= 6;

                switch ($isPending) {
                    case false:
                        $user->indService()->save(factory(IndService::class)->make());
                        $user->roles()->attach(3);
                        break;
                    default:
                        $user->pendingIndService()->save(factory(PendingIndService::class)->make());
                }
            }

            if ($isOrgService >= 6) {
                $isPending = rand(1, 10) >= 6;

                switch ($isPending) {
                    case false:
                        $iteration = rand(1, 5);

                        for ($i = 0; $i < $iteration; $i++) {
                            $user->orgService()->save(factory(OrgService::class)->make());
                        }

                        $user->roles()->attach(4);
                        break;
                    default:
                        $iteration = rand(1, 3);
                        for ($i = 0; $i < $iteration; $i++) {
                            $user->pendingOrgService()->save(factory(PendingOrgService::class)->make());
                        }
                }
            }

            // Attach a role
            $user->roles()->attach(rand(1, 2));
        });
    }
}