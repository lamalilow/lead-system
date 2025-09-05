<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lead\StoreRequest;
use App\Http\Requests\Lead\UpdateRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function store(StoreRequest $request) : JsonResponse
    {
        $lead = Lead::create($request->validated());
        return response()->json($lead, 201);
    }

    public function index(Request $request) : JsonResponse
    {
        $query = Lead::with('source', 'comments.user')->withTrashed();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source_id')) {
            $query->where('source_id', $request->source_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('phone', 'LIKE', "%$search%");
            });
        }

        $leads = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10);

        return response()->json($leads);
    }

    public function show($id) : JsonResponse
    {
        $lead = Lead::with('source', 'comments.user')->withTrashed()->findOrFail($id);
        return response()->json($lead);
    }

    public function update(UpdateRequest $request, $id) : JsonResponse
    {
        $request->validated();
        $lead = Lead::withTrashed()->findOrFail($id);


        $lead->update($request->all());
        return response()->json($lead);
    }

    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();
        return response()->noContent();
    }
}
