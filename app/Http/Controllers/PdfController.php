<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePdf;
use App\Models\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function index(){
        return view('pdf.index');
    }

    public function store(StoreUpdatePdf $request){
        $data = $request->all();

        if($request->file->isValid()){
            $file = $request->file->store('documents');
            $data['file'] = $file;

            dd($data);
            $pdf = Pdf::create($data);
            return response()->json(['success' => 'Documento adicionado com sucesso']);
        }
    }
}
