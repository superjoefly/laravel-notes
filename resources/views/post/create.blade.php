@extends('master')

@section('content')
  <h1>Add a Post</h1>

    <form class="w3-container" method="post" action="/post" >

      {{csrf_field()}}

    <label class="w3-text-blue"><b>Title</b></label>
    <input class="w3-input w3-border" type="text" name="title" id="title">

    <br />

    <label class="w3-text-blue"><b>Body</b></label>
    <br />
    <textarea class="w3-border" rows="5" type="text" name="body" id="body" style="width: 75%; margin: auto;"></textarea>

    <br /><br />

    <button class="w3-btn w3-text-blue w3-xlarge w3-border w3-border-blue">Submit</button>

    @if ($errors->any())
        <div class="w3-panel w3-leftbar w3-border-red w3-pale-red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

  </form>
@endsection
