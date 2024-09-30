<?php

namespace App\Http\Controllers;

use App\Present;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Mendapatkan data kehadiran pengguna untuk hari ini
        $present = Present::whereUserId(auth()->user()->id)->whereTanggal(date('Y-m-d'))->first();

        // URL API untuk mendapatkan data libur
        $url = 'https://kalenderindonesia.com/api/YZ35u6a7sFWN/libur/masehi/' . date('Y/m');

        // Mengambil konten dari API dan decode JSON
        $kalender = file_get_contents($url);
        $kalender = json_decode($kalender, true);

        $libur = false;
        $holiday = null;

        // Memeriksa apakah $kalender dan key yang diperlukan ada
        if (isset($kalender['data'])) {
            if (isset($kalender['data']['holiday']['data']) && !empty($kalender['data']['holiday']['data'])) {
                foreach ($kalender['data']['holiday']['data'] as $key => $value) {
                    if (isset($value['date']) && $value['date'] == date('Y-m-d')) {
                        $holiday = isset($value['name']) ? $value['name'] : null;
                        $libur = true;
                        break;
                    }
                }
            }
        }

        // Mengembalikan view dengan data yang diperlukan
        return view('home', compact('present', 'libur', 'holiday'));
    }
}
