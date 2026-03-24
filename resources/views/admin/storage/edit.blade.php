@extends('layouts.admin')

@section('title', 'Product upload storage')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Product upload storage</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Choose where new product images are stored. Credentials stay in <code class="text-xs bg-slate-100 dark:bg-slate-800 px-1 rounded">.env</code> (not in the database).</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.storage.update') }}" class="max-w-3xl space-y-8">
        @csrf
        <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Storage driver</label>
            <select name="product_upload_disk" class="w-full max-w-md bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white">
                @foreach([
                    'public' => 'Local server (public disk)',
                    's3' => 'Amazon S3',
                    'r2' => 'Cloudflare R2',
                    'do_spaces' => 'DigitalOcean Spaces',
                    'wasabi' => 'Wasabi',
                    'backblaze' => 'Backblaze B2 (S3 API)',
                    'minio' => 'MinIO / self-hosted S3',
                ] as $value => $label)
                    <option value="{{ $value }}" {{ $currentDisk === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('product_upload_disk')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="bg-slate-50 dark:bg-slate-800/40 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-300 space-y-4">
            <p class="font-bold text-slate-800 dark:text-slate-200">Environment variables (examples)</p>
            <ul class="list-disc list-inside space-y-2 text-xs font-mono">
                <li><strong>S3:</strong> AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_DEFAULT_REGION, AWS_BUCKET, optional AWS_URL / AWS_ENDPOINT</li>
                <li><strong>R2:</strong> R2_ACCESS_KEY_ID, R2_SECRET_ACCESS_KEY, R2_BUCKET, R2_ENDPOINT, R2_URL (public), R2_DEFAULT_REGION=auto</li>
                <li><strong>DigitalOcean Spaces:</strong> DO_SPACES_KEY, DO_SPACES_SECRET, DO_SPACES_REGION, DO_SPACES_BUCKET, DO_SPACES_ENDPOINT, DO_SPACES_URL</li>
                <li><strong>Wasabi:</strong> WASABI_ACCESS_KEY_ID, WASABI_SECRET_ACCESS_KEY, WASABI_BUCKET, WASABI_REGION, optional WASABI_ENDPOINT</li>
                <li><strong>Backblaze B2:</strong> B2_ACCESS_KEY_ID, B2_SECRET_ACCESS_KEY, B2_BUCKET, B2_ENDPOINT, B2_URL</li>
                <li><strong>MinIO:</strong> MINIO_ACCESS_KEY_ID, MINIO_SECRET_ACCESS_KEY, MINIO_BUCKET, MINIO_ENDPOINT, MINIO_URL</li>
            </ul>
            <p class="text-xs text-slate-500">After changing .env, run <code class="bg-slate-200 dark:bg-slate-900 px-1 rounded">php artisan config:clear</code>. Existing image paths are not migrated automatically if you switch providers.</p>
        </div>

        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-brand-500/30">Save</button>
    </form>
@endsection
