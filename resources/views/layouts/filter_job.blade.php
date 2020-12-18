<div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
    @foreach ($jobs as $job)
    <div class="tab_grid">
        <div class="jobs-item with-thumb">
            <div class="thumb">
                <a href="">
                    <img src="" class="img-responsive" alt="" />
                </a>
            </div>
            <div class="jobs_right">
                <div class="date">{{ $job->created_at->format('d') }}<span>{{ $job->created_at->format('M') }}</span></div>
                <div class="date_desc">
                    <h6 class="title">
                        <a href="">{{ $job->title }}</a>
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
                    <p>
                        <b>@lang('job.tag'): </b>
                        @foreach ($job->tags as $tag)
                        <button class="tag">{{ $tag->name }}</button>
                        @endforeach
                    </p>
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
