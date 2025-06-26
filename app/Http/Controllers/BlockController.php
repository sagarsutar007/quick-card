<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\District;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    public function index() {
        $districts = District::where('status', 1)->orderBy('name')->get();
        return view('block.index', compact('districts'));
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $blocks = Block::with('creator', 'district')->select('blocks.*');

            return DataTables::of($blocks)
                ->addIndexColumn()
                ->editColumn('district', function ($row) {
                    return $row->district ? $row->district->name : 'N/A';
                })
                ->editColumn('status', function ($row) {
                    return $row->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->editColumn('created_by', function ($row) {
                    return $row->creator ? $row->creator->name : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y h:i A') : '';
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('d M Y h:i A') : '';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="#" class="btn btn-sm btn-success">View</a>
                        <button class="btn btn-sm btn-info edit-btn" 
                            data-id="'.$row->id.'" 
                            data-name="'.e($row->name).'" 
                            data-description="'.e($row->description).'"
                            data-district="'.e($row->district_id).'"
                            data-status="'.e($row->status).'"
                        >Edit</button>
                        <form action="'.route('blocks.delete', $row->id).'" method="POST" class="d-inline delete-form">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function createBlock(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['created_by'] = Auth::id();

        $block = Block::create($validated);

        ActivityLogger::log('Create Block', 'Created ' . str_ireplace(' block', '', $validated['name']) . ' block successfully');
        
        return redirect()->back()->with('success', 'Block created successfully.');
    }

    public function updateBlock(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'district_id' => 'required|exists:districts,id',
            'status' => 'required|in:0,1',
        ]);

        $validated['updated_by'] = Auth::id();

        $block = Block::findOrFail($id);
        $block->update($validated);

        ActivityLogger::log('Update Block', 'Updated block: ' . $block->name);

        return redirect()->back()->with('success', 'Block updated successfully.');
    }

    public function deleteBlock($id)
    {
        $block = Block::findOrFail($id);
        $block->delete();

        ActivityLogger::log('Delete Block', 'Deleted block: ' . $block->name);

        return redirect()->back()->with('success', 'Block deleted successfully.');
    }

    public function getBlocksByDistrict($district_id)
    {
        $blocks = Block::where('district_id', $district_id)
                    ->where('status', 1)
                    ->orderBy('name')
                    ->get(['id', 'name']);

        return response()->json($blocks);
    }
}
