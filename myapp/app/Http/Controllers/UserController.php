<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $users = User::when($request->q, fn($q,$v) =>
    //         $q->where('name','like',"%$v%")
    //           ->orWhere('email','like',"%$v%")
    //           ->orWhere('role','like',"%$v%")
    //     )->paginate(10);

    //     return view('users.index', compact('users'));
    // }
    public function index(Request $request)
    {
        $query = User::query();

        // Jika login sebagai Manajer Gudang → hanya lihat Staff
        if (auth()->user()->role === 'Manajer Gudang') {
            $query->where('role', 'Staff Gudang');
        }

        // Jika ada pencarian
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('role', 'like', "%$q%");
            });
        }

        $users = $query->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'role'      => $request->role,
        ]);

        // return redirect()->route('users.index')->with('success', 'User berhasil dibuat');
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']); // jangan ubah password kalau kosong
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        $user->delete();
        // return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
