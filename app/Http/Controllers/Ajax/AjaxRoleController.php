<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class AjaxRoleController extends Controller
{
    public function getRole(Request $request){
        if ($request->ajax()) {

            // Query role join permissions
            $roles = Role::with('permissions')->get();

            // Return datatables
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete
                    $btn = "<a class='btn btn-info btn-sm d-inline me-1' href='".route('pengaturan.role.edit', $row->id)."'>Info</a>";
                    if ($row['name'] != 'Admin') {
                        $btn = $btn."<form action=".route('pengaturan.role.delete', $row->id)." method='POST' class='d-inline'>".csrf_field().method_field('DELETE')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Hapus</button></form>";
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // Ambil data menu untuk datatable
    public function getRoleEdit(Request $request){
        if ($request->ajax()) {
            $roles = Role::with('permissions')->find($request->id);
            return response()->json([
                'status' => 'success',
                'data' => $roles
            ]);
        }
    }

    public function getPermission(Request $request){
        if ($request->ajax()) {

            // Query permissions join menu
            $permissions = Permission::select('permissions.id as permissions_id','name','menu.judul','menu.id')->join('menu','permissions.id_menu','=','menu.id')->orderBy('permissions_id', 'DESC')->get();

            // Return datatables
            return DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete
                    $btn = "<a class='btn btn-info btn-sm d-inline me-1' href='".route('pengaturan.permission.edit', $row->permissions_id)."'>Info</a>";
                    $btn = $btn."<form action=".route('pengaturan.permission.delete', $row->permissions_id)." method='POST' class='d-inline'>".csrf_field().method_field('DELETE')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Hapus</button></form>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getUser(Request $request){
        if ($request->ajax()) {
            $users = User::with('roles')->orderBy('id', 'DESC')->get();

            // Return datatables
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('roles', function($row){ // Tambah kolom action untuk button edit dan delete
                    if (count($row['roles']) > 0) {
                        return "<a href='".route('pengaturan.role.edit', $row->id)."'><span class='badge bg-label-dark'>".$row['roles'][0]['name']."</span></a>";
                    }else{
                        return "<span class='badge bg-label-danger'>Belum Memiliki Role</span>";
                    }
                })
                ->addColumn('action', function($row){ // Tambah kolom action untuk button edit dan delete
                    $btn = "<a class='btn btn-primary btn-sm d-inline me-1' href='".route('pengaturan.assign-role.edit', $row->id)."'>Tetapkan</a>";
                    $btn = $btn."<form action=".route('pengaturan.assign-role.unssign', $row->id)." method='POST' class='d-inline'>".csrf_field().method_field('PUT')." <button type='submit' class='btn btn-danger btn-sm confirm-delete'>Batalkan</button></form>";
                    return $btn;
                })
                ->rawColumns(['action','roles'])
                ->make(true);
        }
    }

    public function getTabelRoleUser(Request $request)
    {
        if ($request->ajax()) {
            $role = Role::with('permissions')->find($request->idRole);
            $user = User::find($request->idUser)->getPermissionNames();
            $userRole = User::find($request->idUser)->getRoleNames();

            $permissionsAll = new Collection(Permission::select('name','menu.judul','menu.id')
            ->join('menu', 'permissions.id_menu', '=', 'menu.id')
            ->get());
            $permissionsAll = $permissionsAll->groupBy('judul');

            $table = view('components.table-html.table-role-user', compact('role','permissionsAll','user', 'userRole'))->render();

            return response()->json($table);
        }
    }
}
