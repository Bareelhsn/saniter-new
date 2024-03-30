<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AjaxShiftController extends Controller
{
    // Ambil data menu untuk datatable
    public function getShift(Request $request){
        if ($request->ajax()) {

            // Query menu join kategori
            $shift = Shift::all();

            // Return datatables
            return DataTables::of($shift)
                ->addIndexColumn()
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete.
                    $btn = '';
                    if (auth()->user()->can('shift_update')) {
                        $btn = "<button data-bs-toggle='modal' data-bs-target='#modalEditShift' class='btn btn-warning btn-sm d-inline me-1' data-id='".$row->id."' onclick='editData(this)'>Ubah</button>";
                    }
                    if (auth()->user()->can('shift_delete')) {
                        $btn = $btn."<form action=".route('shift.destroy', $row->id)." method='POST' class='d-inline'>".csrf_field().method_field('DELETE')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Hapus</button></form>";
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // Ambil data regional untuk datatable
    public function getShiftEdit(Request $request){
        if ($request->ajax()) {
            $shift = Shift::find($request->id);
            return response()->json([
                'status' => 'success',
                'data' => $shift
            ],200);
        }
    }
}
