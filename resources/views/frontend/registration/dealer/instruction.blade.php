@extends('layouts.frontend.master')

@section('title', 'Dealer Instruction')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-5 px-5">
                <h1 class="mb-4 text-center">Dealer Instructions</h1>
                <h3 data-select-like-a-boss="1">1. Organisation</h3>
                <h4>1.1 RACE ORGANISATION</h4>
                <p>The &Ouml;TILL&Ouml; SWIMRUN WORLD SERIES is organised by &Ouml;TILL&Ouml; AB in Sweden and by SWIMRUN AG outside of Sweden.</p>
                <p>&Ouml;TILL&Ouml; AB<br />Brattlandsg&aring;rden 615<br />830 10 Unders&aring;ker<br />Sweden</p>
                <p>Email:&nbsp;<a href="mailto:michael@otilloswimrun.com">michael@otilloswimrun.com</a><br />Email:&nbsp;<a href="mailto:mats@otilloswimrun.com">mats@otilloswimrun.com</a></p>
                <h3>2. Entry pre-requisite</h3>
                <p>2.1 To be able to participate in &Ouml;TILL&Ouml; SWIMRUN WORLD SERIES you have to race in a team consisting of two persons that are at least 18 years old and able to swim.<br />2.2 Each team member needs to have a&nbsp;<a href="https://otilloswimrun.com/insurance/">valid insurance</a>&nbsp;for competing in swimrun<br />2.3 Your application is final. No entry fee will be reimbursed by the Race Organisation.<br />2.4 It is allowed to change one team member of the same gender.<br />2.5 No external sales of your entry is allowed<br />2.6 No entries can be deferred to another year<br />2.7 No entries can be deferred to another race</p>
                <h3>3. Race course</h3>
                <h4>3.1 RACE COURSE</h4>
                <p>3.1.1 The race course is marked<br />3.1.2 The participants must follow the marked course<br />3.1.3 The participants that do not follow the marked course will be disqualified</p>
                <h4>3.2 ENERGY STATIONS</h4>
                <p>3.2.1 There will be several water / food checkpoints throughout the course</p>
                <h4>3.3 MEDICAL POINTS</h4>
                <p>3.3.1 There will be several medical points where participants can get help if needed<br />3.3.2 There will be medical staff and equipment on boats and on land.</p>
                <h3>4 Equipment</h3>
                <h4>4.1 GENERAL ABOUT EQUIPMENT</h4>
                <p>4.1.1 All teams need to bring all their equipment from start to finish, if a team fail to bring all their equipment to the finish line they will be disqualified.</p>
                <h4>4.2 MANDATORY EQUIPMENT PER TEAM</h4>
                <p>1 First aid pressure bandage, packed waterproof<br />2 Wetsuits, 1 per person, suitable for water temperature of 10 degrees Celsius<br />1 Compass/Watch Compass<br />2 Whistles, 1 per person</p>
                <h4>4.3 MANDATORY EQUIPMENT THAT THE ORGANISATION WILL SUPPLY</h4>
                <p>Race bibs must be worn visible all the time during the race<br />Maps<br />Timing chip (lost or not returned timing chip will be charged 100 Euros or equivalent)<br />Swimcaps must be worn visible during all the swims</p>
                <h4>4.4 RECOMMENDED EQUIPMENT</h4>
                <p>4.4.1 Tether<br />4.4.2 Water bladder</p>
                <h4>4.5 EQUIPMENT CHECK BEFORE THE RACE</h4>
                <p>Before the race there will be a mandatory gear check.</p>
                <h4>4.6 EQUIPMENT THAT IS NOT ALLOWED</h4>
                <p>4.6.1 Floatation help that is bigger than 100 cm x 60 cm.<br />4.6.2 No wetsuits can be modified with paint or color that washes off in the water. This will result in immediate disqualification.<br />4.6.3 Swim fins are NOT allowed if the fin is longer than 15 cm. Measurement is from the toe to the end of the fin.</p>
                <h3>5 Timing</h3>
                <h4>5.1 TIMING</h4>
                <p>5.1.1 The time is from the start until the finish line<br />5.1.2 The team has to pass all timing checkpoints and the finish line together</p>
                <h4>5.2 CUT OFFS</h4>
                <p>5.2.1 Throughout the course there will be cut offs where the teams need to pass by a certain time to be able to continue racing.<br />5.2.2 Information about the cut off times will be given the day before the start</p>
            </div>
        </div>
        @if(!auth()->check() || !auth()->user()->hasRole('dealer'))
            <div class="row mb-5">
                <a role="button" href="{{ route('dealer-registration.index') }}" class="btn btn-success mx-auto mt-4">Registration Now</a>
            </div>
        @endif
    </div>
@endsection