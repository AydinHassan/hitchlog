@extends('layouts.app')

@section('content')
<div class="row">
    <div id="journey-form" class="offset-md-4 col-md-4" v-bind:class="{ 'journey-form-edit': editing }">
        <add-journey v-show="!editing"></add-journey>
        <edit-journey v-show="editing"></edit-journey>
    </div>
</div>
<div class="row" style="padding-top:40px;">
    <div class="col-sm">
        <list-journeys v-show="!editing"></list-journeys>
    </div>
</div>

@endsection('content')