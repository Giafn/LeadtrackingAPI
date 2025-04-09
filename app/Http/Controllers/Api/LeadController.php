<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with('histories:id,lead_id,status,changed_at,notes')
            ->orderBy('created_at', 'desc')
            ->select('id', 'name', 'email', 'phone', 'location', 'status', 'due_date', 'created_at')
            ->get();
        return response()->json(['success' => true, 'data' => $leads], 200);
    }

    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email|max:255',
            'phone' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validateData->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $lead = Lead::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone ?? null,
                'location' => $request->location ?? null,
                'status' => 'New',
                'due_date' => $this->calculateDueDate('New'),
            ]);

            $lead->histories()->create([
                'status' => 'New',
                'changed_at' => Carbon::now(),
                'notes' => 'Lead created via Google Form',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create lead',
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'data' => $lead
        ], 201);
    }

    public function updateStatus(Request $request, $leadId)
    {
        $validator = Validator::make($request->all(), [
            'new_status' => 'required|in:Prospect,Proses Dokumen & Legal,Selesai',
            'notes' => 'nullable|string|max:65535',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $lead = Lead::find($leadId);

            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead not found',
                ], 404);
            }

            $lead->status = $request->new_status;
            $lead->due_date = $this->calculateDueDate($request->new_status);
            $lead->save();

            $lead->histories()->create([
                'status' => $request->new_status,
                'changed_at' => Carbon::now(),
                'notes' => $request->notes,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update lead status',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $lead
        ], 200);
    }

    private function calculateDueDate($newStatus)
    {
        $now = Carbon::now();

        switch ($newStatus) {
            case 'Prospect':
                return $now->copy()->addDays(config('setting.lead_status_durations.prospect'));
            case 'Proses Dokumen & Legal':
                return $now->copy()->addDays(config('setting.lead_status_durations.document_and_legal_process'));
            case 'Selesai':
                return $now;
            default:
                return $now->copy()->addDays(config('setting.lead_status_durations.new'));
        }
    }
}
