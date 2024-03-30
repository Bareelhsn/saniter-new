<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AjaxLokasiController extends Controller
{
    // Ambil data menu untuk datatable
    public function getLokasi(Request $request){
        if ($request->ajax()) {

            // Query menu join kategori
            $lokasi = Lokasi::select('*','lokasi.id as lokasi_id','regional.nama as regional_name')
            ->leftjoin('regional', 'regional.id', '=', 'lokasi.regional_id')
            ->get();

            // Return datatables
            return DataTables::of($lokasi)
                ->addIndexColumn()
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete.
                    $btn = '';
                    if (auth()->user()->can('lokasi_update')) {
                        $btn = "<a class='btn btn-warning btn-sm d-inline me-1' href='".route('lokasi.edit', $row->lokasi_id)."' >Ubah</a>";
                    }
                    if (auth()->user()->can('lokasi_delete')) {
                        $btn = $btn."<form action=".route('lokasi.delete', $row->lokasi_id)." method='POST' class='d-inline'>".csrf_field().method_field('DELETE')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Hapus</button></form>";
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // Ambil data regional untuk datatable
    // public function getRegionalEdit(Request $request){
    //     if ($request->ajax()) {
    //         $regional = Regional::find($request->id);
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $regional
    //         ],200);
    //     }
    // }
}
