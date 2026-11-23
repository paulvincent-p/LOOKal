<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Business;
use App\Models\Category;
use App\Models\EmergencyContact;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Accounts (change these passwords before going live)
        User::updateOrCreate(['email' => 'admin@lookal.test'], [
            'name' => 'LOOKal Admin', 'phone' => '09000000000', 'password' => 'password',
            'role' => 'admin', 'status' => 'active',
        ]);

        $owner = User::updateOrCreate(['email' => 'owner@lookal.test'], [
            'name' => 'Demo Owner', 'phone' => '09000000001', 'password' => 'password',
            'role' => 'owner', 'status' => 'active',
        ]);

        // Categories
        foreach (['Food', 'Transport', 'Home Services', 'Beauty & Health', 'Stores', 'Freelancers'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        // Barangays of Lingayen (please verify against the official PSA or LGU list)
        $barangays = [
            'Aliwekwek', 'Baay', 'Balangobong', 'Balococ', 'Bantayan', 'Basing', 'Capandanan',
            'Domalandan Center', 'Domalandan East', 'Domalandan West', 'Dorongan', 'Dulag', 'Estanza',
            'Lasip', 'Libsong East', 'Libsong West', 'Malawa', 'Malimpuec', 'Maniboc', 'Matalava',
            'Naguelguel', 'Namolan', 'Pangapisan North', 'Pangapisan Sur', 'Poblacion', 'Quibaol',
            'Rosario', 'Sabangan', 'Talogtog', 'Tonton', 'Tumbar', 'Wawa',
        ];
        foreach ($barangays as $name) {
            Barangay::firstOrCreate(['name' => $name]);
        }

        // Emergency numbers: only the national hotline is filled in.
        // Add the barangay hall, health center, and police numbers from the admin panel.
        EmergencyContact::firstOrCreate(
            ['name' => 'National Emergency Hotline', 'number' => '911'],
            ['type' => 'Emergency']
        );

        // Sample listings: fake names and numbers for demo only. Delete them from the admin panel.
        $samples = [
            ['Aling Nena’s Home Bakery', 'Food', 'Poblacion', '09000000011', 'Pandesal, ensaymada, and cakes. Taking orders.'],
            ['Mang Ben Tricycle Service', 'Transport', 'Libsong East', '09000000012', 'Rides around town and to the market.'],
            ['Kuya Rey Aircon Repair', 'Home Services', 'Maniboc', '09000000013', 'Aircon and fridge cleaning and repair.'],
            ['Ate Joy Nail & Lash', 'Beauty & Health', 'Poblacion', '09000000014', 'Manicure, pedicure, and lashes. Home service available.'],
            ['Aling Cora’s Store', 'Stores', 'Tonton', '09000000015', 'Sari-sari store and load. GCash cash-in available.'],
            ['Mika the Virtual Assistant', 'Freelancers', 'Poblacion', '09000000016', 'Data entry, design, and social media help.'],
        ];

        foreach ($samples as [$name, $category, $barangay, $phone, $description]) {
            Business::firstOrCreate(['name' => $name], [
                'owner_id' => $owner->id,
                'category_id' => Category::where('name', $category)->value('id'),
                'barangay_id' => Barangay::where('name', $barangay)->value('id'),
                'phone' => $phone,
                'description' => $description,
                'status' => 'approved',
                'is_verified' => true,
            ]);
        }
    }
}
