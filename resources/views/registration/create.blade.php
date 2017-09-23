@extends('master')

@section('content')
  <h1>Registration Form</h1>
  <form class="w3-container" method="post" action="/register" >

    {{csrf_field()}}

  <label class="w3-text-blue"><b>Name</b></label>
  <input class="w3-input w3-border" type="text" name="name" id="name">

  <label class="w3-text-blue"><b>Email</b></label>
  <input class="w3-input w3-border" type="text" name="email" id="email">

  <label class="w3-text-blue"><b>Password</b></label>
  <input class="w3-input w3-border" type="password" name="password" id="password">

  <h3>Toys:</h3>
  <div>
    <p>
    <input class="w3-check" type="checkbox" name="toys[]" value="1" />
    <label>Toy1</label>

    <input class="w3-check" type="checkbox" name="toys[]" value="2" />
    <label>Toy2</label>

    <input class="w3-check" type="checkbox" name="toys[]" value="3" />
    <label>Toy3</label>
    </p>
  </div>

  <br />

  <button class="w3-btn w3-text-blue w3-border w3-border-blue">Submit</button>

  </form>
@endsection
