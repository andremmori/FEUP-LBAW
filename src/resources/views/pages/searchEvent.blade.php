@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 2%;">
    <form method="POST" action="{{route('event.search')}}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-3"></div>
            <div class="col-9">
                <input type="text" name="event" placeholder="Event">
                <input type="text" name="location" placeholder="Location">
                <input placeholder="Date" name="date" type="text" onfocus="(this.type='date')"
                    onblur="(this.type='text')">
                <a href="{{ url('search')}}"></a><button class="btn btn-primary" type="submit"><i style="color: white"
                        class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="event-page">
    <div class="event-advanced">
        <div class="event-bar">
            <fieldset class="form-group">
                <legend>Category</legend>
                {{-- <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="Any" checked="" name="category">
                    Any
                </label>
            </div> --}}
                @foreach ($categories as $category)
                <div class="form-check disabled">
                    <label class="category-filter form-check-label">
                        <input class="form-check-input" id="category" type="checkbox" value="{{$loop->iteration}}"
                            name="category">
                        {{$category->name}}
                    </label>
                </div>
                @endforeach
            </fieldset>
        </div>
    </div>

    <div class="event-for" style="max-width: 40rem;">

    </div>
</div>

<script>
    $(document).ready(function () {
        filter_data();

        function filter_data() {
            var events = @php echo $events @endphp;
            console.log(events);

            var category = get_filter('category');
            $.ajax({
                url: "{{route('events.filter')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { events: events, category: category },
                success: function (data) {
                    $('.event-for').html(data.html);
                }
            });
        }
        function get_filter(id) {
            var filter = [];
            $('#' + id + ':checked').each(function () {
                filter.push($(this).val());
            });
            return filter;
        }

        $('.category-filter').click(function () {
            filter_data();
        });
    });
</script>

<script>
    var slider = document.getElementById("range");
    var output = document.getElementById("value");
    output.innerHTML = slider.value;

    slider.oninput = function () {
        output.innerHTML = this.value;
    }
</script>

@endsection


</html>