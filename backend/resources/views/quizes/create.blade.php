@extends('layouts.app', ['title' => __('Quiz Management')])

@section('content')
    @include('layouts.navbars.commanheader', ['title' => __('Add Quiz')])   

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Quiz Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('quiz.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="QuizForm" method="post" action="{{ route('quiz.store') }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <h6 class="heading-small text-muted mb-4">{{ __('Quiz information') }}</h6>

                            <div class="pl-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Quiz Title') }}</label>
                                    <input name="quiz_title" class="form-control required" value="">
                                    <span class="invalid-feedback" id="quizTitleError" style="display:none">
                                        <strong>{{ __('Quiz Title is required') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Course') }}</label>
                                    <select name="course_id" class="form-control required" onchange="getSubCourse(this.value, '')">
                                        <option value=''>Please Select Course</option>
                                        @foreach($courses as $key => $course)
                                            <option value="{{$key}}">{{$course}}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" id="courseError" style="display:none">
                                        <strong>{{ __('Course Id is required') }}</strong>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">{{ __('SubCourse') }}</label>
                                    <select id="subcourseId" name="subcourse_id" class="form-control">
                                        
                                    </select>
                                </div>
                                
                                <div id="questionDiv">
                                    <div id="portion_0" class="border border-dark px-2">
                                        <div class="form-group">
                                            <label class="form-control-label">{{ __('Question Type') }}</label>
                                            <select name="quiz[question_type][0]" id="questionType_0" class="form-control required" onchange="getQuestionsByType(event, this.value)">
                                                <option value="">choose Question</option>
                                                <option value="text">Text</option>
                                                <option value="select">Mutiple Choice</option>
                                            </select>
                                            <span class="invalid-feedback" id="QtypeError" style="display:none">
                                                <strong>{{ __('Atleast add one Question type') }}</strong>
                                            </span>
                                            <span class="selectBoxHelp" style="display:none"><mark><small>Choose one radio button for the correct answer</small></mark></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="button" onclick="addQuestion()" class="btn btn-info mt-4">{{ __('Add Question') }}</button>
                                </div>

                                <div class="text-center">
                                    <button type="button" onclick="return checkvalidation()" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/js/quiz.js"></script>
@endpush