<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disposisi;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    public function index()
    {
        $disposisi_masuk = Disposisi::with(['surat', 'pengirim']) 
                                ->where('tujuan_user_id', Auth::id())
                                ->latest()
                                ->paginate(10);
        $listTujuan = collect();
        
        if (Auth::check()) {
            $role = Auth::user()->role; 
            if ($role == 'admin') {
                $listTujuan = User::where('role', 'kabag')->get();
            } 
            elseif ($role == 'kabag') {
                $listTujuan = User::whereIn('role', ['kasubag', 'Kasubag'])->get();
            }
            elseif ($role == 'kasubag') {
                $listTujuan = User::whereIn('role', ['staf'])->get();
            }
        }
        return view('admin.users.inbox', compact('disposisi_masuk', 'listTujuan'));
    }
}