@extends('skeleton')

@section('meta')
    <meta name="datatable-url" content="{{ url('api/' . Request::segment(1) . '/datatable') }}">
    <meta name="first-segment" content="{{ url(Request::segment(1)) }}">
    <meta name="first-segment-api" content="{{ url('api/' . Request::segment(1)) }}">
    <meta name="permission" update="{{ Auth::allowedUri(Request::segment(1) . '.edit') ? true : false }}"
        trash="{{ Auth::allowedUri(Request::segment(1) . '.trash') ? true : false }}">
@endsection

@section('toolbar')
    @if (Auth::allowedUri(Request::segment(1) . '.trashed'))
        <a href="{{ url(Request::segment(1) . '/trashed') }}" class="btn btn-lg btn-light-youtube">
            <span class="svg-icon svg-icon-danger svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <path
                            d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                            fill="currentColor" fill-rule="nonzero" />
                        <path
                            d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                            fill="currentColor" opacity="0.3" />
                    </g>
                </svg>
            </span>
            {{ __('Trash') }}
        </a>
    @endif
@endsection

@section('content')
    @php
        $column = [['data' => 'id']];
    @endphp
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div
            class="alert alert-dismissible bg-light-primary border border-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <span class="svg-icon svg-icon-2hx svg-icon-primary me-4 mb-5 mb-sm-0"><svg width="24" height="24"
                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.3"
                        d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z"
                        fill="currentColor"></path>
                    <path
                        d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z"
                        fill="currentColor"></path>
                </svg>
            </span>
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h5 class="mb-1">This is an alert</h5>
                <span>The alert component can be used to highlight certain parts of your page for higher content
                    visibility.</span>
            </div>
            <button type="button"
                class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                data-bs-dismiss="alert">
                <i class="bi bi-x fs-1 text-primary"></i>
            </button>
        </div>
        <!--end::Alert-->
        <div class="card card-flush h-md-100">
            <div class="card-header pt-7">
                <div class="my-1 d-flex align-items-center position-relative">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <input type="text" data-kt-user-table-filter="search"
                        class="form-control form-control-solid w-250px ps-14"
                        placeholder="{{ __('Search') }} {{ __($segmentName) }}" />
                </div>
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                        <div class="py-5 px-7">
                            <div class="fs-5 text-dark fw-bolder">{{ __('Filter Options') }}</div>
                        </div>
                        <div class="border-gray-200 separator"></div>
                    </div>
                    @if (Auth::allowedUri('vocabularies.generate-language'))
                        <a href="#" class="btn btn-success me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_1">
                            <span class="svg-icon svg-icon-2">
                                <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Media/Repeat.svg--><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M12,8 L8,8 C5.790861,8 4,9.790861 4,12 L4,13 C4,14.6568542 5.34314575,16 7,16 L7,18 C4.23857625,18 2,15.7614237 2,13 L2,12 C2,8.6862915 4.6862915,6 8,6 L12,6 L12,4.72799742 C12,4.62015048 12.0348702,4.51519416 12.0994077,4.42878885 C12.264656,4.2075478 12.5779675,4.16215674 12.7992086,4.32740507 L15.656242,6.46136716 C15.6951359,6.49041758 15.7295917,6.52497737 15.7585249,6.56395854 C15.9231063,6.78569617 15.876772,7.09886961 15.6550344,7.263451 L12.798001,9.3840407 C12.7118152,9.44801079 12.607332,9.48254921 12.5,9.48254921 C12.2238576,9.48254921 12,9.25869158 12,8.98254921 L12,8 Z"
                                            fill="currentColor" />
                                        <path
                                            d="M12.0583175,16 L16,16 C18.209139,16 20,14.209139 20,12 L20,11 C20,9.34314575 18.6568542,8 17,8 L17,6 C19.7614237,6 22,8.23857625 22,11 L22,12 C22,15.3137085 19.3137085,18 16,18 L12.0583175,18 L12.0583175,18.9825492 C12.0583175,19.2586916 11.8344599,19.4825492 11.5583175,19.4825492 C11.4509855,19.4825492 11.3465023,19.4480108 11.2603165,19.3840407 L8.40328311,17.263451 C8.18154548,17.0988696 8.13521119,16.7856962 8.29979258,16.5639585 C8.32872576,16.5249774 8.36318164,16.4904176 8.40207551,16.4613672 L11.2591089,14.3274051 C11.48035,14.1621567 11.7936615,14.2075478 11.9589099,14.4287888 C12.0234473,14.5151942 12.0583175,14.6201505 12.0583175,14.7279974 L12.0583175,16 Z"
                                            fill="currentColor" opacity="0.3" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            {{ __('Generate') }} {{ __('Vocabularies') }}
                        </a>
                    @endif
                    @if (Auth::allowedUri(Request::segment(1) . '.create'))
                        <a href="{{ url(Request::segment(1) . '/create') }}" class="btn btn-primary">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                        rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                        fill="currentColor" />
                                </svg>
                            </span>{{ __('New Record') }}
                        </a>
                    @endif
                </div>
                <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                    <div class="fw-bolder me-5">
                        <span class="me-2" data-kt-user-table-select="selected_count"></span>{{ __('Selected') }}
                    </div>
                    <button type="button" class="btn btn-danger"
                        data-kt-user-table-select="delete_selected">{{ __('Delete Selected') }}</button>
                </div>
            </div>
            <div class="card-body pt-6">
                <div class="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                            id="kt_datatable_example_1">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#kt_datatable_example_1 .form-check-input"
                                                value="1" />
                                        </div>
                                    </th>
                                    @foreach ($forms as $key => $items)
                                        @if ($items['display'])
                                            <th class="text-nowrap">{{ __($items['label']) }}</th>
                                            @switch($items['type'])
                                                @case('select2')
                                                    @php
                                                        $column[] = ['data' => $items['name'] . '.' . $items['options']['display']];
                                                    @endphp
                                                @break

                                                @case('sysparam')
                                                    @php
                                                        $column[] = ['data' => $items['name'] . '.' . $items['options']['display']];
                                                    @endphp
                                                @break

                                                @case('fileupload')
                                                    @php
                                                        $column[] = ['data' => $items['name'] . '.name'];
                                                    @endphp
                                                @break

                                                @default
                                                    @php
                                                        $column[] = ['data' => $items['name']];
                                                    @endphp
                                            @endswitch
                                        @endif
                                    @endforeach
                                    @php
                                        $column[] = ['data' => null];
                                    @endphp
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
    {{-- MODAL --}}
    <div class="modal fade" tabindex="-1" id="kt_modal_1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{ __('Create File') }}</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-1">
                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Navigation/Close.svg--><svg
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                        fill="#000000">
                                        <rect x="0" y="7" width="16" height="2"
                                            rx="1" />
                                        <rect opacity="0.3"
                                            transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000) "
                                            x="0" y="7" width="16" height="2"
                                            rx="1" />
                                    </g>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form action="{{ url('vocabularies/generate-language') }}" method="POST" id="generate-language">
                        @method('POST')
                        @csrf
                        <div class="form-group row">
                            <div class="col-xl-4 col-lg-4 col-form-label text-end">
                                <label class="required form-label">{{ __('Select Country') }}</label>
                            </div>
                            @component('_forms.select2.input',
                                [
                                    'data' => [
                                        'name' => 'code',
                                        'column' => 8,
                                        'label' => 'Select Countries',
                                        'options' => [
                                            'model' => 'countries',
                                            'key' => 'code',
                                            'display' => 'name',
                                        ],
                                    ],
                                ])
                            @endcomponent
                        </div>
                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i>
                        {{ __('Close') }}
                    </button>
                    <button type="button" id="kt_btn_1" class="btn btn-primary btn-submit"
                        onclick="$('#generate-language').submit()">
                        <span class="indicator-label">{{ __('Process') }}</span>
                        <span class="indicator-progress">{{ __('Please wait...') }}
                            <span class="align-middle spinner-border spinner-border-sm ms-2"></span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal --}}
    @php
        $columns = json_encode($column, true);
    @endphp
    <input type="hidden" name="data-columns" value="{{ $columns }}">
@endsection

@section('customjs')
    <script src="{{ asset('assets/js/custom/components/dataTable.js') }}"></script>
@endsection
