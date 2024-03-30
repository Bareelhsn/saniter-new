<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index(){
        // Select menu dan roles
        $menu = Menu::select('id','judul')->get();
        $roles = Role::select('name')->get();
        return view('pengaturan.permission.index', compact('roles', 'menu')); // Pass data
    }

    public function store(Request $request){
        // Validasi
        $validator = Validator::make($request->all(),[
            'id_menu' => 'required',
            'name' => 'required|unique:permissions,name',
            'role' => 'nullable'
        ], ['name.unique' => 'nama permisison tidak boleh sama.']);

        // Jika validasi gagal
        if ($validator->fails()) {
            toast('Mohon periksa form kembali!', 'error'); // Toast
            Session::flash('modalAdd', 'error'); // Flash message
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        // Memanggil fungsi dibawah
        $this->permissionAddCondition($request);

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::back();
    }

    public function delete($id){
        $permission = Permission::with('roles')->findOrFail($id);
        $newAccessRoles = array();

        foreach ($permission->roles as $item) {
            $role = Role::findByName($item);
            array_push($newAccessRoles, $role->name);
        }

        $menu = Menu::find($permission->id_menu);
        $menu->update([
            'access_roles' => json_encode($newAccessRoles)
        ]);

        $permission->delete();

        toast('Data berhasil terhapus!', 'success'); // Toast
        return Redirect::back(); // Return kembali
    }

    public function edit($id){
        $permissions = Permission::with('roles')->findOrFail($id);
        $role = Role::select('name')->get();
        return view('pengaturan.permission.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[ // Validasi request dari form tambah menu
            'name' => 'required|unique:permissions,name,'.$id,
        ], ['name.required' => 'permission wajib diisi.']);

        if ($validator->fails()) { // Jika validasi gagal
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali
        }

        $permission = Permission::with('roles')->find($id); // Cari sub menu berdasarkan ID
        $permission->update([
            'name' => $request->name,
        ]);

        $newRole = array();
        $newAccessRoles = array();

        foreach ($request->roles as $item) {
            $role = Role::findByName($item);
            array_push($newAccessRoles, $role->name);
            array_push($newRole, $role);
        }

        $menu = Menu::find($permission->id_menu);
        $menu->update([
            'access_roles' => json_encode($newAccessRoles)
        ]);

        $permission->syncRoles($newRole);

        toast('Data berhasil tersimpan!', 'success'); // Toast
        return Redirect::route('pengaturan.permission.index'); // Return kembali
    }

    public function permissionAddCondition($request){
        $permissionArray = []; // Valiable menyimpan permission yang baru di create
        $menu = Menu::find($request->id_menu); // Cari menu dengan id

        // Simpan array dengan nama dari menu dicombine string _create, _read, dan seterusnya
        if (isset($request->customCRUD)) {
            $permissionName = [strtolower($request->customCRUD).'_create',strtolower($request->customCRUD).'_read',strtolower($request->customCRUD).'_update',strtolower($request->customCRUD).'_delete'];
        }else{
            $permissionName = [strtolower($menu->judul).'_create',strtolower($menu->judul).'_read',strtolower($menu->judul).'_update',strtolower($menu->judul).'_delete'];
        }

        // Jika request dengan name otomatis mempunyai value on dan request name mempunyai value CRUD
        if ($request->otomatis === "on" && $request->name == "CRUD") {
            for ($i=0; $i < count($permissionName); $i++) { // Looping isi dari variable array diatas
                $permission = Permission::firstOrCreate([ // Buat permission baru
                    'id_menu' => $request->id_menu,
                    'name' => $permissionName[$i] // Diambil per data dikey arraynya
                ]);
                array_push($permissionArray, $permission); // Masukkan permission yg sudah dicreate tadi ke dalam array diatas
            }
        }else{ // Jika tidak ada dari kondisi diatas
            $permission = Permission::create([ // Buat data permission
                'id_menu' => $request->id_menu,
                'name' => $request->name
            ]);
            array_push($permissionArray, $permission); // Masukkan permission yg sudah dicreate tadi ke dalam array diatas
        }

        // Jika ada request role
        if (isset($request->role) != null) {
            $newPermission = array(); // Buat variable array
            $roleNames = array();
            foreach ($request->role as $item) { // Karna name input yg ditentukan berupa array contoh: role[], lakukan foreach
                $role = Role::findByName($item);
                array_push($roleNames, $role->name);
                array_push($newPermission, $role); // Push ke variable diatas
            }

            for ($i=0; $i < count($permissionArray); $i++) {
                $permissionArray[$i]->syncRoles($newPermission); // Cocokkan role dan permission sesuai length data di variable permissionArray
            }

            $menu->update([
                'access_roles' => json_encode($roleNames)
            ]);
        }

        return;
    }
}
