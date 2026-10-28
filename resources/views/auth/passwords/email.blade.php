@extends('layouts.app')

@section('content')
<div class="panel mx-auto max-w-md">
    <h1 class="border-b border-slate-200 px-6 py-4 text-base font-semibold">{{ __('Reset Password') }}</h1>

    <div class="px-6 py-5">
        @if (session('status'))
            <div class="mb-4 border-b-4 border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="field-label">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" class="field-input{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <p class="field-error">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-4 pt-2">
                <button type="submit" class="btn btn-primary">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
