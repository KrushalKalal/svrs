<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lead;
use App\Models\DropdownField;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function leadListApi()
    {
        $leads = Lead::with('latestMeeting')->withCount('meetings')->empScope()->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $leads
        ]);
    }
    public function createLeadApi()
    {        
        $propertyTypes = DropdownField::with('dropdowns')->where('name', 'Property Type')->first()?->dropdowns->pluck('value') ?? collect();
        $propertyStatus = DropdownField::with('dropdowns')->where('name', 'Property Status')->first()?->dropdowns->pluck('value') ?? collect();
        $areaName = DropdownField::with('dropdowns')->where('name', 'Area Name')->first()?->dropdowns->pluck('value') ?? collect();
        $leadSources = DropdownField::with('dropdowns')->where('name', 'Lead Source')->first()?->dropdowns->pluck('value') ?? collect();
        $property = Property::select('id', 'title')->get();

        return response()->json([
            'success' => true,
            'message' => 'Create lead master data loaded successfully',
            'data' => [
                'propertyTypes'   => $propertyTypes,
                'propertyStatus'  => $propertyStatus,
                'areaName'        => $areaName,
                'leadSources'     => $leadSources,
                'property'        => $property,
            ]
        ]);
    }
  
    public function StoreLeadApi(Request $request)
    {
        $rules = [
            'lead_emp_id'      => 'required',
            'project_id'       => 'required|exists:properties,id',
            'full_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'mobile'           => 'required|string|max:10',
            'property_type'    => 'required|string',
            'property_status'  => 'required|string',
            'area_name'        => 'required|string',
            'budget_min'       => 'required|numeric|min:1',
            'budget_max'       => 'required|numeric|gte:budget_min',
            'lead_source'      => 'required|string',
            'status'           => 'required|in:Active,Inactive',
            'property_details' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }
        $property = Property::findOrFail($request->project_id);
        try {
            $lead = Lead::create([
                'lead_emp_id'      => $request->lead_emp_id,
                'project_id'       => $request->project_id,
                'project_name'     => $property->title,
                'full_name'        => $request->full_name,
                'email'            => $request->email,
                'mobile'           => $request->mobile,
                'property_type'    => $request->property_type,
                'property_status'  => $request->property_status,
                'area_name'        => $request->area_name,
                'budget_min'       => $request->budget_min,
                'budget_max'       => $request->budget_max,
                'lead_source'      => $request->lead_source,
                'status'           => $request->status,
                'property_details' => $request->property_details,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully',
                'data'    => [
                    'lead_id' => $lead->id
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create lead',
            ], 500);
        }
    }
}
