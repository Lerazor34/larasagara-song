@extends('skeleton')

@section('breadcrumbs')
    @foreach ($breadcrumbs as $key => $items)
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-600">{{ __(ucwords(str_replace('_', ' ', $items))) }}
    @endforeach
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card card-bordered">
            <div class="card-header ribbon ribbon-end">
                <div class="ribbon-label bg-primary">{{ ucwords(str_replace('.',' ',request()->route()->getName())) }}</div>
                <div class="card-title">{{ ucwords(str_replace('.',' ',request()->route()->getName())) }}</div>
            </div>
            <div class="card-body pt-6">
                @foreach ($forms as $form)
                    <div class="form-group row mb-8">
                        <div class="col-xl-2 col-lg-2 col-form-label text-end">
                            <label
                                class="{{ isset($form['required']) && $form['required'] == true ? 'required' : '' }} form-label">{{ __($form['label']) }}</label>
                        </div>
                        @component('_forms.' . $form['type'] . '.readonly', ['data' => $form])
                        @endcomponent
                    </div>
                @endforeach
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ url(Request::segment(1)) }}" type="button" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Cancel') }}
                </a>
                <div class="d-flex">
                    @if (Auth::allowedUri(Request::segment(1) . '.edit'))
                        <a href="{{ url(Request::segment(1) . '/' . Request::segment(2)) . '/edit' }}" type="button"
                            class="btn btn-success me-3">
                            <i class="fas fa-pencil"></i>
                            {{ __('Update') }}
                        </a>
                    @endif
                    @if (Auth::allowedUri(Request::segment(1) . '.trash'))
                        <a data-remote="{{ url('api/' . Request::segment(1) . '/' . Request::segment(2) . '/trash') }}"
                            data-tablename="{{ Request::segment(1) }}"
                            data-message="Are you sure want to delete this data ?" type="button"
                            class="btn btn-danger delete-row">
                            <i class="fas fa-trash"></i>
                            {{ __('Delete') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
