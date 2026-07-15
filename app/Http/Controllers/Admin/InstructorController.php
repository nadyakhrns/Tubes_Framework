<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InstructorRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class InstructorController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.instructors.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.instructors.create');
    }

    public function store(InstructorRequest $request): RedirectResponse
    {
        User::create([
            ...$request->validated(),
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()
            ->route('admin.instructors.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $instructor): View
    {
        return view('admin.instructors.edit', compact('instructor'));
    }

    public function update(InstructorRequest $request, User $instructor): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $instructor->update($data);

        return redirect()
            ->route('admin.instructors.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $instructor): RedirectResponse
    {
        $instructor->delete();

        return redirect()
            ->route('admin.instructors.index')
            ->with('success', 'User berhasil dihapus.');
    }
}