function ajaxObj( meth, url ) {
   var x = new XMLHttpRequest();
   x.open( meth, url, true );
   x.setRequestHeader("Content-type","application/x-www-form-urlencoded");
   return x;
}
function ajaxReturn(x){
   if(x.readyState === 4 && x.status === 200){
      return true;
   }
}
function _(x){
    return document.getElementById(x);
}
function restrict(elem){
    var tf=_(elem);
    var rx=new RegExp;
    if(tf.type==="email"){rx=/[^a-zA-Z0-9.@]/gi;}
    else if(tf.type==="text"){rx=/[^a-z0-9 ]/gi;}
    if(elem==="phone"||elem==="acc_no"||elem==="amount"){rx=/[^+ 0-9.() ]/gi;}tf.value=tf.value.replace(rx,"");
}
function emptyElement(x){
    _(x).innerHTML="";
}
function fund_member(x,y,z){
    var a  = _(x).value;
    var m  = y;
    var id  = z;
    var btn  = _(z+'_fa'); 
    if(a===""){
        alert("Amount can not be empty");
    }else{
        btn.disabled = true;
        btn.textContent = 'processing...';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){			
                if(ajax.responseText==="success"){					
                    alert("member funded sucessfuly");
                  //  window.location="";
                    btn.textContent = 'Funded';
                }else{
                    alert(ajax.responseText);
                    btn.disabled = false;
                    btn.textContent = 'Fund Account';
                    
                }
            }
        };
        ajax.send("a="+a+"&m="+m+"&unq="+id+"&function=fm");
    }
}
function fund_cancel(x,y){
    var a  = x;
    var unq  = y;
    var btn  = _(y+'_cf');
    btn.disabled = true;
    btn.textContent = 'processing...';
    var ajax=ajaxObj("POST","post.php");
    ajax.onreadystatechange=function(){
        if(ajaxReturn(ajax)===true){			
            if(ajax.responseText==="success"){					
                alert("transaction sucessfuly Cancelled");
               // window.location="";
               btn.textContent = 'Cancelled';
            }else{
                alert(ajax.responseText);
                btn.disabled = false;
                btn.textContent = 'Cancel';
            }
        }
    };
    ajax.send("a="+a+"&unq="+unq+"&function=fund_cancel");
}
function withdraw_member(x,y,z){
    var a  = _(x).value;
    var m  = y;
    var id  = z;
    var btn  = _(z+'_aw');    
    if(a===""){
        alert("Amount can not be empty");
    }else{
        btn.disabled = true;
        btn.textContent = 'processing...';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){			
                if(ajax.responseText==="success"){					
                    alert("Withdrawal sucessful");
//                    window.location="../secure/";
                }else{
                    alert(ajax.responseText);
                }
                btn.textContent = 'Withdraw';
            }
        };
        ajax.send("a="+a+"&m="+m+"&uq="+id+"&function=withdraw_m");
    }
}
function withdraw_cancel(x,y){
    var a  = x;
    var unq  = y;
    var btn  = _(y+'_cw');    
    if(a===""){
        alert("error, please reload the page.. Username can not be empty");
    }else{
        btn.disabled = true;
         btn.textContent = 'processing...';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){			
                if(ajax.responseText==="success"){					
                    alert("transaction sucessfuly Cancelled");
                  //  window.location="../secure/";
                  btn.textContent = 'Cancelled';
                }else{
                    alert(ajax.responseText);
                    btn.disabled = false;
                    btn.textContent = 'Cancel';
                }
            }
        };
        ajax.send("a="+a+"&unq="+unq+"&function=withdraw_cancel");
    }
}
function update_port_acct(x,y){
    var user_name = y;
    var trade_plan  = _(x+'_trade_plan').value;
    var currency  = _(x+'_currency').value;
    var act_type  = _(x+'_acct').value;
    var pr  = _(x+'_profit').value;
    var dpr  = _(x+'_adpr').value;
    var refbonus  = _(x+'_b').value;
    var ld = _(x+'_c').value;
    var td  = _(x+'_d').value;
    var lw = _(x+'_e').value;
    var tw  = _(x+'_f').value;
    var bal = _(x+'_g').value;
    var btn  = _(x+'_ua'); 
    if(pr===""||dpr===""||refbonus===""||ld===""||tw===""||td===""||lw===""||bal===""){
        alert("all fields are required");
    }else{   
        btn.disabled = true;
        btn.textContent = 'processing...';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){
                if(ajax.responseText==="success"){					
                    alert("Portfolio updated sucessfully");
		//			window.location="";
                }else{
                    alert(ajax.responseText);
                }
            }
            btn.disabled = false;
            btn.textContent = 'Update';
        };
        ajax.send("port="+x+"&pr="+pr+"&trade_plan="+trade_plan+"&currency="+currency+"&acct_type="+act_type+"&dpr="+dpr+"&refbonus="+refbonus+"&ld="+ld+"&td="+td+"&lw="+lw+"&tw="+tw+"&bal="+bal+"&un="+user_name+"&function=update_m_portfolio");
    }
}
function update_mem_acct(x,y){
    var user_name = y;
    var currency  = _(x+'_currency').value;
    var refbonus  = _(x+'_b').value;
    var ld = _(x+'_c').value;
    var td  = _(x+'_d').value;
    var lw = _(x+'_e').value;
    var tw  = _(x+'_f').value;
    var bal = _(x+'_g').value;
    var btn  = _(x+'_ua'); 
    if(refbonus===""||ld===""||tw===""||td===""||lw===""||bal===""){
        alert("all fields are required");
    }else{   
        btn.disabled = true;
        btn.textContent = 'processing...';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){
                if(ajax.responseText==="success"){					
                    alert("member account updated sucessfully");
		//			window.location="";
                    
                }else{
                    alert(ajax.responseText);
                }
                btn.textContent = 'Update';
                btn.disabled = false;
            }
        };
        ajax.send("currency="+currency+"&refbonus="+refbonus+"&ld="+ld+"&td="+td+"&lw="+lw+"&tw="+tw+"&bal="+bal+"&un="+user_name+"&function=update_m_acct");
    }
}
function securePaymentDetails(){
    var e=_("email").value;
    var n=_("uname").value;
    var d=_("deposit_method").value;
    var a=_("amount").value;
    var p=_("payment_details").value;
    var status=_("status");
    var btn = _("fund_btn2");
    if(e===""||n===""||d===""||a===""||p===""){
        status.style.color='#a94442';
        status.textContent=' some form values are missing ';
    }else if(e.indexOf("@")<0||e.indexOf('.')<0){
        status.style.color='#a94442';
        status.textContent=' provide a valid e-mail address ';
    }else{
        btn.textContent = 'processing...';
        btn.disabled=true;
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){
                btn.disabled=false;  
                if(ajax.responseText!=="success"){
                    status.style.color='#a94442';
                    status.textContent=ajax.responseText;
                    alert(ajax.responseText);
                }else{
                    alert("Payment details successfully sent to member.");
                    status.style.color='#3c763d';
                    status.textContent="Payment details successfully sent to member.";
                    window.location="../secure/";
                }
                btn.textContent = 'Send';
            }
        };
        ajax.send("function=paymentdetails&e="+e+"&n="+n+"&a="+a+"&d="+d+"&p="+p);
    }
}
function secureMail(){
    var e=_("email").value;
    var n=_("uname").value;
    var s=_("subject").value;
    var m=_("messageBody").value;
    var status=_("statusText");
    var btn = _("mail_btn2");
    if(e==""||n==""||s==""||m==""){
        status.style.color='#a94442';
        status.textContent=' some form values are missing ';
    }else if(e.indexOf("@")<0||e.indexOf('.')<0){
        status.style.color='#a94442';
        status.textContent=' provide a valid e-mail address ';
    }else{
        btn.disabled=true;
        btn.textContent = 'processing...';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){
                btn.disabled=false;  
                if(ajax.responseText!=="success"){
                    status.style.color='#a94442';
                    status.textContent=ajax.responseText;
                    alert(ajax.responseText);
                }else{
                    alert("Email successfully sent to member.");
                    status.style.color='#3c763d';
                    status.textContent="e-mail successfully sent to member.";
                  //  window.location="";
                }
                btn.textContent = 'Submit Mail!!!';
            }
        };
        ajax.send("function=sendMail&e="+e+"&n="+n+"&s="+s+"&m="+m);
    }
}
function update_sponsor(u_id){
    var unique_code_value  = _(u_id+"_sponsor").value;
    var btn  = _(u_id+'_spo_btn');
    btn.disabled = true;
    btn.textContent = 'processing...';
    var ajax=ajaxObj("POST","post.php");
    ajax.onreadystatechange=function(){
        if(ajaxReturn(ajax)===true){	
			btn.disabled = false;
            if(ajax.responseText==="success"){					
                alert("Sponsor sucessfuly Updated");
            }else{
                alert(ajax.responseText);        
            }
            btn.textContent = 'Update';
        }
    };
    ajax.send("unique_code="+u_id+"&s_email="+unique_code_value+"&function=Update_sponsor");
}
function update_wallet(w_id){
    var w_address  = _(w_id+"_wallet").value;
    var dep_disp  = _(w_id+"_dep_display").value;
    var with_disp  = _(w_id+"_with_display").value;
    var btn  = _(w_id+'_w_btn');
    btn.disabled = true;
    btn.textContent = '...';
    var ajax=ajaxObj("POST","post.php");
    ajax.onreadystatechange=function(){
        if(ajaxReturn(ajax)===true){	
            btn.disabled = false;
            if(ajax.responseText==="success"){					
                alert("Wallet sucessfuly Updated");
            }else{
                alert(ajax.responseText);    
            }
            btn.innerHTML = '<em class="fa fa-save"></em>';
        }
    };
    ajax.send("id="+w_id+"&w_address="+w_address+"&dep_disp="+dep_disp+"&with_disp="+with_disp+"&function=Update_wallet");
}
function remove_wallet(w_id){
    var btn  = _(w_id+'_remove_btn');
    var ask = confirm('Click "OK" to DELETE wallet!');
    if(ask){
        btn.disabled = true;
        btn.textContent = '...';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){			
                if(ajax.responseText==="success"){					
                    alert("Wallet Removed sucessfuly");
                }else{
                    alert(ajax.responseText);
                    btn.disabled = false;
                    
                }
                btn.innerHTML = '<em class="fa fa-trash-alt"></em>';
            }
        };
        ajax.send("id="+w_id+"&function=remove_wallet");
    }
        
}
//////////////////////////////////////////
function remove_member(m_email){
    var btn  = _(m_email+'_remove');
    var ask = confirm('Click "OK" to DELETE Member!');
    if(ask){
        btn.disabled = true;
        btn.textContent = 'processing...';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){			
                if(ajax.responseText==="success"){					
                    alert("Member Removed sucessfuly");
                                    btn.textContent = 'DELETED';
                }else{
                    alert(ajax.responseText);
                    btn.disabled = false;
                    btn.textContent = 'Delete Member';
                }
            }
        };
        ajax.send("e="+m_email+"&function=remove_member");
    }
        
}
////// this part verifies members////////////
function swap_admin_account(x,y,z){
    var u  = x;
    var btn  = _(y);
    btn.disabled=true;
    btn.textContent='processing.....';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){			
                if(ajax.responseText==="success"){					
                    alert("Action Sucessful");
                    btn.textContent='SUCESS';
                }else{
                    btn.disabled=false;
                    btn.textContent='Activate Account';
                    alert(ajax.responseText);
                }
            }
        };
        ajax.send("u="+u+"&code="+z+"&function=verify_account");
}
//////// this is the member auto update switch //////
////// ////////////
function update_switch(x,y,z){
    var btn  = _(x+'_update');
    var hold = btn.textContent;
    btn.disabled=true;
    btn.textContent='processing.....';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){			
                if(ajax.responseText==="1"){					
                    alert("Action Sucessful");
                }else{
                    
                    alert(ajax.responseText);
                }
                btn.disabled=false;
                btn.textContent=hold;
            }
        };
        ajax.send("id="+x+"&value="+y+"&tbl="+z+"&function=update_btn");
}
////// this part makes and removes admins////////////
function admin_swap(x,y,z){
    var u  = x;
    var btn  = _(y+"_swap");
    btn.disabled=true;
    btn.textContent='processing.....';
        var ajax=ajaxObj("POST","post.php");
        ajax.onreadystatechange=function(){
            if(ajaxReturn(ajax)===true){			
                if(ajax.responseText==="success"){					
                    alert("Action Sucessful");
                    btn.textContent='Done';
                }else{
                    btn.disabled=false;
                    btn.textContent='Re-try';
                    alert(ajax.responseText);
                }
            }
        };
        ajax.send("u="+u+"&value="+z+"&function=admin_swap");
}