<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;
use App\Models\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalSuratMasuk  = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();
        $totalDisposisi   = Disposisi::count();
        $totalUser        = User::count();

        return view('home', compact(
            'totalSuratMasuk', 
            'totalSuratKeluar', 
            'totalDisposisi', 
            'totalUser'
        ));
    }
}