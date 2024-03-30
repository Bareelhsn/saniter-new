<?php

namespace App\Http\Controllers\Ajax;

use App\DataTables\MenuDataTable;
use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use App\Models\Menu;
use App\Models\MenuKategori;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AjaxMenuController extends Controller
{
    // Ambil data menu untuk datatable
    public function getMenu(Request $request){
        if ($request->ajax()) {

            // Query menu join kategori
            $menu = Menu::join('menu_kategori', 'menu.id_kategori', '=', 'menu_kategori.id')
                ->select('menu.*','menu_kategori.nama_kategori')
                ->orderBy('menu.order', 'ASC')
                ->get();

            // Return datatables
            return DataTables::of($menu)
                ->addIndexColumn()
                ->editColumn('icon', function($menu) {
                    return "<i class='menu-icon tf-icons bx bx-".$menu->icon."'></i> ".$menu->icon;
                })
                ->addColumn('show', function($row){
                    if ($row->show == 1) {
                        return "<form action=".route('pengaturan.menu.updateShow', $row->id)." method='POST'>".csrf_field().method_field('PUT')."
                                <button type='button' class='btn btn-success btn-sm confirm-edit-show'>Tampil</button>
                            </form>";
                    }else{
                        return "<form action=".route('pengaturan.menu.updateShow', $row->id)." method='POST'>".csrf_field().method_field('PUT')."
                            <button type='button' class='btn btn-danger btn-sm confirm-edit-show'>Tidak Tampil</button>
                        </form>";
                    }
                })
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete
                    $btn = "<button data-bs-toggle='modal' data-bs-target='#modalEditMenu' class='btn btn-warning btn-sm d-inline me-1' data-id='".$row->id."' onclick='editData(this)'>Ubah</button>";
                    $btn = $btn."<form action=".route('pengaturan.menu.delete', $row->id)." method='POST' class='d-inline'>".csrf_field().method_field('DELETE')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Hapus</button></form>";
                    return $btn;
                })
                ->escapeColumns('icon')
                ->rawColumns(['action','show'])
                ->make(true);
        }
    }

    // Ambil data menu untuk datatable
    public function getMenuEdit(Request $request){
        if ($request->ajax()) {
            $menu = Menu::find($request->id);
            return response()->json([
                'status' => 'success',
                'data' => $menu
            ]);
        }
    }

    // Ambil data submenu untuk datatable
    public function getSubMenu(Request $request){
        if ($request->ajax()) {

            // Query menu join kategori
            $menu = SubMenu::join('menu', 'sub_menu.id_menu', '=', 'menu.id')
                ->select('sub_menu.*','menu.judul AS judul_menu')
                ->orderBy('sub_menu.order', 'ASC')
                ->get();

            // Return datatables
            return DataTables::of($menu)
                ->addIndexColumn()
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete
                    $btn = "<button data-bs-toggle='modal' data-bs-target='#modalSubMenuEdit' class='btn btn-warning btn-sm d-inline me-1' data-id='".$row->id."' onclick='editData(this)'>Ubah</button>";
                    $btn = $btn."<form action=".route('pengaturan.submenu.delete', $row->id)." method='POST' class='d-inline'>".csrf_field().method_field('DELETE')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Hapus</button></form>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // Ambil data submenu untuk datatable
    public function getSubMenuEdit(Request $request){
        if ($request->ajax()) {
            $menu = SubMenu::find($request->id);
            return response()->json([
                'status' => 'success',
                'data' => $menu
            ]);
        }
    }

    // Ambil data submenu untuk datatable
    public function getKategoriMenu(Request $request){
        if ($request->ajax()) {

            // Query menu join kategori
            $menu = KategoriMenu::orderBy('id', 'DESC')->get();

            // Return datatables
            return DataTables::of($menu)
                ->addIndexColumn()
                ->addColumn('show', function($row){
                    if ($row->show == 1) {
                        return "
                            <form action=".route('pengaturan.kategorimenu.updateShow', $row->id)." method='POST'>".csrf_field().method_field('PUT')."
                                <button type='button' class='btn btn-success btn-sm confirm-edit-show'>Tampil</button>
                            </form>
                        ";
                    }else{
                        return "
                        <form action=".route('pengaturan.kategorimenu.updateShow', $row->id)." method='POST'>".csrf_field().method_field('PUT')."
                            <button type='button' class='btn btn-danger btn-sm confirm-edit-show'>Tidak Tampil</button>
                        </form>
                        ";
                    }
                })
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete
                    $btn = "<button data-bs-toggle='modal' data-bs-target='#modalKategoriMenuEdit' class='btn btn-warning btn-sm d-inline me-1' data-id='".$row->id."' onclick='editData(this)'>Ubah</button>";
                    $btn = $btn."<form action=".route('pengaturan.kategorimenu.delete', $row->id)." method='POST' class='d-inline'>".csrf_field().method_field('DELETE')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Hapus</button></form>";
                    return $btn;
                })
                ->rawColumns(['action','show'])
                ->make(true);
        }
    }

    // Ambil data submenu untuk datatable
    public function getKategoriMenuEdit(Request $request){
        if ($request->ajax()) {
            $menu = KategoriMenu::find($request->id);
            return response()->json([
                'status' => 'success',
                'data' => $menu
            ]);
        }
    }
}
