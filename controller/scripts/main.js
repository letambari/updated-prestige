jQuery(function($) {'use strict';
	// Navigation Scroll
	$(window).scroll(function(event) {
		Scroll();
	});

	
//	$('.navbar-collapse ul li a').on('click', function() {  
//		$('html, body').animate({scrollTop: $(this.hash).offset().top - 5}, 1000);
//		return false;
//	});

	// User define function
	function Scroll() {
		var contentTop      =   [];
		var contentBottom   =   [];
		var winTop      =   $(window).scrollTop();
		var rangeTop    =   200;
		var rangeBottom =   500;
		$('.navbar-collapse').find('.scroll a').each(function(){
                    contentTop.push( $( $(this).attr('href') ).offset().top);
                    contentBottom.push( $( $(this).attr('href') ).offset().top + $( $(this).attr('href') ).height() );
		});
		$.each( contentTop, function(i){
			if ( winTop > contentTop[i] - rangeTop ){
				$('.navbar-collapse li.scroll')
				.removeClass('active')
				.eq(i).addClass('active');			
			}
		})
	};

	$('#tohash').on('click', function(){
		$('html, body').animate({scrollTop: $(this.hash).offset().top - 5}, 1000);
		return false;
	});

	// accordian
	$('.accordion-toggle').on('click', function(){
		$(this).closest('.panel-group').children().each(function(){
		$(this).find('>.panel-heading').removeClass('active');
		 });

	 	$(this).closest('.panel-heading').toggleClass('active');
	});

	//Initiat WOW JS
	new WOW().init();
	//smoothScroll
	smoothScroll.init();


});
//////////////////// registration script validation on the registration page
function register_member(done){    
    if($("#InputEmail").val().length == 0||$("#password").val().length == 0 || $("#name").val().length == 0){
            Msg("<i class='fa fa-warning'></i>&nbsp; fill all fields!!!.","alert-danger",0,"#msg1",10000);
    }else if(done == 0){
        var data = $("#register-form").serialize();
        $("#btn-register").prop('disabled',true);
        $.ajax({
            type:'POST',
            url:'../register/index.php?register=&e=',
            data:data,
            beforeSend: function(){	
                $("#btn-register").html('<span class="">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>');
            },
            success: function(data){
                    if(data == 1){
//                            $("#register-form").trigger('reset');                            
//                            $("#register_start").toggle();
//                            $("#register_done").toggle();
                            Msg("<strong><i class='fa fa-check-square'></i>&nbsp;</strong> Please check your email for instructions to activate your account. If there is no mail in the \"Inbox\", please check your \"Spam\" folder.","alert-success",0,"#msg1",60000);
                            setTimeout(function() {
                                $("#register-form").trigger('reset');
//                                $("#btn-register").html('Sign up');
                                //window.location = "../account-verification";
                            },2000);
                    } else if(data == 2){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> fill all fields.","alert-danger",0,"#msg1",10000);
                            
                    }else if(data == 3){
						Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Enter a valid  email.","alert-danger",0,"#msg1",10000);
						
					}else if(data == 31){
						Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Enter fullname","alert-danger",0,"#msg1",10000);
						
					}else if(data == 4){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> password must be at least 8 characters","alert-danger",0,"#msg1",10000);
                            
                    }else if(data == 5){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Passwords do not match.","alert-danger",0,"#msg1",10000);
                            
                    }else if(data == 6){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Username Already Exist.","alert-danger",0,"#msg1",10000);
                            
                    }else if(data == 7){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Email Already Exist.","alert-danger",0,"#msg1",10000);
                           
                    }else if(data == 8){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Sponsor does not exits.","alert-danger",0,"#msg1",10000);
                            
                    }else if (data == 9){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Action unsucessfull, check the form and try again.","alert-danger",0,"#msg1",10000);
                            
                    }else if (data == 10){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Please accept our Terms of Service.","alert-danger",0,"#msg1",10000);
                            
                    }else{
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong>Action unsucessfull, "+data+".","alert-danger",0,"#msg1",10000);
                            
                    }
                    $("#btn-register").prop('disabled',false);
                    $("#btn-register").html('Sign Up');
            }
    })}
}
$(document).ready(function(){
    $("#btn-register").click(function(e) {
        register_member(0);
        e.preventDefault();
    });
});
//$(function(){
//	$.validator.setDefaults({
//		errorClass: 'help-block',
//		highlight: function(element){
//			$(element)
//			.closest('.form-group')
//			.addClass('has-error');
//		},
//		unhighlight: function(element){
//			$(element)
//			.closest('.form-group')
//			.removeClass('has-error');
//		}
//	});
//	
//	$.validator.addMethod('strongPassword', function(value, element){
//		return this.optional(element)
//		|| value.length >=4;
//	}, 'Your password must be at least 4 characters long');
//		
//	$("#register-form").validate({
//            rules:{
//		InputEmail:{
//                    required:true,
//                    email:true,
//                },
//                Inputpassword:{
//                    required:true,
//                    strongPassword:true,
//                }
//            },
//            messages:{
//		InputEmail:{
//                    required:"Email ID cannot be blank",
//                },
//                Inputpassword:{
//                        required:"Password cannot be blank",
//                }
//            },
//            submitHandler:register_member
//	});
//});
//$(document).ready(function(){
//    $("#btn-register").click(function(e) {
//        register_member();
//        e.preventDefault();
//    });
//});
/////////////// reset password process starts here //////////////////////////
function send_reset_link(){
	if($("#unameemail").val().length == 0){
            Msg("<i class='fa fa-warning'></i>&nbsp; fill all fields!!!.","alert-danger",0,"#pass_msg",10000);
	} else {
        $("#pass_btn").prop('disabled',true);
	var data = $("#pwd_link_form").serialize();
	$.ajax({
		type:'POST',
		url:'../controller/post.php?send_link',
		data:data,
		beforeSend: function(){	
                    $("#pass_btn").html('<span class="">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>');
		},
		success: function(data){
                    if(data == 1){
                            Msg("<strong><i class='fa fa-check-square'></i>&nbsp;</strong> A password re-set link has been sent to your mail.  If there is no mail in the \"Inbox\", please check your \"Spam\" folder.","alert-success",0,"#pass_msg",10000);
                            setTimeout(function() {
                                $("#pwd_link_form").trigger('reset');
                            },10000);
                    } else if (data == 2){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> link Not Sent, user account not found!!!","alert-danger",0,"#pass_msg",10000);
                    } else{
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Unknown error, try again,"+data+"","alert-danger",0,"#pass_msg",10000);
                    } 
                    $("#pass_btn").html('Submit');
                    $("#pass_btn").prop('disabled',false);
		}
	});
    }
}

function reset_password(){
    if($("#emailF").val().length == 0 || $("#password").val().length == 0|| $("#password2").val().length == 0){
        Msg("<i class='fa fa-warning'></i>&nbsp; fill all fields!!!.","alert-danger",0,"#status1",10000);
    } else {
        $("#pass_btn").prop('disabled',true);
	var data = $("#pwd_link_form").serialize();
	$.ajax({
            type:'POST',
            url:'../controller/post.php?reset-password',
            data:data,
            beforeSend: function(){	
                $("#pass_btn").html('<span class="">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>');
            },
            success: function(data){
                if(data == 1){
                    setTimeout(function(){
                        Msg("<i class='fa fa-globe fa fa-spin'></i>&nbsp; Password Changed Successfully. Redirecting...","alert-success",0,"#status1",6000);
                        window.location = "../dashboard";
                    },1500);
                } else if (data == 2){
                    Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Error, fill all fields!!!","alert-danger",0,"#status1",10000);
                } else if(data == 3){
                    Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Error, passwords do not match ","alert-danger",0,"#status1",10000);
                } else if(data == 4){
                    Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Error, account not found","alert-danger",0,"#status1",10000);
                }
                $("#pass_btn").html('Submit');
                $("#pass_btn").prop('disabled',false);
            }
	});
    }
}
$(function(){
	$.validator.setDefaults({
		errorClass: 'help-block',
		highlight: function(element){
			$(element)
			.closest('.form-group')
			.addClass('has-error');
		},
		unhighlight: function(element){
			$(element)
			.closest('.form-group')
			.removeClass('has-error');
		}
	});
	$("#pwd_link_form").validate({
		rules:{
			emailF:{
				required:true,
                                email:true,
			},
			password:{
				required:true,
			},
			password2:{
				required:true,
			}			
		},
		messages:{
			emailF:{
				required:"E-mail cannot be blank",
			},
			password:{
				required:"Field cannot be blank",
			},
			password2:{
				required:"Field cannot be blank",
			}
		},
		submitHandler:reset_password
	});
});

////////////////////// contact us script for home page... ///////////////
function quick_contact(x){
        if($("#form_name1").val().length == 0 || $("#form_email1").val().length == 0 || $("#form_subject1").val().length == 0||$("#form_message1").val().length == 0 ){
		Msg("<i class='fa fa-warning'></i>&nbsp; fill all fields!!!","alert-danger",0,"#status",10000);
	} else {
	var data = $("#contact_form").serialize();
	$.ajax({
		type:'POST',
		url:x+'controller/post.php?contact-home',
		data:data,
		beforeSend: function(){	
			$("#contact_btn1").html('<i class="fa fa-spinner fa-spin"></i> ');
		},
		success: function(data){
			if(data == 1){
				$("#contact_btn1").html('<i class="fa" aria-hidden="true"></i>Send Message');
				Msg("<strong><i class='fa fa-check-square'></i>&nbsp;</strong>  Sent successfuly","alert-success",0,"#status",6000);
				setTimeout(function() {
					$("#contact_form").trigger('reset');
				},10000);
			} else if (data == 2){
				Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> fill all fields!!!","alert-danger",0,"#status",10000);
				$("#contact_btn1").html('<i class="fa" aria-hidden="true"></i>Send Message');
			} else if(data == 3){
				Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> account does not exist","alert-danger",0,"#status",10000);				
				$("#contact_btn1").html('<i class="fa" aria-hidden="true"></i>Send Message');
			}else{
				Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Unknown error. please try again"+data+"","alert-danger",0,"#status",10000);				
				$("#contact_btn1").html('<i class="fa" aria-hidden="true"></i>Send Message');
			} 
		}
	});
    }
}
$(function(){
	$.validator.setDefaults({
		errorClass: 'help-block',
		highlight: function(element){
			$(element)
			.closest('.form-group')
			.addClass('has-error');
		},
		unhighlight: function(element){
			$(element)
			.closest('.form-group')
			.removeClass('has-error');
		}
	});
	$("#contact_form").validate({
		rules:{
			subject:{
				required:true,
			},
			message:{
				required:true,
			},
			name:{
				required:true,
			},
                        phone:{
				required:true,
			},
			email:{
				required:true,
                                email: true
			}			
		},
		messages:{
			subject:{
				required:"Subject cannot be blank",
			},
			message:{
				required:"Message body cannot be blank",
			},
			name:{
				required:"Name cannot be blank",
			},
                        phone:{
				required:"Mobile cannot be blank",
			},
			email:{
				required:"Email cannot be blank",
			}
		},
		submitHandler: quick_contact
	});
});
jQuery(document).ready(function ($) {
    // Your code here
});
function send_contact(){
        if($("#form_name").val().length == 0 || $("#form_email").val().length == 0|| 
                $("#form_subject").val().length == 0|| $("#form_message").val().length == 0 ){
                Msg("<i class='fa fa-warning'></i>&nbsp; fill all fields !!!","alert-danger",0,"#contact_status",10000);
        } else {
            $("#contact_btn").prop('disabled',true);
        var data = $("#contact_page_form").serialize();
        $.ajax({
                type:'POST',
                url:'index.php?contact_page_form',
                data:data,
                beforeSend: function(){	
                    $("#contact_btn").html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(data){
                    if(data == 1){
                        Msg("<strong><i class='fa fa-check-square'></i>&nbsp;</strong>  Sent successfuly","alert-success",0,"#contact_status",6000);
                        setTimeout(function() {
                            $("#contact_page_form").trigger('reset');
                        },10000);
                    } else if (data == 2){
                        Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> fill all fields !!!","alert-danger",0,"#contact_status",10000);
                    } else if(data == 3){
                        Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> account does not exist","alert-danger",0,"#contact_status",10000);				
                    }else{
                        Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Unknown error. please try again "+data+"","alert-danger",0,"#contact_status",10000);				
                    } 
                    $("#contact_btn").html('Submit');
                    $("#contact_btn").prop('disabled',false);
                }
        });
    }
}
function ClearMessage(x){
    var elem = _(x);
    x.innerHTML='';
}
//function send_contact1(){
//    var name = _('form_name').value;
//    var email  = _('form_email').value;
//    var tel  = _('form_phone').value;
//    var subject  = _('form_subject').value;
//    var form_message  = _('form_message').value;
//    var resp = _('contact_status');
//    var btn  = _('contact_btn'); 
//    var color1 = 'alert-danger';
//    var time = 6000;
//    if(name===""||email===""||tel===""||subject===""||form_message===""){
//        var msg = "<i class='fa fa-warning'></i>&nbsp; fill all fields !!!";
//        resp.innerHTML= "<div class='row1' style='text-align:center; margin-top:15px'><div class='col-lg-12'><div class='alert " + color1 + " alert-dismissable'><button onclick='ClearMessage(\"contact_status\")' type='button' style='width:auto; padding:1px; height: 30px;left:5%; margin:0px' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" + msg + "</div></div></div>";        
//    }else{   
//        btn.disabled = true;
//        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
//        var ajax=ajaxObj("POST","index.php?contact_page_form");
//        ajax.onreadystatechange=function(){
//            if(ajaxReturn(ajax)===true){
//                btn.disabled = false;
//                var data = ajax.responseText;
//                if(data == 1){
//                    time = 10000;
//                    color1 = 'alert-success';
//                    msg = "<strong><i class='fa fa-check-square'></i>&nbsp;</strong>  Sent successfuly";
//                } else if (data == 2){
//                    msg = "<strong><i class='fa fa-warning'></i>&nbsp;</strong> fill all fields !!!";
//                }else{
//                    msg = "<strong><i class='fa fa-warning'></i>&nbsp;</strong> Unknown error. please try again"+data+"";        
//                }
//                resp.innerHTML= "<div class='row1' style='text-align:center; margin-top:15px'><div class='col-lg-12'><div class='alert " + color1 + " alert-dismissable'><button onclick='ClearMessage(\"contact_status\")' type='button' style='width:auto; padding:1px; height: 30px;left:5%; margin:0px' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" + msg + "</div></div></div>";        
//                btn.innerHTML = 'Send Now';
//                setTimeout(function() {
//                    resp.innerHTML='';
//                },time);
//                setTimeout(function() {
//                    _("contact_page_form").reset();
//                },5000);
//            }
//        };
//        ajax.send("form_name="+name+"&form_email="+email+"&form_phone="+tel+"&form_subject="+subject+"&form_message="+form_message+"&function=contac_message");
//    }
//}

/////////////////////////////////
////// Newsletter script
function subscribe(){
        if($("#name").val().length == 0 ||  $("#email").val().length == 0 ){
		Msg("<i class='fa fa-warning'></i>&nbsp; fill all fields!!!","alert-danger",0,"#letterstatus",10000);
	} else {
	var data = $("#frmNewsletter1").serialize();
	$.ajax({
		type:'POST',
		url:'controller/post.php?subscribe',
		data:data,
		beforeSend: function(){	
			$("#sub_btn").html('<i class="fa fa-spinner fa-spin"></i> submitting...');
		},
		success: function(data){
			if(data == 1){
				$("#sub_btn").html("<i class='fa fa-newspaper-o'></i>&nbsp;Subscribe");
				Msg("<strong><i class='fa fa-check-square'></i>&nbsp;</strong>  Newsletter successfuly subscribed","alert-success",0,"#letterstatus",6000);
				setTimeout(function() {
					$("#frmNewsletter1").trigger('reset');
				},10000);
			} else if (data == 2){
				Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> fill all fields!!!","alert-danger",0,"#letterstatus",10000);
				$("#sub_btn").html("<i class='fa fa-newspaper-o'></i>&nbsp;Subscribe");
			}else{
				Msg("<strong><i class='fa fa-warning'></i>&nbsp;</strong> Unknown error. please try again","alert-danger",0,"#letterstatus",10000);				
				$("#sub_btn").html("<i class='fa fa-newspaper-o'></i>&nbsp;Subscribe");
			} 
		}
	});
    }
}
$(function(){
	$.validator.setDefaults({
		errorClass: 'help-block',
		highlight: function(element){
			$(element)
			.closest('.form-group')
			.addClass('has-error');
		},
		unhighlight: function(element){
			$(element)
			.closest('.form-group')
			.removeClass('has-error');
		}
	});
	$("#frmNewsletter1").validate({
		rules:{
			name:{
				required:true,
			},
			email:{
				required:true,
                                email: true
			}			
		},
		messages:{
			name:{
				required:"First name cannot be blank",
			},
			email:{
				required:"Email cannot be blank",
			}
		},
		submitHandler: subscribe
	});
});