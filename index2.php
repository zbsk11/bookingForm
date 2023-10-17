<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

  <title>Registration Page</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f4f4f4;
      font-family: Arial, sans-serif;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      color: #333;
    }

    /* Set the width of the input fields */
    input[type="text"],
    input[type="number"],
    input[type="email"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 16px;
      background-color: #f9f9f9;
      color: #333;
    }

    /* Add text-align: center; to center the time-slot buttons horizontally */
    #timeSlotButtons {
      text-align: center;
      display: flex;
      justify-content: center;
      flex-wrap: wrap; /* Add flex-wrap property for wrapping */
    }

    .time-slot-button {
    margin-right: 10px;
    border: 1px solid purple;
    border-radius: 15px;
    padding: 10px 20px;
    background-color: transparent;
    color: purple;
    transition: background-color 0.3s ease;
    width: 100px; /* Set a fixed width for all time slot buttons */
    margin-bottom: 10px; /* Add margin between rows */
    }

    .time-slot-button.selected {
      background-color: purple;
      color: #fff;
    }

    .booking-day-button {
      margin-right: 10px;
      border: none; /* Remove button borders */
      padding: 0; /* Remove button padding */
      width: 30px; /* Set a fixed width for the buttons */
      height: 30px; /* Set a fixed height for the buttons */
      border-radius: 50%; /* Create a circular shape */
    }

    .booking-day-button.selected {
      background-color: purple;
      color: #fff;
    }

    .day-label {
      text-align: center;
      font-weight: bold;
      font-size: 12px; /* Adjust the font size as needed */
    }

    .day-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 10px;
    }

    .day-names {
      display: flex;
      justify-content: center; /* Center the day names horizontally */
      margin-bottom: 10px;
    }

    .day-buttons {
      display: flex;
      justify-content: center; /* Center the buttons horizontally */
    }

    .day-buttons button {
      margin: 0 10px; /* Add margin between buttons */
    }

    .btn-book {
      background-color: purple;
      color: #fff;
      border: none;
      border-radius: 15px;
      padding: 15px 30px;
      text-align: center;
      display: block;
      margin: 0 auto;
    }

    .btn-book:disabled {
      background-color: #ccc; /* Change color for disabled state */
      cursor: not-allowed;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
      <div class="panel-heading text-center" style="margin-top: 20px; margin-bottom: 20px;">
        <h1 style="color: black; font-weight: bold;">Book an Appointment</h1>
      </div>

        <div class="panel-body">
          <form action="connect.php" method="post">
            <div class="row">
              <!-- Use Bootstrap grid system with three equal columns -->
              <div class="col-md-4">
                <div class="form-group">
                  <label for="firstName">Name</label>
                  <input type="text" class="form-control" id="firstName" name="firstName" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="number">Phone Number</label>
                  <input type="number" class="form-control" id="number" name="number" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" />
                </div>
              </div>
            </div>

            <div class="day-names" id="dayNames">
              <!-- Day names will be populated dynamically via JavaScript -->
            </div>
            <div class="day-buttons" id="bookingDayButtons">
              <!-- Booking Day buttons will be populated dynamically via JavaScript -->
            </div>
            <div class="form-group">
              <div id="timeSlotButtons">
                <!-- Time Slot buttons will be populated dynamically via JavaScript -->
              </div>
            </div>
            <div class="form-group">
              <label for="serviceName">Service Name</label>
              <select class="form-control" id="serviceName" name="serviceName">
                <option value="skin fade">Skin Fade</option>
                <option value="skin fade + dye">Skin Fade + Dye</option>
                <option value="shapeup">Shapeup</option>
                <option value="hair + beard">Hair + Beard</option>
              </select>
            </div>
            <!-- Hidden input to store selected day -->
            <input type="hidden" id="selectedDay" name="selectedDay" value="" />
            <!-- Hidden input to store selected time slot -->
            <input type="hidden" id="timeSlot" name="timeSlot" value="" />
            <input type="submit" class="btn btn-book" value="Book" id="bookButton" disabled /> <!-- Give the button an ID -->
            <input type="submit" value="Buy" id="buyButton">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use the full jQuery version -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
      // Function to update the booking day buttons
      function updateBookingDayButtons() {
        var currentDate = new Date();
        var bookingDayButtons = $("#bookingDayButtons");
        bookingDayButtons.empty();
        var dayNamesContainer = $("#dayNames");
        dayNamesContainer.empty();

        for (var i = 0; i < 7; i++) {
          var date = new Date(currentDate);
          date.setDate(currentDate.getDate() + i);
          var dayName = getDayName(date.getDay());
          var dayNumber = date.getDate();

          // Create a container for each day
          var dayContainer = $("<div>").addClass("day-container");

          // Create a day name label
          var dayLabel = $("<div>")
            .addClass("day-label")
            .text(dayName.substring(0, 3).toUpperCase()); // Use the first 3 letters in uppercase

          // Create a button for each booking day
          var button = $("<button>")
            .addClass("btn btn-default booking-day-button")
            .attr("type", "button")
            .data("day", dayName)
            .text(dayNumber); // Only display the date number

          // Add a click event handler to handle day selection
          button.click(function() {
            $(".booking-day-button").removeClass("selected");
            $(this).addClass("selected");
            $("#selectedDay").val($(this).data("day"));
            updateAvailableTimeSlots();
          });

          // Append the day label and button to the container
          dayContainer.append(dayLabel).append(button);
          bookingDayButtons.append(dayContainer);
        }
      }

      // Function to get the day name from a numeric day index (0 = Sunday, 1 = Monday, etc.)
      function getDayName(dayIndex) {
        var daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        return daysOfWeek[dayIndex];
      }

      // Function to update the time slots as buttons
      function updateAvailableTimeSlots() {
        var selectedDay = $("#selectedDay").val();

        // Check if the selected day is a day off
        $.ajax({
          type: "POST",
          url: "get_day_off_events.php",
          data: { selectedDay: selectedDay },
          dataType: "json",
          success: function(response) {
            var timeSlotButtons = $("#timeSlotButtons");
            timeSlotButtons.empty();

            if (response.isDayOff) {
              // If it's a day off, display a message and disable the submit button
              timeSlotButtons.append("<p>Fully booked</p>");
              $("input[type='submit']").prop("disabled", true);
            } else {
              // If it's not a day off, fetch available time slots
              $.ajax({
                type: "POST",
                url: "fetch_available_time_slots.php",
                data: { selectedDay: selectedDay },
                dataType: "json",
                success: function(response) {
                  if (response.length === 0) {
                    timeSlotButtons.append("<p>No available slots</p>");
                  } else {
                    // Limit the number of displayed slots to 5
                    var slotsToShow = response.slice(0, 50);

                    $.each(slotsToShow, function(index, slot) {
                      // Create a button for each time slot
                      var button = $("<button>")
                        .addClass("time-slot-button")
                        .attr("type", "button")
                        .data("slot", slot)
                        .text(slot);

                      // Add a click event handler to handle slot selection
                      button.click(function() {
                        $(".time-slot-button").removeClass("selected");
                        $(this).addClass("selected");
                        $("#timeSlot").val($(this).data("slot"));
                        $("input[type='submit']").prop("disabled", false); // Enable the submit button when a slot is selected
                      });

                      // Append the button to the container
                      timeSlotButtons.append(button);
                    });
                  }
                },
                error: function() {
                  console.log("Error fetching available time slots");
                }
              });
            }
          },
          error: function() {
            console.log("Error checking if the day is off");
          }
        });
      }

      // Function to open the bank details modal
      function openBankDetailsModal() {
        $('#bankDetailsModal').modal('show');
      }


      // Event handler for the "Buy" button
      $("#buyButton").click(function(event) {
        event.preventDefault();

        // Perform any necessary validation and data gathering here

        // Open the bank details modal
        openBankDetailsModal(); // Add this line to open the modal
      });


      // Add an event handler for the "Book" button to submit the form to connect.php and send a confirmation email
      $("#bookButton").click(function(event) {
        // Prevent the default form submission so that we can handle it with AJAX
        event.preventDefault();

        // Gather the form data
        var firstName = $("#firstName").val();
        var selectedDay = $("#selectedDay").val();
        var timeSlot = $("#timeSlot").val();
        var serviceName = $("#serviceName").val();
        var email = $("#email").val();
        var number = $("#number").val();

        // Send the form data to connect.php using AJAX
        $.ajax({
          type: "POST",
          url: "connect.php",
          data: {
            firstName: firstName,
            selectedDay: selectedDay,
            timeSlot: timeSlot,
            serviceName: serviceName,
            email: email,
            number: number
          },
          success: function(response) {
            // Handle the response from connect.php if needed
            console.log(response); // You can log the response for debugging

            // After successfully submitting the form to connect.php,
            // send the confirmation email using another AJAX request
            $.ajax({
              type: "POST",
              url: "emailIndex2.php",
              data: {
                firstName: firstName,
                selectedDay: selectedDay,
                timeSlot: timeSlot,
                serviceName: serviceName,
                email: email,
                number: number
              },
              success: function(response) {
                // Handle the response from emailIndex2.php if needed
                console.log(response); // You can log the response for debugging
                alert("Form submitted and confirmation email sent successfully.");
              },
              error: function() {
                alert("Error sending confirmation email.");
              }
            });
          },
          error: function() {
            alert("Error submitting the form to connect.php.");
          }
        });
      });

      // Event handler for the "Submit" button in the bank details modal
      $("#submitBankDetails").click(function() {
        // Gather payment data from your form
        var cardNumber = $("#cardNumber").val();
        var expirationDate = $("#expirationDate").val();
        var cvv = $("#cvv").val();
        var billingAddress = $("#billingAddress").val();
        var amount = 10.00; // Set the payment amount

        // Send the payment data to Worldpay's API using an AJAX request
        $.ajax({
            type: "POST",
            url: "worldpay_integration.php",
            data: {
                cardNumber: cardNumber,
                expirationDate: expirationDate,
                cvv: cvv,
                billingAddress: billingAddress,
                amount: amount
            },
            success: function(response) {
                // Handle the response from Worldpay
                console.log(response); // You may need to parse and process the response accordingly
                // Redirect the user to Worldpay's payment page using the received URL
                window.location.href = response.redirectUrl; // Replace with the actual response property
            },
            error: function() {
                alert("Error submitting payment data to Worldpay.");
            }
        });

        // Close the modal
        $('#bankDetailsModal').modal('hide');
      });




      // Trigger the initial call to populate the booking day buttons
      updateBookingDayButtons();
    });

  </script>
  <!-- Add this modal structure within your existing HTML structure -->
  <div class="modal fade" id="bankDetailsModal" tabindex="-1" role="dialog" aria-labelledby="bankDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="bankDetailsModalLabel">Enter Bank Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Bank details form -->
          <form id="bankDetailsForm">
            <div class="form-group">
              <label for="cardNumber">Card Number</label>
              <input type="text" class="form-control" id="cardNumber" name="cardNumber" required>
            </div>
            <div class="form-group">
              <label for="expirationDate">Expiration Date</label>
              <input type="text" class="form-control" id="expirationDate" name="expirationDate" placeholder="MM/YYYY" required>
            </div>
            <div class="form-group">
              <label for="cvv">CVV</label>
              <input type="text" class="form-control" id="cvv" name="cvv" required>
            </div>
            <div class="form-group">
              <label for="billingAddress">Billing Address</label>
              <textarea class="form-control" id="billingAddress" name="billingAddress" required></textarea>
            </div>
            <div class="form-group">
              <label for="amount">Amount</label>
              <input type="text" class="form-control" id="amount" name="amount" value="10.00" readonly>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="submitBankDetails">Submit</button>
        </div>
      </div>
    </div>
  </div>


</body>
</html>