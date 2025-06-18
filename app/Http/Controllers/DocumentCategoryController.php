<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;

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
        $documentCategory->delete();

        return redirect()->route('document-categories.index');
    }
}
