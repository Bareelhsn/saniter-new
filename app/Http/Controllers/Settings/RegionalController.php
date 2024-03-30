<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class RegionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regional = Regional::Select('*')
            ->get();

        return view('pengaturan.regional.index', compact('regional'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengaturan.regional.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $regional = Regional::find($id);

        return view('pengaturan.regional.edit', compact('regional'));


    }

    /**
     * Store a newly created resource in storage.
     */
    // Untuk menambahkan data regional kedalam database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|unique:regional,nama',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali membawa error dan old input
        }

        Regional::create([
            'nama' => $request->nama,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::route('regional.index'); // Redirect kembali
    }

    // Untuk menghapus data regional
    public function delete($id)
    {
        $regional = Regional::findOrFail($id);

        $regional->delete();

        toast('Data berhasil dihapus!', 'success');
        return Redirect()->to('/regional'); // Redirect kembali
    }

    // Untuk proses update data Regional
    public function update(Request $request, $id)
    {
        // Mengambil request dari submit form
        $validator = Validator::make($request->all(),[
                // Validasi & ambil semua request
                'nama' => 'required',
                'latitude' => 'required',
                'longitude' => 'required'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            toast('Mohon periksa form kembali!', 'error'); // Toast
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(); // Return kembali membawa error dan old input
        }

        $regional = Regional::find($id); // Where user = $id
        $data = [
            'nama' => $request->nama,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];
        // dd($regional);
        $regional->update($data); // Update data

        toast('Data berhasil tersimpan!', 'success');
        return Redirect::back(); // Redirect kembali
    }
}
