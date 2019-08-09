<div>
        <footer></footer>
    </div>
	<script src="../_admin_mm/js/mm.js"></script>
    <script>
        $(".logout-btn, #nav_logout").click(function () {
            window.close();
        });
		
		setInterval(check_session, 3000);
	
		function check_session(){
			var controller = "../_admin_mm/controllers/";

			$.post( controller, { action: "check_session" }, function( data ) {
				if(data.status != 200){
					window.location.href = "/_admin_mm/noaccess.php";
					console.log('you are about to be logged out');
				}else{
					console.log("User session valid");
				}
			}, "json");
		}
    </script>
	
</body>
</html>
