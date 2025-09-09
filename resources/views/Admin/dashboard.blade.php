@extends('../master')

@section('title', Auth::user()->roles->pluck('name')->first() . ' Dashboard')

@section('content')
    <div class="text-center mt-20">
        <h1 class="text-2xl font-bold text-green-600">
            {{ Auth::user()->roles->pluck('name')->first() }} Dashboard
        </h1>
        <p>Hi {{ Auth::user()->name }}! Welcome Back</p>
    </div>
@endsection
    