@extends('layouts.app')

@section('content')
    <form action="{{ route('users.store') }}" method="POST" class="form__auth">
        {!! csrf_field() !!}

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <input type="text" name="name" placeholder="이름" value="{{ old('name') }}" class="form-control" autofocus>
            {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
        </div>

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <input type="email" name="email" placeholder="이메일" value="{{ old('email') }}" class="form-control">
            {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
        </div>

        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <input type="password" name="password" placeholder="비밀번호" value="{{ old('password') }}" class="form-control">
            {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
        </div>

        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            <input type="password" name="password_confirmation" placeholder="비밀번호 확인" value="{{ old('password_confirmation') }}" class="form-control">
            {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
        </div>

        <div class="form-group">
            <button class="btn btn-primary btn-lg btn-block" type="submit">가입하기</button>
        </div>
    </form>
@endsection
