<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <title>Questr</title>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-78711465-6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', {{ config('app.ga-code') }});
    </script>
    
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"><!-- Latest compiled and minified CSS -->
    <link href="{{ asset('/css/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/csgo-questr.css') }}" rel="stylesheet">
    
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
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <a class="navbar-brand" href="#">Questr</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse mr-5" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
        @auth
            <!-- Balance widget -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tooltip" data-placement="bottom" title="Balances values are cached for 5min."><span class="badge badge-dark">Balance: {{ Auth::user()->balance ?? 0}} <i class="fas fa-coins"></i></span></a>
                </li>
                
                <!-- Home -->
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
                </li>
                
                <!-- Quest -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Quests
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('quests.index') }}">List</a>
                        @admin
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('quests.create') }}">Create</a>
                        @endadmin
                    </div>
                </li>
                
                <!-- Rankings -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Rankings
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @foreach(\App\Classes\RankingMapper::getRankings() as $ranking)
                            <a class="dropdown-item" href="{{ $ranking::getUrl() }}">{{ $ranking::getTitle() }}</a>
                        @endforeach
                    </div>
                </li>
                
                <!-- Shop -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.index') }}">Shop</a>
                </li>
                
                <!-- Inventory -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('inventory.index') }}">Inventory</a>
                </li>
                
                <!-- Coupon -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Coupons
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" data-toggle="modal" data-target="#use-token" href="#">Use</a>
                        @admin
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('coupon.create') }}">Create</a>
                        <a class="dropdown-item" href="{{ route('coupon.index') }}">List</a>
                        @endadmin
                    </div>
                </li>
            
                <!-- Administrative -->
                @admin
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Administrative
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('admin.users.index') }}">User list</a>
                        </div>
                    </li>
                @endadmin
                <!-- Profile -->
                <li class="nav-item">
                    <a href="{{ route('profile') }}">
                        <img style="height: 40px;" class="rounded-circle shadow-sm ml-3" src="{{ Auth::user()->avatar }}">
                    </a>
                </li>
            @endauth
            @guest
                <li class="nav-item">
                    <a class="btn btn-outline-primary" href="{{ route('auth.steam') }}">Sign in</a>
                
                </li>
            @endguest
        </ul>
    </div>
</nav>
<div class="pt-3 container">
    @include('flash::message')
    
    @yield('content')
    
    <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <div class="row">
            <div class="col-12 col-md">
            <!-- <img class="mb-2" src="{{ asset('/brand/bootstrap-solid.svg') }}" alt="" width="24" height="24"> -->
                <small class="d-block mb-3 text-muted">&copy; 2018-2019</small>
            </div>
            <div class="col-12 col-md">
                <h5>Contact</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="http://steamcommunity.com/id/de_nerd">Steam</a></li>
                    <li><a class="text-muted" href="http://denerdtv.com/discord">Discord</a></li>
                    <li><a class="text-muted" href="https://twitter.com/de_nerdTV">Twitter</a></li>
                    <li><a class="text-muted" href="https://www.twitch.tv/de_nerdTV">Twitch</a></li>
                </ul>
            </div>
            <div class="col-12 col-md">
                <h5>Information</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="https://denerdtv.com/questr/">How-to guide</a></li>
                    <li><a class="text-muted" href="http://denerdtv.com">Blog</a></li>
                    <li><a class="text-muted" href="https://github.com/HugoJF/questr">GitHub</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <div class="modal fade" id="use-token" tabindex="-1" role="dialog" aria-labelledby="use-token-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(['route' => ['coupon.use'], 'method' => 'POST']) !!}
                <div class="modal-header">
                    <h5 class="modal-title" id="use-token-label">Delete quest filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Please type the coupon code below:
                    <div class="form-group">
                        {!! Form::text('code', null, ['class' => 'my-3 form-control']) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-xs btn-success" type="submit">Use</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @stack('modals')
</div>
<!-- Bootstrap core JavaScript
================================================== -->

<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>


<script>window.jQuery || document.write('<script src="{{ asset('/js/vendor/jquery-slim.min.js') }}"><\/script>')</script>
<script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/js/moment.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="{{ asset('/js/vendor/holder.min.js') }}"></script>
<script src="{{ asset('/js/summernote.js') }}"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<script>$.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'fa';</script>
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
