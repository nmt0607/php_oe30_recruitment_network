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
                        <a href="" id="employer" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Employer List</a>
                    </li>
                    <li role="presentation">
                        <a href="" role="" id="candidate" data-toggle="tab" aria-controls="profile">Candidate List</a>
                    </li>
                </ul>
                <div class="follow_jobs" id='employer_list'>
                    @foreach ($employers as $employer)
                        <a href="">
                            <img src="images/f1.jpg" alt="" class="img-circle">
                            <div class="title">
                                <h5>{{ $employer->name }}</h5>
                                <p>Employer</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="follow_jobs" id='candidate_list' style="display: none;">
                    @foreach ($candidates as $candidate)
                        <a href="">
                            <img src="{{ asset('images/f4.jpg') }}" alt="" class="img-circle">
                            <div class="title">
                                <h5>{{ $candidate->name }}</h5>
                                <p>Candidate</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
<script>
    let employer =document.getElementById('employer')
    let candidate =document.getElementById('candidate')
    let employer_list =document.getElementById('employer_list')
    let candidate_list =document.getElementById('candidate_list')
    employer.addEventListener('click',()=>{
        employer_list.style.display="";
        candidate_list.style.display="none";
    })
    candidate.addEventListener('click',()=>{
        employer_list.style.display="none";
        candidate_list.style.display="";
    })
</script>
@endsection
