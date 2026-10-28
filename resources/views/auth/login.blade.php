@extends('layouts.app')

@section('content')
<div class="panel mx-auto max-w-md">
    <h1 class="border-b border-slate-200 px-6 py-4 text-base font-semibold">{{ __('Login') }}</h1>

    <div class="px-6 py-5">
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="field-label">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" class="field-input{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

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

            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="remember" class="size-4 rounded border-slate-300 accent-brand" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
            </label>

            <div class="flex flex-wrap items-center gap-4 pt-2">
                <button type="submit" class="btn btn-primary">
                    {{ __('Login') }}
                </button>
                <a class="text-sm font-semibold text-brand hover:underline" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
