<?php

use App\Models\User;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Sandofvega\Bdgeocode\Seeds\BdgeocodeSeeder;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        $this->call(BdgeocodeSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(WorkMethodsTableSeeder::class);
        $this->call(ServiceTypesTableSeeder::class);
        $this->call(PackageTypesTableSeeder::class);
        $this->call(ExpenseTypesSeeder::class);
        $this->call(PackagePropertiesTableSeeder::class);
        $this->call(ContentsTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);

        /**** Create Admin ****/
        $user = new User;
        $user->name = 'Arif Uzzaman';
        $user->mobile = '00000000000';
        $user->photo = 'default/user-photo/person.jpg';
        $user->password = '$2y$10$mBXIXfLULn4Vc7bJtVRk3.ZQ0S3Zb02x1xC/wmxsP.4H5TMGKIkHC'; // 123456
        $user->save();
        $user->attachRole(1);

        /**** Create default referrer package ****/
        $package = new Package;
        $package->package_type_id = 5;
        $package->save();

        DB::table('package_values')->insert([
            ['package_id' => $package->id, 'package_property_id' => 1, 'value' => 'ডিফল্ট'],
            ['package_id' => $package->id, 'package_property_id' => 2, 'value' => null],
            ['package_id' => $package->id, 'package_property_id' => 3, 'value' => null],
            ['package_id' => $package->id, 'package_property_id' => 4, 'value' => null],
            ['package_id' => $package->id, 'package_property_id' => 5, 'value' => null],
            ['package_id' => $package->id, 'package_property_id' => 6, 'value' => 10],
            ['package_id' => $package->id, 'package_property_id' => 7, 'value' => null],
            ['package_id' => $package->id, 'package_property_id' => 8, 'value' => null],
            ['package_id' => $package->id, 'package_property_id' => 9, 'value' => null]
        ]);

        /**** Create free registration packages ****/
        $indPackage = new Package;
        $indPackage->package_type_id = 1;
        $indPackage->save();

        $orgPackage = new Package;
        $orgPackage->package_type_id = 2;
        $orgPackage->save();

        DB::table('package_values')->insert([
            ['package_id' => $indPackage->id, 'package_property_id' => 1, 'value' => 'ফ্রি প্যাকেজ'],
            ['package_id' => $indPackage->id, 'package_property_id' => 2,
                'value' => 'এটি একটি ট্রায়াল প্যাকেজ। সম্পূর্ণ বিনামূল্যে ৭ দিনের জন্য এই প্যাকেজটি ব্যবহার করতে পারবেন।'],
            ['package_id' => $indPackage->id, 'package_property_id' => 3, 'value' => 7],
            ['package_id' => $indPackage->id, 'package_property_id' => 4, 'value' => 0]
        ]);

        DB::table('package_values')->insert([
            ['package_id' => $orgPackage->id, 'package_property_id' => 1, 'value' => 'ফ্রি প্যাকেজ'],
            ['package_id' => $orgPackage->id, 'package_property_id' => 2,
                'value' => 'এটি একটি ট্রায়াল প্যাকেজ। সম্পূর্ণ বিনামূল্যে ৭ দিনের জন্য এই প্যাকেজটি ব্যবহার করতে পারবেন।'],
            ['package_id' => $orgPackage->id, 'package_property_id' => 3, 'value' => 7],
            ['package_id' => $orgPackage->id, 'package_property_id' => 4, 'value' => 0]
        ]);
    }
}