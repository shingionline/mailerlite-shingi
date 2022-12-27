<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<title>{{ config('app.name', 'Laravel') }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ mix('js/app.js') }}" defer></script>
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
<link href="{{ mix('css/all.min.css') }}" rel="stylesheet">
<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300&display=swap" rel="stylesheet">
<style> @import url('https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300&display=swap'); </style>
</head>
<body>

<div id="app">
@yield('content')
</div>

</body>
</html>