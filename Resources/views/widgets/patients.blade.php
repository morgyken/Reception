<?php extract($data); ?>
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Patient List</h3>
    </div>
    <div class="box-body">
        @if($patients->count()>0)
        <table class="table table-condensed table-responsive table-striped" id="patients">
            <tbody>
                @foreach($patients as $patient)
                <tr id="patient{{$patient->id}}">
                    <td>{{$patient->id}}</td>
                    <td>{{$patient->full_name}}</td>
                    <td>{{$patient->mobile}}</td>
                </tr>
                @endforeach
            </tbody>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Mobile</th>
                </tr>
            </thead>
        </table>
        @else
        <p class="text-warning"><i class="fa fa-info"></i> No patients! Strange</p>
        @endif
    </div>
    <div class="box-footer">

    </div>
</div>