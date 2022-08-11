<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Prescription</title>
    <style>
        @media print {
  .m-0 {
  margin: 0;
  }
  .p-0 {
  padding: 0;
  }
  .col-print-1 {width:8%;  float:left;}
.col-print-2 {width:16%; float:left;}
.col-print-3 {width:25%; float:left;}
.col-print-4 {width:33%; float:left;}
.col-print-5 {width:42%; float:left;}
.col-print-6 {width:50%; float:left;}
.col-print-7 {width:58%; float:left;}
.col-print-8 {width:66%; float:left;}
.col-print-9 {width:75%; float:left;}
.col-print-10{width:83%; float:left;}
.col-print-11{width:92%; float:left;}
.col-print-12{width:100%; float:left;}
}
    </style>
  </head>
  <body>
   <div class="row">
    <div class="col-print-6">
<div class="row">
    <h3 class="m-0 p-0">Patient:</h3>
    <p class="p-0 m-0">name: {{$prescription->patient->name}}</p>
    <p class="p-0 m-0">sex: {{$prescription->patient->sex}}</p>
    @php
 $date = new DateTime($prescription->patient->birth_date);
 $now = new DateTime();
 $age = $now->diff($date)->y;

    @endphp
    <p class="p-0 m-0">age:  {{$age}}</p>

</div>
<div class="row">
    <h3 class="m-0 p-0">O/E:</h3>
    @foreach ($prescription->cc as  $cc)
    <p class="p-0 m-0">{{$cc->name}}: {{$cc->value}}</p>
    @endforeach
</div>
<div class="row">
    <h3 class="m-0 p-0">Patient History</h3>

    <p class="p-0 m-0">{{$prescription->patient_history}}</p>

</div>
<div class="row">
    <h3 class="m-0 p-0">Medical History</h3>
@if ($prescription->medical_history)
@php
   $medical_history = json_decode($prescription->medical_history);
@endphp
@if ($medical_history->undergoing_treatment)
<p class="p-0 m-0">(1) Are you undergoing any medical treatment at present? <br>

Ans: Yes
</p>

@endif

<p class="p-0 m-0"> Do you have, or have you had any of the following? <br>
Ans:     <div class="row">
@foreach ($medical_history->diseases as $diseases)
@if($diseases->checked)
<div className="col-print-6">
<div className="form-check" >
<input
className="form-check-input"
type="checkbox"
name="female"
checked
/>

<label
className="form-check-label"
>

{{$diseases->name}}
</label>
</div>
</div>
@endif


@endforeach
</div>


</p>
<p class="p-0 m-0"> Have You suffered allergy or other reactions (Rash, Itchiness etc) to: <br>
Ans: <div class="row">
@foreach ($medical_history->allergies as $allergies)
@if($allergies->checked)
<div className="col-print-6">
<div className="form-check" >
<input
className="form-check-input"
type="checkbox"
name="female"
checked
/>

<label
className="form-check-label"
>

{{$allergies->name}}
</label>
</div>
</div>
@endif


@endforeach
</div>



</p>
@if ($medical_history->local_anaesthetics)
<p class="p-0 m-0"> Have you ever had any adverse effects from local anaesthetics? <br>

Ans: yes
</p>


@endif
@if ($medical_history->prolonged_bleeding)
<p class="p-0 m-0">	 Have you ever experienced unusually prononged bleeding after injury or tooth extraction? <br>
Ans: yes
</p>
@endif
@if ($medical_history->penicillin_given)
<p class="p-0 m-0">	 Have you ever been given penicillin? <br>

Ans:
yes

</p>

@endif
@if ($medical_history->taking_medicines)
<p class="p-0 m-0">	 Are you taking any medicines,tablets, injections (etc.) at present? <br>

Ans:  yes

</p>
<p class="p-0 m-0">
If yes can you please indicate the nature of this medication? <br>

Ans: {{($medical_history->nature_of_medication)}}

</p>
@endif



<p class="p-0 m-0"> Have you been treated with any of the following in the past 5 year:? <br>
Ans:
<div class="row">

    @foreach ($medical_history->treated as $treated)
    @if($treated->checked)
    <div className="col-print-6">
        <div className="form-check" >
   <input
       className="form-check-input"
       type="checkbox"
       name="female"
       checked
   />

   <label
       className="form-check-label"
     >

     {{$treated->name}}
   </label>
</div>
        </div>
   @endif


    @endforeach

</div>


</p>
@if ($medical_history->recieved_radiotherapy)
<p class="p-0 m-0">	(9) Have you ever recieved radioTherapy? <br>

Ans:  yes

</p>
@endif

@if ($medical_history->smoke)
<p class="p-0 m-0">	 (10) Do you smoke?<br>
Ans: yes
</p>
<p class="p-0 m-0">
If yes how much on average per day? <br>

Ans: {{($medical_history->smoke_times)}}

</p>
@endif



<p class="p-0 m-0">(11) For female Patient? <br>
Ans:
<div class="row">
    @foreach ($medical_history->female as $female)
    @if($female->checked)
    <div className="col-print-6">
        <div className="form-check" >
   <input
       className="form-check-input"
       type="checkbox"
       name="female"
    checked
   />

   <label
       className="form-check-label"
     >

     {{$female->name}}
   </label>
</div>
        </div>
   @endif


    @endforeach
</div>

</p>
@if ($medical_history->other_information)
<p class="p-0 m-0">
 Please add any other information or comments on your medical history below<br>

Ans: {{($medical_history->other_information)}}

</p>
@endif



@endif






</div>
<div class="row">
    <h3 class="m-0 p-0">Medicines:</h3>
    <div class="row d-flex justify-content-between">
    @foreach ($prescription->medicines as  $medicines)

        <div class="col-print-4 text-center">
            {{$medicines->product_name}}
        </div>
        <div class="col-print-4 text-center">
            <span class="me-2">

            {{$medicines->morning}}
            </span>
            <span class="me-2">
            +
            </span>
            <span class="me-2">
                {{$medicines->afternoon}}

            </span>
            <span class="me-2">
            +
            </span>

            <span class="me-2">
                {{$medicines->night}}

            </span>

        </div>
        <div class="col-print-4 text-center">
            {{$medicines->end_time}}
        </div>



    @endforeach
</div>
</div>
<div class="row">
    <h3 class="m-0 p-0">Investigations:</h3>
    @foreach ($prescription->tests as  $tests)
    <p class="p-0 m-0">{{$tests->name}}</p>
    @endforeach
</div>


    </div>
    <div class="col-print-6">
        <div class="row">
            <h3 class="m-0 p-0">History</h3>

            <p class="p-0 m-0">{{$prescription->note}}</p>

        </div>

        <div class="row">
            <h3 class="m-0 p-0">Fee</h3>

            <p class="p-0 m-0">{{$prescription->fees}}</p>

        </div>
        <div class="row">
            <h3 class="m-0 p-0">Next Appointment Date</h3>

            <p class="p-0 m-0">{{$prescription->fees}}</p>

        </div>
    </div>
   </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>
