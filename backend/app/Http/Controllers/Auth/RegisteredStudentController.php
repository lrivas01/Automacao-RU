<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredStudentController extends Controller {
    
    public function create(): Response { 
        return Inertia::render('Auth/Register'); 
    }

    public function store(Request $request): RedirectResponse {

        // validação dos dados como os dados devem chegar do frontend 
        $request->validate([
            'ra' => 'required|unique:students,ra',
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:students,email',
            'course' => 'required|string|lowercase|max:255',
            'password' => ['required', 'min:8', 'confirmed', Rules\Password::defaults()],
        ]);

        // inserindo os dados validados do frontend no banco de dados 
        $student = Student::create([
            'ra' => $request->ra,
            'name' => $request->name,
            'email' => $request->email,
            'course' => $request->course,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        event(new Registered($student));

        Auth::guard('students')->login($student);

        return redirect(route('student.dashboard'));
    }
}
