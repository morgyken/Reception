<?php extract($data); ?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Newest Patients</h3>
    </div><!-- /.box-header -->
    <div class="box-body no-padding">
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
    </div><!-- /.box-body -->
</div>
