@extends('layouts.app')

@section('content')
<div class="panel mx-auto max-w-md border-t-4 p-5"
     v-bind:class="editing ? 'border-orange-400' : 'border-green-500'">
    <add-journey v-show="!editing"></add-journey>
    <edit-journey v-show="editing"></edit-journey>
</div>

<div class="mt-10">
    <list-journeys v-show="!editing"></list-journeys>
</div>
@endsection
