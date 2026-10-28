@extends('layouts.app')

@section('content')
<div class="panel mx-auto max-w-md">
    <h1 class="border-b border-slate-200 px-6 py-4 text-base font-semibold">{{ __('Reset Password') }}</h1>

    <div class="px-6 py-5">
        <form method="POST" action="{{ route('password.request') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="field-label">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" class="field-input{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <p class="field-error">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div>
                <label for="password" class="field-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="field-input{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                    <p class="field-error">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div>
                <label for="password-confirm" class="field-label">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="field-input" name="password_confirmation" required>
            </div>

            <div class="flex flex-wrap items-center gap-4 pt-2">
                <button type="submit" class="btn btn-primary">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
