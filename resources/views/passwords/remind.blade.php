@extends('layouts.app')

@section('content')
    <form action="{{ route('remind.store') }}" method="POST" class="form__auth">
        {!! csrf_field() !!}

        <div class="page-header">
            <h4>비밀번호 변경 신청</h4>
            <p class="text-muted">회원가입한 이메일로 신청하신 후, 메일박스를 확인하세요.</p>
        </div>

        <div class="form-group">
            <input type="email" name="email" placeholder="이메일" value="{{ old('email') }}" class="form-control" autofocus>
            {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
        </div>

        <button class="btn btn-primary btn-lg btn-block" type="submit">
            비밀번호 변경 메일 전송
        </button>
    </form>
@endsection
