
$(document).ready(function(){

    $('#pickdate_a').pickadate();


    var counter = 1;
    var alphabet = ['a','b','c','d','e'];

    $("#addTravel").click(function () {

        if(counter>4){
            alert("Maximum 5 travel details allowed for a single request");
            return false;
        }

        var nextTravelDetailDiv = $(document.createElement('div'))
            .attr("id", 'travelDetailDiv' + '_' + alphabet[counter]);

        nextTravelDetailDiv.after().html('<br><br><div class="row"> <div class="col-md-6 col-sm-12"> <div class="form-group"> <input type="text" class="form-control" name="travel_from_' + alphabet[counter] + '" name="travel_from_' + alphabet[counter] + '" value="" placeholder="From" > </div> </div> <div class="col-md-6 col-sm-12"> <div class="form-group" > <input type="text" class="form-control" name="travel_to_' + alphabet[counter] + '" name="travel_to_' + alphabet[counter] + '" value="" placeholder="To"> </div> </div> </div> <div class="row"> <div class="col-md-6 col-sm-12"> <div class="form-group"> <select name="modeSelect_' + alphabet[counter] + '" class="form-control"> <option>--Mode--</option> <option>Cab</option> <option>Bus</option> <option>Rail</option> <option>Flight</option> </select> </div> </div> <div class="col-md-6 col-sm-12"> <div class="form-group"> <input type="text" id="pickdate_' + alphabet[counter] + '" name="pickdate_' + alphabet[counter] + '" class="form-control" placeholder="Date"> </div> </div> </div> <div class="row"> <div class="col-md-6 col-sm-12"> <div class="form-group"> <input type="number" name="amount_' + alphabet[counter] + '" class="form-control" placeholder="Amount"> </div> </div> <div class="col-md-6 col-sm-12"> <input type="file" name="bills_' + alphabet[counter] + '" > </div> </div> </div> <br>');

        nextTravelDetailDiv.appendTo("#travelDetails");

        $('#pickdate_' + alphabet[counter]).pickadate();

        counter++;
    });

    $("#removeTravel").click(function () {
        if(counter==1){
            alert("No more travel details to remove");
            return false;
        }

        counter--;

        $("#travelDetailDiv" + '_' + alphabet[counter]).remove();

    });


    // $("#monthSelect").addEventListener("change",function(){
    //   alert()
    // });

    /*$("#getButtonValue").click(function () {

        var msg = '';
        for(i=1; i<counter; i++){
            msg += "\n Textbox #" + i + " : " + $('#textbox' + i).val();
        }
        alert(msg);
    });*/
});
