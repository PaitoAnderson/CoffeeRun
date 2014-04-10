<?php

// Connect to the Database
define("DB_SERVER", "localhost");
define("DB_USER", "xxxxx");
define("DB_PASS", "xxxxx");
define("DB_NAME", "xxxxx");

$dbConnection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_POST["action"] == "order") {
		$newOrder = $dbConnection->prepare("INSERT INTO coffee_orders(OrderName, OrderDesc) VALUES (?, ?);");
		$newOrder->bind_param("ss", $_POST['fullname'], $_POST['order']);
		$newOrder->execute();
	}

	if ($_POST["action"] == "clear") {
		$deleteOrders = $dbConnection->prepare("DELETE FROM coffee_orders;");
		$deleteOrders->execute();
	}
}

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Coffee Run?</title>
        <meta name="author" content="Paito Anderson">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/flat-ui.min.css">
        <!--[if lt IE 9]>
      		<script src="js/html5shiv.js"></script>
      		<script src="js/respond.min.js"></script>
    	<![endif]-->
        <style type="text/css">
        html {
			position: relative;
			min-height: 100%;
		}
        body {
			margin-bottom: 80px;
		}
        #footer {
			position: absolute;
			bottom: 0;
			width: 100%;
			height: 70px;
			background-color: #f5f5f5;
		}

		.container {
			width: auto;
			max-width: 900px;
			padding: 0 15px;
		}
		.container .text-muted {
			margin: 20px 0;
		}
        </style>
    </head>
	<body>
		<div class="container">
            <div class="page-header">
                <h1>Coffee Run?</h1>
            </div>
            <!--<a data-toggle="modal" href="#CoffeeRun" class="btn btn-primary">I am Going!</a>-->
            <a data-toggle="modal" href="#CoffeeOrder" class="btn btn-primary">I want a Coffee!</a>
            <a href="#" class="btn btn-primary btn-delete-item">Clear Orders</a>
        	
        	<br /><br />
			
			<?php
				$orders = $dbConnection->query('SELECT OrderName, OrderDesc FROM coffee_orders ORDER BY Identity DESC;');

				if($dbConnection->field_count){
					echo "<table class=\"table table-striped\"><tr><th>Name</th><th colspan=2>Coffee Order</th></tr>";
					while ($row = $orders->fetch_assoc()) {
						echo "<tr><td>" . htmlspecialchars($row["OrderName"]) . "</td><td colspan=2>" . htmlspecialchars($row["OrderDesc"]) . "</td></tr>";
					}
					echo "</table>";
				}

				mysqli_close($dbConnection);
			?>
        </div><!-- /.container -->

        <form id="ClearCoffees" action"#" method="post">
        	<input type="hidden" name="action" value="clear">
        </form>

		<!-- Modal -->
		<div class="modal fade" id="CoffeeRun">
			<div class="modal-dialog">
				<div class="modal-content">
					<form action="#" method="post">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">I am Going!</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" name="action" value="run" />
							<input type="text" class="form-control" placeholder="Your Name" name="fullname" id="fullname" required="required" /><br />
							<select name="CoffeeHouse" id="CoffeeHouse" class="form-control">
								<option value="TimHortons">Tim Hortons</option>
								<option value="Starbucks">Starbucks</option>
								<option value="SecondCup">Second Cup</option>
							</select><br />
							<select name="InMintes" id="InMintes" class="form-control">
								<option value="5">5 Minutes</option>
								<option value="10">10 Minutes</option>
								<option value="15">15 Minutes</option>
							</select>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Save</button>
						</div>
					</form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dalog -->
		</div><!-- /.modal -->

		<!-- Modal -->
		<div class="modal fade" id="CoffeeOrder">
			<div class="modal-dialog">
				<div class="modal-content">
					<form action="#" method="post">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">I want a Coffee!</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" name="action" value="order" />
							<input type="text" class="form-control" placeholder="Your Name" name="fullname" id="fullname" required="required" /><br />
							<textarea cols="45" class="form-control" rows="3" placeholder="Your Order" name="order" id="order" required="required"></textarea><br />
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Save</button>
						</div>
					</form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dalog -->
		</div><!-- /.modal -->

		<div id="footer">
            <div class="container">
                <p class="text-muted">
                    Built with <span class="glyphicon glyphicon-heart"></span> by <a href="http://paitoanderson.com">Paito Anderson</a>.
                </p>
            </div>
        </div>

		<script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-confirm-button.js"></script>

        <script type="text/javascript">
        	$('.btn-delete-item').confirmButton({msg:"I'm sure!"}, function(e) {
        		document.getElementById('ClearCoffees').submit();
        	});

        	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        	ga('create', 'UA-49893881-1', 'run.coffee');
        	ga('send', 'pageview');
        </script>
    </body>
</html>