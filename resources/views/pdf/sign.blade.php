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
                <form class="col s12" id="fileForm" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <div class="file-field input-field col s12">
                          <h6 class="grey-text text-darken-1">Arquivo de assinatura(<b class="red-text text-darken-1">.pfx</b>)</h6>
                            <div class="btn">
                              <span>Arquivo</span>
                              <input type="file" name="sign_name" id="sign_name">
                            </div>
                            <div class="file-path-wrapper">
                              <input class="file-path validate" type="text">
                            </div>
                          </div>
                    </div>

                    <div class="input-field col s12">
                      <input placeholder="Placeholder" id="password" name="password" type="password" class="validate">
                      <label for="password">Senha</label>
                    </div>
                    <button class="btn waves-effect waves-light right" type="submit" name="action">
                        Enviar
                      </button>
                </form>
            </div>
        </div>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>
</html>
