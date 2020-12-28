@extends('layouts.app')

@section('content')
@include('layouts.search')
<div class="container">
    <div class="single">
        <div class="col-md-9 single_right">
            <div class="but_list">
                <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs" role="tablist">
                        <li role="presentation" id="all-job"  class="active">
                            <a href="" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile">@lang('job.all')</a>
                        </li>
                        <li role="presentation" id="suitable-job">
                            <a href="" id="home-tab" role="tab" data-toggle="tab" aria-controls="home">@lang('job.suitable')</a>
                        </li>
                    </ul>
                    <div id="all-job-list" class="tab-content" >
                        <div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
                            @foreach ($jobs as $job)
                                <div class="tab_grid">
                                    <div class="jobs-item with-thumb">
                                        <div class="thumb">
                                            <a href=""><img src="" class="img-responsive" alt="" /></a>
                                        </div>
                                        <div class="jobs_right">
                                            <div class="date">{{ $job->created_at->format('d') }}
                                                <span>{{ $job->created_at->format('M') }}</span>
                                            </div>
                                            <div class="date_desc">
                                                <h6 class="title">
                                                    <a href="{{ route('jobs.show', ['job' => $job]) }}">{{ $job->title }}</a>
                                                </h6>
                                                <p>{{ $job->company->address }}</p>
                                                <span class="meta"></span>
                                            </div>
                                            <div class="clearfix"> </div>
                                            <br>
                                            <div class="col-md-5 single_right">
                                                <p>
                                                    <b>@lang('job.company'): </b>
                                                    {{ $job->company->name }}
                                                </p>
                                                <span class="flex">
                                                    <p><b>@lang('job.tag'): </b></p>
                                                    @foreach ($job->tags as $tag)
                                                        <form action="{{ route('job_by_tag', ['id' => $tag->id]) }}" method="GET">
                                                        @csrf
                                                            <p>
                                                                &nbsp;
                                                                <button class="tag">{{ $tag->name }}</button>
                                                            </p>

                                                        </form>
                                                    @endforeach
                                                </span>
                                            </div>
                                            <div class="col-md-5 single_right">
                                                <p>
                                                    <b>@lang('job.exp'): </b>
                                                    {{ $job->experience }}
                                                </p>
                                                <p>
                                                    <b>@lang('job.salary'): </b>
                                                    {{ $job->salary }}
                                                </p>
                                            </div>
                                            <div class="col-md-2 single_right">
                                                <form action="{{ route('apply', ['id' => $job->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">
                                                        @lang('job.apply')
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="clearfix"> </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                    <div id="suitable-job-list" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
                            @foreach ($suitableJobs as $job)
                                <div class="tab_grid">
                                    <div class="jobs-item with-thumb">
                                        <div class="thumb">
                                            <a href="">
                                                <img src="" class="img-responsive" alt="" />
                                            </a>
                                        </div>
                                        <div class="jobs_right">
                                            <div class="date">{{ $job->created_at->format('d') }}
                                                <span>{{ $job->created_at->format('M') }}</span>
                                            </div>
                                            <div class="date_desc">
                                                <h6 class="title">
                                                    <a href="{{ route('jobs.show', ['job' => $job]) }}">{{ $job->title }}</a>
                                                </h6>
                                                <p>{{ $job->company->address }}</p>
                                                <span class="meta"></span>
                                            </div>
                                            <div class="clearfix"> </div>

                                            <br>
                                            <div class="col-md-5 single_right">
                                                <p>
                                                    <b>@lang('job.company'): </b>
                                                    {{ $job->company->name }}
                                                </p>
                                                <span class="flex">
                                                    <p><b>@lang('job.tag'): </b></p>
                                                    @foreach ($job->tags as $tag)
                                                        <form action="{{ route('job_by_tag', ['id' => $tag->id]) }}" method="GET">
                                                        @csrf
                                                            <p>
                                                                &nbsp;
                                                                <button class="tag">{{ $tag->name }}</button>
                                                            </p>
                                                        </form>
                                                    @endforeach
                                                </span>
                                            </div>
                                            <div class="col-md-5 single_right">
                                                <p>
                                                    <b>@lang('job.exp'): </b>
                                                    {{ $job->experience }}
                                                </p>
                                                <p>
                                                    <b>@lang('job.salary'): </b>
                                                    {{ $job->salary }}
                                                </p>
                                            </div>
                                            <div class="col-md-2 single_right">
                                                <form action="{{ route('apply', ['id' => $job->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">
                                                        @lang('job.apply')
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="clearfix"> </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="col_3">
                <h3>@lang('job.skill')</h3>
                <table class="table">
                    <tbody>
                        <input type="hidden" id="_token" value="{{csrf_token()}}">
                        @foreach ($skills as $skill)
                            <tr class="unread checked">
                                <td class="hidden-xs">
                                    <input type="checkbox" name="tag[]" value="{{ $skill->id }}"class="checkbox">
                                </td>
                                <td class="hidden-xs">
                                    {{ $skill->name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <h3>@lang('job.language')</h3>
                <table class="table">
                    <tbody>
                        @foreach ($langs as $lang)
                            <tr class="unread checked">
                                <td class="hidden-xs">
                                    <input type="checkbox" name="tag[]" value="{{ $lang->id }}"class="checkbox">
                                </td>
                                <td class="hidden-xs">
                                    {{ $lang->name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <h3>@lang('job.working_time')</h3>
                <table class="table">
                    <tbody>
                        @foreach ($workingTimes as $workingTime)
                            <tr class="unread checked">
                                <td class="hidden-xs">
                                    <input type="checkbox" name="tag[]" value="{{ $workingTime->id }}"class="checkbox">
                                </td>
                                <td class="hidden-xs">
                                    {{ $workingTime->name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('js/suitable_job.js') }}"></script>
    <script src="{{ asset('js/filter_job.js') }}"></script>
@endsection
