<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\DocumentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);

        $documents = Document::where('user_id', auth()->id())
            ->with('auditTrails.user')
            ->paginate($perPage)
            ->withQueryString();

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function show(Document $document)
    {
        $user = auth()->user();
        if (
            $user->role !== 'admin' &&
            $user->department !== $document->department &&
            $document->user_id !== $user->id
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        $document->load(['versions.uploader', 'auditTrails.user']);
        return view('documents.show', compact('document'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'file' => 'required|file',
        ]);
        $data['user_id'] = $request->user()->id;
        $data['department'] = $request->user()->department;
        $document = Document::create($data);
        $path = $request->file('file')->store('documents');
        DocumentVersion::create([
            'document_id' => $document->id,
            'version' => 1,
            'path' => $path,
            'uploaded_by' => $request->user()->id,
        ]);
        DocumentLog::create([
            'document_id' => $document->id,
            'user_id' => $request->user()->id,
            'action' => 'upload',
        ]);
        return redirect()->route('documents.index');
    }

    public function edit(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $document->load('auditTrails.user');
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'file' => 'nullable|file',
        ]);
        $document->update($data);
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('documents');
            $version = $document->current_version + 1;
            DocumentVersion::create([
                'document_id' => $document->id,
                'version' => $version,
                'path' => $path,
                'uploaded_by' => $request->user()->id,
            ]);
            $document->current_version = $version;
            $document->save();
        }
        DocumentLog::create([
            'document_id' => $document->id,
            'user_id' => $request->user()->id,
            'action' => 'update',
        ]);
        return redirect()->route('documents.index');
    }

    public function destroy(Request $request, Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        foreach ($document->versions as $version) {
            Storage::delete($version->path);
        }
        $document->delete();
        DocumentLog::create([
            'document_id' => $document->id,
            'user_id' => $request->user()->id,
            'action' => 'delete',
        ]);
        return redirect()->route('documents.index');
    }

    public function download(Document $document, DocumentVersion $version)
    {
        if ($version->document_id !== $document->id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $user = auth()->user();
        if (
            $user->role !== 'admin' &&
            $user->department !== $document->department &&
            $document->user_id !== $user->id
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied');
        }

        DocumentLog::create([
            'document_id' => $document->id,
            'user_id' => $user->id,
            'action' => 'download',
        ]);

        return Storage::download($version->path);
    }
}
