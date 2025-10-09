<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function __construct()
    {
        // This applies the ModulePolicy to the resource controller methods.
        $this->authorizeResource(Module::class, 'module');
    }

    public function index()
    {
        $query = Module::with('user')->orderBy('created_at', 'desc');

        // If the user is not an admin, scope the query to their own modules.
        // The policy has already ensured the user is either an admin or lecturer.
        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        $modules = $query->paginate(10);

        return view('admin.modules.index', compact('modules'));
    }

    public function create()
    {
        $lecturers = User::role('lecturer')->get();
        return view('admin.modules.create', compact('lecturers'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'slug' => 'required|string|max:80|unique:modules,slug',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'pass_score' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean',
            'user_id' => 'sometimes|exists:users,id' // Required only for admins
        ]);
        $data['is_active'] = $r->boolean('is_active');
        $data['note'] = $r->filled('note') ? trim($r->input('note')) : null;

        // If the authed user is not an admin, they can only create modules for themselves.
        if (!Auth::user()->hasRole('admin')) {
            $data['user_id'] = Auth::id();
        }

        Module::create($data);

        return redirect()->route('admin.modules.index')->with('ok', 'Module created.');
    }

    public function edit(Module $module)
    {
        $lecturers = User::role('lecturer')->get();
        return view('admin.modules.edit', compact('module', 'lecturers'));
    }

    public function update(Request $r, Module $module)
    {
        $data = $r->validate([
            'slug' => "required|string|max:80|unique:modules,slug,{$module->id}",
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'pass_score' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean',
            'user_id' => 'sometimes|exists:users,id' // Required only for admins
        ]);
        $data['is_active'] = $r->boolean('is_active');
        $data['note'] = $r->filled('note') ? trim($r->input('note')) : null;

        // If the user is not an admin, they cannot change the owner.
        if (!Auth::user()->hasRole('admin')) {
            unset($data['user_id']);
        }

        $module->update($data);

        $redirect = $r->input('redirect_to') === 'builder' ? 'admin.modules.builder' : 'admin.modules.index';

        if ($redirect === 'admin.modules.index') {
            return redirect()->route($redirect)->with('ok', 'Module updated.');
        }

        return redirect()->route($redirect, $module)->with('ok', 'Module updated.');
    }

    public function builder(Module $module)
    {
        $this->authorize('update', $module);

        $module->load(['sections' => function ($query) {
            $query->orderBy('order');
        }, 'user']);

        $questions = $module->questions()->with('section')->latest()->get();

        $lecturers = Auth::user()->hasRole('admin')
            ? User::role('lecturer')->get()
            : collect();

        return view('admin.modules.builder', [
            'module' => $module,
            'lecturers' => $lecturers,
            'questions' => $questions,
        ]);
    }

    public function destroy(Module $module)
    {
        $module->delete();
        return back()->with('ok', 'Module deleted.');
    }
}
