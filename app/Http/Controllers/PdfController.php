<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePdf;
use App\Models\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function index(){
        $documents = Pdf::latest()->simplePaginate(5);
        return view('pdf.index', compact('documents'));
    }

    public function store(StoreUpdatePdf $request){
        $data = $request->all();
        $userID = Auth::id();
        $data['user_id'] = $userID;
        if($request->file('file_name')->isValid()){
            $file = $request->file('file_name')->store('documents');
            $data['file_name'] = $file;
            $pdf = Pdf::create($data);
            return response()->json($data);
        }
    }

    public function show($id){
        $document = Pdf::where('id', $id)->get();
        $fileLocate = public_path()."/storage/".$document[0]->file_name;
        // dd($fileLocate);
        return response()->file($fileLocate);
    }

    public function destroy($id){
        $document = Pdf::find($id);

        if (Storage::exists($document->file_name))
        Storage::delete($document->file_name);

        $document->delete();

        return response()->json($document);
    }
}
