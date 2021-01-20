<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') - Pok&eacute;mon 3D: Skin</title>

        <link rel="shortcut icon" href="http://pokemon3d.net/files/images/favicon.png">

        <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous">

        <!-- Cookie Consent -->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />

        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    </head>
<body>
    <main class="container">

        <nav class="navbar navbar-expand-md navbar-dark bg-success my-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">skin.pokemon3d.net</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            
                <div class="collapse navbar-collapse" id="navbarDefault">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        {{-- <li class="nav-item active"><a class="nav-link" aria-current="page" href="{{ route('home') }}">Home</a></li> --}}
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="{{ route('logout') }}">Log out</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation mr-2" aria-hidden="true"></i> This is currently in &Beta;eta! Check out the <a href="https://github.com/P3D-Legacy/skin.pokemon3d.net"><i class="fab fa-github"></i> Github repo</a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-frown mr-2" aria-hidden="true"></i> {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2" aria-hidden="true"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info mr-2" aria-hidden="true"></i> {{ session('info') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation mr-2" aria-hidden="true"></i> {{ session('warning') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-warning mt-5 mb-0" role="alert">
                <strong><i class="fas fa-exclamation-triangle mr-2" aria-hidden="true"></i> Input Warning</strong>
                <ul class="m-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>

    @yield('javascript')

    <script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
    <script>
        window.cookieconsent.initialise({
          "palette": {
            "popup": {
              "background": "#237afc"
            },
            "button": {
              "background": "#fff",
              "text": "#237afc"
            }
          },
          "theme": "classic",
          "position": "bottom-right",
          "content": {
            "message": "This website uses cookies to ensure you have the best experience on our website.",
            "dismiss": "Got it!",
          }
        });
    </script>
</body>
</html>