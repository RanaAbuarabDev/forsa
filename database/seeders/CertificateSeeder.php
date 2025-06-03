<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $certificates = [
            'AWS Certified Solutions Architect',
            'Google Data Engineer',
            'Certified Scrum Master',
            'Microsoft Azure Fundamentals',
            'Cisco CCNA',
            'Project Management Professional (PMP)',
            'Certified Ethical Hacker (CEH)',
            'Oracle Certified Java Programmer',
            'Adobe Certified Expert',
            'CompTIA Security+',
        ];

        foreach ($certificates as $title) {
            Certificate::create([
                'name' => $title,
            ]);
        }
    }
}
