<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\User;

class EmployeeController extends Controller
{
    private array $employeeRoleNames = [
        'ServiceAdvisor',
        'Mechanic',
        'Admin',
    ];

    public function index()
    {
        $roles = Role::whereIn('name', $this->employeeRoleNames)
            ->get();

        $employeeQuery = User::whereHas('role', function ($query) {
            $query->whereIn('name', $this->employeeRoleNames);
        });

        $statistics = [
            'total' => (clone $employeeQuery)->count(),
            'service_advisor' => (clone $employeeQuery)
                ->whereHas('role', fn($query) => $query->where('name', 'ServiceAdvisor'))
                ->count(),
            'mechanic' => (clone $employeeQuery)
                ->whereHas('role', fn($query) => $query->where('name', 'Mechanic'))
                ->count(),
            'admin' => (clone $employeeQuery)
                ->whereHas('role', fn($query) => $query->where('name', 'Admin'))
                ->count(),
        ];

        $employees = (clone $employeeQuery)
            ->with('role')
            ->latest('id')
            ->paginate(10);

        return view('owner.employees.index', compact(
            'employees',
            'roles',
            'statistics'
        ));
    }

    public function create()
    {
        $roles = Role::whereIn('name', $this->employeeRoleNames)
            ->orderBy('name')
            ->get();

        return view('owner.employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $allowedRoleIds = Role::whereIn('name', $this->employeeRoleNames)
            ->pluck('id')
            ->all();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],

            'phone' => ['nullable', 'string', 'max:20'],

            'role_id' => [
                'required',
                'integer',
                Rule::in($allowedRoleIds),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'Nama pegawai wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'role_id.required' => 'Role pegawai wajib dipilih.',
            'role_id.in' => 'Role yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role_id' => $validated['role_id'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('owner.employees.index')
            ->with('success', 'Akun pegawai berhasil dibuat.');
    }

    public function edit(User $user)
    {
        // Pastikan akun yang diedit merupakan akun pegawai.
        abort_unless(
            $user->role &&
                in_array($user->role->name, $this->employeeRoleNames, true),
            404
        );

        $roles = Role::whereIn('name', $this->employeeRoleNames)
            ->orderBy('name')
            ->get();

        return view('owner.employees.edit', compact(
            'user',
            'roles'
        ));
    }

    public function update(Request $request, User $user)
    {
        // Owner hanya boleh mengedit akun pegawai yang diizinkan.
        abort_unless(
            $user->role &&
                in_array($user->role->name, $this->employeeRoleNames, true),
            404
        );

        $allowedRoleIds = Role::whereIn('name', $this->employeeRoleNames)
            ->pluck('id')
            ->all();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'role_id' => [
                'required',
                'integer',
                Rule::in($allowedRoleIds),
            ],
        ], [
            'name.required' => 'Nama pegawai wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'role_id.required' => 'Jabatan wajib dipilih.',
            'role_id.in' => 'Jabatan yang dipilih tidak valid.',
        ]);

        $user->update($validated);

        return redirect()
            ->route('owner.employees.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function resetPasswordForm(User $user)
    {
        abort_unless(
            $user->role &&
                in_array($user->role->name, $this->employeeRoleNames, true),
            404
        );

        return view('owner.employees.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        abort_unless(
            $user->role &&
                in_array($user->role->name, $this->employeeRoleNames, true),
            404
        );

        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('owner.employees.index')
            ->with('success', 'Password pegawai berhasil direset.');
    }

    public function toggleStatus(User $user)
    {
        abort_unless(
            $user->role &&
                in_array($user->role->name, $this->employeeRoleNames, true),
            404
        );

        $user->is_active = ! $user->is_active;
        $user->save();

        $message = $user->is_active
            ? 'Akun pegawai berhasil diaktifkan.'
            : 'Akun pegawai berhasil dinonaktifkan.';

        return redirect()
            ->route('owner.employees.index')
            ->with('success', $message);
    }
}
