<!DOCTYPE html>
<html lang="en">

<head>
    <title>Percentile Calculation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">

    <!-- custom styles -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />

    <!-- slick slider -->
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
</head>

<body>
    <header>
        {{-- {{dd($data);}} --}}
        <nav class="navbar" id="navbar">
            <div class="container d-block">
                <div class="position-relative text-center">
                    <a href="{{url('/')}}" class="backArrow">
                        <img src="assets/images/arrow-left.png">
                    </a>
                    <h4 class="mb-0">{{$section}}</h4>
                    <h4 class="mb-0">{{$user}}</h4>
                </div>
            </div>
        </nav>
    </header>

    <div class="pageBody">
        <section>
            <div class="container py-5">
                <div class="row">
                    <form method="" action="javascript:void(0)" name="calculate_data" id="calculate_data" class="userDeatilsForm">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputpercentile" class="form-label">percentile</label>
                            <input type="text" name="percentile" value="{{isset($data['0']->percentile) ? $data['0']->percentile : ''}}" class="form-control" id="exampleInputpercentile">
                         </div>
                        
                        <input type="text" style="display: none" name="type" value="{{$title}}" class="form-control" id="type">

                        <button type="submit" class="btn btn-primary">Submit</button>
                      </form>
                </div>


            </div>
        </section>

        <section class="bottomGraphic"></section>
    </div>

    <footer>
        <div class="text-center footer">
            <p class="fw-bold">Powered by</p>
           <h1>ClickNConnect</h1>
        </div>
    </footer>

</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/script.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/jQuery.tagify.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">

<script>
    
    document.addEventListener('DOMContentLoaded', function () {
        var userForm = document.getElementById('calculate_data');

        userForm.addEventListener('submit', function (event) {
            event.preventDefault();

            var form_data = new FormData(userForm);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/calculate_data', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var responseText = xhr.responseText;
                    var responseData = JSON.parse(responseText);
                    console.log(responseData)
                    userForm.reset();

                } else {
                    alert('Error');
                }
            };
            xhr.onerror = function () {
                alert('Error');
            };
            xhr.send(form_data);
        });
    });
    </script>
</html>