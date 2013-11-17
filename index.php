
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Wishlist</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
	<link href="bootstrap/css/jumbotron-narrow.css" rel="stylesheet">
</head>
<style type="text/css">
.wish-item {
	/*font-size:20px;*/
	font-size: 4vmin;
}
.wish-item.reserved {
	text-decoration:line-through;
}

.error {
	color: #b94a48;
}
input{
   text-align:center;
}
</style>

<body>
<?php
	$mysqli = new mysqli("localhost", "root", "root", "Wishlist");
	
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	$mysqli->set_charset("utf8");
	$query = "SET CHARSET utf-8";

	if(!empty($_GET)) {
		$query = "SELECT w_reservation FROM Wishes WHERE w_id = ".$_GET['current_wish']; //." AND w_reservation = '".$_GET['inputReservation']."'";
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
        		if($row['w_reservation']) {
        			if($row['w_reservation']==$_GET['inputReservation']) {
         			$query = "UPDATE Wishes SET w_reservation = '' WHERE w_id = ".$_GET['current_wish'];
        				$mysqli->query($query);       				
        			} 
        			else {
        				echo "Неверный номер брони, чувак";
        			}
        		} 
        		else {
        			$query = "UPDATE Wishes SET w_reservation = '".$_GET['inputReservation']."' WHERE w_id = ".$_GET['current_wish'];
        			$mysqli->query($query);     
        		}
        	/*
        	if($row['count'] == 0) {
        		$query = "UPDATE Wishes SET w_reservation = '".$_GET['inputReservation']."' WHERE w_id = ".$_GET['current_wish'];
        		$result = $mysqli->query($query);
        	}         */
    		}

    	/* free result set */
    	$_GET= array();
    	$result->free();
	}

	} 

	$query = "SELECT * FROM Wishes";

	$result = $mysqli->query($query); 

	for ($res = array(); $tmp = $result->fetch_assoc();) $res[] = $tmp;
	$js_res = json_encode($res);
?>
<script type="text/javascript">
var wish_description = <?php echo $js_res;?>;
        //console.log(json[0]);
</script>

<div class="container">	
	<div class="row">
		<div id ="wish-container" class="col-sm-offset-1 col-sm-10">
			<h1 class="text-center">Chemberlen's wishlist</h1>
    		<table class="table table-hover">
    		<!-- Dynamic wishlist generation goes here! -->
    		</table>
    	</div>
    </div>
</div>
<a data-toggle="modal" href="#setReservation" class="btn btn-default">Добавить книгу</a>

<!-- Modal window for wish info -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Описание подарка</h4>
    </div>
    <div class="modal-body row">
      <h3 id="w_name" class="text-center"></h3>
      <div class="col-sm-4">
        <img id="w_img" src="" style="width:200px;max-width:100%;">
      </div>
      <div class="col-sm-8">
        <div class="row">
          <div class="col-xs-3"><strong>Ссылка:</strong></div>
          <div class="col-xs-3"><a id="w_link" href=""></a></div>
          <div class="col-xs-3"><a id="w_market" href=""><img class="text-center" src="http://yabs.yandex.ru/count/SEfMvmIHwMG40X02gP808OwpY70e1Li1bG6R0MzsYBb5jV01fXwAgofvWAe1fQ4hBG6HlD19P0QJWGv1hlSQ0vUiyKJh1B41k_xAcNZ1pMjz0Nu8" style="max-width:30%;"></a></div>
        </div>
        <div class="row">
          <div class="col-xs-3"><strong>Цена:</strong></div>
          <div id="w_price" class="col-xs-3"></div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
      <button id="reservation_action" data-dismiss="modal" data-toggle="modal" href="#setReservation" type="button" class="btn btn-primary" value="ok">Добавить</button>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div>
<!-- END -->

<div class="modal fade" id="setReservation" tabindex="-1" role="dialog" aria-labelledby="setReservationLabel" aria-hidden="false">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Нужна бронь</h4>
    </div>
    <div class="modal-body">
    	<p>Чтобы застолбить подарок, вам придется выдумать и ввести номер брони.</p>
    	<p>А чтобы, не дай Бог, отменить вашу бронь вам потребуется еще и вспомнить выдуманный вами номер.</p>
    	<p>Ничего не поделаешь, мир жесток.</p> 
    	<form role="form" id="test">
			<div class="form-group text-center row"><div class="col-sm-offset-3 col-sm-6">
				<label for="inputReservation">Номер брони</label>
				<input type="text" class="form-control" id="inputReservation" name="inputReservation" placeholder="К примеру: Ааргх!" minlength="2" maxlength="10" required>
				<span class="help-block">Цифры, буквы, значки бакса, все сгодится!</span>
				<input id="current_wish" name="current_wish" type="text" style="display:none;" value="0"></input>
			</div>

			</div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
      <button id="reservation_action" type="submit" class="btn btn-primary" value="ok">Застолбить</button>
  		</form>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div>





<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="jquery.validate.min.js"></script>
<script type="text/javascript">
// iOS check...ugly but necessary
if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {

    $('.modal').on('show.bs.modal', function() {

        // Position modal absolute and bump it down to the scrollPosition
        $(this)
            .css({
                position: 'absolute',
                marginTop: $(window).scrollTop() + 'px',
                bottom: 'auto'
            });

        // Position backdrop absolute and make it span the entire page
        //
        // Also dirty, but we need to tap into the backdrop after Boostrap 
        // positions it but before transitions finish.
        //
        setTimeout( function() {
            $('.modal-backdrop').css({
                position: 'absolute', 
                top: 0, 
                left: 0,
                width: '100%',
                height: Math.max(
                    document.body.scrollHeight, document.documentElement.scrollHeight,
                    document.body.offsetHeight, document.documentElement.offsetHeight,
                    document.body.clientHeight, document.documentElement.clientHeight
                ) + 'px'
            });
        }, 0);

    });

}
</script>

<script type="text/javascript">
jQuery.extend(jQuery.validator.messages, {
    required: "Еще парочку!",
    remote: "Please fix this field.",
    email: "Please enter a valid email address.",
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "Please enter the same value again.",
    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
    minlength: jQuery.validator.format("Маловато!"),
    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
    min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
});



	$(document).ready(function() {
    	if(wish_description) {
    		for(var i=0;i<wish_description.length;i++)
    			//alert("ddd");
    		$("#wish-container table").append("<tr class='wish-item "+((wish_description[i].w_reservation)?"alert alert-danger reserved":"")+"' data-id='"+i+"'><td>"+((wish_description[i].w_name.length<48)?(wish_description[i].w_name):(wish_description[i].w_name.slice(0,47)+"..."))+"</td><td>"+(wish_description[i].w_price?wish_description[i].w_price:"Бесценно!")+"</td></tr>");
    	}

    	$(".wish-item").click(function() {
  	 		insertInfo($(this));
    	});
	});

function insertInfo(obj) {
	var current_wish = obj.data("id");
	wish_description[current_wish].w_label = wish_description[current_wish].w_label?wish_description[current_wish].w_label:'';
	$("#w_name").html(wish_description[current_wish].w_name+'&nbsp;<span id="w_label" class="label label-info">'+wish_description[current_wish].w_label+'</span>');
	$("#w_img").attr("src",wish_description[current_wish].w_img);
	$("#w_link").attr("href",wish_description[current_wish].w_link);
	wish_description[current_wish].w_link?$("#w_link").text(wish_description[current_wish].w_link.split('/')[2]):$("#w_link").text(""); 
	
	$("#w_market").attr("href","http://market.yandex.ru/search.xml?text="+wish_description[current_wish].w_name+" "+wish_description[current_wish].w_label+"&how=aprice");
	$("#w_price").text(wish_description[current_wish].w_price?wish_description[current_wish].w_price:'Бесценно!');

	$("#current_wish").val(wish_description[current_wish].w_id);

	if(wish_description[current_wish].w_reservation) {
		$(".btn-primary").text("Отвязать");
	}
	else $(".btn-primary").text("Застолбить");
	$('#myModal').modal('show');
}
  	$("#test").validate();
</script>
</body>
</html>