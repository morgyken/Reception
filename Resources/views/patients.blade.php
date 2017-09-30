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
    <div class="col-md-12">
        <input type="text" size="20" id="search_patient" placeholder="Search Patient Name or or ID Number" class="col-xs-4">
        <a class="btn btn-xs btn-primary pull-right" href="{{route('reception.show_patients','all')}}">View entire patient list</a><br>
    </div>
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
                        <td>{{$patient->full_name}}</td>
                        <td>{{$patient->id_no}}</td>
                        <td>{{(new Date($patient->dob))->format('d/m/Y') }}</td>
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
                                <i class="fa fa-sign-in"></i> Review</a>
                            <?php if (in_array(5, $roles)) { ?>
                                <!-- <a style="color: red" href="{{route('reception.purge_patient',$patient->id)}}" class="btn btn-xs">
                                     <i class="fa fa-trash"></i>delete</a> -->
                            <?php } ?>
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
                    <th>Name</th>
                    <th>ID Number</th>
                    <th>DOB</th>
                    <th>Mobile</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="box-footer">

    </div>
</div>

<script type="text/javascript">
    var GET_PATIENTS_FOR_MANAGE = "{{route('api.reception.manage_patients')}}";
    $('#search_patient').keyup(function () {
        get_patients(this.value);
    });
    $(document).ready(function () {
        $('#patients_table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    });

    function get_patients(term) {
        $.ajax({
            url: GET_PATIENTS_FOR_MANAGE,
            data: {'term': term},
            success: function (data) {
                $('.results').html(data);
            }});
    }
</script>
@endsection