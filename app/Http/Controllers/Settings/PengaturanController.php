<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PengaturanController extends Controller
{
    public function index(){
        $permissions = Permission::select('id_menu')->get();

        $id = [];
        foreach ($permissions as $item) {
            array_push($id, $item->id_menu);
        }

        $menu = Menu::whereNotIn('id',$id)->get();

        return view('pengaturan.index');
    }
}
