@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('diary.diary') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('diary.diary') }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ trans('diary.total_diary') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalDiary }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-users"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ trans('admin/main.total_students') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalStudents }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body li">

            <section class="card">
                <div class="card-body">
                    <form action="{{ getAdminPanelUrl() }}/diary" method="get" class="row mb-0">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.search') }}</label>
                                <input type="text" class="form-control" name="title" value="{{ request()->get('title') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('diary.reference_type') }}</label>
                                <select name="reference_type" data-plugin-selectTwo class="form-control populate">
                                    <option value="">{{ trans('diary.all_diary') }}</option>
                                    @foreach(\App\Models\Diary::$referenceTypes as $referenceType)
                                            <option value="{{ $referenceType }}" @if(request()->get('reference_type') == $referenceType) selected @endif>{{ __('diary.'.$referenceType) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3 d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-primary w-100">{{ trans('admin/main.show_results') }}</button>
                        </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @can('admin_diary_exports')
                                <div class="text-right">
                                    <a href="{{ getAdminPanelUrl() }}/diary/excel?{{ http_build_query(request()->all()) }}" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                                </div>
                            @endcan

                            @can('admin_diary_creates')
                                <div class="text-right">
                                    <a href="{{ getAdminPanelUrl() }}/diary/create" class="btn btn-primary ml-2">{{ trans('diary.new_diary') }}</a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('admin/main.student') }}</th>
                                        <th class="text-center">{{ trans('diary.theme') }}</th>
                                        <th class="text-center">{{ trans('diary.reference_type') }}</th>
                                        <th class="text-center">{{ trans('admin/main.date') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($diaries as $diary)
                                        <tr>
                                            <td>
                                                <span>{{ $diary->title }}</span>
                                            </td>


                                            <td class="text-center">
                                                <span>{{ $diary->user->full_name }}</span>
                                            </td>


                                            <td class="text-center">
                                                <span>{{ $diary->theme }}</span>
                                            </td>

                                            <td class="text-center">
                                                <span>{{ __('diary.'.$diary->reference_type) }}</span>
                                            </td>

                                            <td class="text-center">

                                                <span>{{ $diary->dated_at }}</span>
                                            </td>
                                            <td>


                                                @can('admin_diary_edit')
                                                    <a href="{{ getAdminPanelUrl() }}/diary/{{ $diary->id }}/edit" class="btn-transparent btn-sm text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('admin_diary_delete')
                                                    @include('admin.includes.delete_button',['url' => getAdminPanelUrl().'/diary/'.$diary->id.'/delete' , 'btnClass' => 'btn-sm'])
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $diaries->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
