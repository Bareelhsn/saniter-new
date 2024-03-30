<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pengaturan.shift.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $timezoneList = $this->timezoneList();
        return view('pengaturan.shift.create', compact('timezoneList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'required|unique:shift,nama',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required'
        ], ['jam_masuk.required' => 'jam masuk wajib diisi.','jam_pulang.required' => 'jam pulang wajib diisi.']);

        if ($validator->fails()) {
            toast('Mohon periksa form kembali!','error');
            return Redirect::back()->withInput()->withErrors($validator);
        }

        Shift::create([
            'nama' => $request->nama,
            'server_time' => '?',
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang
        ]);

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::route('shift.index'); // Redirect kembali
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'namaEdit' => 'required|unique:shift,nama,'.$id,
            'jam_masukEdit' => 'required',
            'jam_pulangEdit' => 'required'
        ], ['jam_masukEdit.required' => 'jam masuk wajib diisi.','jam_pulangEdit.required' => 'jam pulang wajib diisi.']);

        if ($validator->fails()) {
            Session::flash('modalEdit', 'error');
            toast('Mohon periksa form kembali!','error');
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $shift = Shift::find($id);
        $data = [
            'nama' => $request->namaEdit,
            'server_time' => '?',
            'jam_masuk' => $request->jam_masukEdit,
            'jam_pulang' => $request->jam_pulangEdit
        ];
        $shift->update($data);

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::route('shift.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari berdasarkan id pada tabel shift, kemudian delete
        $shift = Shift::find($id);
        $shift->delete();

        toast('Data berhasil dihapus!', 'success');
        return Redirect::route('shift.index'); // Redirect kembali
    }

    public function timezoneList()
    {
        return $timezones = array(
            'Asia/Almaty'          => "(GMT+06:00) Almaty",
            'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
            'Asia/Baku'            => "(GMT+04:00) Baku",
            'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
            'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
            'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
            'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
            'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
            'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
            'Asia/Kabul'           => "(GMT+04:30) Kabul",
            'Asia/Karachi'         => "(GMT+05:00) Karachi",
            'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
            'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
            'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
            'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
            'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
            'Asia/Magadan'         => "(GMT+12:00) Magadan",
            'Asia/Muscat'          => "(GMT+04:00) Muscat",
            'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
            'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
            'Asia/Seoul'           => "(GMT+09:00) Seoul",
            'Asia/Singapore'       => "(GMT+08:00) Singapore",
            'Asia/Taipei'          => "(GMT+08:00) Taipei",
            'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
            'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
            'Asia/Tehran'          => "(GMT+03:30) Tehran",
            'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
            'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
            'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
            'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
            'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
            'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
            'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
            'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
        );
    }
}
