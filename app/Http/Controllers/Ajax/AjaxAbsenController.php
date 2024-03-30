<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class AjaxAbsenController extends Controller
{
    // Ambil data regional untuk datatable
    public function getAbsenShift(Request $request){
        if ($request->ajax()) {
            $shift = Shift::select('id','nama','jam_masuk','jam_pulang')->find($request->id);

            $shift['jam_masuk'] = date('H:i', strtotime($shift['jam_masuk']));
            $shift['jam_pulang'] = date('H:i', strtotime($shift['jam_pulang']));

            $innerHtml = "
            <p class='mb-3'>Jadwal Anda :</p>
            <h4 class='mb-2'>Shift <b><u style='color: red;'>".$shift['nama']."</u></b></h4>
            <h2>".$shift['jam_masuk']." - ".$shift['jam_pulang']."</h2>
            <div class='row justify-content-center'>
                <div class='col-auto justify-content-center'>
                    <div id='my_camera' style='width: 100% !important;'></div>
                </div>
            </div>
            ";

            return response()->json([
                'status' => 'success',
                'data' => $shift,
                'html' => $innerHtml
            ],200);
        }
    }
}
