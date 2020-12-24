@extends('layouts.app')

@section('content')
<div class="container">
    <div class="single">
        <div class="row">
            <div class="col-sm-2 follow_left">
            </div>
            <div class="col-sm-8 follow_left">
                <ul id="myTab" class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="" id="employer" role="tab" data-toggle="tab">@lang('admin.employer')</a>
                    </li>
                    <li role="presentation">
                        <a href="" role="" id="candidate" data-toggle="tab">@lang('admin.candidate')</a>
                    </li>
                </ul>
                <div class="follow_jobs" id='employer_list'>
                    @foreach ($employers as $employer)
                        <a href="{{ route('users.show', ['user' => $employer]) }}">
                            <img src="{{ asset($employer->image->url) }}" alt="" class="img-circle">
                            <div class="title">
                                <h5>{{ $employer->name }}</h5>
                                @switch ($employer->status)
                                    @case (config('user.block'))
                                        <p>@lang('user.blocked')</p>
                                        @break
                                    @case (config('user.unconfirmed'))
                                        <p>@lang('user.unapprove')</p>
                                        @break
                                    @default
                                        <p>@lang('user.active')</p>
                                @endswitch
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="follow_jobs" id='candidate_list'>
                    @foreach ($candidates as $candidate)
                        <a href="{{ route('users.show', ['user' => $candidate]) }}">
                            <img src="{{ asset($candidate->image->url) }}" alt="" class="img-circle">
                            <div class="title">
                                <h5>{{ $candidate->name }}</h5>
                                @switch ($candidate->status)
                                    @case (config('user.block'))
                                        <p>@lang('user.blocked')</p>
                                        @break
                                    @case (config('user.unconfirmed'))
                                        <p>@lang('user.unapprove')</p>
                                        @break
                                    @default
                                        <p>@lang('user.active')</p>
                                @endswitch
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('js/list_user.js') }}"></script>
@endsection
