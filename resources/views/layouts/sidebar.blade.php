<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sidebar Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <div class="d-flex vh-100">
        <!-- Sidebar -->
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light text-shadow" style="width: 280px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                <span class="fs-4">Sidebar</span>
            </a>
            <hr>
            @php
                $currentRoute = Route::currentRouteName();
            @endphp

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('tasks.index') }}" class="nav-link link-dark {{ $currentRoute === 'tasks.index' ? 'active' : '' }}" aria-current="page">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('tasks.piority') }}" class="nav-link {{ $currentRoute === 'tasks.piority' ? 'active' : '' }} link-dark">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#priority"></use></svg>
                        Priority
                    </a>
                </li>
                <li>
                    <a href="{{ route('tasks.jadwal') }}" class="nav-link {{ $currentRoute === 'tasks.jadwal' ? 'active' : '' }} link-dark">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#schedule"></use></svg>
                        Terencana
                    </a>
                </li>
            </ul>
        </div>
        <!-- Main Content -->
        <div class="flex-grow-1">
            @yield('content')
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        </div>
    </div>
</body>
</html>