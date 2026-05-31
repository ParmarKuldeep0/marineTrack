<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-950 antialiased">


<meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="flex min-h-screen"> 
        <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
            {{ $slot }}
        </main>
</div>
    @fluxScripts
    @stack('scripts')
</body>
</html>