<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use App\Models\Menu;
use App\Models\MenuKategori;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuController extends Controller
{
    public function index(){
        $kategori = KategoriMenu::select('id','nama_kategori')->get(); // Select menu kategori
        return view('pengaturan.menu.index', compact('kategori')); // Passing data ke view
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[ // Validasi request dari form tambah menu
            'id_kategori' => 'required',
            'judul' => 'required|unique:menu,judul',
            'order' => 'required',
            'url' => 'required',
            'icon' => 'required'
        ], ['id_kategori.required' => 'kategori wajib diisi.']);


        if ($validator->fails()) { // Jika validasi gagal
            Session::flash('modalAdd', 'error');
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali membawa error dan old input
        }

        Menu::create([ // Insert data baru pada database
            'id_kategori' => $request->id_kategori, // Ambil request sesuai name input
            'judul' => $request->judul,
            'order' => $request->order,
            'url' => trim($request->url),
            'icon' => trim($request->icon),
            'show' => 1
        ]);

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::back();
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[ // Validasi request dari form tambah menu
            'id_kategori' => 'required',
            'judul' => 'required|unique:menu,judul,'.$id,
            'order' => 'required',
            'url' => 'required',
            'icon' => 'required'
        ], ['id_kategori.required' => 'kategori wajib diisi.']);

        if ($validator->fails()) { // Jika validasi gagal
            Session::flash('modalEdit', 'error');
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali
        }

        $menu = Menu::find($id);
        $menu->update([
            'id_kategori' => $request->id_kategori,
            'judul' => $request->judul,
            'order' => $request->order,
            'url' => trim($request->url),
            'icon' => trim($request->icon)
        ]);

        toast('Data berhasil tersimpan!', 'success'); // Toast
        return Redirect::back(); // Return kembali
    }

    public function updateShow($id){
        $menu = Menu::find($id);
        $data = [
            'show' => $menu->show == 1 ? 0 : 1 // Jika value 1 maka ubah ke 0, dan sebaliknya
        ];

        $menu->update($data);
        toast('Data berhasil tersimpan!', 'success');
        return Redirect::route('pengaturan.menu.index');
    }

    public function delete($id){ // Id pada parameter url

        $menu = Menu::find($id); // Cari menu berdasarkan id
        $subMenu = SubMenu::where('id_menu', $id); // Cari sub menu berdasarkan id_menu FK
        $permission = Permission::where('id_menu', $id);

        $roles = Role::all();
        $permissions = Permission::where('id_menu', $id)->get();

        $roles->revokePermissionTo($permissions);
        $permissions->delete();

        // Delete menu tersebut dan sub menu
        $menu->delete();
        $subMenu->delete();
        $permission->delete();

        // Redirect kembali
        toast('Data berhasil terhapus!', 'success');
        return Redirect::back();
    }
}
