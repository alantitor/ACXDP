<!DOCTYPES html>
<html>
<head>
        <meta charset="utf-8">
        <title>App Center XML Data Process</title>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script>
		function hid(a)
		{
			if (a == 0) {
				$(".ok").hide();
				$(".warning").hide();
				$(".error").hide();
			} else if (a == 1) {
                                $(".ok").show();
                                $(".warning").show();
                                $(".error").show();
			} else if (a == 2) {
                                $(".ok").show();
                                $(".warning").hide();
                                $(".error").hide();
			} else if (a == 3) {
                                $(".ok").hide();
                                $(".warning").show();
                                $(".error").hide();
			} else if (a == 4) {
                                $(".ok").hide();
                                $(".warning").hide();
                                $(".error").show();
			} else {
				//
			}
		}

		$(document).ready(function(){
  			$("#tag_filter").change(function(){
				var tag = $(this).val();
				hid(0);
				$('.' + tag).show();
  			});
		});
	</script>

        <style></style>
</head>
<body>
        <div id="out-wrapper">
                <div id="wrapper">
                        <header id="masthead">
                                <h2>App Center XML Data Process</h2>
                        </header>

			<hr>

                        <div id="main-content">
				<div class="area form-wrapper">
					<form id="f1" action="" method="post" enctype="multipart/form-data">
						<label class="area sub-title">Upload xml files</label><br>
						<input type="file" name="upload[]" multiple="multiple"><br>
						<!--<select name="file-type" form="f1">
							<option value="_4X">4X</option>
							<option value="_4">4</option>
						</select>-->
						<input type="submit" name="submit_1" value="Upload">
					</form>	
				</div>

				<div class="area form-wrapper">
					<form id="f2" action="" method="post" enctype="multipart/form-data">
						<label class="area sub-title">Database</label><br>
						<label>address</label>
                                                <input type="text" name="db1" value="<?=$_POST['db1']?>">
                                                <label>account</label>
                                                <input type="text" name="db2" value="<?=$_POST['db2']?>">
                                                <label>password</label>
                                                <input type="password" name="db3" value="<?=$_POST['db3']?>">
                                                <label>database</label>
                                                <input type="text" name="db4" value="<?=$_POST['db4']?>">
						<br>

						<label class="area sub-title">Function</label>
						<select name="proc-type" form="f2">
							<option value="_4X">4X</option>
							<option value="_4">4</option>
						</select>
						<input type="submit" name="submit_2" value="Process">
					</form>
				</div>

				<div>
					<label class="area sub-title">Do</label>
					<button onclick="hid(0)">hide all</button>
					<button onclick="hid(1)">show all</button>
					<button onclick="hid(2)">show ok</button>
					<button onclick="hid(3)">show warning</button>
					<button onclick="hid(4)">show error</button>
					<label class="area sub-title">Find</label>
					<input id="tag_filter" type="text"></input>
				</div>

				<hr>

                                <div class="area sub-title">System information</div>
                                <div class="area gen-wrapper">
					<?php 
						if (isset($_POST['submit_1'])) {
							include 'uploadFile.php';
							echo uploadFile($_FILES);
						} else if (isset($_POST['submit_2'])) {
							include 'processData.php';
							echo pxd($_POST['proc-type'], $_POST['db1'], $_POST['db2'], $_POST['db3'], $_POST['db4']);
							$test = "ok";
						} else {
							//
						}
					?>
				</div>
                        </div>
                </div>
        </div>
</body>

