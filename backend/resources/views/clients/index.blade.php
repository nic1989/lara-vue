@extends('layouts.app', ['title' => __('Client Management')])

@section('content')
    @include('layouts.headers.cards')

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Clients') }}</h3>
                            </div>
                            @can('client-create')
                                <div class="col-4 text-right">
                                    <a href="{{ route('client.create') }}" class="btn btn-sm btn-primary">{{ __('Add Client') }}</a>
                                </div>
                            @endcan
                        </div>
                        {!! Form::open(['id' => 'modulesearch', 'method' => 'Get', 'class' => 'form-horizontal mt-3', 'route' => ['client.index']]) !!}
                            <div class="row">
                                <div class="col-md-2 col-sm-3">
                                    <div class="form-group">
                                        <input id="searchinput" placeholder="Search by Name" type="text" class="form-control" name="search" value="{{ $searchValue }}">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-3">
                                    <div class="form-group">
                                        <select name="status" class="form-control">
                                            <option @if($searchStatus == '') selected @endif>All</option>
                                            <option value="active" @if($searchStatus == 'active') selected @endif>Active</option>
                                            <option value="inactive" @if($searchStatus == 'inactive') selected @endif>inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('search') }}
                                        </button>
                                        <a href="{{Request::url()}}" class="btn btn-info">{{ __('clear') }}</a>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>


                    <div class="col-12">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Creation Date') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($clients) > 0)
                                    @foreach ($clients as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>
                                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                            </td>
                                            <td>
                                                @if($user->status == 1)
                                                    <span>Active</span>
                                                @else
                                                    <span>Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @can('client-edit')
                                                    <a class="mr-1" title="Edit" href="{{ route('client.edit', $user) }}"><i class="fa fa-edit"></i></a>
                                                @endcan
                                                @can('client-delete')
                                                    {!! Form::open(['method' => 'DELETE','route' => ['client.destroy', $user],'style'=>'display:inline']) !!}
                                                        <a title="Delete" href="javascript:void(0)" onclick="confirm('{{ __("Are you sure you want to delete this client?") }}') ? this.parentElement.submit() : ''">
                                                            <i class="fa fa-trash text-red"></i>
                                                        </a>
                                                    {!! Form::close() !!}
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="5">{{ __('No Record found') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $clients->appends(request()->except('page'))->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection