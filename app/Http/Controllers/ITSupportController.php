<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ITSupportController extends Controller
{
    public function dashboard()
    {
        return view('it_support.dashboard', [
            'title' => 'IT Support Dashboard'
        ]);
    }

    public function becomeITSupport(\Illuminate\Http\Request $request)
    {
        $code = $request->input('code');
        $envCode = env('IT_SUPPORT_CODE');

        if ($envCode && $code === $envCode) {
            $user = auth()->user();
            $user->role = 'it_support';
            $user->save();
            return redirect()->route('itsupport.dashboard')->with('success', 'You are now IT Support!');
        }

        return redirect('/')->with('error', 'Invalid code.');
    }
}
