<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        <tbody>
          {{-- <tr>
            <td>Termo de adesão</td>
            <td>Rômulo Lima Fonseca</td>
            <td>
                <a class="waves-effect waves-light btn green darken-1 tooltipped" data-position="top" data-tooltip="Assinar"><i class="large material-icons">check</i></a>
                <a class="waves-effect waves-light btn cyan darken-1 tooltipped" data-position="top" data-tooltip="Ver"><i class="large material-icons">remove_red_eye</i></a>
                <a class="waves-effect waves-light btn red darken-1 tooltipped" data-position="top" data-tooltip="Deletar"><i class="large material-icons">delete</i></a>
        </td>
          </tr> --}}
        </tbody>
      </table>
            </div>
        </div>

          <!-- Modal Structure -->
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h4 class="center">Enviar documento</h4>
      <br><br>
      <div class="row">
        <form class="col s12" action="{{ route('pdf.store') }}" method="POST" id="fileForm">
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
{{--
    <script>
        $('#fileForm').submit(function(e){
            e.preventDefault();

            $.ajax({
                url: "{{ route('pdf.store') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: 'json',
                success:function(response){
                    if(response){
                        Swal.fire(
                            'Sucesso!',
                            'Documento enviado',
                            'success'
                        );
                        $("#tableRegist").prepend('<tr><td>'+response.name+'</td><td>'+response.author+'</td></tr>');
                        $("#fileForm")[0].reset();
                        $("#modal1").modal('hide');
                    }
                },
                error:function(err){
                $('.modalValidate').empty();
                $.each(err.responseJSON.errors, function(key, error){
                    console.log(error);
                    $('.showAlert').after('<div class="alert alert-danger modalValidate">'+error[0] + (error[1] != undefined ? '<br>' + error[1] : '') +'</div>');
                    $('.modalValidate').delay(4000).fadeOut(500);
                });
              }
            })
        });
    </script> --}}

</body>
</html>
