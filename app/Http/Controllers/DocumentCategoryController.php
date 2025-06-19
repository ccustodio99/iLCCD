<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentCategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $categories = DocumentCategory::paginate($perPage)->withQueryString();

        return view('settings.document-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('settings.document-categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:document_categories,name',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        DocumentCategory::create($data);

        return redirect()->route('document-categories.index');
    }

    public function edit(DocumentCategory $documentCategory)
    {
        return view('settings.document-categories.edit', compact('documentCategory'));
    }

    public function update(Request $request, DocumentCategory $documentCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:document_categories,name,'.$documentCategory->id,
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? false;

        $documentCategory->update($data);

        return redirect()->route('document-categories.index');
    }

    public function destroy(DocumentCategory $documentCategory)
    {
        DB::transaction(function () use ($documentCategory) {
            $documents = Document::where('document_category_id', $documentCategory->id)->get();

            foreach ($documents as $document) {
                foreach ($document->versions as $version) {
                    Storage::delete($version->path);
                }

                $document->delete();
            }

            $documentCategory->delete();
        });

        return redirect()->route('document-categories.index');
    }
}
