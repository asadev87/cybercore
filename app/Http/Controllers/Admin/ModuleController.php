<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $modules = Module::orderBy('created_at', 'desc')->paginate(10);
        } else {
            $modules = Module::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        return view('admin.modules.index', compact('modules'));
    }

    public function create()
    {
        return view('admin.modules.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'slug' => 'required|string|max:80|unique:modules,slug',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'pass_score' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean'
        ]);
        $data['is_active'] = $r->boolean('is_active');
        $data['user_id'] = Auth::id();
        Module::create($data);
        return redirect()->route('admin.modules.index')->with('ok', 'Module created.');
    }

    public function edit(Module $module)
    {
        if (!Auth::user()->hasRole('admin') && $module->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $r, Module $module)
    {
        if (!Auth::user()->hasRole('admin') && $module->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $data = $r->validate([
            'slug' => "required|string|max:80|unique:modules,slug,{$module->id}",
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'pass_score' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean'
        ]);
        $data['is_active'] = $r->boolean('is_active');
        $module->update($data);
        return redirect()->route('admin.modules.index')->with('ok', 'Module updated.');
    }

    public function destroy(Module $module)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        $module->delete();
        return back()->with('ok', 'Module deleted.');
    }
}