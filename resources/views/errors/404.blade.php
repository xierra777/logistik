@extends('layouts.app') <!-- Optional: Extend your app's layout -->

@section('content')
<div class="text-center">
    <h1>404 - Page Not Found</h1>
    <p>Oops! The page you're looking for doesn't exist.</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Return Home</a>
</div>
@endsection