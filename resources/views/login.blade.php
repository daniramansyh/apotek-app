@extends('templates.app')

@section('content-dinamis')
    <form action="{{ route('login.proses') }}" method="POST" class="card d-block mx-auto my-3 p-5 w-50 h-50">
        @csrf
        @if(Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif
        @if(Session::get('logout'))
            <div class="alert alert-primary">{{ Session::get('logout') }}</div>
        @endif  
        <div class="form-group">
            <label for="email" class="form-label">Email: </label>
            <input type="email" class="form-control" id="email" name="email">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password: </label>
            <input type="password" class="form-control" id="password" name="password">
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
            <button type="submit" class="btn btn-primary mt-3">Login</button>
    </form>
@endsection