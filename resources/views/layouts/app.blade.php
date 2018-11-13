<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <title>Questr</title>
    
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="{{ asset('/css/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/pricing.css') }}" rel="stylesheet">
    
    <style>
        .card-deck .card .card-header {
            flex: 1 1 auto;
            display: flex;
            align-items: center;
        }
        
        .card-deck .card .card-body {
            flex: 0 1 auto;
        }
        
        .bg-primary-faded {
            background-color: #f5faff !important;
        }
        
        .bg-success-faded {
            background-color: rgba(40, 167, 69, 0.03) !important;
        }
        
        .bg-danger-faded {
            background-color: rgba(220, 53, 69, 0.03) !important;
        }
    </style>

</head>

<body>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal"><a href="{{ route('home') }}">CSGO-Questr</a></h5>
    <nav class="my-2 my-md-0 mr-md-3">
        @auth
            <a data-toggle="tooltip" data-placement="bottom" title="Balances values are cached for 5min."><span class="badge badge-dark">Balance: {{ Auth::user()->balance ?? 0}} <i class="fas fa-coins"></i></span></a>
        @endauth
        <a class="p-2 text-dark" href="{{ route('quests.index') }}">Quests</a>
        <a class="p-2 text-dark" href="{{ route('quests.create') }}">Create quest</a>
        <a class="p-2 text-dark" href="{{ route('shop.index') }}">Shop</a>
        <a class="p-2 text-dark" href="{{ route('inventory.index') }}">Inventory</a>
    </nav>
    @guest
        <a class="btn btn-outline-primary" href="{{ route('auth.steam') }}">Sign in</a>
    @endguest
</div>
<div class="container">
    @include('flash::message')
    
    @yield('content')
    
    <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <div class="row">
            <div class="col-12 col-md">
                <img class="mb-2" src="{{ asset('/brand/bootstrap-solid.svg') }}" alt="" width="24" height="24">
                <small class="d-block mb-3 text-muted">&copy; 2017-2018</small>
            </div>
            <div class="col-6 col-md">
                <h5>Features</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="#">Cool stuff</a></li>
                    <li><a class="text-muted" href="#">Random feature</a></li>
                    <li><a class="text-muted" href="#">Team feature</a></li>
                    <li><a class="text-muted" href="#">Stuff for developers</a></li>
                    <li><a class="text-muted" href="#">Another one</a></li>
                    <li><a class="text-muted" href="#">Last time</a></li>
                </ul>
            </div>
            <div class="col-6 col-md">
                <h5>Resources</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="#">Resource</a></li>
                    <li><a class="text-muted" href="#">Resource name</a></li>
                    <li><a class="text-muted" href="#">Another resource</a></li>
                    <li><a class="text-muted" href="#">Final resource</a></li>
                </ul>
            </div>
            <div class="col-6 col-md">
                <h5>About</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="#">Team</a></li>
                    <li><a class="text-muted" href="#">Locations</a></li>
                    <li><a class="text-muted" href="#">Privacy</a></li>
                    <li><a class="text-muted" href="#">Terms</a></li>
                </ul>
            </div>
        </div>
    </footer>
    @stack('modals')
</div>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="{{ asset('/js/vendor/jquery-slim.min.js') }}"><\/script>')</script>
<script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/js/vendor/holder.min.js') }}"></script>
<script src="{{ asset('/js/moment.min.js') }}"></script>
<script src="{{ asset('/js/summernote.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    
    Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
    });
</script>
@stack('scripts')
</body>
</html>
