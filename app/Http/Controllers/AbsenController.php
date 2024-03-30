<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Izin;
use App\Models\Lokasi;
use App\Models\Shift;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AbsenController extends Controller
{
    public function index()
    {
        // // Ambil semua izin berdasarkan user id, tahun dan bulan sekarang
        // $cekUserIzin = Izin::select('tgl_mulai_izin','tgl_akhir_izin')
        //     ->where('user_id', auth()->user()->id)
        //     ->whereYear('created_at', Carbon::now()->year)
        //     ->whereMonth('created_at', Carbon::now()->month)
        //     ->get();

        // // Perulangan user izin
        // foreach ($cekUserIzin as $item) {

        //     if (Carbon::parse($item->tgl_mulai_izin)->format('m') == Carbon::now()->format('m')) {
        //         $periodeTanggal = CarbonPeriod::create(Carbon::parse($item->tgl_mulai_izin), Carbon::parse($item->tgl_akhir_izin));

        //         $periode = array();

        //         foreach ($periodeTanggal as $tgl) {
        //             array_push($periode, $tgl->format('Y-m-d'));
        //         }

        //         dd($periode);

        //     }
        // }

        // Ambil lokasi user berdasarkan id user, join tabel lokasi
        $lokasiUser = User::select('users.lokasi_id as lokasi_id','lokasi.lokasi_proyek','lokasi.latitude','lokasi.longitude','lokasi.radius')
            ->join('lokasi','users.lokasi_id','=','lokasi.id')
            ->where('users.id', auth()->user()->id)
            ->first();

        $cekAbsenMasukHariIni = Absen::where('user_id', auth()->user()->id)
            ->where('tgl_masuk', Carbon::now()->format('Y-m-d'))
            ->get();

        // Ambil shift
        $shift = Shift::all();

        return view('absen.index', compact('cekAbsenMasukHariIni','shift', 'lokasiUser'));
    }

    // Simpan absen
    public function store(Request $request){
        // Jika tidak terdapat request foto
        if ($request->image == null) {
            toast('Oppss, terjadi kesalahan!', 'error');
            return Redirect::back();
        }

        // Panggil fungsi dibawah
        // Kondisi jika didalam radius
        if ($this->radiusLokasi($request->latitude, $request->longitude)) {

            // Pilih shift yang di checked pada form
            $shiftYangDipilih = Shift::find($request->shiftChecked);

            // Cek apakah user sudah absen masuk pada hari itu dengan shiftnya
            $cekAbsenMasuk = Absen::where('user_id', auth()->user()->id)
                ->where('shift_id', $shiftYangDipilih->id)
                ->where('tgl_masuk', Carbon::now()->format('Y-m-d'))
                ->get();

            // Cek apakah user sudah absen pulang pada hari itu dengan shiftnya
            $cekAbsenPulang = Absen::where('user_id', auth()->user()->id)
                ->where('shift_id', $shiftYangDipilih->id)
                ->where('tgl_pulang', Carbon::now()->format('Y-m-d'))
                ->get();

            // Jika belum absen masuk pada hari itu (Jika row data = 0 atau berarti tidak ada)
            if (count($cekAbsenMasuk) == 0) {

                // Ambil nama foto dari fungsi absenImageStore dibawah
                $pathFoto = $this->absenImageStore($request->image);

                // Fungsi cek terlambat, return ['time', 'status']
                $keterlambatan = $this->cekTerlambat(Carbon::now()->format('H:i:s'), $shiftYangDipilih->jam_masuk);

                $data = [
                    'lokasi_id' => '',
                    'shift_id' => '',
                    'tgl_masuk' => Carbon::now()->format('Y-m-d'),
                    'jam_masuk' => Carbon::now()->format('H:i:s'),
                    'foto_masuk' => $pathFoto,
                    'lokasi_masuk' => $request->lokasi,
                    'tgl_pulang' => '',
                    'jam_pulang' => '',
                    'foto_pulang' => '',
                    'lokasi_pulang' => '',
                    'terlambat' => $keterlambatan['time'],
                    'keterangan' => request()->userAgent(),
                    'status' => $keterlambatan['status']
                ];

                Absen::create($data);

                toast('Absen berhasil', 'success');
                return Redirect::back();

            }else{ // Kondisi jika sudah absen
                toast('Anda sudah absen masuk hari ini!','Info');
                return Redirect::back();
            }

            // Jika belum absen pulang pada hari itu (Jika row data = 0 atau berarti tidak ada)
            if (count($cekAbsenPulang) == 0) {
                toast('Proses Absen Pulang','Info');
                return Redirect::back();
            }else{
                toast('Anda sudah absen pulang hari ini!','Info');
                return Redirect::back();
            }

        }else{

        }
    }

    // Fungsi simpan data ke folder
    private function absenImageStore($imageRequest)
    {
        $img = $imageRequest;
        $folderPath = "user-absen/".Carbon::now()->format('Y-m-d')."/";
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';

        $file = $folderPath . $fileName;
        Storage::disk('public')->put($file, $image_base64);
        return $file;
    }

    // Fungsi cek lebih waktu keterlambatan
    private function cekTerlambat($jamMasukUser, $jamMasukShift){
        $jamShift = Carbon::parse($jamMasukShift);
        $jamMasuk = Carbon::parse($jamMasukUser);

        if ($jamMasuk <= $jamShift) {
            return [
                'time' => "00:00:00",
                'status' => "Normal"
            ];
        }else{
            return [
                'time' => $jamMasuk->diff($jamShift)->format('%H:%I:%S'),
                'status' => "Normal"
            ];
        }
    }

    // Fungsi set status apakah terlambat atau tidak
    // private function setStatus($jamMasukUser, $jamMasukShift){
    //     $jamShift = Carbon::parse($jamMasukShift);
    //     $jamMasuk = Carbon::parse($jamMasukUser);

    //     if ($jamMasuk <= $jamShift) {
    //         return 'Normal';
    //     }else{
    //         return 'Terlambat';
    //     }
    // }

    // Fungsi radius lokasi, apakah didalam atau diluar radius || Output true false
    private function radiusLokasi($latitudeRequest, $longitudeRequest) {

        // Ambil lokasi user berdasarkan id user, join tabel lokasi
        $lokasiUser = User::select('lokasi.latitude','lokasi.longitude','lokasi.radius')
        ->join('lokasi','users.lokasi_id','=','lokasi.id')
        ->where('users.id', auth()->user()->id)
        ->first();

        // Koordinat user saat ini
        $latitudeFrom = $latitudeRequest;
        $longitudeFrom = $longitudeRequest;

        // Koordinat user ditempatkan
        $latitudeTo = $lokasiUser->latitude;
        $longitudeTo = $lokasiUser->longitude;

        // Radius planet bumi pada tatasurya ini
        $earthRadius = 6371000;

        // Konversi dari degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        // Kurangin dulu
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        // Rumus yang tidak dimengerti
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $radius = $angle * $earthRadius;

        // Kondisi jika radius lebih kecil daripada perhitungan diatas
        if ($radius <= $lokasiUser->radius) {
            return true;
        }

        // Sebaliknya
        return false;
    }
}
