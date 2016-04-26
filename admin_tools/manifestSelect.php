<?php require 'check_login.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Manifest Selection</title>
    <meta charset = "UTF-8">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="../css/boatRes.css">
    <script>

        $(function () {

                //init datepickers once boats added
                var boatDates = [];     //will hold json data
                $.getJSON("../getManifestBoats.php", function(jsonDates){
                    for(i=0; i < jsonDates.length; i++){
                        boatDates.push(jsonDates[i]);
                    }

                    $("#manifestDate").datepicker({
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
            $('#manifestDateReq').hide();
            $('#manifestTimeReq').hide();

            //remove error msgs on changing values          q
            $('#manifestDate').on('change', function(){
                $('#manifestDateReq').hide();
                $('#manifestTimeReq').hide();
            });


        });
        function whenSubmit(){
            //remove any submit msg
            $('#subMsg').html("");

                //check date and time
                if(!$('#manifestDate').val()){
                    $('#manifestDateReq').show();
                    return false;
                }
                else if (!$('#manifestTime').val()) {
                    $('#manifestTimeReq').show();
                    return false;
                }


            //submit if passes all
            var formData = $('#manifestForm').serialize();
            //return true to complete submit
            return true;
        }


    </script>
</head>
<body>
    <div id="outerDiv">
        <form id="manifestForm" action="manifest.php" method="post"
            onsubmit="return whenSubmit()" novalidate>
        <legend><b>Manifest Selections</b></legend>

        <div id="manifestDiv">
            <p>
                <label for="manifestDate">Select Manifest Date:
                </label>
            </p>
            <p><input id="manifestDate" name="manifestDate" type="text"></p>
            <div id="manifestDateReq">Select a manifest date.</div>
        </div>

        <!--   New code from here 4/21  -->
        <div id="manifestTimeDiv">
            <label for="manifestTime">Select Boat Time: </label>
            <select id="manifestTime" name="manifestTime">
                <script>
                    $('#manifestDate').on('change', function () {
                        var dd = new Date($('#manifestDate').val());
                        var d = dd.getDate();
                        var m = dd.getMonth();
                        m += 1; //since months are 0-11
                        var y = dd.getFullYear();
                        var formatDate = y + '-' +
                            (m < 10 ? '0' : '') + m + '-' +
                            (d < 10 ? '0' : '') + d; //format date yyyy-mm-dd
                        $('#manifestDate').val(formatDate);
                        var sendDate = $('#manifestForm').serialize();
                        var manifestTimes = [];
                        $.post("getTimesManifest.php",
                        sendDate,
                        function (jsonTimes) {
                            console.log(jsonTimes);
                            for (i = 0; i < jsonTimes.length; i++) {
                                manifestTimes.push(jsonTimes[i]);
                                //console.log(toDecTimes[i]);
                            }
                            $('#manifestTime').html("<option value=''>" +
                                "Select boat time.</option>");
                            for (i = 0; i < manifestTimes.length; i++) {
                                //convert to string and 12hr time
                                var tmp = manifestTimes[i];
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

                                var valueStr = manifestTimes[i];
                                if (am === 0) {
                                    var timeText = hrStr + ":" + min + pmStr;
                                }
                                else {
                                    var timeText = hrStr + ":" + min + amStr;
                                }
                                $('#manifestTime').append("<option value='" +
                                    valueStr + "'>" + timeText + "</option>");
                            }

                            $('#manifestTimeDiv').show();
                            $('#manifestTime').val("");
                        }, "JSON");
                    });
                </script>
            </select>
            <div id="manifestTimeReq">Select a manifest time.</div>
        </div>


        <div id="submitDiv">
            <input id="submitButton" type="submit" value="Submit">
            <span id="subMsg"></span>
        </div>
        </form>
    </div>
</body>
</html>
