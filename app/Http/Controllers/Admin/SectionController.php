<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    public function index(Module $module){
        $sections = $module->sections()->paginate(12);
        return view('admin.sections.index', compact('module','sections'));
    }

    public function create(Module $module){
        return view('admin.sections.create', compact('module'));
    }

    public function store(Request $r, Module $module){
        $data = $r->validate([
            'title' => 'required|string|max:150',
            'slug'  => ['required','alpha_dash','max:160', Rule::unique('sections','slug')],
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ]);
        $data['order'] = $data['order'] ?? ($module->sections()->max('order') + 1);
        $module->sections()->create($data);
        return redirect()->route('admin.modules.sections.index',$module)->with('ok','Section created.');
    }

    public function edit(Module $module, Section $section){
        return view('admin.sections.edit', compact('module','section'));
    }

    public function update(Request $r, Module $module, Section $section){
        $data = $r->validate([
            'title' => 'required|string|max:150',
            'slug'  => ['required','alpha_dash','max:160', Rule::unique('sections','slug')->ignore($section->id)],
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ]);
        $section->update($data);
        return redirect()->route('admin.modules.sections.index',$module)->with('ok','Section updated.');
    }

    public function destroy(Module $module, Section $section){
        $section->delete();
        return back()->with('ok','Section deleted.');
    }
}
