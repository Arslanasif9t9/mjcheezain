@extends('errors.layout')

@section('code', '500')
@section('title', 'Something Went Wrong')

@if(!empty($reason))
    @section('reason')
        {{ $reason }}
    @endsection
@endif

@if(!empty($reference))
    @section('reference')
        {{ $reference }}
    @endsection
@endif

@section('message')
    We hit an unexpected problem while loading this page. Our team has been notified.
    Please try again in a few minutes.
@endsection
