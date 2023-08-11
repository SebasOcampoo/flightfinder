<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Flight Finder</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include Select 2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
    <!-- Include SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
</head>
<body>
<nav class="navbar navbar-lg navbar-dark bg-dark">
    <span class="navbar-brand mb-0 h1">
        <img src="/images/logo.svg" width="30" height="30" class="d-inline-block align-top mr-2" alt="" loading="lazy">
        Flight Finder
    </span>
    </nav>
<div class="section">
    <div class="container text-center">
        <img src="/images/header_flights.svg" width="70%" height="70%" class="img-fluid d-inline-block align-top" alt="Responsive image">
    </div>
    <div class="container mt-5">
        <form id="search-form">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="departure">Departure:</label>
                    <select id="departure" name="departure" class="form-control select2">
                        <option value="">Select Departure Airport</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->code }}">{{ $airport->name }} ({{ $airport->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="arrival">Arrival:</label>
                    <select id="arrival" name="arrival" class="form-control select2">
                        <option value="">Select Arrival Airport</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->code }}">{{ $airport->name }} ({{ $airport->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="stops">Stops:</label>
                    <select id="stops" name="stops" class="form-control select2">
                        <option value="">Select Stops</option>
                        @foreach($stops as $stop)
                            <option value="{{ $loop->index }}">{{ $stop }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary float-right"><i class="fas fa-search"></i> Search Flights</button>
        </form>
    </div>
</div>
<div class="section">
    <hr>
    <div class="container mt-5">
        <div id="results" class="mt-4" style="display: none;">
            <h2 class="mb-3">Search Results</h2>
            <ul id="results-list" class="list-group"></ul>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<!-- Bootstrap 4 JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function () {

    $(".select2").select2();

    $("#search-form").submit(function (event) {
        event.preventDefault();

        const departure = $("#departure").val();
        const arrival   = $("#arrival").val();
        const stops     = $("#stops").val();

        $.ajax({
            url: "/search-flights",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            data: { departure: departure, arrival: arrival , stops: stops },
            success: function (response) {
                const resultsList = $("#results-list");
                resultsList.empty();

                if (response.length > 0) {
                    $("#results").show();
                    let lowestPrice = response[0].price;

                    $.each(response, function (index, flight) {

                        const listItem = $("<li>")
                            .addClass("list-group-item d-flex justify-content-between align-items-center")
                            .html(`<span class="mr-2"><i class="fas fa-plane-departure fa-lg"></i> ${flight.departure_airport} </span>
                                <span class="ml-2"><i class="fas fa-plane-arrival fa-lg"></i> ${flight.arrival_airport} </span>`);

                        if (flight.price != lowestPrice) {
                            listItem.append(`<h5><span class="badge badge-primary badge-pill">€${flight.price}</span></h5>`);
                        }else{
                            listItem.addClass("list-group-item-success");
                            listItem.append(`<h4><span class="badge badge-warning badge-pill">€${flight.price} Lowest Price!</span></h4>`);
                        }
                        resultsList.append(listItem);

                        if (flight.stopovers.length > 0) {

                            listItem.append(`<button class="btn btn-link stopover-toggle" type="button" data-toggle="collapse"
                            data-target="#collapseStopover${index}" aria-expanded="false" aria-controls="collapseStopover${index}"
                            title="Show Stops"><i class="fas fa-chevron-down"></i></button>`);

                            const stopoverCollapse = $("<div>")
                                .addClass("collapse mt-3")
                                .attr("id", `collapseStopover${index}`);
                            let stopOversBadges = '';

                            $.each(flight.stopovers, function (index, stop) {
                                stopOversBadges += `<h4><span class="badge badge-secondary mb-2">Stop Over: ${stop}</span></h4>`;
                            });
                            stopoverCollapse.html(stopOversBadges);
                            resultsList.append(stopoverCollapse);
                        }

                    });
                } else {
                    $("#results").hide();
                    showNoResultsModal();
                }

            },
            error: function (error) {
                console.error(error);
            }
        });
    });

    // Function to show a modal using SweetAlert2
    function showNoResultsModal() {
        Swal.fire({
            icon: 'info',
            title: 'No Results Found',
            text: 'No flights matching your criteria were found.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }
});
</script>
</body>
</html>
