<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access denied</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-900 flex items-center justify-center p-6 antialiased">
    <div class="max-w-md w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-lg text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>
        </div>
        <h1 class="text-lg font-bold text-slate-900 dark:text-white">Access denied</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">{{ $message }}</p>
    </div>
</body>
</html>
