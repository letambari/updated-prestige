////// send mail function to send members verification emails
function send_mail(x){
	if(x == ''){
		Msg("<i class='fa fa-warning'></i>&nbsp; Error! Reload Page.","alert-danger",1,"#code_container",10000);
	} else {
	var data = "user_unique="+x;
	$.ajax({
		type:'POST',
		url:'../controller/post.php?send_mail',
		data:data,
		beforeSend: function(){	
			$("#code_btn").html('<i class="fa fa-spinner fa-spin"></i> ');
		},
		success: function(data){
			if(data == 1){
                            $("#code_btn").html('send code');
                            $("#code_btn").attr('disabled', true);
                            Msg("<strong><i class='fa fa-check-square'></i>&nbsp; Code sent. If there is no mail in the \"Inbox\", please check your \"Spam\" folder.</strong>","alert-success",1,"#code_container",10000);
			    setTimeout(function(){
                                $("#code_btn").attr('disabled', false);
                            },3000);
			} else if (data == 2){
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; User not found. Reload page!!!</strong>","alert-danger",1,"#code_container",10000);
				$("#code_btn").html('send code');
			} else{
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Unknown error, try again,"+data+"</strong>","alert-danger",1,"#code_container",10000);
				
				$("#code_btn").html('send code');
			} 
		}
	});
    }
}///////////////////////////
//
//
//// Update Wallet
function add_wallet_acct(){
        if($("#wallet").val().length == 0){
		Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#wallet_status",12000);
	} else {
	var data = $("#wallet_form").serialize();
	$.ajax({
		type:'POST',
		url:'index.php?update_wallet',
		data:data,
		beforeSend: function(){	
			$("#wallet_btn").html('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function(data){
			if(data == 1){
                            $("#wallet_btn").html('Submit');
                            Msg("<strong><i class='fa fa-check-square'></i>&nbsp;  Wallet updated successfuly</strong>","alert-success",1,"#wallet_status",10000);
				
			} else if (data == 2){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp; fill the form!!!</strong>","alert-danger",1,"#wallet_status",12000);
                            $("#wallet_btn").html('Submit');
			} else if(data == 3){
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Invalid wallet address</strong>","alert-danger",1,"#wallet_status",12000);				
                            $("#wallet_btn").html('Submit');
			}  else if(data == 4){
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; User not found. Reload page</strong>","alert-danger",1,"#wallet_status",12000);				
                            $("#wallet_btn").html('Submit');
			} else if(data == 5){
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Invalid verification code</strong>","alert-danger",1,"#wallet_status",12000);				
                            $("#wallet_btn").html('Submit');
			} else{
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; "+data+"</strong>","alert-danger",1,"#wallet_status",12000);				
                            $("#wallet_btn").html('Submit');
			} 
		}
	});
    }
}
//////////////// to change password from profile page
function change_pass(){ 
        if( $("#password").val().length == 0|| $("#password2").val().length == 0){
		Msg("<em class='icon ni ni-alert'></em>&nbsp; fill the form!!!.","alert-danger",1,"#status1",12000);
	} else {
	var data = $("#changePass").serialize();
	$.ajax({
            type:'POST',
            url:'../controller/post.php?user-reset-password',
            data:data,
            beforeSend: function(){	
                    $("#pass_btn").html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>');
            },
            success: function(data){
                if(data == 1){
                    setTimeout(function(){
                            Msg("<i class='fa fa-check-square'></i>&nbsp; Password Changed Successfully.","alert-success",1,"#status1",10000);
                    },3000);                     
                    setTimeout(function(){  
                        window.location = "";
                    },6000); 
                } else if (data == 2){
                    Msg("<strong><i class='fa fa-warning'></i>&nbsp; Error, fill the form!!!</strong>","alert-danger",1,"#status1",12000);
                } else if(data == 3){
                    Msg("<strong><i class='fa fa-warning'></i>&nbsp; Error, passwords do not match </strong>","alert-danger",1,"#status1",12000);
                } else if(data == 31){
                    Msg("<strong><i class='fa fa-warning'></i>&nbsp; Error, incorrect current password </strong>","alert-danger",1,"#status1",12000);
                } else if(data == 4){
                    Msg("<strong><i class='fa fa-warning'></i>&nbsp; Error, account not found</strong>","alert-danger",1,"#status1",12000);
                }
                $("#pass_btn").html('Update Password');
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
	$("#changePass").validate({
		rules:{
			password:{
				required:true,
			},
			password2:{
				required:true,
			}			
		},
		messages:{
			password:{
				required:"Field cannot be blank",
			},
			password2:{
				required:"Field cannot be blank",
			}
		},
		submitHandler:change_pass
	});
});
/////////////// reset passwordends here //////////////////////////
//
////////////////////// update portfolio page ///////////////
function update_account(){
        if($("#name").val().length == 0 || $("#UserMobile").val().length == 0|| $("#country").val().length == 0){
		Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#update_status",12000);
	} else {
	var data = $("#accountForm").serialize();
	$.ajax({
		type:'POST',
		url:'index.php?update_account',
		data:data,
		beforeSend: function(){	
			$("#acct_btn").html('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function(data){
			if(data == 1){
                            $("#acct_btn").html('Save');
                            Msg("<strong><i class='fa fa-check-square'></i>&nbsp;  Profile updated successfuly</strong>","alert-success",1,"#update_status",10000);
				
			} else if (data == 2){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp; fill the form!!!</strong>","alert-danger",1,"#update_status",12000);
                            $("#acct_btn").html('Save');
			} else if(data == 3){
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; account does not exist</strong>","alert-danger",1,"#update_status",12000);				
                            $("#acct_btn").html('Save');
			}  else if(data == 4){
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Enter a valid bitcoin wallet address.</strong>","alert-danger",1,"#update_status",12000);				
                            $("#acct_btn").html('Save');
			} else{
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; "+data+"</strong>","alert-danger",1,"#update_status",12000);				
                            $("#acct_btn").html('Save');
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
	$("#accountForm").validate({
		rules:{
			name:{
				required:true
				
			},
			UserMobile:{
				required:true
			},
			country:{
				required:true
			}			
		},
		messages:{
			name:{
				required:"Name cannot be blank",
			},
			UserMobile:{
				required:"Mobile number cannot be blank",
			},
			country:{
				required:"Country cannot be blank",
			}
		},
		submitHandler:update_account
	});
});
////////////// update settings
function update_settings(done){  
    if($("#two_fa_method").val().length == 0 ){
		Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#status3",12000);
	} else if(done == 0) {
	var data = $("#Two_Fa").serialize();
	$.ajax({
		type:'POST',
		url:'index.php?update_settings',
		data:data,
		beforeSend: function(){	
			$("#two_fa_btn").html('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function(data){
			if(data == 1){
                            $("#two_fa_btn").html("Submit");
				Msg("<strong><i class='fa fa-check-square'></i>&nbsp;  Two factor athentication successfuly updated.</strong>","alert-success",1,"#status3",10000);
				setTimeout(function() {
                                    window.location = "";
				},3000);
			} else if (data == 2){
				Msg("<strong><i class='fa fa-warning'></i>&nbsp; fill the form!!!</strong>","alert-danger",1,"#status3",12000);
				$("#two_fa_btn").html("Submit");
			} else if(data == 3){
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Code incorrect.</strong>","alert-danger",1,"#status3",12000);				
				$("#two_fa_btn").html("Submit");
			}else{
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Unknown error. please try again</strong>","alert-danger",1,"#status3",12000);				
				$("#two_fa_btn").html("Submit");
			} 
                        done = 1;
		}
	});
    }
}
function update_settings_two(y){  
    if($("#"+y).val() ==1){
        $("#"+y).val(2);
    }else{
        $("#"+y).val(1);
    }
    var data = {'field':y,'value':$("#"+y).val()};
    $.ajax({
        type:'POST',
        url:'index.php?update_settings',
        data:data,
        success: function(data){
            if(data == 1){
//                alert('Successful');
//                $("#popover").toggle(); 
//                setTimeout(function() {
//                    $("#popover").toggle(); 
//                },2000); 
            }
        }
    });
}
////////////////////// account funding request step one ///////////////
//('.panel-heading').toggleClass('active')
function deposit_step_one(x,y){
    if($("#amount").val().length == 0){
        Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#status_step_"+x,12000);
    } else {
        $('#dep_step_'+x).toggleClass('current');
        $('#dep_step_'+x+'_head').toggleClass('current');
        $('#dep_step_'+y).toggleClass('current');
        $('#dep_step_'+y+'_head').toggleClass('current');
    }
}
function deposit_step_prev(x,y){
    $('#dep_step_'+x).toggleClass('current');
    $('#dep_step_'+x+'_head').toggleClass('current');
    $('#dep_step_'+y).toggleClass('current');
    $('#dep_step_'+y+'_head').toggleClass('current');
}
function copy_text(x) {
    $("#"+x+"_btn").html('<i class="fas fa-spinner fa-spin"></i>');
    /* Get the text field */
    var copyText = document.getElementById(x);

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */

     /* Copy the text inside the text field */
    navigator.clipboard.writeText(copyText.value);

    /* Alert the copied text */
    $("#"+x+"_btn").html('<i class="fa fa-copy"></i>');
}
// Update the count down every 10 second
var i_time = 600;
function startTimer(a,y) {
    var countdownTimer = setInterval(function() {
        i_time = i_time - 1;
        if (i_time % 20 ==0 ) {
            // check payment status
            check_payment_status(y);
        }
        if(a == 0){
            i_time = 0;
        }
        if (i_time < 1) {
            clearInterval(countdownTimer);
        } 
    }, 1000);
}
function deposit_funds(x,y){
    if($("#amount").val().length == 0){
        Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#status_step_"+x,12000);
    } else {
        var btcontent = 'Continue &nbsp;'
                        +'<span class="svg-icon svg-icon-3 ms-1 me-0">'
                         +'   <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'
                          +'      <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>'
                           +'     <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"'
                            +    '    fill="currentColor"></path></svg></span>';
        $("#funds_btn").prop('disabled',true);
	var data = $("#deposite_form").serialize();
	$.ajax({
            type:'POST',
            url:'../controller/post.php?deposit',
            data:data,
            beforeSend: function(){	
                    $("#funds_btn").html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>');
            },
            success: function(data){
//                alert(data);
//                return;
                if(data == 1){

                        Msg("<strong><i class='fa fa-check-square'></i>&nbsp;  Request sent successfuly. An email has been sent to you.</strong>","alert-success",1,"#status_step_"+x,10000);
                        setTimeout(function() {
                                $("#deposite_form").trigger('reset');
                        },12000);
                } else if (data == 2){
                        Msg("<strong><i class='fa fa-warning'></i>&nbsp; fill the form!!!</strong>","alert-danger",1,"#status_step_"+x,12000);
                } else if(data == 3){
                        Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; account does not exist</strong>","alert-danger",1,"#status_step_"+x,12000);				
                }else if(data == 33){
                        Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Minimum deposit is $500.</strong>","alert-danger",1,"#status_step_"+x,12000);				
                }else if(data == 4){
                        Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error!!! Your first deposit can not be below 100 USD.</strong>","alert-danger",1,"#status_step_"+x,12000);				
                }else if(data == 5){
                        Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error! Bank Wire is allowed for only deposits from $50,000 above</strong>","alert-danger",1,"#status_step_"+x,12000);				
                }else{       
                    var check = data.split("|||")[0];
                    if(check == 0){
                        data = data.split("|||")[1];
                        deposit_step_one(x,y);
                        var qr = data.split("||")[0];
                        var addr = data.split("||")[1];
                        var invoice = data.split("||")[2];
                        var amount = data.split("||")[3];
                        var method = data.split("||")[4];
                        var payment_Id = data.split("||")[5];
                        startTimer(1,payment_Id);
                        $("#dep_invoice").html("#"+invoice);
                        $(".coin_name").html(method);
                        $("#coin_address").val(addr);
                        $(".amount_holder").html(amount);
                        $("#dep_qr_code").html(qr);
                    }else{
                        data = data.split("|||")[1];
                        Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error!!! "+data+" </strong>","alert-danger",1,"#status_step_"+x,12000);				
                        $("#funds_btn").prop('disabled',false);
                    }
                        
//				Msg("<i class='fa fa-check-square'></i>&nbsp;Request sent successfuly.  <br/><br/>","alert-success",1,"#dep_status",50000);
//                                Msg_qr(qr+'<br/>'+addr+'<br/>',"#dep_qr_code",1200000);                            
//                    $("#deposite_form").trigger('reset');
//                    $("#funds_btn").html("Submit");
//                    $("#deposite_form").toggle();
//                    $("#code_container").toggle();
                } 
                $("#funds_btn").prop('disabled',true);
                $("#funds_btn").html(btcontent);
            }
	});
    }
}
function check_payment_status(x){
    $.ajax({
            type:'POST',
            url:'../controller/post.php?check_payment_status',
            data:{'payment_id':x},
            success: function(data){
                    if(data == 0){
                        /////
                    }else if(data == 1){
                        $("#status_display").html('Confirming payment...<i class="fa fa-spinner fa-spin"></i>');
                    }else if(data == 2){
                        deposit_step_one('three','four');
                        startTimer(0,x);
                    }else{
                        alert(data);
                    } 
            }
    });
}
//function deposit_funds(){
//        if($("#fund_method").val().length == 0 || $("#amount").val().length == 0){
//		Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#dep_status",12000);
//	} else {
//	var data = $("#deposite_form").serialize();
//	$.ajax({
//		type:'POST',
//		url:'../controller/post.php?deposit',
//		data:data,
//		beforeSend: function(){	
//			$("#funds_btn").html('<i class="fa fa-spinner fa-spin"></i>');
//		},
//		success: function(data){
//			if(data == 1){
//                            
//				Msg("<strong><i class='fa fa-check-square'></i>&nbsp;  Request sent successfuly. An email has been sent to you.</strong>","alert-success",1,"#dep_status",10000);
//				setTimeout(function() {
//					$("#deposite_form").trigger('reset');
//				},12000);
//			} else if (data == 2){
//				Msg("<strong><i class='fa fa-warning'></i>&nbsp; fill the form!!!</strong>","alert-danger",1,"#dep_status",12000);
//				
//			} else if(data == 3){
//				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; account does not exist</strong>","alert-danger",1,"#dep_status",12000);				
//				
//			}else if(data == 4){
//				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error!!! Your first deposit can not be below 100 USD.</strong>","alert-danger",1,"#dep_status",12000);				
//				
//			}else if(data == 5){
//				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error! Bank Wire is allowed for only deposits from $50,000 above</strong>","alert-danger",1,"#dep_status",12000);				
//				
//			}else{
//                            var qr = data.split("||")[0];
//                            var addr = data.split("||")[1];
//                            var invoice = data.split("||")[2];
//                            var amount = data.split("||")[3];
//                            var method = data.split("||")[4];
//                            $("#dep_invoice").html("#"+invoice);
//                            $(".coin_name").html(method);
//                            $("#coin_address").html(addr);
//                            $(".amount_holder").html(amount);
//                            $("#dep_qr_code").html(qr);
////				Msg("<i class='fa fa-check-square'></i>&nbsp;Request sent successfuly.  <br/><br/>","alert-success",1,"#dep_status",50000);
////                                Msg_qr(qr+'<br/>'+addr+'<br/>',"#dep_qr_code",1200000);                            
//                            $("#deposite_form").trigger('reset');
//                            $("#funds_btn").html("Submit");
//                            $("#deposite_form").toggle();
//                            $("#code_container").toggle();
//			} 
//                        $("#funds_btn").html("Submit");
//		}
//	});
//    }
//}
////////////////////// Subscribe to investment ///////////////
function select_portfolio(x,y,z){
    if(x != ''){
        $("#plan").val(x);
        $("#plan_tire").val(y);
        $("#plan_name").html(x);
        $("#"+z).modal('show');
        var min = '1,000';
        if(y == "Tier 2"){
            min = '51,000';
        }else if(y == "Tier 3"){
            min = '211,000';
        }else if(y == "Tier 4"){
            min = '501,000';
        }else if(y == "Crypto IRA"){
            min = '50,000';
        }
        $("#min_amount").html(min);
        $("#amount").prop('min',min);
        $("#sub_btn").prop('disabled',false); 
    }
}
// purchase plan page
function select_plan(x){
    if(x != ''){
        var plan = x;
        var min = '100';
        var y = plan[1];
        if(plan == "Pack 104"){
            min = '1000';
        }
        $("#min_amount").html(min);
        $("#amount").prop('min',min);
        $("#sub_btn").prop('disabled',false); 
    }
}
$(document).ready(function() {
    $("#sub_btn").click(function(){
        purchase_portfolio();
    });
    
});
function purchase_portfolio(){
        if($("#amount").val().length == 0 || $("#plan").val().length == 0 ){
            Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#status_step",12000);
	} else {
	var data = $("#invest_form").serialize();
        $("#sub_btn").prop('disabled',true); 
	$.ajax({
		type:'POST',
		url:'../controller/post.php?purchase_portfolio',
		data:data,
		beforeSend: function(){	
			$("#sub_btn").html('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function(data){
                    if(data == 1){                            
                        Msg("<strong><i class='fa fa-check-square'></i>&nbsp;  Purchase Successful.</strong>","alert-success",1,"#status_step",10000);
//                        we check if we are on the pricing page or the purchase investment page
                        $("#invest_step_one").toggle();
                        $("#invest_step_two").toggle();
                        setTimeout(function(){
                            $("#invest_form").trigger('reset');
                        },12000);
			} else if (data == 2){
				Msg("<strong><i class='fa fa-warning'></i>&nbsp; fill the form!!!</strong>","alert-danger",1,"#status_step",12000);
				
			} else if (data == 22){
                            Msg("<strong><i class='fa fa-warning'></i>&nbsp; can not purchase below minimum investment.</strong>","alert-danger",1,"#status_step",12000);

                        } else if(data == 3){
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; account does not exist</strong>","alert-danger",1,"#status_step",12000);				
				
			}else if(data == 31){
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; <a href='../deposit'>Click here to fund your account. </a></strong>","alert-danger",1,"#status_step",12000);				

                        }else if(data == 32){
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; insufficient account balance</strong>","alert-danger",1,"#status_step",12000);				

                        }else{
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; "+data+" </strong>","alert-danger",1,"#status_step",12000);				
			} 
                        if(data != 1){
                            $("#sub_btn").prop('disabled',false);       
			}
                        $("#sub_btn").html("Submit");
		}
	});
    }
}
////////////////////// to top up investment ///////////////
function top_up_portfolio(x){
        if($("#top_up_amount_"+x).val().length == 0 ){
		Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#top_up_status_"+x,12000);
	} else {
	var data = $("#top_up_form_"+x).serialize();
        $("#top_up_btn_"+x).prop('disabled',true);
	$.ajax({
		type:'POST',
		url:'../controller/post.php?top_up=&portfolio='+x,
		data:data,
		beforeSend: function(){	
                    $("#top_up_btn_"+x).html('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function(data){
			if(data == 1){                            
                            Msg("<strong><i class='fa fa-check-square'></i>&nbsp; Funds successfully added to your portfolio.</strong>","alert-success",1,"#top_up_status_"+x,10000);
                            $("#cancle_btn_"+x).html('close');
                            setTimeout(function() {
                                $("#top_up_form_"+x).trigger('reset');
                            },12000);
			} else if (data == 2){
				Msg("<strong><i class='fa fa-warning'></i>&nbsp; fill the form!!!</strong>","alert-danger",1,"#top_up_status_"+x,12000);
				
			} else if(data == 3){
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; account does not exist</strong>","alert-danger",1,"#top_up_status_"+x,12000);				
				
			}else if(data == 4){
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error!!! Investment Portfolio is not active or has expired.</strong>","alert-danger",1,"#top_up_status_"+x,12000);				
				
			}else if(data == 5){
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Your wallet is empty.</strong> <a href='../deposit/'> Click here to fund your wallet</a>","alert-danger",1,"#top_up_status_"+x,12000);				
				
			}else if(data == 6){
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Insufficient wallet balance.</strong> <a href='../deposit/'> Click here to fund your wallet.</a>","alert-danger",1,"#top_up_status_"+x,12000);				
				
			}else{
                            Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; "+data+".</strong> <a href='../deposit/'> Click here to fund your wallet.</a>","alert-danger",1,"#top_up_status_"+x,12000);				
				
			} 
                        if(data != 1){
                            $("#top_up_btn_"+x).prop('disabled',false);       
			}
                        $("#top_up_btn_"+x).html("Submit");
		}
	});
    }
}
/////////////////////
////////////// withdrawal
function withdrawal_step_one(x,y){
    if($("#amount").val().length == 0||$("#withdraw_from").val().length == 0){
        Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#status_step_"+x,12000);
    } else {
        var a = parseFloat($("#amount").val());
        var b = parseFloat($("#with_amount").val());
        if(a > b){
            Msg("<i class='fa fa-warning'></i>&nbsp; You can not withdraw above your profit","alert-danger",1,"#status_step_"+x,12000);
            return;
        } 
        $('#with_step_'+x).toggleClass('current');
        $('#with_step_'+x+'_head').toggleClass('current');
        $('#with_step_'+y).toggleClass('current');
        $('#with_step_'+y+'_head').toggleClass('current');
    }
}
function withdrawal_step_prev(x,y){
    $('#with_step_'+x).toggleClass('current');
    $('#with_step_'+x+'_head').toggleClass('current');
    $('#with_step_'+y).toggleClass('current');
    $('#with_step_'+y+'_head').toggleClass('current');
}
////////////
function select_from(x){
    if(x != ''){
        var plan = x.split("-");
        $("#plan_id").val(plan[0]);
        $("#with_amount").val(plan[1]);
        var max = plan[1];
        $("#amount").prop('max',max); 
    }
}
////////////////////// account withdrawal request ///////////////
function withdraw_funds(x,y){
    if($("#amount").val().length == 0||$("#plan_id").val().length == 0||$("#withdraw_details").val().length == 0){
        Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#status_step_"+x,12000);
    } else {
        var btcontent = 'Submit &nbsp;'
                        +'<span class="svg-icon svg-icon-3 ms-1 me-0">'
                         +'   <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'
                          +'      <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>'
                           +'     <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"'
                            +    '    fill="currentColor"></path></svg></span>';
        $("#withdraw_btn").prop('disabled',true);
	var data = $("#withdraw_form").serialize();
	$.ajax({
            type:'POST',
            url:'../controller/post.php?withdraw',
            data:data,
            beforeSend: function(){	
                    $("#withdraw_btn").html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>');
            },
            success: function(data){
                if(data == 1){
                    withdrawal_step_one(x,y);
                    Msg("<strong><i class='fa fa-check-square'></i>&nbsp;  Request sent successfully and is being processed.</strong>","alert-success",1,"#status_step_"+x,10000);
                    setTimeout(function() {
                            $("#withdraw_form").trigger('reset');
                    },12000);
                } else if (data == 2){
                        Msg("<strong><i class='fa fa-warning'></i>&nbsp; fill the form!!!</strong>","alert-danger",1,"#status_step_"+x,12000);
                } else if(data == 3){
                        Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; account does not exist</strong>","alert-danger",1,"#status_step_"+x,12000);				
                }else if(data == 4){
                        Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Action Rejected!!!. You must fund your account first</strong>","alert-danger",1,"#status_step_"+x,12000);				
                }else if(data == 5){
                        Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error!!! You can not withdraw above your profit</strong>","alert-danger",1,"#status_step_"+x,12000);				
                }else if(data == 6){
                        Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error!!! Can go below the Minimum withdrawal</strong>","alert-danger",1,"#status_step_"+x,12000);				
                }else{
                    Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Unknown error. "+data+"</strong>","alert-danger",1,"#status_step_"+x,12000);
                } 
                $("#withdraw_btn").prop('disabled',false);
                $("#withdraw_btn").html(btcontent);
            }
	});
    }
}
//function withdraw_funds(){
//        if($("#withdraw_details").val().length == 0 ||$("#withdraw_method").val().length == 0 || $("#amount").val().length == 0){
//		Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#status_pay",12000);
//	} else {
//	var data = $("#withdraw_form").serialize();
//	$.ajax({
//		type:'POST',
//		url:'../controller/post.php?withdraw',
//		data:data,
//		beforeSend: function(){	
//			$("#withdraw_btn").html('<i class="fa fa-spinner fa-spin"></i> submitting...');
//		},
//		success: function(data){
//			if(data == 1){
//                            $("#withdraw_btn").html("Submit");
//				Msg("<strong><i class='fa fa-check-square'></i>&nbsp;  Request sent successfuly</strong>","alert-success",1,"#status_pay",10000);
//				setTimeout(function() {
//					$("#deposite_form").trigger('reset');
//				},12000);
//			} else if (data == 2){
//				Msg("<strong><i class='fa fa-warning'></i>&nbsp; fill the form!!!</strong>","alert-danger",1,"#status_pay",12000);
//				$("#withdraw_btn").html("Submit");
//			} else if(data == 3){
//				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error!!! Account does not exist</strong>","alert-danger",1,"#status_pay",12000);				
//				$("#withdraw_btn").html("Submit");
//			} else if(data == 4){
//				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Action Rejected!!!. You must fund your account first</strong>","alert-danger",1,"#status_pay",12000);				
//				$("#withdraw_btn").html("Submit");
//			} else if(data == 5){
//				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error!!! You can not withdraw above your profit</strong>","alert-danger",1,"#status_pay",12000);				
//				$("#withdraw_btn").html("Submit");
//			} else if(data == 6){
//				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Error!!! Can go below the Minimum withdrawal</strong>","alert-danger",1,"#status_pay",12000);				
//				$("#withdraw_btn").html("Submit");
//			}else{
//				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Unknown error. please try again</strong>","alert-danger",1,"#status_pay",12000);				
//				$("#withdraw_btn").html("Submit");
//			} 
//		}
//	});
//    }
//}
////////////////////// in page support script ///////////////
////////////////////// support script ///////////////
function send_message(done){
        if($("#subject").val().length == 0 || $("#message").val().length == 0){
		Msg("<i class='fa fa-warning'></i>&nbsp; fill the form!!!","alert-danger",1,"#support_status",12000);
	} else if(done == 0) {
	var data = $("#support_form").serialize();
	$.ajax({
		type:'POST',
		url:'index.php',
		data:data,
		beforeSend: function(){	
			$("#support_btn").html('<i class="fa fa-spinner fa-spin"></i>');
		},
		success: function(data){
			if(data == 1){
                            $("#support_btn").html("&nbsp;&nbsp;&nbsp;Send&nbsp;&nbsp;&nbsp;");
				Msg("<strong><i class='fa fa-check-square'></i>&nbsp;  Sent successfuly</strong>","alert-success",1,"#support_status",10000);
				setTimeout(function() {
					$("#support_form").trigger('reset');
				},12000);
			} else if (data == 2){
				Msg("<strong><i class='fa fa-warning'></i>&nbsp; fill the form!!!</strong>","alert-danger",1,"#support_status",12000);
				$("#support_btn").html("&nbsp;&nbsp;&nbsp;Send&nbsp;&nbsp;&nbsp;");
			} else if(data == 3){
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; account does not exist</strong>","alert-danger",1,"#support_status",12000);				
				$("#support_btn").html("&nbsp;&nbsp;&nbsp;Send&nbsp;&nbsp;&nbsp;");
			}else{
				Msg("<strong><i class='fa fa-times-circle'></i>&nbsp; Unknown error. please try again</strong>","alert-danger",1,"#support_status",12000);				
				$("#support_btn").html("&nbsp;&nbsp;&nbsp;Send&nbsp;&nbsp;&nbsp;");
			} 
                        done = 1;
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
	$("#support_form").validate({
		rules:{
			subject:{
				required:true,
				
			},
			message:{
				required:true,
			}			
		},
		messages:{
			subject:{
				required:"subject cannot be blank",
			},
			message:{
				required:"message body cannot be blank",
			}
		},
		submitHandler:send_message
	});
});
//// Update the last time they checked notification bar
function update_seen(x){
	var data = {'update_email_seen':x};
	$.ajax({
		type:'POST',
		url:'../controller/post.php?update_seen',
		data:data,		
		success: function(data){
//			if(data == 1){
//                            alert('good');
//			}else{
//                            alert('error');
//			} 
		}
	});
}