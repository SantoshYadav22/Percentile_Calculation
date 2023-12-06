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
<style>
    .auth{       
    margin-top: -25px;
    float: right;
    }

   #Inputpercentile{
    display: flex;
    justify-content: space-around;
    }
</style>
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
                    <div class="auth" >
                        @if(Auth::check())
                        <p>Welcome, {{ Auth::user()->name }}</p>
                    @else
                        <p>Guest user</p>
                    @endif
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="pageBody">
        <section>
            <div class="container py-5">
                <button type="button" class="btn btn-primary" style="float:right;margin-top: -36px;margin-right: -20px;"  onclick="check_again()">Percentile Check</button>

                <div class="row">
                    <form method="" action="javascript:void(0)" name="calculate" id="calculate" class="userDeatilsForm">
                        @csrf
                                               
                        <div class="mb-3">
                          <label for="exampleInputname" class="form-label">Name</label>
                          <input disabled type="text" name="name" value="{{Auth::user()->name ? Auth::user()->name : ''}}" class="form-control" id="examplname" >
                        </div>
                        <div class="mb-3">
                          <label for="exampleInputphone" class="form-label">Phone</label>
                          <input disabled type="text" name="phone" value="{{Auth::user()->phone ? Auth::user()->phone : ''}}" class="form-control" id="exampleInputphone">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputemail" class="form-label">Email</label>
                            <input disabled type="text" name="email" value="{{Auth::user()->email ? Auth::user()->email : ''}}" class="form-control" id="exampleInputemail">
                        </div>
                        
                        
                        <input disabled type="text" style="display: none" name="type" value="{{$title}}" class="form-control" id="type">

                        {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                      </form>
                </div>
            </div>

            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Calculate Percentile</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="" action="javascript:void(0)" name="calculate_percentile" id="calculate_percentile" class="userDeatilsForm">
                            @csrf
                            <div class="mb-3">
                                <label for="exampleInputmarks" class="form-label">Enter Your Marks </label>
                                <input type="text" name="marks" maxlength="3" value="" class="form-control" id="exampleInputmarks" oninput="validateMarks(this)" required>
                            </div>                                   
                            <div class="mb-3">
                                <label for="exampleInputslot"  class="form-label">Select Your Slot</label>
                                    <select class="form-select" name="slot" aria-label="Default select example" required>
                                        <option value="">Select Slot</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                            </div>

                            
                            <button type="submit" class="btn btn-primary">Check Your Percentile</button>
                        </form>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      
                    </div>
                  </div>
                </div>
              </div>


              <div class="modal fade" id="result_percentile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="result_percentileLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="result_percentileLabel">Your Percentile Score</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">     
                        <div class="scoreBoard">
                            {{-- <h2 class="fw-normal">Hey !!!</span></h2> --}}
                            <div class="scoreCircle my-5">
                                <p class="mb-0">Your Score</p>
                                <span id="Inputpercentile" ></span>
                                <div class="animateCircle" style="animation-delay: 0s"></div>
                                <div class="animateCircle" style="animation-delay: 0.5s"></div>
                                <!-- <div class="animateCircle" style="animation-delay: 1s"></div> -->
                            </div>
                        
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary"  onclick="check_again()">Again Calculate</button>
                      
                    </div>
                  </div>
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
    
    // document.addEventListener('DOMContentLoaded', function () {
    //     var userForm = document.getElementById('calculate');

    //     userForm.addEventListener('submit', function (event) {
    //         event.preventDefault();

    //         var form_data = new FormData(userForm);
    //         var xhr = new XMLHttpRequest();
    //         xhr.open('POST', '/calculate', true);
    //         xhr.onload = function () {
    //             if (xhr.status === 200) {
    //                 var responseText = xhr.responseText;
    //                 var responseData = JSON.parse(responseText);
    //                 console.log(responseData)
    //                 userForm.reset();
    //                 window.location.href = "{{url('calculate_page')}}";

    //             } else {
    //                 alert('Error');
    //             }
    //         };
    //         xhr.onerror = function () {
    //             alert('Error');
    //         };
    //         xhr.send(form_data);
    //     });
    // });


    window.onload = () => {
      const myModal = new bootstrap.Modal('#staticBackdrop');
      myModal.show();
    }

    function validateMarks(input) {
        let sanitizedValue = input.value.replace(/[^0-9-]/g, '');
        sanitizedValue = sanitizedValue.replace(/^-+/g, '-');
        let numericValue = parseInt(sanitizedValue, 10);

        if (!isNaN(numericValue) && numericValue >= -66 && numericValue <= 198) {
            input.value = sanitizedValue;
        } else {
            input.value = '';
        }
    }


    document.addEventListener('DOMContentLoaded', function () {
        var userForm = document.getElementById('calculate_percentile');

        userForm.addEventListener('submit', function (event) {
            event.preventDefault();

            var form_data = new FormData(userForm);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/calculate_percentile', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var responseText = xhr.responseText;
                    var responseData = JSON.parse(responseText);
                    // console.log(responseData)
                    $("#staticBackdrop").modal('hide');
                    $("#result_percentile").modal('show');
                    document.getElementById('Inputpercentile').innerHTML = responseData.result;
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

    function check_again(){
        $("#staticBackdrop").modal('show');
        $("#result_percentile").modal('hide');
    }
    </script>
</html>