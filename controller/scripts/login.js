// JavaScript Document
function Login(){
    if($("#Uemail").val().length == 0 || $("#password").val().length == 0){
        Msg("<i class='fa fa-warning'></i>&nbsp; Required Field(s) Empty!!!.","alert-danger",1,"#msg",12000);
    }else{
        var data = $("#loginform").serialize();
        $("#btn-login").prop('disabled',true);
        $.ajax({
            type:'POST',
            url:'../login/index.php?login',
            data:data,
            beforeSend: function(){	
                $("#btn-login").html('<span class="">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>');
        
            },
            success: function(data){
                if(data == 1){
                    Msg("<i class='fa fa-globe fa fa-spin'></i>&nbsp; Login Successful. Redirecting...","alert-success",1,"#msg",6000);
                    setTimeout(function(){
                        window.location = "../dashboard";
                    },1500);
                }else if(data == 11){
                    window.location = "../login/?xy="+Math.floor(Math.random()*982773773687766)+Math.floor(Math.random()*982773773687766)+"&security-clearance="+$("#Uemail").val()+"#login_section";
                } else if(data == 2){
                    Msg("<i class='fa fa-warning'></i>&nbsp; Invalid Login Details!!!","alert-danger",1,"#msg",12000);
                } else if(data == 3){
                    Msg("<i class='fa fa-globe fa fa-spin'></i>&nbsp; Please check your email for instructions to activate your account. If there is no mail in the \"Inbox\", please check your \"Spam\" folder.","alert-success",1,"#msg",10000);
                } else if(data == 4){
                    Msg("<i class='fa fa-warning'></i>&nbsp; Account Suspended, Contact Adminstrator!!!","alert-danger",1,"#msg",12000);
                } else if(data == 0){
                    Msg("<i class='fa fa-warning'></i>&nbsp; Required Field(s)","alert-danger",1,"#msg",12000);
                }else{
                    Msg("<i class='fa fa-warning'></i>&nbsp;"+data,"alert-danger",1,"#msg",12000);
                }
                if(data != 1){
                    $("#btn-login").prop('disabled',false);
                }
                
                setTimeout(function(){
                    $("#btn-login").html('Sign In');
                },1500);
            }            
    })}
}
$(document).ready(function(){
    $("#btn-login").click(function(e) {
        Login();
        e.preventDefault();
    });
});
function Login2(){
    if($("#username_email").val().length == 0 || $("#code").val().length == 0){
        Msg("<i class='fa fa-warning'></i>&nbsp; Required Field(s) Empty!!!.","alert-danger",1,"#msg",12000);
    } else{
        var data = $("#loginform").serialize();
        $("#btn-login2").prop('disabled',true);
        $.ajax({
            type:'POST',
            url:'../login/index.php?login_pin',
            data:data,
            beforeSend: function(){	
                $("#btn-login2").html('<span class="">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>');
            },
            success: function(data){
                if(data == 1){
                    setTimeout(function(){
                        Msg("<i class='fa fa-globe fa fa-spin'></i>&nbsp; Login Successful. Redirecting...","alert-success",1,"#msg",6000);
                        window.location = "../dashboard";
                    },1500);
                } else if(data == 2){
                    Msg("<i class='fa fa-warning'></i>&nbsp; Invalid Pin Details. Try again!!!","alert-danger",1,"#msg",12000);
                } else if(data == 0){
                    Msg("<i class='fa fa-warning'></i>&nbsp; Required Field(s)","alert-danger",1,"#msg",12000);
                }
                if(data != 1){
                    $("#btn-login2").prop('disabled',false);
                }
                $("#btn-login2").html('Approve Login');
            }            
    })}
}
$(document).ready(function(){
    $("#btn-login2").click(function(e) {
        Login2();
        e.preventDefault();
    });
});
