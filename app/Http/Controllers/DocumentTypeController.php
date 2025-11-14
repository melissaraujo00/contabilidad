<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Http\Requests\StoreDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentTypes = DocumentType::paginate(10);

        return view('documentTypes.index', compact('documentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('documentTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentTypeRequest $request)
    {
        // datos validados desde FormRequest
        $data = $request->validated();

        DocumentType::create([
            'nombre' => $data['nombre'],
        ]);

        return redirect()
            ->route('documentTypes.index')
            ->with('success', 'Tipo de documento creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        return view('documentTypes.show', compact('documentType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        return view('documentTypes.edit', compact('documentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentTypeRequest $request, DocumentType $documentType)
    {
        $data = $request->validated();

        $documentType->update([
            'nombre' => $data['nombre'],
        ]);

        return redirect()
            ->route('documentTypes.index')
            ->with('success', 'Tipo de documento actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return redirect()
            ->route('documentTypes.index')
            ->with('success', 'Tipo de documento eliminado correctamente');
    }
}
