@extends('layouts.app')

@section('content')
    <form action="{{ route('reset.store') }}" method="POST" class="form__auth">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="page-header">
            <h4>비밀번호 바꾸기</h4>
            <p class="text-muted">회원가입했던 이메일을 입력하고, 새로운 비밀번호를 입력하세요.</p>
        </div>

        <div class="form-group">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="이메일" autofocus class="form-control">
            {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
        </div>

        <div class="form-group">
            <input type="password" name="password" value="{{ old('password') }}" placeholder="새로운 비밀번호" class="form-control">
            {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
        </div>

        <div class="form-group">
            <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="비밀번호 확인" class="form-control">
            {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
        </div>

        <button class="btn btn-primary btn-lg btn-block" type="submit">
            비밀번호 바꾸기
        </button>
    </form>
@endsection
