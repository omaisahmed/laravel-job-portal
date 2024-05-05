@include('front.message')
<div class="card shadow border-0 px-5 py-5">
    <div class="job_details_header">
        <div class="single_jobs white-bg d-flex justify-content-between">
            <div class="jobs_left d-flex align-items-center">

                <div class="jobs_conetent">
                    <a href="#">
                        <h4>{{ $job->title }}</h4>
                    </a>
                    <div class="links_locat d-flex align-items-center">
                        <div class="location">
                            <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                        </div>
                        <div class="location">
                            <p> <i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="jobs_right">
                <div class="apply_now {{ $count == 1 ? 'saved-job' : '' }}">
                    <a class="heart_mark " href="javascript:void(0);" onclick="saveJob({{ $job->id }})"> <i
                            class="fa fa-heart-o" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="descript_wrap white-bg">
        <div class="single_wrap">
            <h4>Job description</h4>
            {!! nl2br($job->description) !!}


        </div>
        @if (!empty($job->responsibility))
            <div class="single_wrap">
                <h4>Responsibility</h4>
                {!! nl2br($job->responsibility) !!}
            </div>
        @endif
        @if (!empty($job->qualifications))
            <div class="single_wrap">
                <h4>Qualifications</h4>
                {!! nl2br($job->qualifications) !!}
            </div>
        @endif
        @if (!empty($job->benefits))
            <div class="single_wrap">
                <h4>Benefits</h4>
                {!! nl2br($job->benefits) !!}
            </div>
        @endif
        <div class="border-bottom"></div>
        <div class="pt-3 text-end">

            @if (Auth::check())
                <a href="#" onclick="saveJob({{ $job->id }});" class="btn btn-secondary">Save</a>
            @else
                <a href="javascript:void(0);" class="btn btn-secondary disabled">Login to Save</a>
            @endif

            @if (Auth::check())
                <a href="#" onclick="applyJob({{ $job->id }})" class="btn btn-primary">Apply</a>
            @else
                <a href="javascript:void(0);" class="btn btn-primary disabled">Login to Apply</a>
            @endif


        </div>
    </div>
</div>

{{-- @if (Auth::user())
    @if (Auth::user()->id == $job->user_id)
        <div class="card shadow border-0 mt-4">
            <div class="job_details_header">
                <div class="single_jobs white-bg d-flex justify-content-between">
                    <div class="jobs_left d-flex align-items-center">
                        <div class="jobs_conetent">
                            <h4>Applicants</h4>
                        </div>
                    </div>
                    <div class="jobs_right"></div>
                </div>
            </div>
            <div class="descript_wrap white-bg">
                <table class="table table-striped">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Applied Date</th>
                    </tr>
                    @if ($applications->isNotEmpty())
                        @foreach ($applications as $application)
                            <tr>
                                <td>{{ $application->user->name }}</td>
                                <td>{{ $application->user->email }}</td>
                                <td>{{ $application->user->mobile }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($application->applied_date)->format('d M, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">Applicants not found</td>
                        </tr>
                    @endif

                </table>

            </div>
        </div>
    @endif
@endif --}}
