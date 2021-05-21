<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePdf;
use App\Models\Pdf;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Elibyy\TCPDF\TCPDF;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdfController extends Controller
{
    public function index(){
        $user = User::find(Auth::id());
        if($user->is_admin == true)
        $documents = Pdf::latest()->simplePaginate(5);
        else
        $documents = Pdf::where('user_id', '=', Auth::id())->latest()->simplePaginate(5);
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

    public function signPage($id){
        return view('pdf.sign', compact('id'));
    }

    public function sign($id, Request $request){
         // pasta local pública onde o certificado .pfx será salvo temporariamente
         $path = base_path() . '/public/storage/certificate';

        // arquivo .pfx
        $file = $request->file('sign_name');

        // criptografia do nome do arquivo .pfx
        $filename = md5($file->getClientOriginalName() . '-' . implode('-', explode(':', date('H:i:s')))) . '.' . $file->getClientOriginalExtension();

        // salvando o arquivo .pfx no local público temporariamente
        $file->move($path, $filename);

        // obtendo o caminho do arquivo .pfx onde ele foi salvo temporariamente
        $certificate = 'file://' . $path . '/' . $filename;

        // pegando o conteúdo do certificado .pfx
        $data = file_get_contents($certificate);

                // verificando se o conteúdo do certificado .pfx é válido
                if (openssl_pkcs12_read($data, $certs, $request->password)) {
                // coloca os dados do certificado em $certs['cert'] e a chave privada em $certs['pkey']
                file_put_contents($certificate, $certs['cert'] . $certs['pkey']);

                // converte o certificado e pega os dados do proprietário
                $content = openssl_x509_parse(openssl_x509_read($certs['cert']));

                // variável da razão social e CNPJ/CPF
                $info = explode(':', $content['subject']['CN']);
                $hashInfo = md5($info[1]);
                // salva o nome da empresa
                $company = $info[0];

                // verifica se o registro é um CNPJ ou CPF e salva o registro
                if (strlen($info[1]) == 14) {
                    $type = 'CNPJ: ' . $info[1];
                } else {
                    $type = 'CPF: ' . $info[1];
                }

                // informação para a assinatura digital, contendo o nome da empresa, nº do registro, data e hora da assinatura
                $info = $company . "\n" . $type . "\n" . 'Data: ' . date('d/m/Y H:i');
            } else {
                    // removendo o arquivo .pfx do local público
                    File::delete($certificate);

                    // se o conteúdo do arquivo .pfx for inválido
                    return redirect()->back()->with('status', 'Arquivo .pfx ou senha inválida.');
                }


        /*
        |--------------------------------------------------------------------------
        | Atualizando e assinando o PDF
        |--------------------------------------------------------------------------
        */

        // pegando o pdf do banco
        $pdfToSign = Pdf::where('id', $id)->get();
        $fileLocate = public_path()."/storage/".$pdfToSign[0]->file_name;

        // pegando nome do arquivo no banco sem o diretório
        $onlyName = explode('/', $pdfToSign[0]->file_name);
        $onlyName = $onlyName[1];

        $pdf = new Fpdi();
        $pdf->SetTitle($onlyName);
        // assinando o PDF com a certificado (assinatura digital)
        $pdf->setSignature($certificate, $certificate, '', '', 2, '', 'A');


        // pegando quantidade de páginas do PDF a ser assinado
        $pdftext = file_get_contents($fileLocate);
        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        // dd($num);
        // loop para criação do PDF
        for($i = 1; $i < $num+1; $i++){
            $pdf->AddPage();
            $pdf->setSourceFile($fileLocate);
            $tplId = $pdf->importPage($i);
            $pdf->useTemplate($tplId, 0, 0);
            $pdf->Cell(0, 0, "Chave de acesso ${hashInfo}", 1, 1, 'C', 0, '', 0);
        }

        $filenamePDF = md5($file->getClientOriginalName() . '-' . implode('-', explode(':', date('H:i:s'))).'.pdf');
        $pdf->Output($path.'/'.$onlyName, 'FI'); // salva em um diretório e mostra na tela
        // removendo o arquivo .pfx do local público
        File::delete($certificate);
        //atualizando pdf no banco
        Storage::delete($pdfToSign[0]->file_name);
        $pdfToSign[0]->file_name = 'certificate/'.$onlyName;
        $pdfToSign[0]->save();
    }

}
