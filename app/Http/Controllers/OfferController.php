<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\offer_adv;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    public function index()
    {
        try{
	        $data["count"] = offer_adv::count();
	        $offer_adv = array();

	        foreach (offer_adv::all() as $p) {
	            $item = [
	                "id"          		=> $p->id,
	                "judul"             => $p->judul,
                    "deskripsi"  		=> $p->deskripsi,
                    "lokasi"            => $p->lokasi,
                    "kontak"            => $p->kontak,
                    "harga"             => $p->harga,
	                "gambar"    	  		=> $p->gambar
	            ];

	            array_push($offer_adv, $item);
	        }
	        $data["offer_adv"] = $offer_adv;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function store()
    {
        try{
    		$validator = Validator::make($request->all(), [
    			'judul'                 => 'required|string|max:255',
				'deskripsi'			   	=> 'required|string|max:255',
                'lokasi'			  	=> 'required|string|max:500',
                'kontak'			  	=> 'required|numeric|max:13',
                'harga'                 => 'required|string|max:150',
                'gambar'			  	=> 'required|string|max:150',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		$data = new offer_adv();
	        $data->judul = $request->input('judul');
            $data->deskripsi = $request->input('deskripsi');
            $data->lokasi = $request->input('lokasi');
            $data->kontak = $request->input('kontak');
            $data->harga = $request->input('harga');
	        $data->gambar = $request->input('gambar');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data iklan berhasil ditambahkan!'
    		], 201);

      } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
