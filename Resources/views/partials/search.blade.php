<?php
/**
 * Created by PhpStorm.
 * User: bravoh
 * Date: 10/16/17
 * Time: 12:12 PM
 */
?>
<div class="input-group">
    {!! Form::open(['route'=>['reception.patient.search'],'class'=>'navbar-form navbar-left','role'=>'search'])!!}
    <div class="form-group">
        <input type="text" name="search" size="50" class="form-control" placeholder="Search: name, mobile or id-number">
    </div>
    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>Search</button>
    {!! Form::close() !!}
</div>
