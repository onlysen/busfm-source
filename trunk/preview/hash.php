<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>巴士电台</title>
		<style type="text/css">
        #tab1_header,#tab2_header{cursor:pointer;border:1px solid;width:50px; text-decoration:none; color:#000;}
        #tab1,#tab2{width:90%;height:200px;border:1px solid;}
    </style>
	</head>
<body>    
    <div id="tab_header">
    	<a id="tab1_header" href="#tab1">Tab1</a>
    	<a id="tab2_header" href="#tab2">Tab2</a>
    </div>
    <div id="tab1">1</div>
    <div id="tab2" style="display:none;">2</div>
</body>
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript">
$(function(){
			showTab();
			$(window).bind('hashchange', function(e){
				showTab();
			});
			//~ $("#tab1_header").click(showTab1);
			//~ $("#tab2_header").click(showTab2);
		});

        function showTab() {
            if (window.location.hash == "#tab2"){
				showTab2();
			} else {
				showTab1();
			}
        }
        function showTab1() {
            $("#tab2").hide();
            $("#tab1").show();
            //window.location.hash = "#tab1";
        };
        function showTab2() {
            $("#tab1").hide();
            $("#tab2").show();
            //window.location.hash = "#tab2";
        };
</script>
</html>