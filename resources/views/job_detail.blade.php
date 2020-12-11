@extends('layouts.app')

@section('content')
<div class="container">
    <div class="single">
        <div class="row">
            <div class="col-md-1 single_right"></div>
            <div class="col-md-7 single_right">
                <div class="tab_grid">
                    <div class="jobs-item with-thumb">
                        <div class="thumb"><a href=""><img src="" class="img-responsive"
                            alt="" /></a></div>
                            <div class="jobs_right">
                                <div class="date">{{ $job->created_at->format('d') }}<span>{{ $job->created_at->format('M') }}</span></div>
                                <div class="date_desc">
                                    <h6 class="title"><a href="">{{ $job->title }}</a></h6>
                                    <span class="meta">{{ $job->company->address }}</span>
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 single_right">
                            <p>
                                <b>@lang('job.company'): </b>
                                {{ $job->company_id }}
                            </p>
                            <br>
                            <p>
                                <b>@lang('job.tag'): </b>
                                @foreach ($job->tags as $tag)
                                    <button class="tag">{{ $tag->name }}</button>
                                @endforeach
                            </p>
                        </div>
                        <div class="col-md-6 single_right">
                            <p>
                                <b>@lang('job.exp'): </b>{{ $job->experience }}
                            </p>
                            <br>
                            <p>
                                <b>@lang('job.salary'): </b>{{ $job->salary }}
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
                                <b>@lang('job.company')</b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1 single_right"></div>
                <div class="col-md-9 single_right">
                    <hr>
                    <h5>@lang('job.similar')</h5>
                    @foreach ($similarJobs as $similarJob)
                        <div class="tab_grid">
                            <div class="jobs-item with-thumb">
                                <div class="thumb"><a href=""><img src="" class="img-responsive"
                                    alt="" /></a></div>
                                <div class="jobs_right">
                                    <div class="date">{{ $similarJob->created_at->format('d') }}
                                        <span>{{ $similarJob->created_at->format('M') }}</span>
                                    </div>
                                    <div class="date_desc">
                                        <h6 class="title">
                                            <a href="">{{ $similarJob->title }}</a>
                                        </h6>
                                        <span class="meta">{{ $similarJob->company->address }}</span>
                                    </div>
                                    <div class="clearfix"> </div>
                                    <br>
                                    <div class="col-md-6 single_right">
                                        <p>
                                            <b>@lang('job.company'): </b>
                                            {{ $similarJob->company->name }}
                                        </p>
                                        <p>
                                            <b>@lang('job.tag'): </b>
                                            @foreach($similarJob->tags as $tag)
                                                <button class="tag">{{ $tag->name }}</button>
                                            @endforeach
                                        </p>
                                    </div>
                                    <div class="col-md-6 single_right">
                                        <p>
                                            <b>@lang('job.exp'): </b>{{ $similarJob->experience }}
                                        </p>
                                        <p>
                                            <b>@lang('job.salary'): </b>{{ $similarJob->salary }}
                                        </p>
                                    </div>
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        <div class="clearfix"> </div>
    </div>
</div>
@endsection
