<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8"/>

@extends('layouts.app')

@section('content')

    <div class="container-md">
        <form method="POST" action="{{ route('create', Auth::user()) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row" style="margin-top: 5%">
                <div class="col-md-2"></div>
                <div class="col">
                    <h1>Create Event</h1>
                </div>
            </div>
            <br>
            <div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <label for="name">Event name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="input-group-append">
                            <select class="custom-select" id="idCategory" name="idCategory" required>
                                <option value="">Category</option>
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <label for="address">Address</label>
                    <div class="input-group">
                        <input type="text" name="address" id="address" class="form-control"
                            aria-label="Text input with dropdown button" required>
                        <div class="input-group-append">
                            <select class="custom-select" id="country" name="country"
                                required>
                                <option value="" selected>Country</option>
                                @foreach ($countries as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group-append">
                            <select class="custom-select" id="idLocation" name="idLocation" required>
                                <option value="" selected>City</option>
                                <!-- @foreach ($cities as $city)
                                <option value="{{$city->id}}">{{$city->name}}</option>
                                @endforeach -->
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <label for="date">Date (MM/DD/YYYY)</label>
                    <input class="form-control" name="date" type="datetime-local" min="@php $tomorrow = new DateTime('tomorrow'); echo $tomorrow->format('Y-m-d\TH:i:s') @endphp"
                        value="@php $tomorrow = new DateTime('tomorrow'); echo $tomorrow->format('Y-m-d\TH:i:s') @endphp" id="date" required>
                </div>
                <div class="col-md-2">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" placeholder="FREE" min="0" max="999" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="numberspots">Number of Spots</label>
                    <input type="number" id="numberspots" min="1" max="999" name="numberspots"
                        class="form-control" required>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="3" id="description" name="description" style="resize: none"
                        required></textarea>
                </div>
                <div class="col-md-3">
                    <label for="inputGroupFile02">Event Cover</label><br>
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input name="cover" type="file" class="custom-file-input"
                                id="inputGroupFile02">
                            <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row form-group" style="margin-bottom: 5%">
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg">Create</button>
                </div>
            </div>
        </form>
    </div>

<script>
    $(document).ready(function() {
        $('select[name="country"]').on('change', function() {
            var countryId = $(this).val();
            if(countryId) {
                $.ajax({
                    url: "{{route('cities.get')}}?idcountry="+countryId,
                    type: "GET",
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#idLocation').html(data.html);
                    }
                });
            }else{
                $('select[name="idLocation"]').empty();
            }
        });
    });
</script>

@endsection