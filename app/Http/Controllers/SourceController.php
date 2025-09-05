<?php

namespace App\Http\Controllers;

use App\Http\Requests\Source\StoreRequest;
use App\Http\Requests\Source\UpdateRequest;
use App\Models\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function index()
    {
        return response()->json(Source::all());
    }

    public function store(StoreRequest $request)
    {
        $source = Source::create($request->validated());
        return response()->json($source, 201);
    }

    public function update(UpdateRequest $request, $id)
    {
        $source = Source::findOrFail($id);
        $source->update($request->validated());
        return response()->json($source);
    }

    public function destroy($id)
    {
        $source = Source::findOrFail($id);
        $source->delete();
        return response()->noContent();
    }
}
