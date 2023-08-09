<?php
//////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////// here we get all active investment history
$invest_transaction = '<tr>
                                <td colspan="5"> <strong >No record(s)..</strong></td>
                          </tr>';
$invest_transaction_Dash = $invest_transaction;
$sql = "SELECT * FROM plan_account WHERE user_id='$profile_id' AND active='1' order by id desc";
$query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($query);
if ($numrows > 0) {
    $invest_transaction = '';    
    $in_count = 0;
    // Fetch the user row from the query above
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $in_count += 1;
        $in_plan_id = $row['plan_unique'];
        $in_plan = $row['plan'];
        $in_profit = $row['profit'];
        $in_total_withdrawal = $row['total_withdrawal'];
        $in_total_deposite = $row['total_deposite'];
        $in_active = $row['active'];
        $in_plan_duration = $row['plan_duration'];
        $in_balance = $row['balance'];
        $in_created_at = $row['created_at'];
        $in_status_dis = '<span class="badge py-3 px-4 fs-7 badge-light-warning">Expired</span>';
        if ($in_active == '0') {
            $in_status_dis = '<span class="badge py-3 px-4 fs-7 badge-light-danger">Cancelled</span>';
        } elseif ($in_active == '1') {
            $in_status_dis = '<span class="badge py-3 px-4 fs-7 badge-light-success">Active</span>';
        }
        $new_time = date("Y-m-d H:i:s");
        $maturity_time = date('Y-m-d H:i:s', strtotime($row['created_at']. " + $in_plan_duration days"));
        $timeDiff = strtotime($maturity_time) - strtotime($new_time);
        $invest_transaction .=
            '<tr class="invest_li_con" data-set="'.$in_active.'|'.$timeDiff.'|'.$in_plan_id.'">
                <td>
                    <div class="d-flex align-items-center">' 
                        .$in_count . 
                    '</div>
                </td>
                <td>
                    <span class="text-gray-600 fw-bold fs-6">' 
                        .$in_plan . 
                    '</span>
                </td>
                <td>
                    <span class="text-gray-600 fw-bold fs-6">' 
                        .$symbol.number_format($in_total_deposite,2) . 
                    '</span>
                </td>
                <td>
                    <span class="text-gray-600 fw-bold fs-6">' 
                        .$symbol.number_format($in_profit,2) . 
                    '</span>
                </td>
                <td>
                    <span class="text-gray-600 fw-bold fs-6">' 
                        .$symbol.number_format($in_total_deposite+$in_profit,2) . 
                    '</span>
                </td>
                <td>
                    <span class="text-gray-600 fw-bold fs-6">' 
                        .date('d M, Y', strtotime($row['created_at'])) . 
                    '</span>
                </td>
                <td>
                    <div class="col-12 pt-3 text-center text-warning" id="'.$in_plan_id.'_timer"  style="font-size: 11px; display:none"></div>
                </td>
            </tr>';
        if($in_count < 6){
            $invest_transaction_Dash = $invest_transaction;
        }
    }
}
