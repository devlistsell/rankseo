@extends('layouts.core.backend', [
    'menu' => 'customer',
])

@section('title', trans('messages.contact_information'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action('Admin\HomeController@index') }}">{{ trans('messages.home') }}</a>
            </li>
            <li class="breadcrumb-item"><a
                    href="{{ action('Admin\CustomerController@index') }}">{{ trans('messages.customers') }}</a></li>
            <li class="breadcrumb-item active">keywords</li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-address-book3"></i> {{ $contact->company }}
                ({{ $contact->name($customer->getLanguageCode()) }})</span>
        </h1>
    </div>

@endsection

@section('content')

    @include('admin.customers._tabs')

    <h3 class="text-semibold text-primary">Invoice Management</h3>

    <div class="row">
        <div class="col-md-4">
            <div class="row">
                
            </div>
        </div>

    </div>

    {{-- <hr> --}}
   
@endsection
