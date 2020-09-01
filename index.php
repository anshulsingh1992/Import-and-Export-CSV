<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <title>ANNOUNCEMENTS</title>

  
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <table id="main" border="0" cellspacing="0">
    <tr>
      <td id="header">
        <h1>ANNOUNCEMENTS</h1>

        <div id="search-bar">
          <label>Search :</label>
          <input type="text" id="search" autocomplete="off">
        </div>
      </td>
    </tr>
    <tr>
      <td id="table-form">
      <div>
        <form id="addForm">
        
          SYMBOL: <input type="text" id="symbol">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          COMPANY NAME : <input type="text" id="companyname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          ANNOUNCEMENT DATE : <input type="text" id="announcementdate">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          ANNOUNCEMENT TIME : <input type="text" id="announcementtime">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
          BSE ANNOUNCEMENT TIME : <input type="text" id="bseannouncementtime">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          ANNOUNCEMENT TYPE : <input type="text" id="announcementtype">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          SHORT DESCRIPTION : <input type="text" id="shortdescription">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
          ANNOUNCEMENT : <input type="text" id="announcement">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          
          <input type="submit" id="save-button" value="Save">
        </form>
        </div>

        <div>
          <form action="http://localhost/php-ajax/export.php">
          <input type="submit" value="Download CSV" />
        </form>
        </div>
        
		<br />
		<br />
		<div class="container">
			
			<br />
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title" align="center">Import CSV File Data</h3>
				</div>
  				<div class="panel-body">
  					<span id="message"></span>
  					<form id="sample_form" method="POST" enctype="multipart/form-data" class="form-horizontal">
  						<div class="form-group">
  							<label class="col-md-4 control-label">Select CSV File</label>
  							<input type="file" name="file" id="file" />
  						</div>
  						<div class="form-group" align="center">
  							<input type="hidden" name="hidden_field" value="1" />
  							<input type="submit" name="import" id="import" class="btn btn-info" value="Import" />
  						</div>
  					</form>
  					<div class="form-group" id="process" style="display:none;">
  						<div class="progress">
  							<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
  								<span id="process_data">0</span> - <span id="total_data">0</span>
  							</div>
  						</div>
  					</div>
  				</div>
  			</div>
		</div>

      </td>
    </tr>
    <tr>
      <td id="table-data">
      </td>
    </tr>
  </table>
  <div id="error-message"></div>
  <div id="success-message"></div>
  <div id="modal">
    <div id="modal-form">
      <h2>Edit Form</h2>
      <table cellpadding="10px" width="100%">
      </table>
      <div id="close-btn">X</div>
    </div>
  </div>

<script type="text/javascript" src="js/jquery.js"></script>



</body>





</html>
<script>
	
	$(document).ready(function(){

		var clear_timer;

		$('#sample_form').on('submit', function(event){
			$('#message').html('');
			event.preventDefault();
			$.ajax({
				url:"upload.php",
				method:"POST",
				data: new FormData(this),
				dataType:"json",
				contentType:false,
				cache:false,
				processData:false,
				beforeSend:function(){
					$('#import').attr('disabled','disabled');
					$('#import').val('Importing');
				},
				success:function(data)
				{
					if(data.success)
					{
						$('#total_data').text(data.total_line);

						start_import();

						clear_timer = setInterval(get_import_data, 2000);

						//$('#message').html('<div class="alert alert-success">CSV File Uploaded</div>');
					}
					if(data.error)
					{
						$('#message').html('<div class="alert alert-danger">'+data.error+'</div>');
						$('#import').attr('disabled',false);
						$('#import').val('Import');
					}
				}
			})
		});

		function start_import()
		{
			$('#process').css('display', 'block');
			$.ajax({
				url:"import.php",
				success:function()
				{

				}
			})
		}

		function get_import_data()
		{
			$.ajax({
				url:"process.php",
				success:function(data)
				{
					var total_data = $('#total_data').text();
					var width = Math.round((data/total_data)*100);
					$('#process_data').text(data);
					$('.progress-bar').css('width', width + '%');
					if(width >= 100)
					{
						clearInterval(clear_timer);
						$('#process').css('display', 'none');
						$('#file').val('');
						$('#message').html('<div class="alert alert-success">Data Successfully Imported</div>');
						$('#import').attr('disabled',false);
						$('#import').val('Import');
					}
				}
			})
		}

    
   
	});
</script>
<script type="text/javascript">
  $(document).ready(function(){
  
    // Load Table Records
    function loadTable(){
      $.ajax({
        url : "ajax-load.php",
        type : "POST",
        success : function(data){
          $("#table-data").html(data);
        }
      });
    }
    loadTable(); // Load Table Records on Page Load

    // Insert New Records
    $("#save-button").on("click",function(e){
      e.preventDefault();
      var symbol = $("#symbol").val();
      var companyname = $("#companyname").val();
      var announcementdate = $("#announcementdate").val();
      var announcementtime = $("#announcementtime").val();
      var bseannouncementtime = $("#bseannouncementtime").val();
      var announcementtype = $("#announcementtype").val();
      var shortdescription = $("#shortdescription").val();
      var announcement = $("#announcement").val();
      if(symbol == "" || companyname == ""|| announcementdate == ""|| announcementtime == ""|| bseannouncementtime == ""|| announcementtype == ""|| shortdescription == ""|| announcement == ""){
        $("#error-message").html("All fields are required.").slideDown();
        $("#success-message").slideUp();
      }else{
        $.ajax({
          url: "ajax-insert.php",
          type : "POST",
          data : {sym_bol:symbol, company_name:companyname, announcement_date: announcementdate,  announcement_time:announcementtime, bse_announcement_time:bseannouncementtime ,  announcement_type:announcementtype, short_description:shortdescription ,  announce_ment:announcement},
          success : function(data){
            if(data == 1){
              loadTable();
              $("#addForm").trigger("reset");
              $("#success-message").html("Data Inserted Successfully.").slideDown();
              $("#error-message").slideUp();
            }else{
              $("#error-message").html("Can't Save Record.").slideDown();
              $("#success-message").slideUp();
            }

          }
        });
      }

    });

    //Delete Records
    $(document).on("click",".delete-btn", function(){
      if(confirm("Do you really want to delete this record ?")){
        var studentId = $(this).data("id");
        var element = this;

        $.ajax({
          url: "ajax-delete.php",
          type : "POST",
          data : {id : studentId},
          success : function(data){
              if(data == 1){
                $(element).closest("tr").fadeOut();
              }else{
                $("#error-message").html("Can't Delete Record.").slideDown();
                $("#success-message").slideUp();
              }
          }
        });
      }
    });

    //Show Modal Box
    $(document).on("click",".edit-btn", function(){
      $("#modal").show();
      var studentId = $(this).data("eid");

      $.ajax({
        url: "load-update-form.php",
        type: "POST",
        data: {id: studentId },
        success: function(data) {
          $("#modal-form table").html(data);
        }
      })
    });

    //Hide Modal Box
    $("#close-btn").on("click",function(){
      $("#modal").hide();
    });

    //Save Update Form
      $(document).on("click","#edit-submit", function(){
        var stuId = $("#edit-id").val();
        var fname = $("#edit-fname").val();
        var lname = $("#edit-lname").val();

        $.ajax({
          url: "ajax-update-form.php",
          type : "POST",
          data : {id: stuId, first_name: fname, last_name: lname},
          success: function(data) {
            if(data == 1){
              $("#modal").hide();
              loadTable();
            }
          }
        })
      });

    // Live Search
     $("#search").on("keyup",function(){
       var search_term = $(this).val();

       $.ajax({
         url: "ajax-live-search.php",
         type: "POST",
         data : {search:search_term },
         success: function(data) {
           $("#table-data").html(data);
         }
       });
     });
  });
</script>

