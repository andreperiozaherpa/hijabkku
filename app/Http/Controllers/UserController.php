<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $toko = Toko::get();
        return view('manajemen_user.user', ['toko' => $toko]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $nama = $request->nama;
        $email = $request->email;
        $role = $request->role;
        $namaToko = $request->namaToko;
        $status = $request->status;
        $shift = $request->shift;
        $password = $request->password;
        $confirm_password = $request->confirm_password;

        $data_user = User::where('email', $email)->count();
        if (!($password == $confirm_password)) {
            $icon = 'error';
            $title = 'Cek Password';
            $text = 'Password Tidak sama';
        } elseif ($data_user >= 1) {
            $icon = 'error';
            $title = 'Cek Email';
            $text = 'Email Sudah Digunakan';
        } else {
            $data = new User();
            $data->name = $nama;
            $data->email = $email;
            $data->role = $role;
            $data->kode_toko = $namaToko;
            $data->status = $status;
            $data->shift = $shift;
            $data->password = Hash::make($password);
            $data->save();

            $icon = 'success';
            $title = 'Sukses';
            $text = 'Data Berhasil Ditambahkan';
        }

        return response()->json([
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
        // dd($password . ' ' . $confirm_password);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
        if (Auth::user()->role == 'admin') {
            # code...
            $data = User::get();
        } else {
            $data = User::where('id', Auth::user()->id)->get();
        }
        return DataTables()->of($data)
            ->addColumn('status', function ($data) {
                $checked = $data->status == 'on' ? 'checked' : '';
                $disabled = Auth::user()->id == $data->id ? 'disabled' : '';
                return '<div class="form-check form-switch mb-0">
                            <input class="form-check-input status-toggle" type="checkbox" data-kode="' . $data->id . '" ' . $checked . ' ' . $disabled . ' style="cursor: pointer; width: 2.5rem; height: 1.25rem;">
                        </div>';
            })
            ->addColumn('shift', function ($data) {
                $shift = $data->shift;
                if ($shift == 1) {
                    # code...
                    $waktu = 'Pagi';
                } elseif ($shift == 2) {
                    # code...
                    $waktu = 'Sore';
                } else {
                    $waktu = 'Semua';
                }
                return $waktu;
            })
            ->addColumn('aksi', function ($data) {
                $group = '<button data-kode="' . $data->id . '" type="button" class="edit btn btn-quaternary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
              </svg></button>';
                $group .= '<button data-kode="' . $data->id . '" type="button" class="password btn btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-lock2" viewBox="0 0 16 16">
                <path d="M8 5a1 1 0 0 1 1 1v1H7V6a1 1 0 0 1 1-1zm2 2.076V6a2 2 0 1 0-4 0v1.076c-.54.166-1 .597-1 1.224v2.4c0 .816.781 1.3 1.5 1.3h3c.719 0 1.5-.484 1.5-1.3V8.3c0-.627-.46-1.058-1-1.224z"/>
                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
              </svg></button>';
                $group .= '<button data-kode="' . $data->id . '" type="button" class="destroy btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
              </svg></button>';

                return '<div class="btn-group" role="group" aria-label="Basic example">' . $group . ' </div>';
            })
            ->rawColumns(['aksi', 'status', 'shift'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
        $id = $request->kode;
        $users = User::where('id', $id)->first();
        return response()->json([
            'data' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $param = $request->params;
        $nama = $request->nama;
        $email = $request->email;
        $role = $request->role;
        $namaToko = $request->namaToko;
        $status = $request->status;
        $shift = $request->shift;


        if ($param == 'password') {
            $changePassword = $request->changePassword;
            $changeConfirmPassword = $request->changeConfirmPassword;
            $changeEmail = $request->changeEmail;

            if ($changePassword == $changeConfirmPassword) {
                User::where('email', $changeEmail)->update([
                    'password' => Hash::make($changePassword)
                ]);
                $icon = 'success';
                $title = 'Sukses';
                $text = 'Password Berhasil Diubah';
            } else {
                $icon = 'error';
                $title = 'Password';
                $text = 'Password Tidak Sama';
            }
        } else {
            User::where('email', $email)->update([
                'name' => $nama,
                'role' => $role,
                'kode_toko' => $namaToko,
                'status' => $status,
                'shift' => $shift,
            ]);
            $icon = 'success';
            $title = 'Sukses';
            $text = 'Data Berhasil Update';
        }

        return response()->json([
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->kode;
        $data = User::find($id)->delete();
        $icon = 'success';
        $title = 'Sukses';
        $text = 'User Berhasil Dihapus';

        return response()->json([
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }

    /**
     * Toggle the active/inactive status of a user.
     */
    public function toggleStatus(Request $request)
    {
        $id = $request->kode;
        $user = User::find($id);

        if (!$user) {
            return response()->json(['icon' => 'error', 'title' => 'Gagal', 'text' => 'User tidak ditemukan']);
        }

        // Prevent self-deactivation
        if (Auth::user()->id == $user->id) {
            return response()->json(['icon' => 'error', 'title' => 'Tidak Diizinkan', 'text' => 'Anda tidak dapat mengubah status akun Anda sendiri.']);
        }

        $user->status = $user->status === 'on' ? 'off' : 'on';
        $user->save();

        $label = $user->status === 'on' ? 'Aktif' : 'Nonaktif';

        return response()->json([
            'icon'   => 'success',
            'title'  => 'Status Diperbarui',
            'text'   => "Akun {$user->name} sekarang {$label}.",
            'status' => $user->status,
        ]);
    }

    public function rbacIndex()
    {
        $roles = ['admin', 'kasir', 'gudang'];
        $permissions = \DB::table('permissions')->get();
        
        // Fetch current role permissions mapping
        $rolePermissions = \DB::table('role_permissions')
            ->select('role', 'permission_id')
            ->get()
            ->groupBy('role')
            ->map(function ($items) {
                return $items->pluck('permission_id')->toArray();
            })
            ->toArray();

        return view('manajemen_user.rbac', [
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function rbacUpdate(Request $request)
    {
        $role = $request->role;
        $permissionId = $request->permission_id;
        $checked = $request->checked; // boolean

        if (!in_array($role, ['admin', 'kasir', 'gudang'])) {
            return response()->json(['icon' => 'error', 'title' => 'Gagal', 'text' => 'Role tidak valid']);
        }

        if ($checked) {
            \DB::table('role_permissions')->updateOrInsert([
                'role' => $role,
                'permission_id' => $permissionId
            ]);
            $text = 'Hak akses berhasil ditambahkan.';
        } else {
            \DB::table('role_permissions')
                ->where('role', $role)
                ->where('permission_id', $permissionId)
                ->delete();
            $text = 'Hak akses berhasil dicabut.';
        }

        return response()->json([
            'icon' => 'success',
            'title' => 'Sukses',
            'text' => $text
        ]);
    }
}
