<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->hasRole('admin')) {
            // Admin sees all stats
            $data = [
                'users'     => User::count(),
                'modules'   => Module::count(),
                'questions' => Question::count(),
                'attempts'  => QuizAttempt::count(),
                'recent'    => QuizAttempt::with('user', 'module')->latest()->take(8)->get(),
            ];
        } else {
            // Lecturer sees stats for their own modules
            $myModuleIds = Module::where('user_id', $user->id)->pluck('id');

            $data = [
                'users'     => User::count(), // Total users is fine for lecturers to see
                'modules'   => $myModuleIds->count(),
                'questions' => Question::whereIn('module_id', $myModuleIds)->count(),
                'attempts'  => QuizAttempt::whereIn('module_id', $myModuleIds)->count(),
                'recent'    => QuizAttempt::whereIn('module_id', $myModuleIds)->with('user', 'module')->latest()->take(8)->get(),
            ];
        }

        return view('admin.dashboard', $data);
    }
}