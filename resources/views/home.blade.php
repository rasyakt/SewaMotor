@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h4>Mengarahkan ke Dashboard...</h4>
                <p class="text-muted">Silakan tunggu sebentar</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Redirect to appropriate dashboard based on user role
    setTimeout(function() {
        window.location.href = '{{ url("/home") }}';
    }, 1000);
});
</script>
@endpush