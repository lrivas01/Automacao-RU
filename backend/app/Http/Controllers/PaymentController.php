<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller {

    public function store(Request $request){
        $request->validate([
            'tokens' => 'required|integer|min:1', // Deve ter pelo menos 1 ficha
            'payment_method' => 'required|in:pix,card', // Apenas pix ou cartão
        ]);

        $student = Auth::guard('students')->user();

        if (!$student) {
            return response()->json(['error' => 'Estudante não autenticado.'], 401);
        }

        $amount = $request->tokens * 4; // Cada ficha custa R$4,00

        $payment = Payment::create([
            'student_id' => $student->id,
            'tokens' => $request->tokens,
            'amount' => $amount,
            'payment_method' => $request->payment_method,
            'status' => 'pending', // O pagamento começa como pendente
        ]);

        return response()->json([
            'message' => 'Pagamento criado com sucesso. Aguarde a confirmação.',
            'payment' => $payment,
        ]);
    }

    public function index() {
        $student = Auth::guard('students')->user();

        if (!$student) {
            return response()->json(['error' => 'Estudante não autenticado.'], 401);
        }

        $payments = Payment::where('student_id', $student->id)->get();

        return response()->json($payments);
    }
}
