<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class AjaxUserController extends Controller
{
    public function getUser(Request $request){
        if ($request->ajax()) {
            $users = User::join('regional', 'users.regional_id', '=', 'regional.id')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->select('users.id','users.name', 'regional.nama as regional_name', 'email', 'is_active', 'roles.name as roles_name')
                ->get(); // Ambil semua user

            // Return datatables
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('is_active', function($row){ // Tambah kolom action untuk button edit dan delete
                    if ($row->roles_name == 'Admin') {
                        return "<button type='button' class='btn btn-success btn-sm' disabled >Aktif</button>";
                    }else{
                        if (auth()->user()->can('user_update')) {
                            if ($row->is_active == 1) {
                                return "
                                    <form action=".route('user.updateIsActive', $row->id)." method='POST'>".csrf_field().method_field('PUT')."
                                        <button type='button' class='btn btn-success btn-sm confirm-edit-is-active'>Aktif</button>
                                    </form>
                                ";
                            }else{
                                return "
                                <form action=".route('user.updateIsActive', $row->id)." method='POST'>".csrf_field().method_field('PUT')."
                                    <button type='button' class='btn btn-danger btn-sm confirm-edit-is-active'>Tidak Aktif</button>
                                </form>
                                ";
                            }
                        }else{
                            if ($row->is_active == 1) {
                                return "<button type='button' class='btn btn-success btn-sm' disabled >Aktif</button>";
                            }else{
                                return "<button type='button' class='btn btn-danger btn-sm' disabled >Tidak Aktif</button>";
                            }
                        }
                    }
                })
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete
                    $btn = "<button type='button' class='btn btn-info btn-sm me-1' data-id='".$row->id."' onclick='detailData(this)'> Detail </button>";
                    if (auth()->user()->can('user_update')) {
                        $btn = $btn."<a href='".route('user.edit', $row->id)."' class='btn btn-warning btn-sm'>Ubah</button></a>";
                    }
                    if (auth()->user()->can('user_delete')) {
                        if($row->roles_name != 'Admin'){
                            $btn = $btn."<form action=".route('user.delete', $row->id)." method='POST' class='d-inline'>".csrf_field().method_field('DELETE')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Hapus</button></form>";
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action','is_active'])
                ->make(true);
        }
    }

    public function getUserDetail(Request $request)
    {
        if ($request->ajax()) {
            $user = User::select('users.name','users.nik','users.telp','users.foto','users.ttd','users.alamat_ktp','users.alamat_dom','regional.nama as regional_name', 'email', 'is_active', 'roles.name as roles_name','lokasi.nama_bandara as bandara','lokasi.lokasi_proyek as lokasi')
            ->join('regional', 'users.regional_id', '=', 'regional.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('lokasi', 'users.lokasi_id', '=', 'lokasi.id')
            ->where('users.id', $request->id)
            ->first();

            $modal = view('components.modals.detail-user', compact('user'))->render();

            return response()->json([
                'status' => 'success',
                'data' => $user,
                'modal' => $modal
            ]);
        }
    }
}
