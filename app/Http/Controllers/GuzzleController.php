<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GuzzleController extends Controller
{
    private $_client;

    public function __construct()
    {
        $this->_client = new Client([
            'base_uri' => 'https://api.qrm15.com/logistik/',
            'http_errors' => false,
            'protocols'       => ['http', 'https']
            //   'auth'  => ['public', 'qrm15@bali123']
        ]);
        }

    public function index()
    {
        // $response = $this->_client->request('GET', 'users',
        //     [
        //         'query' => [
        //             'q-tech-KEY'    => 'Qrm!5@Bali123'
        //         ]
        //     ]
        // );

        // $result = json_decode($response->getBody()->getContents(), true);
        // $result['data'] = isset($result['data']) ? $result['data'] : 'default';

        // $hasil = $result['data'];


        $response = $this->_client->request('GET', 'liststockmaterial',
            [
                'query' => [
                    'q-tech-KEY'    => 'Qrm!5@Bali123'
                ]
            ]
        );

        $result = json_decode($response->getBody()->getContents(), true);
        $hasil = $result['stokmaterial'];

        return view('guzzle', compact('hasil'));
    }
}
