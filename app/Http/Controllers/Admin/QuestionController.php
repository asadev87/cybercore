<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    private function redirectAfter(Module $module, Request $request, string $fallbackRoute, string $message)
    {
        $route = $request->input('redirect_to') === 'builder'
            ? 'admin.modules.builder'
            : $fallbackRoute;

        return redirect()->route($route, $module)->with('ok', $message);
    }

    // GET /admin/modules/{module}/questions
    public function index(Module $module)
    {
        $questions = $module->questions()->latest()->paginate(20);
        return view('admin.questions.index', compact('module', 'questions'));
    }

    // GET /admin/modules/{module}/questions/create
    public function create(Module $module)
    {
        $module->load('sections'); // for the Section <select>
        return view('admin.questions.create', compact('module'));
    }

    // POST /admin/modules/{module}/questions
    public function store(Request $request, Module $module)
    {
        $rules = [
            'type'       => ['required','in:mcq,truefalse,fib'],
            'difficulty' => ['nullable','in:easy,medium,hard'],
            'section_id' => ['nullable', Rule::exists('sections','id')->where('module_id', $module->id)],
            'stem'       => ['required','string'],
            'choices'    => ['nullable','string'], // textarea, will be turned to array
            'answer'     => ['nullable','string'], // textarea or single line
            'is_active'  => ['boolean'],
        ];

        // Strengthen rules by type
        if ($request->input('type') === 'mcq') {
            $rules['choices'] = ['required','string'];
            $rules['answer']  = ['required','string'];
        } elseif ($request->input('type') === 'fib') {
            $rules['answer']  = ['required','string']; // at least one acceptable answer
        }

        $data = $request->validate($rules); // Validation per docs. :contentReference[oaicite:1]{index=1}

        // Normalize to arrays (JSON) the model will cast
        $toLines = function (?string $s): array {
            if (!is_string($s)) return [];
            $arr = preg_split("/\r\n|\n|\r/", trim($s));
            return array_values(array_filter(array_map('trim', $arr), fn($x) => $x !== ''));
        };

        if ($data['type'] === 'mcq') {
            $data['choices'] = $toLines($data['choices'] ?? '');
            $data['answer']  = $toLines($data['answer']  ?? '');
            // Optional: ensure every answer is in choices
            $data['answer']  = array_values(array_intersect($data['answer'], $data['choices']));
        } elseif ($data['type'] === 'truefalse') {
            $data['choices'] = ['true','false']; // canonical
            $data['answer']  = $toLines($data['answer'] ?? '');
            // Optional: constrain answers to true/false
            $data['answer']  = array_values(array_intersect($data['answer'], ['true','false']));
        } else { // fib
            $data['choices'] = [];
            $data['answer']  = $toLines($data['answer'] ?? '');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['module_id'] = $module->id;

        Question::create($data);

        return $this->redirectAfter($module, $request, 'admin.modules.questions.index', 'Question created.');
    }

    // GET /admin/modules/{module}/questions/{question}/edit
    public function edit(Module $module, Question $question)
    {
        $module->load('sections');
        return view('admin.questions.edit', compact('module','question'));
    }

    // PUT/PATCH /admin/modules/{module}/questions/{question}
    public function update(Request $request, Module $module, Question $question)
    {
        $rules = [
            'type'       => ['required','in:mcq,truefalse,fib'],
            'difficulty' => ['nullable','in:easy,medium,hard'],
            'section_id' => ['nullable', Rule::exists('sections','id')->where('module_id', $module->id)],
            'stem'       => ['required','string'],
            'choices'    => ['nullable','string'],
            'answer'     => ['nullable','string'],
            'is_active'  => ['boolean'],
        ];

        if ($request->input('type') === 'mcq') {
            $rules['choices'] = ['required','string'];
            $rules['answer']  = ['required','string'];
        } elseif ($request->input('type') === 'fib') {
            $rules['answer']  = ['required','string'];
        }

        $data = $request->validate($rules);

        $toLines = function (?string $s): array {
            if (!is_string($s)) return [];
            $arr = preg_split("/\r\n|\n|\r/", trim($s));
            return array_values(array_filter(array_map('trim', $arr), fn($x) => $x !== ''));
        };

        if ($data['type'] === 'mcq') {
            $data['choices'] = $toLines($data['choices'] ?? '');
            $data['answer']  = $toLines($data['answer']  ?? '');
            $data['answer']  = array_values(array_intersect($data['answer'], $data['choices']));
        } elseif ($data['type'] === 'truefalse') {
            $data['choices'] = ['true','false'];
            $data['answer']  = $toLines($data['answer'] ?? '');
            $data['answer']  = array_values(array_intersect($data['answer'], ['true','false']));
        } else { // fib
            $data['choices'] = [];
            $data['answer']  = $toLines($data['answer'] ?? '');
        }

        $data['is_active'] = $request->boolean('is_active');

        $question->update($data);

        return $this->redirectAfter($module, $request, 'admin.modules.questions.index', 'Question updated.');
    }

    // DELETE /admin/modules/{module}/questions/{question}
    public function destroy(Module $module, Question $question)
    {
        $question->delete();

        return $this->redirectAfter($module, request(), 'admin.modules.questions.index', 'Question deleted.');
    }
}
