<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Estoque</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-blue-600 p-4 text-white shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Estoque & Requisições</h1>
            <div class="space-x-4">
                <a href="{{ route('requisicoes.index') }}" class="hover:underline">Requisições</a>
                <a href="#" class="hover:underline">Equipamentos</a>
                <a href="#" class="hover:underline">Clientes</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto mt-8 p-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>