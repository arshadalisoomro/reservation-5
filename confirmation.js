// this function determines and transforms the relevant reservation info
// so that it can be displayed to the user who can confirm it is correct
$(function () {
    //clear any old sessionStorage, no longer needed
    sessionStorage.clear();

    //allInfo is set in confirmation.php
    for (var key in allInfo) {
        sessionStorage.setItem(key, allInfo[key]);
    }

    //display confirmation data
    $('#homeowner').html("Homeowner Name: " +
        sessionStorage.getItem('homeownerName'));
    if (sessionStorage.getItem('isGuest') !== '0') {
        $('#guest').html("Guest Name: " + sessionStorage.getItem('guestName'));
    }
    $('#email').html("Email: " + sessionStorage.getItem('email'));

    //display phone with hyphens aaa-ccc-nnnn
    var area = allInfo['phone'].substr(0, 3);
    var co = allInfo['phone'].substr(3, 3);
    var num = allInfo['phone'].substr(6, 4);
    var telStr = '(' + area + ')-' + co + '-' + num;
    $('#phone').html("Phone: " + telStr);

    //display traveler details
    var nAH = allInfo['numAdultHomeowner'];
    var nCH = allInfo['numChildHomeowner'];
    var nAG = allInfo['numAdultGuest'];
    var nCG = allInfo['numChildGuest'];
    var tab = "&nbsp;&nbsp;&nbsp;&nbsp;"
    var travStr = "Passengers: " + allInfo['totalNumber'] + " total<br>";
    var add = '';
    if (nAH > 0) {
        if (nAH > 1) {
            add = tab + nAH + ' Adult Homeowners<br>';
            travStr = travStr + add;
        }
        else {
            add = tab + nAH + ' Adult Homeowner<br>';
            travStr = travStr + add;
        }
    }
    if (nCH > 0) {
        if (nAH > 1) {
            add = tab + nCH + ' Child Homeowners<br>';
            travStr = travStr + add;
        }
        else {
            add = tab + nCH + ' Child Homeowner<br>';
            travStr = travStr + add;
        }
    }
    if (nAG > 0) {
        if (nAG > 1) {
            add = tab + nAG + ' Adult Guests<br>';
            travStr = travStr + add;
        }
        else {
            add = tab + nAG + ' Adult Guest<br>';
            travStr = travStr + add;
        }
    }
    if (nCG > 0) {
        if (nCG > 1) {
            add = tab + nCG + ' Child Guests<br>';
            travStr = travStr + add;
        }
        else {
            add = tab + nCG + ' Child Guest<br>';
            travStr = travStr + add;
        }
    }
    $('#travellers').html(travStr);

    //display trip details
    var tripStr = '';
    //case for round trip
    var printDecDate = allInfo['printDecDate'];
    var printDecTime = allInfo['printDecTime'];
    var printAnaDate = allInfo['printAnaDate'];
    var printAnaTime = allInfo['printAnaTime'];
    if (printDecDate && printAnaDate) {
        tripStr = "Round Trip: <br>&nbsp;&nbsp;&nbsp;&nbsp;" +
            "Going to Decatur Island on " +
            printDecDate +
            " at " +
            printDecTime +
            "<br>&nbsp;&nbsp;&nbsp;&nbsp;" +
            "Going to Anacortes on " +
            printAnaDate +
            " at " +
            printAnaTime;
        $('#trip').html(tripStr);
    }
    //case for one way to Decatur
    else if (printDecDate) {
        tripStr = "One way to Decatur Island on " +
            printDecDate +
            " at " +
            printDecTime;
        $('#trip').html(tripStr);
    }
    //case for one way to Anacortes
    else {
        tripStr = "One way to Anacortes on " +
            printAnaDate +
            " at " +
            printAnaTime;
        $('#trip').html(tripStr);
    }
    //display comments if any were entered
    var comm = (sessionStorage.getItem('comments'));
    var commCheckForAllSpaces = comm.replace(/\s/g, "");
    if (commCheckForAllSpaces !== "") {
        $('#comments').html("Comments: " + sessionStorage.getItem('comments'));
    }
    


});
