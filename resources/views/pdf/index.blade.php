<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('css/style.css')  }}">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="_token" content="{{ csrf_token() }}">
      <title>Document</title>
    </head>
    <body>
        <div class="container">
            <div class="row">

                <h1 class="center">Seus Documentos</h1>
    <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Novo documento</a>
      <table id="tableRegist">
        <thead>
          <tr>
              <th>Nome</th>
              <th>Autor</th>
              <th>Ações</th>
          </tr>
        </thead>

        <tbody id="reload">
    @foreach ($documents as $doc)
        <tr id="line">
            <td>{{ $doc->name }}</td>
            <td>{{ $doc->author }}</td>
            <td>
                @php
                    $folder = explode('/', $doc->file_name);
                    $folder = $folder[0];
                @endphp
                <a target="_blank" href="{{ route('pdf.signPage', $doc->id)}}" class="waves-effect waves-light btn green darken-1 tooltipped modal-trigger {{ ($folder == "certificate") ? 'disabled' : '' }}" data-position="left" data-tooltip="Assinar"><i class="large material-icons">check</i></a>
                <a target="_blank" href="{{ route('pdf.show', $doc->id) }}" class="waves-effect waves-light btn cyan darken-1 tooltipped" data-position="top" data-tooltip="Ver"><i class="large material-icons">remove_red_eye</i></a>
                <a href="{{ route('pdf.destroy', $doc->id) }}" class="waves-effect waves-light btn red darken-1 tooltipped" id="deleteDoc" data-position="right" data-tooltip="Deletar"><i class="large material-icons">delete</i></a>
            </td>
          </tr>
    @endforeach
        </tbody>
      </table>
      {{ $documents->links() }}
            </div>
        </div>

          <!-- Modal Cadastro -->
  <div id="modal1" class="modal">
      <div class="showAlert"></div>
    <div class="modal-content">
      <h4 class="center">Enviar documento</h4>
      <br><br>
      <div class="row">
        <form class="col s12" id="fileForm" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="input-field col s6">
              <input placeholder="Arquivo" id="name" name="name" type="text" class="validate">
              <label for="name">Nome do arquivo</label>
            </div>

            <div class="input-field col s6">
              <input placeholder="Placeholder" id="author" name="author" type="text" class="validate">
              <label for="author">Autor</label>
            </div>

            <div class="row">
                <div class="file-field input-field col s12">
                    <div class="btn">
                      <span>Arquivo</span>
                      <input type="file" name="file_name" id="file_name">
                    </div>
                    <div class="file-path-wrapper">
                      <input class="file-path validate" type="text">
                    </div>
                  </div>
            </div>
            <button class="btn waves-effect waves-light right" type="submit" name="action">
                Enviar
              </button>
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Fechar</a>
    </div>
  </div>

  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.modal').modal();
            $('.tooltipped').tooltip();
        });
    </script>

    <script>
        $.ajaxSetup({
            headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            }
        });

        $('#fileForm').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route('pdf.store') }}",
                type: "POST",
                data: new FormData(this),
                // data: $(this).serialize(),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success:function(response){
                    console.log(response);
                    if(response){
                        Swal.fire(
                            'Sucesso!',
                            'Documento enviado',
                            'success'
                        );

                        $("#reload").load("{{ route('pdf.index') }} #line");
                        $("#fileForm")[0].reset();
                        $("#modal1").modal('close');
                    }
                },
                error:function(err){
                    console.log("erro: "+err);
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Preencha os dados corretamente!',
                    })
              }
            })
        });
    </script>

    <script>

        $(document).ready(function() {
                $("body").on("click", "#deleteDoc", function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Tem certeza?',
                        text: "O registro será removido permanentemente!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Cancelar'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            var id = $(this).data("id");
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = $(this).attr('href');
                            console.log(url);
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                dataType: 'json',
                                data: {
                                    _token: token,
                                    id: id
                                },
                                success: function(data) {
                                    $("#reload").load("{{ route('pdf.index') }} #line");
                                    Swal.fire(
                                        'Deletado!',
                                        'Arquivo removido com sucesso.',
                                        'success'
                                    );
                                },
                                error: function(err){
                                    console.log(err);
                                    Swal.fire(
                                        'Oops...',
                                        'Algo deu errado, tente novamente.',
                                        'error'
                                    );
                                }
                            });
                        }
                    })
                });
            });

    </script>

</body>
</html>
