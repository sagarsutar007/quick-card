<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

use App\Helpers\ActivityLogger;
use App\Models\Block;
use App\Models\District;
use App\Models\Cluster;

class ClusterController extends Controller
{
    public function index() {
        $districts = District::where('status', 1)->orderBy('name')->get();
        $firstDistrictBlocks = $districts->isNotEmpty() ? Block::where('district_id', $districts->first()->id)->where('status', 1)->orderBy('name')->get() : collect();
        return view('cluster.index', compact('districts', 'firstDistrictBlocks'));
    }

    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $cluster = Cluster::with('creator', 'block.district')
                ->join('blocks', 'clusters.block_id', '=', 'blocks.id')
                ->join('districts', 'blocks.district_id', '=', 'districts.id')
                ->select('clusters.*', 'blocks.name as block_name', 'districts.name as district_name');

            return DataTables::of($cluster)
                ->addIndexColumn()

                ->editColumn('district', function ($row) {
                    return $row->district_name ?? 'N/A';
                })
                ->orderColumn('district', 'districts.name $1')

                ->editColumn('block', function ($row) {
                    return $row->block_name ?? 'N/A';
                })
                ->orderColumn('block', 'blocks.name $1')

                ->editColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                ->editColumn('created_by', function ($row) {
                    return $row->creator ? $row->creator->name : 'N/A';
                })

                ->editColumn('created_at', function ($row) {
                    return optional($row->created_at)->format('d M Y h:i A');
                })

                ->editColumn('updated_at', function ($row) {
                    return optional($row->updated_at)->format('d M Y h:i A');
                })

                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $actions = '<div class="d-inline-flex gap-1">';
                    
                    if ($user->can('view cluster')) {
                        $actions .= '<a href="#" class="btn btn-sm btn-success" title="View"><i class="bi bi-eye"></i></a>';
                    }
                    
                    if ($user->can('edit cluster')) {
                        $actions .= '
                            <button class="btn btn-sm btn-info edit-btn" 
                                data-id="' . $row->id . '" 
                                data-name="' . e($row->name) . '" 
                                data-description="' . e($row->description) . '"
                                data-district="' . e($row->block->district->id ?? '') . '"
                                data-block="' . e($row->block_id) . '"
                                data-status="' . e($row->status) . '"
                                title="Edit"
                            >
                                <i class="bi bi-pencil"></i>
                            </button>';
                    }
                    
                    if ($user->can('delete cluster')) {
                        $actions .= '
                            <form action="' . route('blocks.delete', $row->id) . '" method="POST" class="d-inline delete-form">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>';
                    }

                    $actions .= '</div>';
                    return $actions;
                })

                ->rawColumns(['status', 'action'])
                ->make(true);

        }
    }

    public function createCluster(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'block_id' => 'required|exists:blocks,id',
            'status' => 'required|in:0,1',
        ]);
        $validated['created_by'] = Auth::id();
        $cluster = Cluster::create($validated);
        ActivityLogger::log('Create Cluster', 'Created ' . str_ireplace(' cluster', '', $validated['name']) . ' cluster successfully');
        return redirect()->back()->with('success', 'Cluster created successfully.');
    }

    public function updateCluster(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'block_id' => 'required|exists:blocks,id',
            'status' => 'required|in:0,1',
        ]);
        $validated['updated_by'] = Auth::id();
        $cluster = Cluster::findOrFail($id);
        $cluster->update($validated);
        ActivityLogger::log('Update Cluster', 'Updated cluster: ' . $cluster->name);
        return redirect()->back()->with('success', 'Cluster updated successfully.');
    }

    public function deleteCluster($id)
    {
        $cluster = Cluster::findOrFail($id);
        $cluster->delete();
        ActivityLogger::log('Delete Cluster', 'Deleted cluster: ' . $cluster->name);
        return redirect()->back()->with('success', 'Cluster deleted successfully.');
    }

    public function getClustersByBlock($block_id)
    {
        $clusters = Cluster::where('block_id', $block_id)
                    ->where('status', 1)
                    ->orderBy('name')
                    ->get(['id', 'name']);

        return response()->json($clusters);
    }
}
