
<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 *  Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
?>

<div class="row">
    <div class="col-md-12">
        <div class="form-horizontal">
            {!! Form::open(['files'=>true,'route'=>['reception.upload_doc',$data['patient']->id]])!!}
            <div class="form-group req {{ $errors->has('document_type') ? ' has-error' : '' }}">
                {!! Form::label('document_type', 'Document Type',['class'=>'control-label col-md-4']) !!}
                <div class="col-md-8">
                    <div id="tags">
                        {!! Form::select('document_type',config('system.document_types'), old('document_type'), ['class' => 'form-control']) !!}
                        {!! $errors->first('document_type', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="form-group req {{ $errors->has('file') ? ' has-error' : '' }}">
                {!! Form::label('doc', 'File',['class'=>'control-label col-md-4']) !!}
                <div class="col-md-8">
                    <div id="tags">
                        {!! Form::file('doc', ['class' => 'form-control']) !!}
                        {!! $errors->first('doc', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-4 col-md-8">
                    <button type="submit" id="upload" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <hr/>
    <div class="col-md-12">
        <div class="col-md-12">
            @if($data['docs']->isEmpty())
            <p class="text-warning"><i class="fa fa-warning"></i> No documents for this patient</p>
            @else
            <table class="table table-condensed" id="documents-tbl">
                <tbody>
                    @foreach($data['docs'] as $doc)
                    <tr id="row_id{{$doc->document_id}}">
                        <td><a href="{{route('reception.view_document',$doc->id)}}" target="_blank">
                                {{$doc->filename}}</a>
                        </td>
                        <td>{{config('system.document_types.'.$doc->document_type)}}</td>
                        <td>{{number_format($doc->description/1024,2)}} KiB</td>
                        <td>{{$doc->mime}}</td>
                        <td><a href="{{route('reception.view_document',$doc->id)}}" target="_blank">
                                <i class="fa fa-eye"></i> View
                            </a>
                            <button class="btn btn-xs btn-danger trash" value="{{$doc->id}}">
                                <i class="fa fa-trash-o "></i> Delete</button></td>
                    </tr>
                    @endforeach
                </tbody>
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>Document Type</th>
                        <th>Size</th>
                        <th>File type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
            @endif
        </div>
    </div>
</div>
<div class="modal fade"  id="delete_file" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete patient document?</h4>
                </div>
                <div class="modal-body">
                    <p>The selected file will be deleted permanently. </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="delete" ><i class="fa fa-trash-o"></i> Delete</button>
                    <button class="btn btn-primary" data-dismiss="modal">Nope</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var DELTE_FILE_URL = "{{route('ajax.delete_doc')}}";
    $(document).ready(function () {
        var discard = null;
        $('.trash').click(function () {
            discard = $(this).val();
            $('#delete_file').modal('show');
        });
        $("#delete").click(function () {
            if (!discard) {
                return;
            }
            $.ajax({
                type: 'DELETE',
                url: DELTE_FILE_URL,
                data: {'id': discard},
                success: function () {
                    $("#row_id" + discard).remove();
                },
                error: function (data) {
                }
            });
            $("#delete_file").modal('hide');
        });
        try {
            $('#documents-tbl').DataTable();
        } catch (e) {
            console.log(e);
        }
    });
</script>