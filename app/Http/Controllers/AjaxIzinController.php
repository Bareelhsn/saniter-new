<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\JumlahIzin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;

class AjaxIzinController extends Controller
{
    // Ambil data menu untuk datatable
    public function getJumlahIzin(Request $request){
        if ($request->ajax()) {

            // Mengambil user sesuai regional
            $userSesuaiRegional = User::select('id','name')->where('regional_id', $request->id)->where('role_id', 2)->get();

            // Variable array
            $jumlahIzin = array();

            // Foreach data user sesuai regional
            foreach ($userSesuaiRegional as $item) {

                // ambil data jumlah izin dimana sesuai dengan $userSesuaiRegional id
                $data = JumlahIzin::select('jumlah_izin.id as jumlahIzin_id','users.name','users.id','jumlah_izin.*')
                    ->where('user_id', $item->id)
                    ->join('users','jumlah_izin.user_id','=','users.id')
                    ->first();

                if ($data) {
                    array_push($jumlahIzin, $data);
                }
            }

            // return response()->json($jumlahIzin);

            // Return datatables
            return DataTables::of(Collection::make($jumlahIzin))
                ->addIndexColumn()
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete.
                    $btn = '';
                    if (auth()->user()->can('jumlah izin_update')) {
                        $btn = "<a class='btn btn-warning btn-sm d-inline me-1' href='".route('lokasi.edit', $row['jumlahIzin_id'])."' >Ubah</a>";
                    }
                    if (auth()->user()->can('jumlah izin_delete')) {
                        $btn = $btn."<form action=".route('lokasi.delete', $row['jumlahIzin_id'])." method='POST' class='d-inline'>".csrf_field().method_field('DELETE')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Hapus</button></form>";
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
            ->make(true);
        }
    }
}
