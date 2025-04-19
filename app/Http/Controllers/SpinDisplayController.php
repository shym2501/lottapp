<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class SpinDisplayController extends Controller
{
    public function show(Form $form)
    {
        return view('spin-display', [
            'form' => $form
        ]);
    }

    public function display($formId)
    {
        $form = Form::findOrFail($formId);  // Ambil form berdasarkan ID
        return view('spin-display', compact('form'));  // Kirim form ke view
    }
}
