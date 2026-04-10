<html>
<head>
    <title>Convert HTML to PDF in CodeIgniter using Dompdf</title>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>
 <div class="container box">
  <br />
  <h3 align="center">Convert HTML to PDF in CodeIgniter using Dompdf</h3>
  <br />
  <?php
  if(isset($customer_data))
  {
  ?>
  <div class="table-responsive">
   <table class="table table-striped table-bordered">
    <tr>
     <th>Customer ID</th>
     <th>Customer Name</th>
     <th>View</th>
     <th>View in PDF</th>
    </tr>
   <?php
   foreach($customer_data->result() as $row)
   {
    echo '
    <tr>
     <td>'.$row->user_id.'</td>
     <td>'.$row->user_name.'</td>
     <td><a href="'.base_url().'htmltopdf/details/'.$row->user_id.'">View</a></td>
     <td><a href="'.base_url().'htmltopdf/pdfdetails/'.$row->user_id.'">View in PDF</a></td>
    </tr>
    ';
   }
   ?>
   </table>
  </div>
  <?php
  }
  if(isset($customer_details))
  {
   echo $customer_details;
  }
  ?>
 </div>
</body>
</html>