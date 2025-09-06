<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = [
            // Operator documents
            ['name' => 'Barangay Clearance', 'applies_to' => 'operator'],
            ['name' => 'Police Clearance', 'applies_to' => 'operator'],
            ['name' => 'NBI Clearance', 'applies_to' => 'operator'],
            ['name' => 'Medical Certificate', 'applies_to' => 'operator'],
            ['name' => 'Tax Identification Number (TIN)', 'applies_to' => 'operator'],
            ['name' => 'Business Permit', 'applies_to' => 'operator'],
            ['name' => 'Valid ID (Government Issued)', 'applies_to' => 'operator'],
            
            // Driver documents
            ['name' => 'Professional Driver\'s License', 'applies_to' => 'driver'],
            ['name' => 'Barangay Clearance', 'applies_to' => 'driver'],
            ['name' => 'Police Clearance', 'applies_to' => 'driver'],
            ['name' => 'NBI Clearance', 'applies_to' => 'driver'],
            ['name' => 'Medical Certificate', 'applies_to' => 'driver'],
            ['name' => 'Valid ID (Government Issued)', 'applies_to' => 'driver'],
        ];

        foreach ($documentTypes as $docType) {
            DocumentType::create($docType);
        }
    }
}
