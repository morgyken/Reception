<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
extract($data);
$roles = get_this_user_roles();
?>
@extends('layouts.app')
@section('content_title','Patients')
@section('content_description','Featured list of all patients')

@section('content')
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Manage Patients</h3>
        </div>
        @include('reception::partials.search')
        <hr>
        <div class="box-body">
            <table id="patients_table" class="table table-condensed table-responsive table-striped" id="patients">
                <tbody class="results">
                <?php
                try {
                ?>
                @foreach($patients as $patient)
                    <tr id="patient{{$patient->id}}">
                        <td>{{$patient->id}}</td>
                        <td>{{$patient->number??'-'}}</td>
                        <td>{{$patient->full_name}}</td>
                        <td>{{$patient->dob }}</td>
                        <td>{{$patient->mobile}}</td>
                        <td>
                            <a class="btn  btn-xs" href="{{route('reception.view_patient',$patient->id)}}">
                                <i class="fa fa-eye-slash"></i> View</a>
                            <a class="btn  btn-xs" href="{{route('reception.add_patient',$patient->id)}}">
                                <i class="fa fa-pencil-square-o"></i> Edit
                            </a>
                            <a href="{{route('reception.checkin',$patient->id)}}" class="btn btn-xs">
                                <i class="fa fa-sign-in"></i> Check in</a>
                            <a href="{{route('reception.checkin',$patient->id)}}" class="btn btn-xs">
                                <i class="fa fa-sign-in"></i> Review
                            </a>
                            <a href="{{route('reception.upload_doc',$patient->id)}}" class="btn btn-xs btn-primary">
                                <i class="fa fa-folder-open-o"></i> View Files
                            </a>
                        </td>
                    </tr>
                @endforeach
                <?php
                } catch (\Exception $e) {
                    //Hepa
                }
                ?>
                </tbody>
                <thead>
                <tr>
                    <th>#</th>
                    <th>P. No</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Mobile</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>P. No</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Mobile</th>
                    <th>Actions</th>
                </tr>
                </tfoot>
            </table>
            {{ $patients->links() }}
        </div>
        <div class="box-footer">

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#patients_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ],
                "paginate": false,
                "lengthChange": true,
                "filter": false,
                "sort": true,
                "info": true,
                "autoWidth": true,
            });
        });
    </script>
@endsection