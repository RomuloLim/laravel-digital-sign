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
    public function sign(StoreUpdatePdf $request){
         // pasta local pública onde o certificado .pfx será salvo temporariamente
         $path = base_path() . '/public/storage/certificate';

        // arquivo .pfx
        $file = $request->file('sign_name');

        // dd($request->file('sign_name')->getMimeType());

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

            // dd($content);
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
            return response()->json(['erro' => 'Arquivo .pfx ou senha inválida.']);
        }


        /*
        |--------------------------------------------------------------------------
        | Atualizando e assinando o PDF
        |--------------------------------------------------------------------------
        */

        // pegando o pdf do banco
        // $pdfToSign = Pdf::where('id', $id)->get();
        // $fileLocate = public_path()."/storage/".$pdfToSign[0]->file_name;

        $pdfToSign = $request->file('pdf');
        // pegando nome do arquivo no banco sem o diretório
        $onlyName = $pdfToSign->getClientOriginalName();

        $pdf = new Fpdi('P', 'mm', 'P', true, 'UTF-8', false);
        $pdf->SetTitle($onlyName);
        // assinando o PDF com a certificado (assinatura digital)
        $pdf->setSignature($certificate, $certificate, '', '', 2, '', 'A');

        // dd($pdfToSign->path());

        // pegando quantidade de páginas do PDF a ser assinado
        $pdftext = file_get_contents($pdfToSign);
        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        // dd($num);
        // loop para criação do PDF
        for($i = 1; $i < $num+1; $i++){
            $pdf->AddPage();
            $pdf->setSourceFile($pdfToSign->path());
            $tplId = $pdf->importPage($i);
            $pdf->useTemplate($tplId, 0, 0);
            $pdf->Cell(0, 0, "Chave de acesso ${hashInfo}", 1, 1, 'C', 0, '', 0);
        }

        $pdf->setSignatureAppearance(180, 60, 15, 15);

        // dd($pdf);

        // $filenamePDF = md5($file->getClientOriginalName() . '-' . implode('-', explode(':', date('H:i:s'))).'.pdf');
        $pdf->Output($path.'/'.$onlyName, 'I'); // salva em um diretório e mostra na tela
        // removendo o arquivo .pfx do local público
        File::delete($certificate);
    }
}
