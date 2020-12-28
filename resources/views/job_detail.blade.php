@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="single">
            <div class="row">
                <div class="col-md-1 single_right"></div>
                <div class="col-md-6 single_right">
                    <div class="tab_grid">
                        <div class="jobs-item with-thumb">
                            <div class="thumb">
                                <a href="">
                                    <img src="" alt="" />
                                </a>
                            </div>
                            <div class="jobs_right">
                                <div class="date">{{ $job->created_at->format('d') }}
                                    <span>{{ $job->created_at->format('M') }}</span>
                                </div>
                                <div class="date_desc">
                                    <h6 class="title">
                                        <a>{{ $job->title }}</a>
                                    </h6>
                                    <span class="meta">{{ $job->company->address }}</span>
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-7 single_right">
                                <p>
                                    <b>@lang('job.company'): </b>
                                    {{ $job->company->name }}
                                </p>
                                <br>
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
                                <br>
                                <p>
                                    <b>@lang('job.salary'): </b>
                                    {{ $job->salary }}
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 single_right">
                                <p>
                                    <b>@lang('job.detail'): </b>
                                    {!! $job->description !!}
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 single_right">
                                <p>
                                    <b>@lang('job.company'): </b>
                                    {!! $job->company->introduce !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 single_right"></div>
                <div class="col-md-3">
                    <br><br><br>
                    <h4><b>@lang('job.salary'): </b></h4>
                    <h3><b>&#36; {{ $job->salary }}</b></h3>
                    <br>
                    <form action="{{ route('apply', ['id' => $job->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            @lang('job.applynow')
                        </button>
                    </form>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 single_right">
                            <p><i class="fa fa-comments"></i>@lang('job.support')</p>
                        </div>
                        <div class="col-md-8 single_right">
                            <p><i class="fa fa-external-link"></i> {{ $job->company->website }}</p>
                            <p><i class="fa fa-envelope"></i> {{ $job->company->user->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="single" >
                        <div class="col_3">
                            <h3>@lang('job.similar')</h3>
                            <ul class="list_1">
                                @foreach ($similarJobs as $similarJob)
                                    <li><a href="{{ route('jobs.show', ['job' => $similarJob]) }}">{{ $similarJob->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
@endsection
