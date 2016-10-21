"use strict"
/* watchers for icon clicks */
window.onbeforeunload = function () {
   return "Are you sure you want to leave? Your order progress will be lost"
}

 $(".info").change(function(){
    $(this).removeClass("input-warning");
 })
$('.size-treat').each(function(){
    $(this).click(function(){
        navigate(false);
    });
});

$('.treat-size').each(function(){
    $(this).click(function(){
        navigate(true);
    });
});

$('.treat-extras').each(function(){
    $(this).click(function(){
        navigate(false);
    });
});

$('.extras-treat').each(function(){
    $(this).click(function(){
        navigate(true);
    });
});

$('.extras-message-screen').each(function(){
    $(this).click(function(){
        navigate(false);
    });
});

$('.message-screen-extras').each(function(){
    $(this).click(function(){
      navigate(true);
    });
});

$('.message-screen-delivery-info').each(function(){
    $(this).click(function(){
       navigate(false);
    });
});

$('.delivery-info-message-screen').each(function(){
    $(this).click(function(){
        navigate(true);
    });
});
$('.delivery-info-summary').each(function(){
    $(this).click(function(){
       navigate(false);
    });
});

$('.summary-delivery-info').each(function(){
    $(this).click(function(){
        navigate(true);
    });
});

/*watchers for arrow navigation*/
$(document).ready(function() {
   if(window.innerWidth <= 500) {
    $('#container-size').removeClass("container").addClass("container-fluid");
    $('#container-treat').removeClass("container").addClass("container-fluid");
    $('#summary-container').removeClass("container").addClass("container-fluid");
    $('#container-extras').removeClass("container").addClass("container-fluid");
    $('#container-message').removeClass("container").addClass("container-fluid");
    $('#delivery-container').removeClass("container").addClass("container-fluid");
    $('#card-image').addClass('small-window');
   } 
   else{
    $('#card-image').addClass('big-window');
   }
});

$('.go-to-top').each(function(){
    $(this).click(function(){ 
        $('html,body').animate({ scrollTop: 0 }, 'slow');
        return ; 
    });
});

var cake_type = "";
 $('#cake_type').change(function(){ 
    cake_type = $(this).val();

});
  $('#cake_frosting').change(function(){ 

   order.item = cake_type + " " + $(this).val() + " Cake";
   order.treatSelected=true;


});

var order = {
    price:0, 
            item:"",
            giftcards:{
                Amazon:{
                    selected:false,
                    type:0
                }, 
                    
                Visa:{
                    selected:false,
                    type:0
                },
                Chipotle:{
                    selected:false,
                    type:0
                },
                Bookstore:{
                    selected:false,
                    type:0
                }, 
                orderID:0,


               },
            sizeSelected:false,
            treatSelected:false,
            giftCardSelected:false,
            imageUploaded:false,
            orderPage:0,
            orderSize:null,
           };

var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
var w_start = new Date(2016, 11, 20, 0, 0, 0,0);
var w_end = new Date(2017, 0, 20,0,0,0,0);
var sem_end = new Date(2017, 4, 15, 0, 0,0, 0);
$('#dp1').datepicker({
  onRender: function(date) {
    var nw = now.valueOf();
    return (date.valueOf() < (now.valueOf()+172800000)||(date.valueOf()>w_start.valueOf()&&date.valueOf()<w_end.valueOf())||(date.valueOf()>sem_end.valueOf())) ? 'disabled' : '';
  }})
  .on('changeDate', function(ev){
    $('#dp1').datepicker('hide');
  });


function validateCalendar(){
    var date = $('#date').val();
    var arr = date.split('-');
    for(var i=0;i<arr.length;i++){
        arr[i]=Number(arr[i]);
    }
    var d = new Date();
    var valid = true;
    var day = d.getDate();
    var year = d.getFullYear();
    var month = d.getMonth() +1;
     if(arr[1]>12){
            valid = false;
        }
    if(arr[2]>31){
            valid = false;
        }        
    if(arr[1]==month&&arr[2]<day){
            valid = false;
        }
    if(arr[0]==2016){
        if(arr[1]<month){
            valid = false;
        }    
        if(arr[1]==12&&arr[2]>20){
            valid = false;
        }
    }
    else if(arr[0]==2017){
        if(arr[1]==1&&arr[2]<20) valid = false;
        if(arr[1]==5&&arr[2]>15)valid = false;
        if(arr[1]>5)valid = false;
    }
    else{
        valid = false;
    }
    return valid;

}

function processSize(size){
    order.orderSize=size;
    order.sizeSelected=true;
    if(size==="Group"){
        $('#num_donut').text("(x6)");
        $('#num_cook').text("(x6)");
        $('#cake-size').text("Group Cake (x3)");
    }
    else if(size==="Double"){
         $('#num_donut').text("(x4)");
        $('#num_cook').text("(x4)");
         $('#cake-size').text("Double Cake (x2)");
    }
    else {
     $('#num_donut').text("(x2)");
     $('#num_cook').text("(x2)");
     $('#cake-size').text("Individual Cake");
    
    }
}
var progressValue = 0;
function navigate(left){
    var leftToggle = "";
    var rightToggle = "";
    var proceed = true;
    var displayMessage = true;
    if(order.orderPage==0){
        $('#left').prop('disabled', false);
        leftToggle = "#size";
        rightToggle = "#treat";
        proceed = order.sizeSelected;
    }
    else if(order.orderPage==1){
        if(left){
            leftToggle="#size";
            rightToggle="#treat";
            $('#left').prop('disabled', true);
        }
        else{
            rightToggle="#extras";
            leftToggle="#treat";
            proceed = order.treatSelected;
        }
       
    }
    else if(order.orderPage==2){
         if(left){
            leftToggle="#treat";
            rightToggle="#extras";
        }
        else{
            leftToggle="#extras";
            rightToggle="#message-screen";       
        }
    }
    else if(order.orderPage==3){
        if(left){
            leftToggle="#extras";
            rightToggle="#message-screen";
            }
        else{
            rightToggle="#delivery-info";
            leftToggle="#message-screen";
            /*if(!validateCalendar()){
                window.alert("Please enter a date in the range of today - 12/20/16 and 1/20/16 - 5/15/16");
                displayMessage = false;
                proceed = false;
            }*/
           
        }
    }
    else if(order.orderPage==4){
        if(left){
            leftToggle="#message-screen";
            rightToggle="#delivery-info";
        }
        else{
            leftToggle="#delivery-info";
            rightToggle="#summary";
             var displayAlert = false;
            $(".info").each(function() {
                 if($(this).val() === ""){
                 displayAlert = true;
                 proceed = false;
                $(this).addClass('input-warning');
                displayMessage = false;
                }
            });
            if(displayAlert) alert("One or more required fields is empty");
            else $('#right').prop('disabled', true);
            displayOrder();
        }
    }
    else if(order.orderPage==5){
        leftToggle="#delivery-info";
        rightToggle="#summary";
        $('#right').prop('disabled', false);
    }
    if(!proceed){
        if(displayMessage) alert("You must make a selection before proceeding");
    }
    else{

        $(rightToggle).toggle("slide", {direction:"right", duration:1000});
         $(leftToggle).toggle("slide", {direction:"left", duration:1000});
         if(!left){
            progressValue+=16;
            order.orderPage++;
        }

    }
    if (left){
        progressValue-=16;
        order.orderPage--;
    }
    $(".prog").css("width", progressValue + "%");

   
}

function processGiftCard(type, value){
    var gc = order.giftcards;

    hideChipotle();
    hideBookstore();
    hideVisa();
    hideAmazon();
    var already = false;

    if(type==="Amazon"){
     
        gc.Amazon.type = Number(value);
        already = amazonChosen(Number(value));
        gc.Amazon.selected = true;
        if(gc.Amazon.type==1){
            order.price = 25;
        }
        else if(gc.Amazon.type==2){
            order.price = 50;
        }
        else if(gc.Amazon.type==3){
            order.price = 100;
        }
    }
    else if(type==="Visa"){
     
        already = visaChosen(Number(value));
           gc.Visa.selected = true;
        gc.Visa.type = Number(value);
        if(gc.Visa.type==1){
            order.price = 25;
        }
        else if(gc.Visa.type==2){
            order.price = 50;
        }
        else if(gc.Visa.type==3){
            order.price = 100;
        }
    }
    else if(type==="Bookstore"){
      
        gc.Bookstore.type = Number(value);
        already = bookstoreChosen(Number(value));
         gc.Bookstore.selected = true;
        if(gc.Bookstore.type==1){
            order.price = 25;
        }
        else if(gc.Bookstore.type==2){
            order.price = 50;
        }
        else if(gc.Bookstore.type==3){
            order.price = 100;
        }
    }
    else if(type==="Chipotle"){
     
        gc.Chipotle.type = Number(value);
        already = chipotleChosen(Number(value));
           gc.Chipotle.selected = true;
        if(gc.Chipotle.type==1){
            order.price = 15;
        }
        else if(gc.Chipotle.type==2){
            order.price = 25;
        }
         else if(gc.Chipotle.type==3){
            order.price = 50;
        }
    }

    order.giftCardSelected = true;
    if(already){
        order.giftCardSelected = false;
        order.price=0;
        for(var giftCard in order.giftcards){
            if(giftCard!="orderID"){
               order.giftcards[giftCard].selected = false;
                order.giftcards[giftCard].type = 0;
            }
        }
    }
    else{
        for(var giftCard in order.giftcards){
            if(giftCard!="orderID"&&giftCard!=type){
               order.giftcards[giftCard].selected = false;
                order.giftcards[giftCard].type = 0;
            }
        }
    }
   
                    

}

function amazonChosen(num){

    $('.overlay-amazon').hide();
    $('#amazon-img').show();
    var check = false;
    var already = false;
    if(order.giftcards.Amazon.selected) check = true;
    var type = order.giftcards.Amazon.type;
    if(num==1){
        if(check&&type==1){
            $('#amazon-25').hide();
            $('#amazon-25-overlay').show(); 
            already = true;  
        }        else{
            $('#amazon-25').show();
            $('#amazon-25-overlay').hide();
        }
    }
    else if(num==2){
         if(check&&type==2){
            $('#amazon-50').hide();
            $('#amazon-50-overlay').show(); 
            already = true;  
        } else{
        $('#amazon-50').show();
        $('#amazon-50-overlay').hide();
        }
    }
    else{
         if(check&&type==3){
            $('#amazon-100').hide();
            $('#amazon-100-overlay').show(); 
            already = true;  
        } 
        else{
            $('#amazon-100').show();
            $('#amazon-100-overlay').hide();
        }
    }
    return already;
}

function visaChosen(num){

    $('.overlay-visa').hide();
    $('#visa-img').show();
    var already = false;
    var check = false
    if(order.giftcards.Visa.selected) check = true;
    var type = order.giftcards.Visa.type;
    if(num==1){
        if(check&&type==1){
            $('#visa-25').hide();
            $('#visa-25-overlay').show(); 
            already = true;  
        }
        else{
            $('#visa-25').show();
            $('#visa-25-overlay').hide();
        }
    }
    else if(num==2){
        if(check&&type==2){
            $('#visa-50').hide();
            $('#visa-50-overlay').show(); 
            already = true;  
        }
        else{
            $('#visa-50').show();
            $('#visa-50-overlay').hide();
        }
    }
    else{
         if(check&&type==3){
            $('#visa-100').hide();
            $('#visa-100-overlay').show(); 
            already = true;  
        }
        else{
            $('#visa-100').show();
            $('#visa-100-overlay').hide();
        }
    }
    return already;
}

function chipotleChosen(num){

    $('.overlay-chip').hide();
    $('#chip-img').show();
        var check = false
    if(order.giftcards.Chipotle.selected) check = true;
    var type = order.giftcards.Chipotle.type;
    var already = false;

    if(num==1){
        if(check&&type==1){
            $('#chip-15').hide();
            $('#chip-15-overlay').show(); 
            already = true;  
        }
        else{
            $('#chip-15').show();
            $('#chip-15-overlay').hide();
        }
    }
    else if(num==2){
        if(check&&type==2){
            $('#chip-25').hide();
            $('#chip-25-overlay').show(); 
            already = true;  
        }
        else{
            $('#chip-25').show();
            $('#chip-25-overlay').hide();
        }
    }
    else{
        if(check&&type==3){
            $('#chip-50').hide();
            $('#chip-50-overlay').show(); 
            already = true;  
        }
        else{
            $('#chip-50').show();
            $('#chip-50-overlay').hide();
        }
    }
    return already;
}


function bookstoreChosen(num){

    $('.overlay-bs').hide();
    $('#bs-img').show();
    var already = false;
    var check = false
    if(order.giftcards.Bookstore.selected) check = true;
    var type = order.giftcards.Bookstore.type;
    if(num==1){
         if(check&&type==1){
            $('#bs-25').hide();
            $('#bs-25-overlay').show(); 
            already = true;  
        }
        else{
            $('#bs-25').show();
            $('#bs-25-overlay').hide();
        }
    }
    else if(num==2){
        if(check&&type==2){
            $('#bs-50').hide();
            $('#bs-50-overlay').show(); 
            already = true;  
        }
        else{
            $('#bs-50').show();
            $('#bs-50-overlay').hide();
        }
    }
    else{
        if(check&&type==3){
            $('#bs-100').hide();
            $('#bs-100-overlay').show(); 
            already = true;  
        }
        else{
            $('#bs-100').show();
            $('#bs-100-overlay').hide();
        }
    }
    return already;
}

function hideChipotle(){
    $('.overlay-chip').show();
    $('#chip-img').hide();

     $('#chip-15').hide();
     $('#chip-15-overlay').show();
     $('#chip-25').hide();
     $('#chip-25-overlay').show();
     $('#chip-50').hide();
     $('#chip-50-overlay').show();

}
function hideVisa(){
    $('.overlay-visa').show();
    $('#visa-img').hide(); 

    $('#visa-25').hide();
    $('#visa-25-overlay').show();
    $('#visa-50').hide();
    $('#visa-50-overlay').show();
    $('#visa-100').hide();
    $('#visa-100-overlay').show();
}
function hideBookstore(){
    $('.overlay-bs').show();
    $('#bs-img').hide();

    $('#bs-25').hide();
    $('#bs-25-overlay').show();
    $('#bs-50').hide();
    $('#bs-50-overlay').show();
    $('#bs-100').hide();
    $('#bs-100-overlay').show();
}
function hideAmazon(){
    $('.overlay-amazon').show();
    $('#amazon-img').hide();

    $('#amazon-25').hide();
    $('#amazon-25-overlay').show();
    $('#amazon-50').hide();
    $('#amazon-50-overlay').show();
    $('#amazon-100').hide();
    $('#amazon-100-overlay').show();
}

function clearGC(){
    var gc = order.giftcards;
    gc.Amazon.type = 0;
    gc.Amazon.selected = false;
    gc.Bookstore.type = 0;
    gc.Bookstore.selected = false;
    gc.Chipotle.type = 0;
    gc.Chipotle.selected = false;
    gc.Visa.type = 0;
    gc.Visa.selected = false;
}

function processTreat(num){
    hideDonut();
    hideCookie();
    hideCake();


    if(num==0){
        order.item="Donut";

    $('#donut').show();
    $('.overlay-donut').hide(); 


    }
    else if(num==1){
        order.item="Cookie";

    $('#cookie').show();
    $('.overlay-cookie').hide();
    }
    else if(num==2){
        order.item="Chocolate Cake";

           $('#cake').show();
          $('.overlay-cake').hide();
          $('#choc-overlay').hide();
          $('#choc').show();
    }
    else if(num==3){
        order.item="Vanilla Cake";

           $('#cake').show();
          $('.overlay-cake').hide();
            $('#van-overlay').hide();
          $('#van').show();
    }
    else if(num==4){
        order.item="Swirl Cake";

           $('#cake').show();
          $('.overlay-cake').hide();
            $('#bday-overlay').hide();
          $('#bday').show();
    }
    order.treatSelected=true;

}

    function hideCookie(){
        $('#cookie').hide();
        $('.overlay-cookie').show();

    }
    function hideDonut(){
        $('#donut').hide();
        $('.overlay-donut').show(); 
        
    }
    function hideCake(){
        $('#cake').hide();
        $('.overlay-cake').show();
        $('#choc').hide();
        $('#choc-overlay').show();
        $('#van').hide();
        $('#van-overlay').show();
        $('#bday').hide();
        $('#bday-overlay').show();
        
    }

function displayOrder(){
    var gc = order.giftcards;
    document.getElementById("treat_type").innerHTML = "1 " + order.item + " " + order.orderSize + " box";
    var total = order.price;
    if(order.orderSize==="Individual") {
        document.getElementById("treat_price").innerHTML = "$20.00";
        total+=20;
    }
    else if(order.orderSize==="Double") {
        document.getElementById("treat_price").innerHTML = "$30.00";
        total+=30;
    }
    else if(order.orderSize==="Group") {
        document.getElementById("treat_price").innerHTML = "$40.00";
        total+=40;
    }
    if(gc.Amazon.selected){
            document.getElementById("amazon_num").innerHTML ="1 Amazon Gift Card";
            if(gc.Amazon.type==1) document.getElementById("amazon_price").innerHTML = "$25.00";
            else if(gc.Amazon.type==2) document.getElementById("amazon_price").innerHTML = "$50.00"; 
            else if(gc.Amazon.type==3)document.getElementById("amazon_price").innerHTML = "$100.00";
        }
    else  {
        document.getElementById("amazon_num").innerHTML = "";
        document.getElementById("amazon_price").innerHTML = "";
    }
     if(gc.Bookstore.selected){
           document.getElementById("bs_num").innerHTML = "1 University Bookstore Gift Card";
             if(gc.Bookstore.type==1) document.getElementById("bs_price").innerHTML = "$25.00";
            else if(gc.Bookstore.type==2) document.getElementById("bs_price").innerHTML = "$50.00"; 
            else if(gc.Bookstore.type==3)document.getElementById("bs_price").innerHTML = "$100.00";
        }
     else  {
        document.getElementById("bs_num").innerHTML = "";
        document.getElementById("bs_price").innerHTML = "";
    }
     if(gc.Chipotle.selected){
           document.getElementById("chip_num").innerHTML = "1 Chipotle Gift Card";
            
            if(gc.Chipotle.type==1) document.getElementById("chip_price").innerHTML = "$15.00";
            else if(gc.Chipotle.type==2) document.getElementById("chip_price").innerHTML = "$25.00"; 
            else if(gc.Chipotle.type==3) document.getElementById('chip_price').innerHTML = "$50.00"
        }
    else  {
        document.getElementById("chip_num").innerHTML="";
        document.getElementById("chip_price").innerHTML="";
    }
     if(gc.Visa.selected){
            document.getElementById("visa_num").innerHTML = "1 Visa Gift Card";
            if(gc.Visa.type==1) document.getElementById("visa_price").innerHTML = "$25.00";
            else if(gc.Visa.type==2) document.getElementById("visa_price").innerHTML = "$50.00"; 
            else if(gc.Visa.type==3)document.getElementById("visa_price").innerHTML = "$100.00";
        }
      else  {
        document.getElementById("visa_num").innerHTML="";
        document.getElementById("visa_price").innerHTML="";
    }
 

    document.getElementById("total").innerHTML = "$" + total.toFixed(2);

}

