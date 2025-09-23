<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller {
  public function index() {
    return view('admin.dashboard', [
      'users'    => \App\Models\User::count(),
      'modules'  => \App\Models\Module::count(),
      'questions'=> \App\Models\Question::count(),
      'attempts' => \App\Models\QuizAttempt::count(),
      'recent'   => \App\Models\QuizAttempt::with('user','module')->latest()->take(8)->get(),
    ]);
  }
}

