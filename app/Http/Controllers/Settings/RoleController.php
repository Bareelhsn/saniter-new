<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(){
        // Masukkan ke dalam collection agar bisa group by, permission join menu
        $permissions = new Collection(Permission::select('name','menu.judul','menu.id')
        ->with('roles')
        ->join('menu', 'permissions.id_menu', '=', 'menu.id')
        ->get());
        $permissions = $permissions->groupBy('judul');

        return view('pengaturan.role.index', compact('permissions'));
    }

    // Fungsi menambah role user route(/assign-role)
    public function indexAssign(){
        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::all();
        $menu = Menu::all();
        return view('pengaturan.role.assign-role', compact('users','roles','permissions','menu'));
    }

    public function create(){
        // Masukkan ke dalam collection agar bisa group by, permission join menu
        $permissions = new Collection(Permission::select('name','menu.judul','menu.id')
        ->with('roles')
        ->join('menu', 'permissions.id_menu', '=', 'menu.id')
        ->get());
        $permissions = $permissions->groupBy('judul');
        return view('pengaturan.role.create', compact('permissions'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[ // Validasi request dari form tambah menu
            'name' => 'required|unique:roles,name',
        ], ['name.required' => 'role wajib diisi.']);

        if ($validator->fails()) { // Jika validasi gagal
            toast('Mohon periksa form kembali!', 'error'); // Toast
            Session::flash('modalAdd', 'error');
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali membawa error dan old input
        }

        // Buat role sesuai request name
        $role = Role::create(['name' => $request->name]);

        // request checkbox berasal dari inputan array, <input name='checkBox[]' />
        if (!empty($request->checkBox)) { // Jika input checkbox tidak kosong
            if (count($request->checkBox) > 0) { // Jika tercentang lebih dari 0
                foreach ($request->checkBox as $item) {
                    $permission = Permission::findByName($item); // Cari nama permision berdasarkan value checkbox
                    $role->givePermissionTo($permission); // Berikan permission pada role yg dibuat
                }
            }
        }

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::route('pengaturan.role.index');
    }

    public function edit($id){
        $role = Role::with('permissions')->findOrFail($id); // Cari role berdasarkan id

        // Masukkan ke dalam collection agar bisa group by, permission join menu
        $permissionsAll = new Collection(Permission::select('name','menu.judul','menu.id')
        ->join('menu', 'permissions.id_menu', '=', 'menu.id')
        ->get());
        $permissionsAll = $permissionsAll->groupBy('judul');

        return view('pengaturan.role.edit', compact('role', 'permissionsAll'));
    }

    // Fungsi mengedit role user route(/assign-role)
    public function editAssign($id)
    {
        $role = Role::select('id','name')->get();
        $user = User::with('roles')->select('users.id','users.name')->find($id);
        $permissionsAll = new Collection(Permission::select('name','menu.judul','menu.id')
        ->join('menu', 'permissions.id_menu', '=', 'menu.id')
        ->get());
        $permissionsAll = $permissionsAll->groupBy('judul');

        return view('pengaturan.role.assign-role-edit', compact('role','user','permissionsAll'));
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[ // Validasi request dari form tambah menu
            'name' => 'required|unique:roles,name,'.$id,
        ], ['name.required' => 'role wajib diisi.']);

        if ($validator->fails()) { // Jika validasi gagal
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali
        }

        $role = Role::with('permissions')->find($id); // Cari role dengan permission berdasarkan id
        $role->update([
            'name' => $request->name, // Update nama role tersebut
        ]);

        $newPermission = array();
        if ($request->checkBox != null) {
            foreach ($request->checkBox as $item) { // Ambil value checkbox berdasarkan nama itemnya
                $permission = Permission::findByName($item); // Kemudian cari nama item tersebut dan sesuaikan dengan nama permission yg ada
                array_push($newPermission, $permission);
            }
        }

        $role->syncPermissions($newPermission); // Cocokkan role dengan permission

        toast('Data berhasil tersimpan!', 'success'); // Toast
        return Redirect::route('pengaturan.role.index'); // Return kembali
    }

    // Fungsi update role user route(/assign-role)
    public function updateAssign(Request $request, $id){
        $validator = Validator::make($request->all(),[ // Validasi request dari form tambah menu
            'user_name' => 'required|unique:roles,name,'.$id,
            'role_name' => 'required',
        ], ['user_name.required' => 'nama user wajib diisi.', 'role_name.required' => 'role wajib diisi.']);

        if ($validator->fails()) { // Jika validasi gagal
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali
        }

        $user = User::find($id);
        $role = Role::find($id);

        $user->syncRoles($role); // Cocokkan user dengan role

        $newPermission = array();
        if ($request->checkBox != null) {
            foreach ($request->checkBox as $item) {
                $permission = Permission::findByName($item);
                array_push($newPermission, $permission);
            }
        }

        $user->syncPermissions($newPermission); // Cocokkan user dengan permission

        toast('Data berhasil tersimpan!', 'success'); // Toast
        return Redirect::route('pengaturan.assign-role.index'); // Return kembali
    }

    public function delete($id){
        $role = Role::with('permissions')->findOrFail($id);
        $role->delete();
        toast('Data berhasil terhapus!', 'success'); // Toast
        return Redirect::back(); // Return kembali
    }

    public function updateUnssign($id){
        $user = User::find($id);
        $user->syncRoles([]);
        $user->syncPermissions([]);
        toast('Data berhasil terimpan!', 'success'); // Toast
        return Redirect::back(); // Return kembali
    }
}
