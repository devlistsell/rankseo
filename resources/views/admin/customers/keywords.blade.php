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

    <h3 class="text-semibold text-primary">Keyword Management</h3>

    <div class="row">
        <div class="col-md-4">
            <div class="row">
                <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                <div id="success-message" class="alert alert-success" style="display: none;"></div>

                <div class="col-md-10">
                    <div class="form-group control-text">
                        <label> Keyword <span class="text-danger">*</span> </label>
                        <input id="keyword" placeholder="Enter keyword" value="" type="text" name="keyword"
                            class="form-control required  " maxlength="40">
                        <span class="error keyword_error hide" style="color: #b00020; font-size: 90%;"> The keyword field is
                            required. </span>
                        <span class="error keyword_exist_error hide" style="color: #b00020; font-size: 90%;"> This keyword
                            already exists! </span>
                    </div>
                </div>
                <div class="col-md-10 search-related hide">
                    <div class="form-group control-text">
                        <label> Rank <span class="text-danger">*</span> </label>
                        <input id="rank" readonly placeholder="Enter keyword" value="" type="text"
                            name="rank" class="form-control required">
                        <span class="error rank_error hide" style="color: #b00020; font-size: 90%;"> The rank field is
                            required. </span>
                    </div>
                </div>
                <div class="col-md-10 search-related hide">
                    <div class="form-group control-select">
                        <label> Difficulty <span class="text-danger">*</span> </label>
                        <select name="difficulty" id="difficulty" class="select select2-hidden-accessible" tabindex="-1"
                            aria-hidden="true">
                            <option value="">Choose</option>
                            <option value="1">0-49</option>
                            <option value="2">50-69</option>
                            <option value="3">70+</option>
                        </select>
                        <span class="error difficulty_error hide" style="color: #b00020; font-size: 90%;"> Please select
                            difficulty. </span>
                    </div>
                </div>
                @if (session('alert-error'))
                    <div class="alert alert-danger">
                        {{ session('alert-error') }}
                    </div>
                @endif

                <div class="">
                    <button class="btn btn-secondary search_btn"><i class="icon-check"></i> Search</button>
                    <button class="btn btn-primary search-related hide submit_btn"><i class="icon-check"></i>
                        {{ trans('messages.save') }}</button>
                </div>

            </div>
        </div>

        {{-- Keyword list --}}
        <div class="col-md-8">
            {{-- <div class="col-sm-4 col-md-4 text-end">
            <input type="text" id="search" class="form-control mb-3" placeholder="Search keywords...">
            </div> --}}
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Keyword</th>
                        <th scope="col">Rank</th>
                        <th scope="col">Difficulty</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($keywords))
                        @foreach ($keywords as $key => $val)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $val->keyword }}</td>
                                <td>{{ $val->ranking }}</td>
                                <td>
                                    @if ($val->difficulty_id == 1)
                                        0-49
                                    @elseif ($val->difficulty_id == 2)
                                        50-69
                                    @elseif ($val->difficulty_id == 3)
                                        70+
                                    @else
                                        --
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>

            </table>
            <div class="pagination-container">
                {{ $keywords->links('pagination::bootstrap-4') }}
            </div>
            @if (count($keywords) == 0)
                <div class="pml-table-container">
                    <div class="empty-list">
                        <i class="material-symbols-rounded">assignment_turned_in</i>
                        <span class="line-1">
                            There is no keyword result
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- <hr> --}}
    <script>
        $(document).ready(function() {
            //Keyword Search Section
            $(".search_btn").click(function() {
                $('#error-message').hide();
                $('#success-message').hide();
                if ($('#keyword').val().trim() == '') {
                    $('.keyword_error').removeClass('hide');
                    return false;
                }
                $(".keyword_error").addClass("hide");
                $.ajax({
                    url: "{{ route('admin.searchkeyword') }}",
                    type: "POST",
                    data: {
                        client_id: "{{ $customer->id }}",
                        keyword: $('#keyword').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == true) {
                            $('#success-message').text(response.message).show();
                            $('#rank').val(response.position);
                            $(".search-related").removeClass('hide');
                        }else if (response.status == 'site') {
                            $("#error-message").text(response.message).show();
                            return false;
                        }else if(response.status == 'empty'){
                            $('.keyword_error').removeClass('hide');
                            return false;
                        }else{
                            $("#error-message").text(response.message).show();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error("Error:", error);
                    }
                });
            });
            //Keyword Save Section
            $(".submit_btn").click(function() {
                let error = 1;
                $('#error-message').hide();
                if ($('#keyword').val().trim() == '') {
                    $('.keyword_error').removeClass('hide');
                    error = 0;
                }
                if ($('#rank').val().trim() == '') {
                    $('.rank_error').removeClass('hide');
                    error = 0;
                }
                if ($('#difficulty').val().trim() == '') {
                    $('.difficulty_error').removeClass('hide');
                    error = 0;
                }
                if (error == 0) {
                    return false;
                }

                $(".keyword_error").addClass("hide");
                $(".rank_error").addClass("hide");
                $(".difficulty_error").addClass("hide");

                $.ajax({
                    url: "{{ route('admin.savekeyword') }}",
                    type: "POST",
                    data: {
                        client_id: "{{ $customer->id }}",
                        keyword: $('#keyword').val(),
                        ranking: $('#rank').val(),
                        difficulty: $('#difficulty').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == false) {
                            $('.keyword_exist_error').removeClass('hide');
                        } else if (response.status == true) {
                            location.reload();
                        } else {
                            $("#error-message").text(response.message).show();
                            $("#success-message").hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#error-message").text('Something went wrong!').show();
                    }
                });
            });
        });
    </script>
@endsection
