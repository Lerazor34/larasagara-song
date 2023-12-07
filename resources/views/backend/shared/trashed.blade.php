@extends('skeleton')

@section('meta')
<meta name="datatable-url" content="{{ url('api/' . Request::segment(1) . '/datatable') }}">
<meta name="first-segment" content="{{ url(Request::segment(1)) }}">
<meta name="first-segment-api" content="{{ url('api/'.Request::segment(1)) }}">
<meta name="permission" restore="{{ Auth::allowedUri(Request::segment(1).'.restore') ? true : false }}" delete="{{ Auth::allowedUri(Request::segment(1).'.delete') ? true : false }}">
<meta name="status" content="2">
@endsection

@section('customcss')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('toolbar')
<a href="{{ url()->previous() }}" class="btn btn-light-success">
    <span class="svg-icon svg-icon-success svg-icon-2"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Navigation/Arrow-left.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <polygon points="0 0 24 0 24 24 0 24"/>
            <rect fill="currentColor" opacity="0.3" transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1"/>
            <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="currentColor" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/>
        </g>
    </svg><!--end::Svg Icon--></span>
    {{ __('Back') }}
</a>
@endsection

@section('content')

<?php
    $column = [
        ['data' => 'id']
    ];
?>
<div id="kt_app_content_container" class="app-container container-fluid">
    <div class="card card-flush h-md-100">
        <div class="card-header pt-7">
            <div class="my-1 d-flex align-items-center position-relative">
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                    </svg>
                </span>
                <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="{{ __('Search') }} {{ __($segmentName) }}"/>
            </div>
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
            </div>
            <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                <div class="fw-bolder me-5">
                <span class="me-2" data-kt-user-table-select="selected_count"></span>{{ __('Selected') }}</div>
                <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">{{ __('Delete Selected') }}</button>
            </div>
            <!--end::Toolbar-->
        </div>
        <div class="card-body pt-6">
            <div class="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_datatable_example_1">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_datatable_example_1 .form-check-input" value="1"/>
                                    </div>
                                </th>
                                @foreach($forms as $key => $items)
                                    @if($items['display'])
                                    <th class="text-nowrap">{{ __($items['label']) }}</th>
                                    <?php
                                        if($items['type'] == 'select2') {
                                            $column[] = ['data' => $items['name'] . '.' . $items['options']['display']];
                                        } else {
                                            $column[] = ['data' => $items['name']];
                                        }
                                    ?>
                                    @endif
                                @endforeach
                                <?php $column[] = ['data' => null]; ?>
                                <th class="text-end min-w-100px"></th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $columns = json_encode($column, true); ?>
<input type="hidden" name="data-columns" value="{{ $columns }}">
@endsection

@section('customjs')
<script src="{{ asset('assets/js/custom/components/dataTable.js') }}"></script>
@endsection
