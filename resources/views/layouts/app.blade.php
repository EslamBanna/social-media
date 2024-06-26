<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ABG Social Media APP</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
      
          // Enable pusher logging - don't include this in production
          Pusher.logToConsole = true;
      
          var pusher = new Pusher('80615a6410e7bc4afe9d', {
            cluster: 'eu'
          });
      
          var channel = pusher.subscribe('my-channel');
          channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
          });
        </script>
        @yield('css')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    ABG
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile', Auth::user()->id) }}">
                                    <i class="bi bi-person-circle"></i>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#" id="notification-toggle">
                                    <i class="bi bi-bell-fill"></i>
                                    <span id="notifications-count" class="text-danger">0</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" id="notification-list"
                                    aria-labelledby="notification-toggle">
                                    @php
                                        $notifications = myNotifications();
                                    @endphp
                                    <ul>
                                        @foreach ($notifications as $notification)
                                            <li>{{ $notification->content }}</li>
                                            <br>
                                        @endforeach
                                    </ul>
                                    <!-- Notification list items go here -->
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown">
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('home') }}">Home</a>
                                        <a class="dropdown-item" href="{{ route('post.create') }}">Publish New Post</a>
                                        <a class="dropdown-item" href="{{ route('new.users') }}">Add New Users</a>
                                        <a class="dropdown-item" href="{{ route('new.users.requests') }}">Friends
                                            Requests</a>
                                        <a class="dropdown-item" href="{{ route('my.requests') }}">My Requests</a>

                                        <a class="dropdown-item"
                                            href="{{ route('profile', Auth::user()->id) }}">Profile</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </div>
                                </div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
        const notificationToggle = document.getElementById('notification-toggle');
        const notificationList = document.getElementById('notification-list');

        notificationToggle.addEventListener('click', (event) => {
            event.preventDefault();
            notificationList.classList.toggle('show');
        });

        document.addEventListener('click', (event) => {
            if (!notificationToggle.contains(event.target) && !notificationList.contains(event.target)) {
                notificationList.classList.remove('show');
            }
        });
    </script>
    @yield('script')
</body>

</html>
