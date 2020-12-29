<!DOCTYPE HTML>
<html>

<head>
    <title>@lang('home.title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel='stylesheet'
        type='text/css' />
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
    <link href="{{ asset('bower_components/lato-font/css/lato-font.min.css') }}">
    <link href="{{ asset('css/mycss.css') }}" rel="stylesheet" type='text/css'>
    <link href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    @yield('css')
</head>

<body>
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset(config('user.logo')) }}"
                        alt="" /></a>
            </div>
            <div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="{{ route('jobs.index') }}">@lang('home.listjobs')</a>
                    </li>
                    @if (!Auth::check())
                        <li>
                            <a href="{{ route('login') }}">@lang('home.login')</a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}">@lang('home.register')</a>
                        </li>
                    @elseif (Auth::user()->role_id === config('user.employer'))
                        <li>
                            <a class="" type="button" data-toggle="dropdown">@lang('home.profile_user')
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a
                                        href="{{ route('users.show', ['user' => Auth::user()->id]) }}">@lang('layout.view_profile')</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('users.edit', ['user' => Auth::user()->id]) }}">@lang('layout.edit_profile')</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="" type="button" data-toggle="dropdown">@lang('home.profile_company')
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a
                                        href="{{ route('companies.show', ['company' => Auth::user()->company->id]) }}">@lang('layout.view_company')</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('companies.edit', ['company' => Auth::user()->company->id]) }}">@lang('layout.edit_company')</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('history')}}">@lang('layout.list_job_created')</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('jobs.create')}}">@lang('layout.create_job')</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="">
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <input id="logout-input" type="submit" value="@lang('home.logout')">
                                </form>
                            </a>
                        </li>
                    @else
                        <li>
                            <a class="" type="button" data-toggle="dropdown">@lang('home.profile_user')
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a
                                        href="{{ route('users.show', ['user' => Auth::user()->id]) }}">@lang('layout.view_profile')</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('users.edit', ['user' => Auth::user()->id]) }}">@lang('layout.edit_profile')</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('show_apply_list') }}">@lang('layout.show_apply_list')</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="">
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <input id="logout-input" type="submit" value="@lang('home.logout')">
                                </form>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a class="" type="button" data-toggle="dropdown">@lang('home.language')
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('change-language', ['locale' => Config::get('user.en')]) }}">@lang('home.en')</a>
                            </li>
                            <li>
                                <a href="{{ route('change-language', ['locale' => Config::get('user.vi')]) }}">@lang('home.vi')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="clearfix"> </div>
        </div>
    </nav>
    @yield('content')
    <div class="footer">
        <div class="container">
            <div class="col-md-3 grid_3">
                <h4>@lang('home.navigate')</h4>
                <ul class="f_list f_list1">
                    <li><a href="">@lang('home.home')</a></li>
                    <li><a href="">@lang('home.signin')</a></li>
                    <li><a href="">@lang('home.joinnow')</a></li>
                    <li><a href="">@lang('home.about')</a></li>
                </ul>
                <ul class="f_list">
                    <li><a href="">@lang('home.feature')</a></li>
                    <li><a href="">@lang('home.terms')</a></li>
                    <li><a href="">@lang('home.contact')</a></li>
                    <li><a href="">@lang('home.post')</a></li>
                </ul>
                <div class="clearfix"> </div>
            </div>
            <div class="col-md-3 grid_3">
                <h4>@lang('home.twitters')</h4>
                <div class="footer-list">
                    <ul>
                        <li><i class="fa fa-twitter tw1"> </i>
                            <p><span class="yellow"><a
                                        href="">@lang('home.consectetuer')</a></span>@lang('home.adipiscing')</p>
                        </li>
                        <li><i class="fa fa-twitter tw1"> </i>
                            <p><span class="yellow"><a
                                        href="">@lang('home.consectetuer')</a></span>@lang('home.adipiscing')</p>
                        </li>
                        <li><i class="fa fa-twitter tw1"> </i>
                            <p><span class="yellow"><a
                                        href="">@lang('home.consectetuer')</a></span>@lang('home.adipiscing')</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3 grid_3">
                <h4>@lang('home.seeking')</h4>
                <p>@lang('home.introduce')</p>
            </div>
            <div class="col-md-3 grid_3">
                <h4>@lang('home.signup_email')</h4>
                <form>
                    <input type="text" class="form-control" placeholder="@lang('home.email')">
                    <button type="button" class="btn red">@lang('home.subcribe')</button>
                </form>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
    @yield('script')
</body>

</html>
