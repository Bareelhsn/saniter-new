<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\User;
use App\Models\Regional;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->_client = new Client([
            'base_uri' => 'https://api.qrm15.com/user/',
            'http_errors' => false,
            'protocols'       => ['http', 'https']
            //   'auth'  => ['public', 'qrm15@bali123']
        ]);
    }

    public function index()
    {
        $users = User::select('*', 'users.name','users.id','users.email','regional.nama as regional_name')
            ->leftjoin('regional', 'regional.id', '=', 'users.regional_id')
            ->get();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        $lokasi = Lokasi::select('lokasi.id','regional.nama','nama_bandara','lokasi_proyek')
            ->join('regional','lokasi.regional_id','=','regional.id')
            ->get();

        $regional = Regional::select('id','nama')
            ->get();

        $roles = Role::select('id','name')
            ->get();

        return view('user.create', compact('regional','roles','lokasi'));
    }

    // Fungsi Simpan Data
    public function store(Request $request)
    {
        $messages = [
            'regional_id.required' => 'regional wajib diisi.',
            'lokasi_id.required' => 'lokasi wajib diisi.',
            'role_id.required' => 'role wajib diisi.',
            'alamat_ktp.required' => 'alamat ktp wajib diisi.',
            'alamat_dom.required' => 'alamat domisili wajib diisi.',
            'telp.required' => 'nomor telepon wajib diisi.',
            'telp.unique' => 'nomor telepon sudah ada sebelumnya.'
        ];

        $validator = Validator::make($request->all(), [
            'regional_id' => 'required',
            'lokasi_id' => 'required',
            'role_id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'nik' => 'required',
            'alamat_ktp' => 'required',
            'alamat_dom' => 'required',
            'telp' => 'required|unique:users,telp',
            'foto' => 'mimes:png,jpg,jpeg|max:2000',
            'ttd' => 'mimes:png,jpg,jpeg|max:2000',
        ],$messages);

        // Jika validasi gagal
        if ($validator->fails()) {
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali membawa error dan old input
        }

        // dd($request->all());
        $user = User::create([
            'regional_id' => $request->regional_id,
            'lokasi_id' => $request->lokasi_id,
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_dom' => $request->alamat_dom,
            'telp' => $request->telp,
            'foto' => $this->imageStore($request->file('foto')) ?? 'user-images/default.jpg',
            'password' => bcrypt('qrm123'),
            'ttd' => 'user-ttd/default.jpg'
        ]);

        $role = Role::findById(($request->role_id));
        $user->assignRole($role);

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::route('user.index'); // Redirect kembali
    }

    // Fungsi Update Data
    public function update(Request $request, $id)
    {
        // Mengambil request dari submit form
        $messages = [
            'regional_id.required' => 'regional wajib diisi.',
            'lokasi_id.required' => 'lokasi wajib diisi.',
            'role_id.required' => 'role wajib diisi.',
            'alamat_ktp.required' => 'alamat ktp wajib diisi.',
            'alamat_dom.required' => 'alamat domisili wajib diisi.',
            'telp.required' => 'nomor telepon wajib diisi.',
            'telp.unique' => 'nomor telepon sudah ada sebelumnya.',
            'ttd.max' => 'tanda tangan maksimal berukuran 2mb.',
            'ttd.mimes' => 'tanda tangan berupa file png, jpg dan jpeg.'
        ];

        $validator = Validator::make($request->all(), [
            'regional_id' => 'required',
            'lokasi_id' => 'required',
            'role_id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'nik' => 'required',
            'alamat_ktp' => 'required',
            'alamat_dom' => 'required',
            'telp' => 'required|unique:users,telp,'.$id,
            'foto' => 'mimes:png,jpg,jpeg|max:2000',
            'ttd' => 'mimes:png,jpg,jpeg|max:2000',
        ],$messages);

        // Jika validasi gagal
        if ($validator->fails()) {
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali membawa error dan old input
        }

        $user = User::find($id); // Where user = $id
        $data = [
            'regional_id' => $request->regional_id,
            'lokasi_id' => $request->lokasi_id,
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_dom' => $request->alamat_dom,
            'telp' => $request->telp,
            'foto' => $this->imageStore($request->file('foto'), $user) ?? 'user-images/default.jpg',
            'ttd' => $this->ttdStore($request->file('ttd'), $user) ?? 'user-ttd/default.jpg',
        ];

        $role = Role::findById(($request->role_id));
        $user->syncRoles($role);
        $user->update($data);

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::route('user.index'); // Redirect kembali
    }

    public function updateIsActive(Request $request, $id)
    {
        $user = User::find($id);
        $data = [
            'is_active' => $user->is_active == 1 ? 0 : 1 // Jika value 1 maka ubah ke 0, dan sebaliknya
        ];

        if ($user->update($data)) {
            if (Auth()->user()->is_active != 1) {
                Auth::logout();
                return Redirect::route('login');
            }else{
                toast('Data berhasil tersimpan!', 'success');
                return Redirect::route('user.index');
            }
        }
    }

    // Fungsi View Edit Data
    public function edit($id)
    {
        $user = User::find($id);

        $lokasi = Lokasi::select('lokasi.id','regional.nama','nama_bandara','lokasi_proyek')
            ->join('regional','lokasi.regional_id','=','regional.id')
            ->get();

        $regional = Regional::select('id','nama')
            ->get();

        $roles = Role::select('id','name')
            ->get();

        return view('user.edit', compact('user','lokasi','regional','roles'));
    }

    // Fungsi Delete Data
    public function delete($id)
    {
        $user = User::findOrFail($id);

        Storage::disk('public')->delete($user->foto);
        Storage::disk('public')->delete($user->ttd);
        $user->delete();

        toast('Data berhasil dihapus!', 'success');
        return Redirect::route('user.index'); // Redirect kembali
    }

    // Fungsi simpan data ke folder
    private function imageStore($image, $user = null)
    {
        // Delete jika foto bukan default.jpg
        if (!empty($user)) {
            if ($user->foto != 'user-images/default.jpg') {
                Storage::disk('public')->delete($user->foto);
            }
        }

        if (!empty($image)) {
            // Masukkan ke folder user-images dengan nama random dan extensi saat upload
            $image = Storage::disk('public')->put('user-images', $image);
            return $image;
        }
    }

    // Fungsi simpan data ke folder
    private function ttdStore($image, $user = null)
    {
        // Delete jika foto bukan default.jpg
        if (!empty($user)) {
            if ($user->ttd != 'user-ttd/default.jpg') {
                Storage::disk('public')->delete($user->ttd);
            }
        }

        if (!empty($image)) {
            // Masukkan ke folder user-images dengan nama random dan extensi saat upload
            $image = Storage::disk('public')->put('user-ttd', $image);
            return $image;
        }
    }
}
