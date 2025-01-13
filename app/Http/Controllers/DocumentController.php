<?php

namespace App\Http\Controllers;

use App\Models\ArchivedDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    // Archive a document
    public function archiveDocument(Request $request)
    {
        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $file = $request->file('document');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName);

        ArchivedDocument::create([
            'user_id' => Auth::id(),
            'document_name' => $fileName,
            'file_path' => $filePath,
            'archived_at' => now(),
        ]);

        return response()->json(['message' => 'Document archived successfully']);
    }

    // List all archived documents for the logged-in user
    public function listDocuments()
    {
        $documents = ArchivedDocument::where('user_id', Auth::id())->get();

        return response()->json($documents);
    }

    // Download an archived document
    public function downloadDocument($id)
    {
        $document = ArchivedDocument::findOrFail($id);

        if ($document->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return Storage::download($document->file_path, $document->document_name);
    }
    
}
