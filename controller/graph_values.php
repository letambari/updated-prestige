<?php
////////////////////////////////////////
    ////////////// CHARTS DATA AND VARIABLES
    //////////// here we get the data to plot the present day's earnings profit graph
    $sign_up_date = date('M d', strtotime($signup));
    $sign_up_year = date('M d y', strtotime($signup));
    $sql = "SELECT amount,transaction_type FROM transactions WHERE user_id='$profile_id' AND (transaction_type='Profit' or  transaction_type='Referral Bonus') and status='2'  AND DATE(created_at) = CURDATE() order by id asc";
    $query = mysqli_query($db_conx, $sql);
    // Now make sure that user exists in the table
    $numrows = mysqli_num_rows($query);
    $xPnlDate = [];
    $yPnlAmount = [];
    $count =0;
    $todays_earnings = 0;
    if($numrows > 0){
        // Fetch the user row from the query above
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $amount = $row['amount'];
            $payment_method = $row['transaction_type'];
            $todays_earnings += $amount;
            array_push($xPnlDate, $payment_method);
            array_push($yPnlAmount, $amount);
        }
    }
    if($count < 1){
        $yPnlMin = 0;
        $yPnlMax = 0;
    }else{
        $yPnlMin = min($yPnlAmount);
        $yPnlMax = max($yPnlAmount);
        if($yPnlMin >1 && $yPnlMax > 1){
            $yPnlMin -= 2;
            $yPnlMax += 5;
        }
    }
    //////////// here we get the data to plot the $life_earnings profit graph
    $sql = "SELECT amount,created_at FROM transactions WHERE user_id='$profile_id' AND transaction_type='Profit' and status='2' order by id asc";
    $query = mysqli_query($db_conx, $sql);
    // Now make sure that user exists in the table
    $numrows = mysqli_num_rows($query);
    $xProfitDate = ["$sign_up_date"];
    $yProfitAmount = [0];
    $xProfitDateYear = ["$sign_up_year"];
    $xProfitDateMin = 'XX 00';
    $xProfitDateMax = '0/00';
    $count =0;
    $life_earnings = 0;
    if($numrows > 0){
//        $xProfitDate = array();$yProfitAmount = array();
//        $xProfitDateYear = array();
        // Fetch the user row from the query above
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $amount = $row['amount'];
            $created_at = $row['created_at'];
            $life_earnings += $amount;
//            $xMonth = date('d/m', strtotime($created_at));
//            array_push($xProfitDate, $xMonth);
//            array_push($yProfitAmount, $amount);
//            if($count == 1){
//                $xProfitDateMin = $xMonth;
//            }
//            $xProfitDateMax = $xMonth;
//            
            $xMonth = date('M d', strtotime($created_at));
            $xMonthYear = date('M d y', strtotime($created_at));
            $value = array_search($xMonthYear, $xProfitDateYear);
            if(strlen($value) > 0){
                $yProfitAmount[$value] = $yProfitAmount[$value] + $amount;                 
            }else{
                array_push($xProfitDate, $xMonth);
                array_push($xProfitDateYear, $xMonthYear);
                array_push($yProfitAmount, $amount);
            }
        }
    }
    $yProfitMin = min($yProfitAmount);
    $yProfitMax = max($yProfitAmount);
    if($yProfitMin >1 && $yProfitMax > 1){
        $yProfitMin -= 2;
        $yProfitMax += 5;
    }
    if($count < 1){
        $yProfitMin = 0;
        $yProfitMax = 0;
    }
    /////////////////////////////////
    //////////// here we get the data to plot the Revenue Paid graph
    $sql = "SELECT amount,created_at FROM transactions WHERE user_id='$profile_id' AND transaction_type='Withdrawal' and status='2' order by id asc";
    $query = mysqli_query($db_conx, $sql);
    // Now make sure that user exists in the table
    $numrows = mysqli_num_rows($query);
    $xRevenueDate = ["$sign_up_date"];
    $yRevenueAmount = [0];
    $xRevenueDateYear = ["$sign_up_year"];
    $xRevenueDateMin = '0/00';
    $xRevenueDateMax = '0/00';
    $count =0;
    if($numrows > 0){
        
//        $xRevenueDate = array();$yRevenueAmount = array();
//        $xRevenueDateYear = array();
        // Fetch the user row from the query above
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $amount = $row['amount'];
            $created_at = $row['created_at'];
//            $xMonth = date('d/m', strtotime($created_at));
//            array_push($xRevenueDate, $xMonth);
//            array_push($yRevenueAmount, $amount);
//            if($count == 1){
//                $xRevenueDateMin = $xMonth;
//            }
//            $xRevenueDateMax = $xMonth;
            
            $xMonth = date('M d', strtotime($created_at));
            $xMonthYear = date('M d y', strtotime($created_at));
            $value = array_search($xMonthYear, $xRevenueDateYear);
            if(strlen($value) > 0){
                $yRevenueAmount[$value] = $yRevenueAmount[$value] + $amount;                 
            }else{
                array_push($xRevenueDate, $xMonth);
                array_push($xRevenueDateYear, $xMonthYear);
                array_push($yRevenueAmount, $amount);
            }
        }
    }
    $yRevenueMin = min($yRevenueAmount);
    $yRevenueMax = max($yRevenueAmount);
    if($yRevenueMin >1 && $yRevenueMax > 1){
        $yRevenueMin -= 2;
        $yRevenueMax += 5;
    }
    if($count < 1){
        $yRevenueMin = -1;
        $yRevenueMax = 1;
    }
    /////////////////////////////////
    //////////// here we get the data to plot the Investment graph
    $sql = "SELECT amount,created_at FROM transactions WHERE user_id='$profile_id' AND transaction_type='Deposit' and status='2'  order by id asc";
    $query = mysqli_query($db_conx, $sql);
    // Now make sure that user exists in the table
    $numrows = mysqli_num_rows($query);
    $xDepositDate = [$sign_up_date];
    $yDepositAmount = [0];
    $xDepositDateYear = ["$sign_up_year"];
    $xDepositDateMin = '0/00';
    $xDepositDateMax = '0/00';
    $count =0;
    if($numrows > 0){
        
//        $xDepositDate = array();$yDepositAmount = array();
//        $xDepositDateYear = array();
        // Fetch the user row from the query above
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $amount = $row['amount'];
            $created_at = $row['created_at'];
//            $xMonth = date('d/m', strtotime($created_at));
//            array_push($xDepositDate, $xMonth);
//            array_push($yDepositAmount, $amount);
//            if($count == 1){
//                $xDepositDateMin = $xMonth;
//            }
//            $xDepositDateMax = $xMonth;
            $xMonth = date('M d', strtotime($created_at));
            $xMonthYear = date('M d y', strtotime($created_at));
            $value = array_search($xMonthYear, $xDepositDateYear);
            if(strlen($value) > 0){
                $yDepositAmount[$value] = $yDepositAmount[$value] + $amount;                 
            }else{
                array_push($xDepositDate, $xMonth);
                array_push($xDepositDateYear, $xMonthYear);
                array_push($yDepositAmount, $amount);
            }
        }
    }
    $yDepositMin = min($yDepositAmount);
    $yDepositMax = max($yDepositAmount);
    if($yDepositMin >1 && $yDepositMax > 1){
        $yDepositMin -= 2;
        $yDepositMax += 5;
        $NewxDepositDate = '';
        $NewyDepositAmount = '';
//        for($i=0;$i<count($xDepositDate);$i++){
//            $NewxDepositDate.= $xDepositDate[$i];
//            $NewyDepositAmount.= $yDepositAmount[$i];
//            if($i!= count($xDepositDate)-1){
//                $NewxDepositDate.= ', ';
//                $NewyDepositAmount.= ', ';
//            }
//        }
    }
    if($count < 1){
        $yDepositMin = -1;
        $yDepositMax = 1;
    }
    
    /////////////////////////////////
    //////////// here we get the data to plot the Affiliate graph
    $sql = "SELECT amount,created_at FROM transactions WHERE user_id='$profile_id' AND transaction_type='Referral Bonus' and status='2' order by id asc";
    $query = mysqli_query($db_conx, $sql);
    // Now make sure that user exists in the table
    $numrows = mysqli_num_rows($query);
    $xAffiliateDate = [];
    $yAffiliateAmount = [];
    $xAffiliateDateYear = [];
    $xAffiliateDateMin = '0/00';
    $xAffiliateDateMax = '0/00';
    $count =0;
    if($numrows > 0){
        
//        $xAffiliateDate = array();$yAffiliateAmount = array();
//        $xAffiliateDateYear = array();
        // Fetch the user row from the query above
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $amount = $row['amount'];
            $created_at = $row['created_at'];
            $xMonth = date('M d', strtotime($created_at));
            $xMonthYear = date('M d y', strtotime($created_at));
            $value = array_search($xMonthYear, $xAffiliateDateYear);
            if(strlen($value) > 0){
                $yAffiliateAmount[$value] = $yAffiliateAmount[$value] + $amount;                 
            }else{
                array_push($xAffiliateDate, $xMonth);
                array_push($xAffiliateDateYear, $xMonthYear);
                array_push($yAffiliateAmount, $amount);
            }
        }
    }
        
    if($count < 1){
        $yAffiliateMin = 0;
        $yAffiliateMax = 0;
    }else{
        $yAffiliateMin = min($yAffiliateAmount);
        $yAffiliateMax = max($yAffiliateAmount);
        if($yAffiliateMin >1 && $yAffiliateMax > 1){
            $yAffiliateMin -= 2;
            $yAffiliateMax += 5;
        }
    }
    
    /////////////////////////////////
    //////////// here we get the data to plot the dashboard Affiliate mini graph
    $sql = "SELECT amount,created_at FROM transactions WHERE user_id='$profile_id' AND transaction_type='Referral Bonus' and status='2' order by id asc";
    $query = mysqli_query($db_conx, $sql);
    // Now make sure that user exists in the table
    $numrows = mysqli_num_rows($query);
    $xMiniAffDate = [];
    $yMiniAffAmount = [];
    $count =0;
    if($numrows > 0){
        // Fetch the user row from the query above
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $amount = $row['amount'];
            $created_at = $row['created_at'];
            $xMonth = date('M d', strtotime($created_at));
            $xMonthYear = date('M d y', strtotime($created_at));
            array_push($xMiniAffDate, $xMonth);
            array_push($yMiniAffAmount, $amount);
        }
    }
        
    if($count < 1){
        $yMiniAffMin = 0;
        $yMiniAffMax = 0;
    }else{
        $yMiniAffMin = min($yMiniAffAmount);
        $yMiniAffMax = max($yMiniAffAmount);
        if($yMiniAffMin >1 && $yMiniAffMax > 1){
            $yMiniAffMin -= 2;
            $yMiniAffMax += 5;
        }
    }
        /////////////////////////////////
    //////////// here we get the data to plot the dashboard Affiliate mini graph
    $sql = "SELECT total_deposite,balance,created_at FROM plan_account WHERE user_id='$profile_id' AND active!='0' order by id asc";
    $query = mysqli_query($db_conx, $sql);
    // Now make sure that user exists in the table
    $numrows = mysqli_num_rows($query);
    $xPlanDate = [];
    $yPlanDep = [];
    $yPlanBal = [];
    $count =0;
    if($numrows > 0){
        // Fetch the user row from the query above
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $amount = $row['total_deposite'];
            $balance = $row['balance'];
            $created_at = $row['created_at'];
            $xMonth = date('M d', strtotime($created_at));
            array_push($xPlanDate, $xMonth);
            array_push($yPlanDep, $amount);
            array_push($yPlanBal, $balance);
        }
    }
        
    