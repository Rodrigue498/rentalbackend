<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArchivedDocument;

class ArchivedDocumentSeeder extends Seeder
{
    public function run()
    {
        ArchivedDocument::create([
            'user_id' => 1,
            'document_name' => 'Test Contract.pdf',
            'file_path' => 'documents/test_contract.pdf',
            'archived_at' => now(),
        ]);
    }
}
