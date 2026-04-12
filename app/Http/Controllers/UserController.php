<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('nome')) {
            $query->where('name', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return Inertia::render('Admin/Users/Index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'is_admin' => 'boolean',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'is_admin' => $request->is_admin ?? false,
            ]);

            return back()->with('success', 'Usuário cadastrado com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao cadastrar: ' . $th->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'is_admin' => 'boolean',
        ]);

        try {
            $user = User::findOrFail($id);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'is_admin' => $request->is_admin ?? false,
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $user->update($data);

            return back()->with('success', 'Usuário atualizado com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        if (!$request->id) {
            return back()->with('error', 'Erro: Requisição inválida.');
        }

        if ($request->id == $request->user()->id) {
            return back()->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        try {
            User::findOrFail($request->id)->delete();
            return back()->with('success', 'Usuário deletado.');
        } catch (QueryException $th) {
            return back()->with('error', 'Não foi possível excluir o usuário.');
        }
    }
}
