<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Regional;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AjaxRegionalController extends Controller
{
    // Ambil data menu untuk datatable
    public function getRegional(Request $request){
        if ($request->ajax()) {

            // Query menu join kategori
            $regional = Regional::select('id','nama')->get();

            // Return datatables
            return DataTables::of($regional)
                ->addIndexColumn()
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete.
                    $btn = '';
                    if (auth()->user()->can('regional_update')) {
                        $btn = "<a class='btn btn-warning btn-sm d-inline me-1' href='".route('regional.edit', $row->id)."' >Ubah</a>";
                    }
                    if (auth()->user()->can('regional_delete')) {
                        $btn = $btn."<form action=".route('regional.delete', $row->id)." method='POST' class='d-inline'>".csrf_field().method_field('DELETE')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Hapus</button></form>";
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // Ambil data regional untuk datatable
    public function getRegionalEdit(Request $request){
        if ($request->ajax()) {
            $regional = Regional::find($request->id);
            return response()->json([
                'status' => 'success',
                'data' => $regional
            ],200);
        }
    }

    // Ambil data map regional untuk datatable
    public function getRegionalMap(Request $request){
        if ($request->ajax()) {
            $regional = Regional::select('nama','latitude','longitude')->find($request->id);
            return response()->json([
                'status' => 'success',
                'data' => $regional
            ],200);
        }
    }

    // Ambil data map regional untuk datatable
    public function getAllRegionalMap(Request $request){
        if ($request->ajax()) {
            $regional = Regional::select('nama','latitude','longitude')->get();
            return response()->json([
                'status' => 'success',
                'data' => $regional
            ],200);
        }
    }
}
