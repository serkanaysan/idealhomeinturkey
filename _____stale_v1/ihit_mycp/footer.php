        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul>

                        <li>
                            <a href="http://www.idealhomeinturkey.com">
                                Ideal Home In Turkey
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="copyright pull-right">
                    &copy; <script>document.write(new Date().getFullYear())</script>, web solutions <i class="fa fa-heart heart"></i> by <a href="https://www.pamuk.net.tr/">pamuk.net.tr</a>
                </div>
            </div>
        </footer>

    </div>
</div>


</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio.js"></script>

	<!--  Charts Plugin -->
	<!-- <script src="assets/js/chartist.min.js"></script> -->

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin -->
	<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>-->

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
	<script src="assets/js/paper-dashboard.js"></script>

	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

	<script type="text/javascript">
    	$(document).ready(function(){

        	//demo.initChartist();

			/*
        	$.notify({
            	icon: 'ti-gift',
            	message: "Yönetim Paneline Hoşgeldiniz Sayın <b>Yiğit ERTEM</b> Güzel bir gün geçirmeniz dileğiyle."

            },{
                type: 'success',
                timer: 1000
            }); */

    	});
		
		$(document).ready(function() {
			
			$("#fancy-photo").fancybox({
				  helpers: {
					  title : {
						  type : 'float'
					  }
				  }
			  });
			  
		$(".various").fancybox({
			maxWidth	: 1000,
			maxHeight	: 600,
			fitToView	: true,
			width		: '75%',
			height		: '75%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
		  
		});
	</script>
	<link rel="stylesheet" href="assets/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="assets/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
	<link rel="stylesheet" href="assets/sweetalert.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="assets/sweetalert.min.js?v=2.1.5"></script>

</html>
