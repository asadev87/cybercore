<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModuleController extends Controller {
  public function index() {
    $modules = Module::orderBy('created_at','desc')->paginate(10);
    return view('admin.modules.index', compact('modules'));
  }
  public function create(){ return view('admin.modules.create'); }
  public function store(Request $r){
    $data = $r->validate([
      'slug'=>'required|string|max:80|unique:modules,slug',
      'title'=>'required|string|max:150',
      'description'=>'nullable|string',
      'pass_score'=>'required|integer|min:1|max:100',
      'is_active'=>'nullable|boolean'
    ]);
    $data['is_active'] = $r->boolean('is_active');
    Module::create($data);
    return redirect()->route('admin.modules.index')->with('ok','Module created.');
  }
  public function edit(Module $module){ return view('admin.modules.edit', compact('module')); }
  public function update(Request $r, Module $module){
    $data = $r->validate([
      'slug'=>"required|string|max:80|unique:modules,slug,{$module->id}",
      'title'=>'required|string|max:150',
      'description'=>'nullable|string',
      'pass_score'=>'required|integer|min:1|max:100',
      'is_active'=>'nullable|boolean'
    ]);
    $data['is_active'] = $r->boolean('is_active');
    $module->update($data);
    return redirect()->route('admin.modules.index')->with('ok','Module updated.');
  }
  public function destroy(Module $module){
    $module->delete();
    return back()->with('ok','Module deleted.');
  }
}


