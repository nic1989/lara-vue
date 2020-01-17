@extends('layouts.app', ['title' => __('Quiz Management')])

@section('content')
    @include('layouts.navbars.commanheader', ['title' => __('Edit Quiz')])   

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
                        <form id="QuizForm" method="post" action="{{ route('quiz.update', $quiz) }}" autocomplete="off">
                            @csrf
                            @method('put')
                            <h6 class="heading-small text-muted mb-4">{{ __('Quiz information') }}</h6>

                            <div class="pl-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Quiz Title') }}</label>
                                    <input name="quiz_title" class="form-control required" value="{{$quiz->quiz_title}}">
                                    <span class="invalid-feedback" id="quizTitleError" style="display:none">
                                        <strong>{{ __('Quiz Title is required') }}</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Course') }}</label>
                                    <select name="course_id" class="form-control required" onchange="getSubCourse(this.value, '')">
                                        <option value=''>Please Select Course</option>
                                        @foreach($courses as $key => $course)
                                            <option value="{{$key}}" @if($key == $quiz->course_id) selected @endif>{{$course}}</option>
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
                                    @foreach($questionDetail as $key => $question)
                                        <div id="portion_{{$key}}" class="border border-dark px-2 mt-3">
                                            <a href="javascript:void(0)" class="float-right removeQes"><i class="fa fa-trash"></i></a>
                                            <div class="form-group">
                                                <label class="form-control-label">{{ __('Question Type') }}</label>
                                                <select name="quiz[question_type][{{$key}}]" id="questionType_{{$key}}" class="form-control required" onchange="getQuestionsByType(event, this.value)">
                                                    <option value="">choose Question</option>
                                                    <option value="text" @if($question['question_type'] == 'text') selected @endif>Text</option>
                                                    <option value="select" @if($question['question_type'] == 'select') selected @endif>Mutiple Choice</option>
                                                </select>
                                                <span class="invalid-feedback" id="QtypeError" style="display:none">
                                                    <strong>{{ __('Atleast add one Question type') }}</strong>
                                                </span>
                                                @if($question['question_type'] == 'select')
                                                    <span class="selectBoxHelp" style="display:block">
                                                        <mark><small>Choose one radio button for the correct answer</small></mark>
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            @if ($question['question_type'] == 'select')
                                                <div id="quesdiv_{{$key}}" class="form-group">
                                                    <label class="form-control-label">Question</label>
                                                    <input typt="text" class="form-control required" name="quiz[select][{{$key}}][question]" value="{{$question['question']}}">
                                                    <span class="invalid-feedback" style="display:none">
                                                        <strong>This field is required</strong>
                                                    </span>

                                                    <div class="row mt-2">
                                                        @foreach($question['option'] as $opkey => $option)
                                                            @if($opkey < 2)
                                                                <div class="col-md-3 option">
                                                                    <div class="float-left">
                                                                        <input typt="text" id="option_{{$opkey}}" class="form-control required" name="quiz[select][{{$key}}][option][{{$opkey}}]" value="{{$option['option_val']}}">
                                                                        <span class="invalid-feedback" style="display:none">
                                                                            <strong>This field is required</strong>
                                                                        </span>
                                                                    </div>
                                                                    <input type="radio" @if($option['is_correct'] == 1) checked @endif name="quiz[select][{{$key}}][answer]" value="{{$opkey}}" class="ml-2">
                                                                </div>
                                                            @else
                                                                <div class="col-md-3 mb-2 option">
                                                                    <div class="float-left">
                                                                        <input typt="text" id="option_{{$opkey}}" class="form-control required" name="quiz[select][{{$key}}][option][{{$opkey}}]" value="{{$option['option_val']}}">
                                                                    </div>
                                                                    <div class="float-right">
                                                                        <input type="radio" @if($option['is_correct'] == 1) checked @endif	name="quiz[select][{{$key}}][answer]" value="{{$opkey}}" class="ml-2">
                                                                        <a href="javascript:void(0)" class="ml-2 remove"><i class="fa fa-trash"></i></a>
                                                                    </div>
                                                                    <span class="invalid-feedback" style="display:none">
                                                                        <strong>This field is required</strong>
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        @endforeach    
                                                    </div>

                                                    <button type="button" onclick="addOption({{$key}})" class="btn btn-info mt-2">Add Option</button>
                                                </div>
                                            @endif

                                            @if ($question['question_type'] == 'text')
                                                <div id="quesdiv_{{$key}}" class="form-group">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Question</label>
                                                        <input typt="text" class="form-control required" name="quiz[text][{{$key}}][question]" value="{{$question['question']}}">
                                                        <span class="invalid-feedback" style="display:none">
                                                            <strong>This field is required</strong>
                                                        </span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-control-label">Answer</label>
                                                        <textarea class="form-control required" name="quiz[text][{{$key}}][answer]" rows="10" cols="50">{{$question['option'][0]['answer']}}</textarea>
                                                        <span class="invalid-feedback" style="display:none">
                                                            <strong>This field is required</strong>
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach
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
    <script type="text/javascript">
        $(document).ready(function() {
            var courseId = '<?php echo $quiz->course_id; ?>';
            var subCourseId = '<?php echo $quiz->subcourse_id; ?>';
            getSubCourse(courseId, subCourseId);
        })
    </script>
    <script src="{{ asset('argon') }}/js/quiz.js"></script>
@endpush