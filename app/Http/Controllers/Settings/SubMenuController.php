<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SubMenuController extends Controller
{
    public function index(){
        $menu = Menu::select('menu.id', 'menu.judul')->orderBy('id', 'DESC')->get();
        return view('pengaturan.sub-menu.index', compact('menu'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[ // Validasi request dari form tambah menu
            'id_menu' => 'required',
            'judul' => 'required',
            'order' => 'required',
            'url' => 'required',
        ], ['id_menu.required' => 'menu wajib diisi.']);


        if ($validator->fails()) { // Jika validasi gagal
            toast('Mohon periksa form kembali!', 'error'); // Toast
            Session::flash('modalAdd', 'error');
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali membawa error dan old input
        }

        SubMenu::create([ // Insert data baru pada database
            'id_menu' => $request->id_menu, // Ambil request sesuai name input
            'judul' => $request->judul,
            'order' => $request->order,
            'url' => trim($request->url),
        ]);

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::back();
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[ // Validasi request dari form tambah menu
            'id_menu' => 'required',
            'judul' => 'required',
            'order' => 'required',
            'url' => 'required'
        ], ['id_menu.required' => 'menu wajib diisi.']);

        if ($validator->fails()) { // Jika validasi gagal
            Session::flash('modalEdit', 'error');
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali
        }

        $menu = SubMenu::find($id); // Cari sub menu berdasarkan ID
        $menu->update([
            'id_menu' => $request->id_menu,
            'judul' => $request->judul,
            'order' => $request->order,
            'url' => trim($request->url),
        ]);

        toast('Data berhasil tersimpan!', 'success'); // Toast
        return Redirect::back(); // Return kembali
    }

    public function delete($id){ // ID parameter url

        $subMenu = SubMenu::where('id', $id); // Cari sub menu berdasarkan id

        // Delete sub menu
        $subMenu->delete();

        // Redirect kembali
        toast('Data berhasil terhapus!', 'success');
        return Redirect::back();
    }
}
