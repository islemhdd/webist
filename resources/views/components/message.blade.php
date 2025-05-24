@props(['for' => null])

{{-- ✅ Message de succès --}}
@if (session('success') && !$for)
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- ❌ Message d'erreur spécifique --}}
@if ($for && $errors->has($for))
    <div class="alert alert-error">
        {{ $errors->first($for) }}
    </div>
@endif

{{-- ❌ Tous les autres messages d'erreur (généraux) --}}
@if (!$for && $errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-error">{{ $error }}</div>
    @endforeach
@endif
