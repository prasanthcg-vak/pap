@extends('layouts.app')

@section('content')
    <style>
        .img-container .image-wrapper {
            height: 150px;
            /* Set a fixed height for consistent display */
            overflow: hidden;
        }

        .img-container .image-wrapper img {
            object-fit: cover;
            max-height: 100%;
            max-width: 100%;
        }
    </style>

    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <div class="campaign-card-contents">
                <div class="col-lg-12 p-0">
                    <div class="card">
                        <!-- Table -->
                        <div class="campaigns-title mb-4">
                            <h3>{{ $campaign->name }} - TASKS</h3>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary ms-4" style="float: right;">
                                <i class="fa fa-arrow-left"></i> 
                            </a>
                        </div>
                        <div class="campaingn-table common-table">
                            <div class="table-wrapper">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="campaingn-title">
                                                <span>Task Title</span>
                                            </th>
                                            <th>
                                                <span>Campaign</span>
                                            </th>
                                            <th>
                                                <span>Due Date</span>
                                            </th>
                                            <th class="description">
                                                <span>description</span>
                                            </th>
                                            <th class="">
                                                <span>active</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks as $task)
                                            <tr>
                                                <td class="campaingn-title">
                                                    <span>{{ $task->name }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ $campaign->name }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ $task->date_required }}</span>
                                                </td>
                                                <td class="description">
                                                    <span>{!! $task->description !!}
                                                    </span>
                                                </td>
                                                <td class="">
                                                    <span class="status {{ $task->is_active == 1 ? 'green' : 'red' }}"> {{ $task->is_active == 1 ? 'ACTIVE' : 'INACTIVE' }}</span>
                                                </td>
                                               
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Table -->
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <div class="card-pagination">

            </div>
            <!-- Pagination -->
        </div>
    </div>
@endsection
