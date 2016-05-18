<?php
// require valid admin login
require 'check_login.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cancel Boat Selection</title>
    <meta charset = "UTF-8">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
  <script>

    $(function () {

            //init datepickers once boats added
            var boatDates = []; //will hold json data
            //can use getManifestBoats.php because it's same code we need here
            $.getJSON("getManifestBoats.php", function(jsonDates){
                for(i=0; i < jsonDates.length; i++){
                    boatDates.push(jsonDates[i]);
                }
                $("#cancelBoatDate").datepicker({
                    minDate: '+1',
                    maxDate: '+30',
                    beforeShowDay: function(testDate){
                        var d = testDate.getDate();
                        var m = testDate.getMonth();
                        m += 1; //since months are 0-11
                        var y = testDate.getFullYear();
                        //format date yyyy-mm-dd
                        var formatDate = y + '-' +
                            (m<10 ? '0' : '') + m + '-' +
                            (d<10 ? '0' : '') + d;
                        if ($.inArray(formatDate, boatDates) > -1){
                            return [true];
                        }
                        else {
                            return [false];
                        }
                    }
                });

        });
        //hide error messages on initial load
        $('#cancelBoatDateReq').hide();
        $('#cancelBoatTimeReq').hide();

        //remove error msgs on changing values          q
        $('#cancelBoatDate').on('change', function(){
            $('#cancelBoatDateReq').hide();
            $('#cancelBoatTimeReq').hide();
        });

    });
    function whenSubmit(){
        //remove any submit msg
        $('#subMsg').html("");
            //check date and time
            if(!$('#cancelBoatDate').val()){
                $('#cancelBoatDateReq').show();
                return false;
            }
            else if (!$('#cancelBoatTime').val()) {
                $('#cancelBoatTimeReq').show();
                return false;
            }
        //submit if passes all
        var formData = $('#cancelBoatForm').serialize();
        //return true to complete submit
        return true;
     }

</script>


</head>
<body>
  <div id="outerDiv">
      <form id="cancelBoatForm" action="cancelBoatConfirm.php" method="post"
          onsubmit="return whenSubmit()" novalidate>
      <br />
      <legend><b>Cancel Boat Selections</b></legend>
      <br />
      <div id="cancelBoatDiv">
         <p>
         <label for="cancelBoatDate">Select Cancel Boat Date:  </label>
         </p>
         <p><input id="cancelBoatDate" name="cancelBoatDate" type="text"></p>
         <div id="cancelBoatDateReq">Select a date for the boat to cancel.</div>
      </div>
      <div id="cancelBoatTimeDiv">
        <p>
        <label for="cancelBoatTime">Select Time from Anacortes: </label>
        </p>
         <select id="cancelBoatTime" name="cancelBoatTime">
              <script>
                  $('#cancelBoatDate').on('change', function () {
                      var dd = new Date($('#cancelBoatDate').val());
                      var d = dd.getDate();
                      var m = dd.getMonth();
                      m += 1; //since months are 0-11
                      var y = dd.getFullYear();
                      var formatDate = y + '-' +
                          (m < 10 ? '0' : '') + m + '-' +
                          (d < 10 ? '0' : '') + d; //format date yyyy-mm-dd
                      $('#cancelBoatDate').val(formatDate);
                      var sendDate = $('#cancelBoatForm').serialize();
                      var cancelBoatTimes = [];
                      $.post("getTimesCancelBoat.php",
                      sendDate,
                      function (jsonTimes) {
                          console.log(jsonTimes);
                          for (i = 0; i < jsonTimes.length; i++) {
                              cancelBoatTimes.push(jsonTimes[i]);
                              //console.log(toDecTimes[i]);
                          }
                          $('#cancelBoatTime').html("<option value=''>" +
                              "Select time.</option>");
                          for (i = 0; i < cancelBoatTimes.length; i++) {
                              //convert to string and 12hr time
                              var tmp = cancelBoatTimes[i];
                              var hr = parseInt(tmp.slice(0, 2));
                              var min = tmp.slice(3, 5);
                              var am = 0;
                              if (hr === 0) {
                                  hr = 12;
                                  am = 1;
                              }
                              else if (hr > 12) {
                                  hr -= 12;
                              }
                              else {
                                  am = 1;
                              }

                              if (hr < 10) {
                                  var hrStr = "0" + hr.toString();
                              }
                              else {
                                  var hrStr = hr.toString();
                              }

                              var amStr = " am";
                              var pmStr = " pm";

                              var valueStr = cancelBoatTimes[i];
                              if (am === 0) {
                                  var timeText = hrStr + ":" + min + pmStr;
                              }
                              else {
                                  var timeText = hrStr + ":" + min + amStr;
                              }
                              $('#cancelBoatTime').append("<option value='" +
                                  valueStr + "'>" + timeText + "</option>");
                          }

                          $('#cancelBoatTimeDiv').show();
                          $('#cancelBoatTime').val("");
                      }, "JSON");
                  });
              </script>
          </select>
          <br />
          <div id="cancelBoatTimeReq">
             <br />
             Select a time for the boat to cancel.
          </div>
          <br />
      </div>


      <div id="submitDiv">
          <input id="submitButton" type="submit" value="Submit">
          <span id="subMsg"></span>
      </div>
      </form>
    </div>
  </body>
</html>
