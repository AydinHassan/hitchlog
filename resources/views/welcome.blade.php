@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm">
    </div>
    <div class="col-sm">
        <form method="POST" action="{{ route('journey.store') }}">
            @csrf
            <div class="form-group">
                <label for="name" class=" col-form-label text-md-right">{{ __('Start Location') }}</label>
    
                <div class="">
                    <input id="start_location" type="text" class="form-control{{ $errors->has('start_location') ? ' is-invalid' : '' }}" name="start_location" value="{{ old('start_location') }}" required autofocus>
    
                    @if ($errors->has('start_location'))
                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('start_location') }}</strong>
                                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="name" class=" col-form-label text-md-right">{{ __('End Location') }}</label>

                <div class="">
                    <input id="end_location" type="text" class="form-control{{ $errors->has('end_location') ? ' is-invalid' : '' }}" name="end_location" value="{{ old('end_location') }}" required autofocus>

                    @if ($errors->has('end_location'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('end_location') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="name" class=" col-form-label text-md-right">{{ __('Start Date/Time') }}</label>

                <div class="">
                    <input id="start" type="datetime-local" class="form-control{{ $errors->has('start') ? ' is-invalid' : '' }}" name="start" value="{{ old('start') }}" required autofocus>

                    @if ($errors->has('start'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('start') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="name" class=" col-form-label text-md-right">{{ __('End Date/Time') }}</label>

                <div class="">
                    <input id="end" type="datetime-local" class="form-control{{ $errors->has('end') ? ' is-invalid' : '' }}" name="end" value="{{ old('end') }}" required autofocus>

                    @if ($errors->has('end'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('end') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="name" class=" col-form-label text-md-right">{{ __('Driver Name') }}</label>

                <div class="">
                    <input id="driver_name" type="text" class="form-control{{ $errors->has('driver_name') ? ' is-invalid' : '' }}" name="driver_name" value="{{ old('driver_name') }}" autofocus>

                    @if ($errors->has('driver_name'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('driver_name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="name" class=" col-form-label text-md-right">{{ __('Notes') }}</label>

                <div class="">
                    <textarea id="notes" class="form-control{{ $errors->has('notes') ? ' is-invalid' : '' }}" name="notes" value="{{ old('notes') }}" autofocus></textarea>

                    @if ($errors->has('notes'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('notes') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
    
            <div class="form-group mb-0">
                <div class="">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Add') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm">
    </div>
</div>
<div class="row" style="padding-top:40px;">
    <div class="col-sm">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Start Location</th>
                    <th scope="col">End Location</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Driver</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Added By</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($journeys as $journey)
                    <tr>
                        <td style="vertical-align: middle">{{ $journey->start_location }}</td>
                        <td style="vertical-align: middle">{{ $journey->end_location }}</td>
                        <td style="vertical-align: middle">{{ $journey->start }}</td>
                        <td style="vertical-align: middle">{{ $journey->end }}</td>
                        <td style="vertical-align: middle">{{ $journey->driver_name }}</td>
                        <td style="vertical-align: middle">{{ $journey->notes }}</td>
                        <td style="vertical-align: middle" >{{ $journey->user->name }}</td>
                        <td style="vertical-align: middle">
                            <div style="display: flex; flex-direction: row; justify-content: space-between">
                                <div>
                                    <a href="{{ route('journey.edit', $journey->id) }}"><i class="fas fa-edit icon-action"></i></a>
                                </div>
                                <div>
                                    <form action="{{ route('journey.destroy', $journey->id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" id="completed-task" style=" background: none; padding: 0px; border: none;">
                                            <i class="fas fa-times-circle icon-action"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection('content')