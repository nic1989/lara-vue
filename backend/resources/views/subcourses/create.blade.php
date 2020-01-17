@extends('layouts.app', ['title' => __('Sub Course Management')])

@section('content')
    @include('layouts.navbars.commanheader', ['title' => __('Add Sub Course')])   

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Sub Course Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('subcourse.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('subcourse.store') }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="1" name="status">
                            <h6 class="heading-small text-muted mb-4">{{ __('Sub Course information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-name" class="form-control form-control-alternative{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required autofocus>
                                    
                                    @if ($errors->has('title'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('video_file') ? ' has-danger' : '' }}">
                                    <label class="form-control-label">{{ __('Upload Video') }}</label>
                                    <input type="file" name="video_file" id="video_file" class="form-control{{ $errors->has('video_file') ? ' is-invalid' : '' }}" />
                                    
                                    @if ($errors->has('video_file'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('video_file') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('course_id') ? ' has-danger' : '' }}">
                                    @if($errors->has('course_id'))
                                        @php $class = 'form-control is-invalid' @endphp
                                    @else
                                        @php $class = 'form-control' @endphp
                                    @endif
                                    
                                    <label class="form-control-label">{{ __('Course') }}</label>
                                    <select name="course_id" class="{{$class}}">
                                        <option value=''>Please Select Course</option>
                                        @foreach($courses as $key => $course)
                                            <option value="{{$key}}">{{$course}}</option>
                                        @endforeach
                                    </select>
                                    
                                    @if ($errors->has('course_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('course_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name">{{ __('Sort Order') }}</label>
                                    <input type="number" name="sort_order" class="form-control form-control-alternative" placeholder="{{ __('Sort Order') }}" value="{{ old('sort_order') }}" required autofocus style="width:60px">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection