<?php
// Connect to the Database 
$insert = false;
$update = false;
$delete=false;
$conn = pg_connect("host=localhost dbname=ncpor user=postgres password=ns");
if (!$conn)
  {
    die("Sorry we failed to connect: ");
  }
  if(isset($_GET['delete'])){
    $sno = $_GET['delete'];
    $delete = true;
    $sql = "DELETE FROM detail WHERE sno = $sno";
    $result = pg_query($conn, $sql);
  }
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (isset( $_POST['snoEdit']))
  {
    // Update the record
    $sno = $_POST["snoEdit"];
    $name = $_POST["nameEdit"];
    $contact = $_POST["contactEdit"];

    // Sql query to be executed
    $sql = "UPDATE detail SET name = '$name' , contact = '$contact' WHERE detail.sno = $sno";
    $result = pg_query($conn, $sql);
    if($result)
    {
      $update = true;
    }
    else
    {
      echo "We could not update the record successfully";
    }
  }  
  else
  {
    $name=$_POST["name"];
    $contact=$_POST["contact"];
    //sql query to be executed
    $sql="INSERT INTO  detail(name,contact) VALUES ('$name','$contact')";
    $result=pg_query($conn,$sql);
    //Add a new row to the table
    if($result)
    {
      $insert=true;
    }
    else
    {
      echo"Record was not inserted successfully because of this error ---->".pg_last_error($conn);
    }
  }
}
?>

<!doctype html>
<html lang="en">
  <head>
   <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    
    <title>TELEPHONE DIRECTORY</title>
  </head>
  <body>
    <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit this detail</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="/WEB/demo.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="name">NAME</label>
              <input type="text" class="form-control" id="nameEdit" name="nameEdit" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
              <label for="contact">CONTACT</label>
              <textarea class="form-control" id="contactEdit" name="contactEdit" rows="3"></textarea>
            </div> 
          </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
    <h3>PERFORMING CRUD OPERATONS</h3>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">TELEPHONE DIRECTORY</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<?php
  if($insert){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your contact detail has been inserted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
   <?php
  if($update){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your contact detail has been updated successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($delete){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your contact detail has been deleted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  

<div class="container my-4">
    <h2>ADD A CONTACT DETAIL</h2>
    <form action ="/WEB/demo.php " method="POST">
    <div class="mb-3">
            <label for="NAME" class="form-label">NAME</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp">
        </div>   
        <div class="mb-3">
            <label for="CONTACT" class="form-label">CONTACT NO</label>
            <input type="integer" class="form-control" id="contact" name="contact" aria-describedby="emailHelp">
        </div>    
        <button type="submit" class="btn btn-primary">Add Contact</button>
    </form>
 </div>
 <div class="container my-4">


<table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">S.No</th>
      <th scope="col">Name</th>
      <th scope="col">Contact No</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $sql = "select * from detail order by sno asc";
      $result = pg_query($conn, $sql);
      $sno = 0;
      while($row = pg_fetch_assoc($result)){
        $sno = $sno + 1;
        echo "<tr>
        <th scope='row'>". $sno . "</th>
        <td>". $row['name'] . "</td>
        <td>". $row['contact'] . "</td>
        <td> <button class='edit btn btn-sm btn-primary' id=".$row['sno'].">Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['sno'].">Delete</button>  </td>
      </tr>";
    } 
      ?>

  </tbody>
</table>
</div>
<hr>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();

    });
  </script>
  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        tr = e.target.parentNode.parentNode;
        name = tr.getElementsByTagName("td")[0].innerText;
        contact = tr.getElementsByTagName("td")[1].innerText;
        console.log(name, contact);
        nameEdit.value = name;
        contactEdit.value = contact;
        snoEdit.value = e.target.id;
        console.log(e.target.id)
        $('#editModal').modal('toggle');
      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        sno = e.target.id.substr(1);

        if (confirm("Are you sure you want to delete this contact detail!")) {
          console.log("yes");
          window.location = `/WEB/demo.php?delete=${sno}`;
          // TODO: Create a form and use post request to submit a form
        }
        else {
          console.log("no");
        }
      })
    })
  </script>
  </body>
</html>