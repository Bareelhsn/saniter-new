<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KategoriMenuController extends Controller
{
    public function index(){
        return view('pengaturan.kategori-menu.index');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[ // Validasi request dari form tambah menu
            'nama_kategori' => 'required',
            'order' => 'required'
        ], ['nama_kategori.required' => 'nama kategori wajib diisi.']);

        if ($validator->fails()) { // Jika validasi gagal
            toast('Mohon periksa form kembali!', 'error'); // Toast
            Session::flash('modalAdd', 'error');
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali membawa error dan old input
        }

        KategoriMenu::create([ // Insert data baru pada database
            'nama_kategori' => $request->nama_kategori,
            'order' => $request->order, // Ambil request sesuai name input
            'show' => 1
        ]);

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::back();
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[ // Validasi request dari form tambah menu
            'nama_kategori' => 'required',
            'order' => 'required'
        ], ['nama_kategori.required' => 'nama kategori wajib diisi.']);

        if ($validator->fails()) { // Jika validasi gagal
            Session::flash('modalEdit', 'error');
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali
        }

        $menu = KategoriMenu::find($id); // Cari sub menu berdasarkan ID
        $menu->update([
            'nama_kategori' => $request->nama_kategori,
            'order' => $request->order,
        ]);

        toast('Data berhasil tersimpan!', 'success'); // Toast
        return Redirect::back(); // Return kembali
    }

    public function updateShow($id){
        $kategori = KategoriMenu::find($id);
        $data = [
            'show' => $kategori->show == 1 ? 0 : 1 // Jika value 1 maka ubah ke 0, dan sebaliknya
        ];

        $kategori->update($data);
        toast('Data berhasil tersimpan!', 'success');
        return Redirect::route('pengaturan.kategorimenu.index');
    }

    public function delete($id){ // ID parameter url

        $kategori = KategoriMenu::find($id); // Cari sub menu berdasarkan id

        if(Menu::where('id_kategori', $id)->get()->count() > 0)
        {
            // Redirect kembali
            toast('Gagal, terdapat relasi data pada menu!', 'error');
            return Redirect::back();
        }

        // Delete sub menu
        $kategori->delete();

        // Redirect kembali
        toast('Data berhasil terhapus!', 'success');
        return Redirect::back();
    }
}
