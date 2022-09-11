<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Prescription</title>
    <style>
        @media print {
            .m-0 {
                margin: 0;
            }

            .p-0 {
                padding: 0;
            }
  .font1 {
    font-size: 1rem;
  }
  .font2 {
    font-size: 2rem;
  }

        }
        @page {
    size: 7in 9.25in;
    margin: 27mm 16mm 27mm 16mm;
}
        html,body{
    height:297mm;
    width:210mm;
}

    </style>
</head>

<body>
    <div class="row ">
    <div class="col-12">
 <img src="{{asset('header_image.jpg')}}"/>
        </div>
        <div class="col-12">
            <div class=" container d-flex justify-content-between">
                <div class="col-3"><p class="font2"> name: {{ $prescription->patient->name }}</p> </div>
                @php
                $date = new DateTime($prescription->patient->birth_date);
                $now = new DateTime();
                $age = $now->diff($date)->y;
                @endphp

                <div class="col-3"><p class="font2">age: {{ $age }}</p> </div>
                <div class="col-3"> <p class="font2">sex: {{ $prescription->patient->sex }}</p></div>
                <div class="col-3"> <p class="font2">date:{{$prescription->created_at}}</p> </div>
            </div>

        </div>
        <div class="col-6">

            <div class="container">
                <h3 class="">CC:</h3>
                @foreach ($prescription->cc as $cc)
                    <p class="font1">{{ $cc->name }}: {{ $cc->value }}</p>
                @endforeach
            </div>
            {{-- <div class="container">
                <h3 class="">Past Medical History</h3>

                <p class="font2">{{ $prescription->past_medical_history }}</p>

            </div> --}}
            <div class="container">
                <h3 class="">Drug History</h3>

                <p class="">{{ $prescription->drug_history }}</p>

            </div>
            <div class="container">
                <h3 class="">O/E:</h3>
                @foreach ($prescription->oe as $oe)
                    <p class="font1">{{ $oe->name }}: {{ $oe->value }}</p>
                @endforeach
            </div>
            <div class="container">
                <h3 class="">Investigations:</h3>
                @foreach ($prescription->tests as $tests)
                    <p class="">{{ $tests->name }}</p>
                @endforeach
            </div>
            {{-- <div class="container">
                <h3 class="">Medical History</h3>
                @if ($prescription->medical_history)
                    @php
                        $medical_history = json_decode($prescription->medical_history);
                    @endphp
                    @if ($medical_history->undergoing_treatment)
                        <p class="">(1) Are you undergoing any medical treatment at present? <br>

                            Ans: Yes
                        </p>
                    @endif

  @php
    $showDeasesCollection =  collect($medical_history->diseases);
    $showDeases =   $showDeasesCollection->contains("checked",true);
  @endphp
@if ($showDeases)
<p class=""> Do you have, or have you had any of the following? b{{$showDeases}}

    a <br>
   Ans:
<div class="row container">
   @foreach ($medical_history->diseases as $diseases)
       @if ($diseases->checked)
           <div className="col-6">
               <div className="form-check">
                   <input className="form-check-input" type="checkbox" name="female" checked />

                   <label className="form-check-label">

                       {{ $diseases->name }}
                   </label>
               </div>
           </div>
       @endif
   @endforeach
</div>


</p>
@endif
@php
$showAllergiesCollection =  collect($medical_history->allergies);
$showAllergies =   $showAllergiesCollection->contains("checked",true);
@endphp

@if ($showAllergies)
<p class=""> Have You suffered allergy or other reactions (Rash, Itchiness etc) to: <br>
    Ans:
<div class="row container">
    @foreach ($medical_history->allergies as $allergies)
        @if ($allergies->checked)
            <div className="col-6">
                <div className="form-check">
                    <input className="form-check-input" type="checkbox" name="female" checked />

                    <label className="form-check-label">

                        {{ $allergies->name }}
                    </label>
                </div>
            </div>
        @endif
    @endforeach
</div>



</p>
@endif


                    @if ($medical_history->local_anaesthetics)
                        <p class=""> Have you ever had any adverse effects from local anaesthetics? <br>

                            Ans: yes
                        </p>
                    @endif
                    @if ($medical_history->prolonged_bleeding)
                        <p class=""> Have you ever experienced unusually prononged bleeding after injury or tooth
                            extraction? <br>
                            Ans: yes
                        </p>
                    @endif
                    @if ($medical_history->penicillin_given)
                        <p class=""> Have you ever been given penicillin? <br>

                            Ans:
                            yes

                        </p>
                    @endif
                    @if ($medical_history->taking_medicines)
                        <p class=""> Are you taking any medicines,tablets, injections (etc.) at present? <br>

                            Ans: yes

                        </p>
                        <p class="">
                            If yes can you please indicate the nature of this medication? <br>

                            Ans: {{ $medical_history->nature_of_medication }}

                        </p>
                    @endif

                    @php
                    $showTreatedCollection =  collect($medical_history->treated);
                    $showTreated =   $showTreatedCollection->contains("checked",true);
                    @endphp
@if ($showTreated)
<p class=""> Have you been treated with any of the following in the past 5 year:? <br>
    Ans:
<div class="row container">

    @foreach ($medical_history->treated as $treated)
        @if ($treated->checked)
            <div className="col-6">
                <div className="form-check">
                    <input className="form-check-input" type="checkbox" name="female" checked />

                    <label className="form-check-label">

                        {{ $treated->name }}
                    </label>
                </div>
            </div>
        @endif
    @endforeach

</div>


</p>
@endif

                    @if ($medical_history->recieved_radiotherapy)
                        <p class=""> (9) Have you ever recieved radioTherapy? <br>

                            Ans: yes

                        </p>
                    @endif

                    @if ($medical_history->smoke)
                        <p class=""> (10) Do you smoke?<br>
                            Ans: yes
                        </p>
                        <p class="">
                            If yes how much on average per day? <br>

                            Ans: {{ $medical_history->smoke_times }}

                        </p>
                    @endif


                    @php
                    $showFemaleCollection =  collect($medical_history->female);
                    $showFemale =   $showFemaleCollection->contains("checked",true);
                    @endphp

                    @if ($showFemale)
                    <p class="">(11) For female Patient? <br>
                        Ans:
                    <div class="row container">
                        @foreach ($medical_history->female as $female)
                            @if ($female->checked)
                                <div className="col-6">
                                    <div className="form-check">
                                        <input className="form-check-input" type="checkbox" name="female" checked />

                                        <label className="form-check-label">

                                            {{ $female->name }}
                                        </label>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    </p>
                    @endif

                    @if ($medical_history->other_information)
                        <p class="">
                            Please add any other information or comments on your medical history below<br>

                            Ans: {{ $medical_history->other_information }}

                        </p>
                    @endif



                @endif






            </div> --}}


                <div class="container">
                <h3 class="">Past Medical History</h3>
                @if ($prescription->medical_history)
                    @php
                        $medical_history = json_decode($prescription->medical_history);
                    @endphp


  @php
    $showDeasesCollection =  collect($medical_history->diseases);
    $showDeases =   $showDeasesCollection->contains("checked",true);
  @endphp
@if ($showDeases)
<p class="">

<div class="row container">
   @foreach ($medical_history->diseases as $diseases)
       @if ($diseases->checked)
           <div className="col-6">
               <div className="form-check">
                   <input className="form-check-input" type="checkbox" name="female" checked />

                   <label className="form-check-label">

                       {{ $diseases->name }}
                   </label>
               </div>
           </div>
       @endif
   @endforeach
</div>


</p>
@endif




















                @endif






            </div>


        </div>
        <div class="col-6">
            <div class="container">
                <h3 class="">Medicines:</h3>
                <div class="row ">
                    @foreach ($prescription->medicines as $medicines)
                        <div class="col-4 text-center">
                            {{ $medicines->product_name }}
                        </div>
                        <div class="col-4 text-center">
                            <span class="me-2">

                                {{ $medicines->morning }}
                            </span>
                            <span class="me-2">
                                +
                            </span>
                            <span class="me-2">
                                {{ $medicines->afternoon }}

                            </span>
                            <span class="me-2">
                                +
                            </span>

                            <span class="me-2">
                                {{ $medicines->night }}

                            </span>

                        </div>
                        <div class="col-4 text-center">
                            {{ $medicines->end_time }}
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- <div class="container">
                <h3 class="">History</h3>

                <p class="">{{ $prescription->note }}</p>

            </div>

            <div class="container">
                <h3 class="">Fee</h3>

                <p class="">{{ $prescription->fees }}</p>

            </div>
            <div class="container">
                <h3 class="">Next Appointment Date</h3>

                <p class="">{{ $prescription->fees }}</p>

            </div> --}}
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
</body>

</html>
