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
function ___(x){
    return document.getElementById(x);
}
function restrict(elem){
    var tf=___(elem);
    var rx=new RegExp;
    if(tf.type==="email"){rx=/[^a-zA-Z0-9.@]/gi;}
    else if(tf.type==="text"){rx=/[^a-z0-9 ]/gi;}
    if(elem==="phone"||elem==="acct_num"||elem==="amount"||elem==="with_amount"){rx=/[^+,0-9. ()]/gi;}tf.value=tf.value.replace(rx,"");
}
function emptyElement(x){
    ___(x).innerHTML="";
}
function open_page(){
    var elem = ___('loader');
    elem.style.display ='none';
}
function ShowPassform(x){
    var elem = ___(x);
    if(elem.style.display =='block'){
        elem.style.display ='none';
        ___('sign-up').style.display ='none';
        ___('sign-in').style.display ='block';
        
    }else{
        elem.style.display ='block';
        ___('sign-in').style.display ='none';
        ___('sign-up').style.display ='none';
    }
}

function ShowLogin(){
    window.location="?sign-in";
}
function add_wallet(){
    var name = ___('wal_name').value;
    var add = ___('wallet_address').value;
    var btn  = ___('add_wallet_btn');
    if(''==name||''==add){
        return false;
    }
    btn.disabled = true;
    btn.textContent = 'processing...';
    var ajax=ajaxObj("POST","index.php?add_w");
    ajax.onreadystatechange=function(){
        if(ajaxReturn(ajax)===true){			
            if(ajax.responseText==="success"){		
                window.location="?add-wallet";
            }else{
                alert(ajax.responseText);
                btn.disabled = false;
                btn.textContent = 'Add wallet';
            }
        }
    };
    ajax.send("w_name="+name+"&w_add="+add);
}

function remove_wallet(x,y){
    var btn  = ___(y+'_btn');
    btn.disabled = true;
    btn.textContent = 'processing...';
    var ajax=ajaxObj("POST","index.php?remove_w");
    ajax.onreadystatechange=function(){
        if(ajaxReturn(ajax)===true){			
            if(ajax.responseText==="success"){		
                window.location="?remove-wallet";
            }else{
                alert(ajax.responseText);
                btn.disabled = false;
                btn.textContent = 'Remove wallet';
            }
        }
    };
    ajax.send("w_add="+x+"&w_id="+y);
}
function check(x){
    var ajax=ajaxObj("POST","../controller/post.php");
    ajax.onreadystatechange=function(){
        if(ajaxReturn(ajax)===true){			
            if(ajax.responseText==="success"){		
                ___('withdraw_btn').disabled = false;
                ___('amount').disabled = false;
            }else if(ajax.responseText=="0"){		
                ___('withdraw_btn').disabled = true;
                ___('amount').disabled = true;
            }else{
                alert(ajax.responseText);
                ___('withdraw_btn').disabled = true;
                ___('amount').disabled = true;
            }
        }
    };
    ajax.send("from="+x+"&function=check_balance");
}
function check_btn(x){
    var terms =___(x);
    if(terms.checked === false){
        ___('btn-register').disabled = true;
    }else{
        ___('btn-register').disabled = false;
    }
}
function check_coin(x){			
    if("Alt_Coin"==x){		
        ___('alt_coin').style.display = 'block';
        ___('coinname').style.display = 'block';
        ___('coinname').required = true;
    }else{		
        ___('alt_coin').style.display = 'none';
        ___('coinname').style.display = 'none';
        ___('coinname').required = false;
    }
}
function check_two_fa(x){			
    if("Google"==x){		
        ___('two_fa_google').style.display = 'block';
        ___('two_fa_email').style.display = 'none';
//        ___('coinname').required = true;
    }else if("Email"==x){		
        ___('two_fa_google').style.display = 'none';
        ___('two_fa_email').style.display = 'block';
//        ___('coinname').required = false;
    }else{
        ___('two_fa_google').style.display = 'none';
        ___('two_fa_email').style.display = 'none';
    }
}