<?php
if($search)
{
    $squery = make_search_condition($search, ["worker_name", "worker_fname", "worker_passport_number", "worker_cnic_number", "worker_fee", "company_name", "agent_name", "job_title" ]);
    $query_limit = "";
}else
{
    $squery = "1=1";
}

$query = $conx->query("SELECT * FROM user_account WHERE $squery $query_limit");



$workers = [];
if($query && $query->num_rows>0)
{
    $workers = $query->fetch_all(1);
}


/************
* Pagination
***********/
$total = $conx->query("SELECT count(user_id) as total FROM user_account");
$total = $total->fetch_row();
$total = $total[0];

?><h1>Users</h1>
<div id="alerts"></div>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Father Name</th>
            <th>Passport</th>
            <th>CNIC</th>
            <th>Agent</th>
            <th>Company</th>
            <th>Job</th>
            <th>Fee</th>
            <th>Date added</th>
            <th colspan=2>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($workers as $worker):
        
//        $worker_id = $worker["worker_id"];
//        $worker_name = $worker["worker_name"];
//        $worker_fname = $worker["worker_fname"];
//        $worker_passport_number = $worker["worker_passport_number"];
//        $worker_cnic_number = $worker["worker_cnic_number"];
//        $agent_id = $worker["AID"];
//        $agent_name = $worker["agent_name"];
//        $company_name = $worker["company_name"];
//        $company_id = $worker["company_id"];
//        $worker_fee = $worker["worker_fee"];
//        $job_id = $worker["job_id"];
//        $job_title = $worker["job_title"];
//        $date_added = $worker["date_added"];

//        $job_error = false;
//        $agent_error = false;




//        $job_title = add_sterm_class($job_title);
//        $agent_name = add_sterm_class($agent_name);
        ?>
        <tr>
            <td><?= add_sterm_class($worker_name);?></td>
            <td><?= add_sterm_class($worker_fname);?></td>
            <td><?= add_sterm_class($worker_passport_number);?></td>
            <td><?= add_sterm_class($worker_cnic_number);?></td>
            <td><?= $agent_error?"<s>".$agent_name."</s>":$agent_name;?></td>
            <td><?= add_sterm_class($company_name);?></td>
            <td><?= $job_error?"<s>".$job_title."</s>":$job_title;?></td>
            <td><?= add_sterm_class($worker_fee);?></td>
            <td><?= add_sterm_class($date_added);?></td>
            <td>
                <a href="<?= BASE_URL."/update_worker?wid=".$worker_id;?>" class=""><i class="fa fa-pencil fa-lg"></i></a>
                <a href="#" data-table="worker" data-id="<?= $worker["worker_id"];?>" class="red btn-delete"><i class="fa fa-trash fa-lg "></i></a>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>


<?php if(!$search){
    echo create_pagination($total, $limit, $pg, $page);
}?>