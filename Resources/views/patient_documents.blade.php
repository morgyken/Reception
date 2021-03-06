<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
extract($data);
?>
@extends('layouts.app')
@section('content_title','Patient Documents')
@section('content_description','View patient documents')

@section('content')
<div class="box box-info">
    <div class="box-body">
        @if($patients->isEmpty())
        <p class="text-warning"><i class="fa fa-warning"></i> No patients yet</p>
        @else
            @include('reception::partials.search')
        <table class="table table-condensed table-striped">
            <tbody>
                <tr>
                    <td><a href="{{route('reception.bulk_upload')}}" class="btn btn-primary btn-xs">
                            Upload files in bulk</a></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-condensed table-striped">
            <tbody>
                @foreach($patients as $patient)
                <?php try{ ?>
                <tr>
                    <td>{{$patient->id}}</td>
                    <td>{{$patient->full_name}}</td>
                    <td>{{$patient->mobile}}</td>
                    <td>{{$patient->id_no}}</td>
                    <td>{{(new Date($patient->dob))->format('d M Y')}}</td>
                    <td>
                        <a href="{{route('reception.upload_doc',$patient->id)}}" class="btn btn-xs btn-primary">
                            <i class="fa fa-folder-open-o"></i> View Files
                        </a>
                    </td>
                </tr>
                <?php }catch (\Exception $e){ } ?>
                @endforeach
            </tbody>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>ID Number</th>
                    <th>Date of Birth</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
            {{ $patients->links() }}
        @endif
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('table').DataTable({
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
