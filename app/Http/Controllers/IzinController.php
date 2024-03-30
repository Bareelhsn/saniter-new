<?php

namespace App\Http\Controllers;

use App\Models\JumlahIzin;
use App\Models\Regional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class IzinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('izin/index');
    }

    public function create()
    {
        return view('izin/create');
    }

    public function setting_index()
    {
        $regional = Regional::all();
        $jumlah = JumlahIzin::select('*','users.name as name_user', 'regional.nama  as regional_nama')
            ->leftjoin('users', 'users.id', '=', 'jumlah_izin.user_id')
            ->leftjoin('regional', 'regional.id', '=', 'users.regional_id')
            ->get();

        return view('pengaturan.izin.index', compact('regional','jumlah'));
    }

    public function setting_create()
    {
        $user = User::select('*','users.name as user_name', 'roles.name as role_name','users.id as user_id')
            ->from('users')->where('users.role_id', '=', '2')
            ->leftjoin('roles', 'roles.id', '=', 'users.role_id')
            ->get();
        return view('pengaturan.izin.create', compact('user'));
    }

    public function setting_izin_add(Request $request)
    {
        // Validasi form
        $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'tahun' => 'required',
                'jumlah_izin' => 'required',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali membawa error dan old input
        }

        // Insert data lokasi baru dari input form
        JumlahIzin::create([
            'user_id' => $request->user_id,
            'tahun' => $request->tahun,
            'jumlah_izin' => $request->jumlah_izin,
        ]);

        toast('Data Lokasi berhasil tersimpan!', 'success');
        return Redirect()->to('/izin/setting'); // Redirect kembali
    }

    public function delete($id)
    {
        // Cari lokasi berdasarkan ID
        $jumlah = JumlahIzin::findOrFail($id);

        // Delete lokasi tersebut
        $jumlah->delete();

        toast('Data berhasil dihapus!', 'success');
        return Redirect()->to('/'); // Redirect kembali
    }
}
