<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 *  Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
$f_vitals = \Dervis\Modules\Evaluation\Entities\PatientVitals::whereHas('visits', function ($query) use ($patient) {
            return $query->where('patient', $patient->patient_id);
        })->first();
$checkout = '';
if (!empty($data['section'])) {
    $checkout = route('evaluation.sign_out', [$data['visit'], $data['section']]);
}
?>
<div class="row">
    <div class="col-md-12">
        <dl class="dl-horizontal">
            <div class="col-md-3">
                <dt>Name:</dt>
                <dd>{{$patient->full_name}}
                    <strong>({{(new Date($patient->dob))->age}} years)</strong>
                </dd>
                <dt>NO:</dt>
                <dd>
                    {{m_setting('reception.patient_id_abr')}}{{$invoice->patient->id}}
                </dd>
            </div>
            <div class="col-md-6">
                @if(!empty($f_vitals))
                <dt>Chronic Illnesses: </dt><dd>{{$f_vitals->chronic_illnesses}}</dd>

                <dt>Allergies: </dt><dd>{{$f_vitals->allergies}}</dd>
                @else
                <dt>Allergies</dt>
                <dd>No record</dd>
                <dt>Chronic illnesses</dt>
                <dd>No record</dd>
                @endif
            </div>

            <div class="col-md-3">
                @if(!$data['visits']->sign_out)
                <a class="btn btn-primary" href="{{$checkout}}">
                    <i class="fa fa-sign-out"></i> Check out</a>
                @else
                <p>Patient signed out for this visit</p>
                @endif
            </div>
            <hr/>
        </dl>
    </div>
</div>