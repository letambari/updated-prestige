// JavaScript Document
// Error Msgs
//$(document).bind("contextmenu",function(e){
//	e.preventDefault();
//});

function checkPasswordStrength() {
	var number = /([0-9])/;
	var alphabets = /([a-zA-Z])/;
	var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
	
	if($('#reg_password').val().length<6) {
		$('#password-strength-status').removeClass();
		$('#password-strength-status').addClass('weak-password');
		$('#password-strength-status').html("Weak (should be at least 6 characters.)");
	} else {  	
	    if($('#reg_password').val().match(number) && $('#reg_password').val().match(alphabets) && $('#reg_password').val().match(special_characters)) {            
			$('#password-strength-status').removeClass();
			$('#password-strength-status').addClass('strong-password');
			$('#password-strength-status').html("Strong");
        } else {
			$('#password-strength-status').removeClass();
			$('#password-strength-status').addClass('medium-password');
			$('#password-strength-status').html("Medium (should include alphabets, numbers and special characters.)");
        } 
	}
}


function MsgCustomSuccess(msg,element,time){
        var show = "<div class='row'><div class='notice-success'>"+msg+"</div></div>";
    setTimeout(function() {
            $(element).html("");
    },time);
    $(element).html(show);
}

function MsgCustomDanger(msg,element,time){
        var show = "<div class='row'><div class='notice-danger'>"+msg+"</div></div>";
    setTimeout(function() {
            $(element).html("");
    },time);
    $(element).html(show);
}


function Msg(msg,color,dismiss,element,time) { 
    if (dismiss == 1) {
        var show = "<div class='row1' id='alert_div'><div class='col-lg-12'><div class='alert " + color + " alert-dismissable'><button type='button' onclick='$(\"#alert_div\").toggle()' style='width:auto; padding:1px; height: 30px;left:5%; margin:0px' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" + msg + "</div></div></div>";
    } else if(dismiss == 0) {
        var show = "<div class='row1'><div class='col-lg-12'><div class='alert " + color + "'>" + msg + "</div></div></div>";
    }
    setTimeout(function() {
        $(element).html("");
    },time);
    $(element).html(show);
}
function Msg_custom(msg,color,dismiss,element,time) { 
    if (dismiss == 1) {
        var show = "<div class='row1' id='alert_div'><div class='col-lg-12'><div class='alert " + color + " alert-dismissable'><button type='button'  onclick='$(\"#alert_div\").toggle()' style='width:auto; padding:1px; height: 30px;left:5%; margin:0px' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" + msg + "</div></div></div>";
    } else if(dismiss == 0) {
        var show = "<div class='row1'><div class='col-lg-12'><div class='alert " + color + "'>" + msg + "</div></div></div>";
    }
    setTimeout(function() {
        document.getElementById(element).innerHTML='';
    },time);
    document.getElementById(element).style.display ='block';
}
function Msg_qr(msg,element,time) { 
    
    setTimeout(function() {
        $(element).html("");
    },time);
    $(element).html(msg);
}

function Msg2(msg,color,dismiss,element) { 
    if (dismiss == 1) {
            var show = "<div class='row1' id='alert_div'><div class='col-lg-12'><div class='alert " + color + " alert-dismissable'><button type='button' class='close' onclick='$(\"#alert_div\").toggle()' data-dismiss='alert' aria-hidden='true'>&times;</button>" + msg + "</div></div></div>";
    } else if(dismiss == 0) {
            var show = "<div class='row1'><div class='col-lg-12'><div class='alert " + color + "'>" + msg + "</div></div></div>";
    }
    $(element).html(show);
}

//  Messages Define
function err2(message,time){
    $('#msgs').show();
    $('#msgs').css({"color":"#a94442","font-size":"18px"});
    $('#msgs p').html(message);
	setTimeout(function() {
            $("#msgs").html("");
    },time);
    $("#msgs").html(message);
}

function success2(message,time){
    $('#msgs').show();
    $('#msgs').css({"color":"#3c763d","font-size":"18px"});
    $('#msgs p').html(message);
	setTimeout(function() {
            $("#msgs").html("");
    },time);
    $("#msgs").html(message);
}

// Function for message Slide Up and Down Starts Here
function err(message) {
	$("#alert").css("background","#a94442");
	$("#alert").slideDown(500);
	$("#alert p").html(message);
	$("#alert span").click(function(){
		$("#alert").slideUp(500);
	});
}

function success(message) {
	$("#alert").css("background","#3c763d");
	$("#alert").slideDown(500);
	$("#alert p").html(message);
	$("#alert span").click(function(){
		$("#alert").slideUp(500);
	});
}


function post_data(url,Case,value,action){
    $.post(url,{'action':Case},function(responseText){
        if(responseText == 1){
           	action;
        }
    });
}

function post_data2(url,value,action){
    $.post(url,{value:value},function(responseText){
        if(responseText == 1){
           	return true;
        }
    });
}

function post_data3(url,value){
	$.post(url,{value:value},function(data){
		alert(data);
	});
}


function readRecords2(url,element,value) {
    $.get(url, {'value':value}, function (data) {
        $(element).html(data);
    });
}


function readRecords(url,element) {
    $.get(url, {}, function (data, status) {
        $(element).html(data);
    });
}


function DU_fetch(url,value,url2,element){
	$.post(url,{value:value},function(data){
		readRecords(url2,element);
    });
}

function DU_fetch2(url,value,url2,element,msg1,msg2,msg3,element2){
	$.post(url,{value:value},function(data){
		if(data == 1){
			Msg(msg1,'alert-success',1,element2,3000);
		} else if(data == 2){
			Msg(msg2,'alert-success',1,element2,3000);
		} else if(data == 0){
			Msg(msg3,'alert-success',1,element2,3000);
		}
		readRecords(url2,element);
    });
}




function FormValidate(inputName,nearinput){
	$(inputName).addClass('border-danger');
	$(nearinput).addClass('adderrorClass');
	 $(".").html(message);
	$(inputName).keyup(function(e) {
        $(inputName).removeClass('border-danger');
		$(nearinput).removeClass('adderrorClass');
    });
}


/*'action':'card_check','serial':serial.val(),'pin':pin.val(),'email':email.val()*/
function SendDataPost(url,data,element,msgBtn){
	 return $.ajax({
		type:'post',
		url:url,
		data:data,
		beforeSend: function(){	
			$(element).html('<span class="fa fa-circle-o-notch fa-spin"></span> &nbsp; '+msgBtn+'');
		}
	 });
}