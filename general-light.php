<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
header("Content-type: text/css; charset: UTF-8");
include("includes/environment.php");
?>
body {
	font: 14px/1.6 Arial, sans-serif;
	color: #333;
    <?php echo ($environment=="dev" ? "background-color:#fea2cb;\n background-image:url('images/dev.png');\n":"");?>
}
.banner {
	display:block;
}
.banner img{
margin-right:10px;
margin-top:-10px;
float:right;

}

h1 {
	font: 28px/1.238 Arial, sans-serif;
	color: #333;
	margin: 30px 0 0 0;
}
h2 {
	color:#336699;
	font-size: 18px;
}
h3 {
	margin-top:10px;
	color:#336699;
}
.heading {
	color:#336699;
}
.emphasis {
	color:#336699;
}

.ctutable .tablesubheading {
	display:block; 
	text-align:left;
	clear:both;
	font-size:16px;
	font-weight:bold;
	color:#336699;
}

#section3b .ctutable .counter-text {
	margin-top:-20px;
	margin-right:70px;
	
}
#section3b .four .counter-text {
margin-right:7px;
margin-top:2px;
float:right;
}
#section3b .four textarea {
margin-top:18px;
}
#section3b .four td {
	height:50px;
}
#section3 .seven .counter-text {
margin-right:7px;
margin-top:2px;
float:right;
}
#section3 .seven textarea.countstyle {
margin-top:18px;
}
#section3 .seven td {
	height:50px;
}
#section3 .seven textarea.form-input-table {
	height:60px;
}
.ctutable .counter-text {
	margin-top:-20px;
	margin-right:45px;
}
.counter-text {
	font-size: 11px; 
	clear: both; 
	float:right;
	margin-top: 3px; 
	margin-right:30px;
	display: block;

}





.form-watermark {
	text-align: right;
	color:#888;
}

.form-select {
	margin-bottom: 20px;
}

form.general {
	width: 1010px;
	padding: 20px 20px;
	border: 2px solid #fff;
	margin: 20px auto;
	overflow: hidden;
	background-color: <?php echo ($environment=="prod" ? "#e5e7ea" : "#fea2cb")?>;
	background-image: -webkit-gradient(linear, left top, right top, color-stop(0%, #dcdee2), color-stop(50%, #eff0f1), color-stop(100%, #dcdee2)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(left, #dcdee2, #eff0f1, #dcdee2); /* Chrome 10+, Saf5.1+, iOS 5+ */
	background-image:    -moz-linear-gradient(left, #dcdee2, #eff0f1, #dcdee2); /* FF3.6 */
	background-image:     -ms-linear-gradient(left, #dcdee2, #eff0f1, #dcdee2); /* IE10 */
	background-image:      -o-linear-gradient(left, #dcdee2, #eff0f1, #dcdee2); /* Opera 11.10+ */
	background-image:         linear-gradient(left, #dcdee2, #eff0f1, #dcdee2);
	-pie-background:          linear-gradient(left, #dcdee2, #eff0f1, #dcdee2); /* IE6-IE9 */
	-moz-border-radius:    8px;
	-webkit-border-radius: 8px;
	/*border-radius:         8px;*/
	-moz-box-shadow:    0 1px 2px rgba(0, 0, 0, 0.5);
	-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
	box-shadow:         0 1px 2px rgba(0, 0, 0, 0.5);
	behavior: URL(PIE.htc);
}
form.wide {
	width:1600px;
}

/* Labels */
label {
	margin-right: 10px;
	float: left;
	clear: both;
	width: 250px;
	color: #333;
	font-size: 14px;
	font-weight: bold;
	text-align:right;
	line-height:normal;
}

label span {
	font-size: 12px;
	font-weight: normal;
	color: #777;
}
#section1 label.error
{
	display:inline;
	margin: 0px 0px 0px 10px;
	clear:inherit;
	color: red;
}

#section2 label.error
{
	display:inline;
	margin: 0px 0px 0px 10px;
	clear:inherit;
	color: red;
	padding-bottom:2px;
}
#section3 label.error
{
	display:inline;
	margin: 0px 0px 0px 10px;
	clear:inherit;
	color: red;
	padding-bottom:2px;
}
#section3b label.error
{
	display:inline;
	margin: 0px 0px 0px 10px;
	clear:inherit;
	color: red;
	padding-bottom:2px;
}
#section2 .select-wrapper .error{
	margin:0px;
	display:inline;
	width:200px;
}
#section3 .select-wrapper .error{
	margin:0px;
	display:inline;
	width:200px;
}
#section3b .select-wrapper .error{
	margin:0px;
	display:inline;
	width:200px;
}

#section3 label.error
{
	display:inline;
	margin: 0px 0px 0px 10px;
	clear:inherit;
	color: red;
}
#section3 .select-wrapper .error{
	margin:0px;
	display:inline;
	width:200px;
}
/* Textbox and textarea */
.form-input , .form-input-table{
	float: left;
	width: 300px;
	height: 24px;
	padding: 6px 10px;
	margin-bottom: 20px;
	font: 14px 'Helvetica Neue', Helvetica, Arial, sans-serif;
	font-weight:bold;
	color: #333;
	background: #fff;
	border: 1px solid #ccc;
	outline: none;
	-moz-border-radius:    8px;
	-webkit-border-radius: 8px;
	border-radius:         8px;
	-moz-box-shadow:    inset 0 0 1px rgba(0, 0, 0, 0.5), 0 1px 0 rgba(255, 255, 255, 0.7);
	-webkit-box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.5), 0 1px 0 rgba(255, 255, 255, 0.7);
	box-shadow:         inset 0 0 1px rgba(0, 0, 0, 0.5), 0 1px 0 rgba(255, 255, 255, 0.7);
	-moz-background-clip:    padding;
	-webkit-background-clip: padding-box;
	background-clip:         padding-box;
	-moz-transition:    all 0.4s ease-in-out;
	-webkit-transition: all 0.4s ease-in-out;
	-o-transition:      all 0.4s ease-in-out;
	-ms-transition:     all 0.4s ease-in-out;
	transition:         all 0.4s ease-in-out;
	behavior: url(PIE.htc);
}
.number {
	width:80px;
}
textarea.form-input {
	height: 200px;
	width: 700px;
	overflow: auto;
	border: 1px #ccc solid;
}

textarea.form-input-address {
	height: 130px;
	width: 300px;
	padding:10px;
	line-height:18px;
	overflow: auto;
	border: 1px #ccc solid;
}

.select-wrapper {
	width: 200px;
	float: left;
	/*margin-bottom: 40px;*/
	overflow: hidden;
	position: relative;
}

p.option-label {
	float: left;
	clear: both;
	margin-right: 10px;
	width: 250px;
	font-size: 14px;
	font-weight: bold;
	line-height:normal;
}

div.option-group{
	float: left;
	width: 300px;
	height:40px;
	/*margin-bottom: 40px;*/
}

.option-group input[type=radio], .option-group input[type=checkbox] {
	float: left;
	clear: both;
	opacity: 0;
	outline: none;
} 

.option-group label {
	width: 260px;
	margin: 0 0 20px 10px;
	float: left;
	clear: none;
	position: relative;
	line-height: 1;
	font-weight: normal;
}


/* Focus style */
.form-input:focus {
	border: 1px solid #7fbbf9;
	-moz-box-shadow:    inset 0 0 1px rgba(0, 0, 0, 0.5), 0 0 3px #7fbbf9;
	-webkit-box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.5), 0 0 3px #7fbbf9;
	box-shadow:         inset 0 0 1px rgba(0, 0, 0, 0.5), 0 0 3px #7fbbf9;
}

/* Error style */
.form-input:-moz-ui-invalid, .form-input.invalid {
	border: 1px solid #e00;
	-moz-box-shadow:    inset 0 0 1px rgba(0, 0, 0, 0.5), 0 0 3px #e00;
	-webkit-box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.5), 0 0 3px #e00;
	box-shadow:         inset 0 0 1px rgba(0, 0, 0, 0.5), 0 0 3px #e00;
}

.form-input.invalid {
	border: 1px solid #e00;
	-moz-box-shadow:    inset 0 0 1px rgba(0, 0, 0, 0.5), 0 0 3px #e00;
	-webkit-box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.5), 0 0 3px #e00;
	box-shadow:         inset 0 0 1px rgba(0, 0, 0, 0.5), 0 0 3px #e00;
}


/* Form submit button */
.form-btn {
	clear: both;
	/*float: left;*/
	padding: 0 15px;
	height: 30px;
	font: bold 12px 'Helvetica Neue', Helvetica, Arial, sans-serif;
	text-align: center;
	color: #fff;
	text-shadow: 0 1px 0 rgba(0, 0, 0, 0.5);
	text-decoration:none;
	cursor: pointer;
	border: 1px solid #1972c4;
	outline: none;
	position: relative;
	background-color: #1d83e2;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#77b5ee), to(#1972c4)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #77b5ee, #1972c4); /* Chrome 10+, Saf5.1+, iOS 5+ */
	background-image:    -moz-linear-gradient(top, #77b5ee, #1972c4); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #77b5ee, #1972c4); /* IE10 */
	background-image:      -o-linear-gradient(top, #77b5ee, #1972c4); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #77b5ee, #1972c4);
	-pie-background:          linear-gradient(top, #77b5ee, #1972c4); /* IE6-IE9 */
	-moz-border-radius:    16px;
	-webkit-border-radius: 16px;
	border-radius:         16px;
	-moz-box-shadow:    inset 0 1px 0 rgba(255, 255, 255, 0.3), 0 1px 2px rgba(0, 0, 0, 0.5);
	-webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3), 0 1px 2px rgba(0, 0, 0, 0.5);
	box-shadow:         inset 0 1px 0 rgba(255, 255, 255, 0.3), 0 1px 2px rgba(0, 0, 0, 0.5);
	-moz-background-clip:    padding;
	-webkit-background-clip: padding-box;
	background-clip:         padding-box;
	behavior: url(PIE.htc);
}

.form-btn:active {
	border: 1px solid #77b5ee;
	background-color: #1972c4;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#1972c4), to(#77b5ee)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #1972c4, #77b5ee); /* Chrome 10+, Saf5.1+, iOS 5+ */
	background-image:    -moz-linear-gradient(top, #1972c4, #77b5ee); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #1972c4, #77b5ee); /* IE10 */
	background-image:      -o-linear-gradient(top, #1972c4, #77b5ee); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #1972c4, #77b5ee);
	-pie-background:          linear-gradient(top, #1972c4, #77b5ee); /* IE6-IE9 */
	-moz-box-shadow:    inset 0 0 5px rgba(0, 0, 0, 0.5), 0 1px 0 rgba(255, 255, 255, 0.5);
	-webkit-box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.5), 0 1px 0 rgba(255, 255, 255, 0.5);
	box-shadow:         inset 0 0 5px rgba(0, 0, 0, 0.5), 0 1px 0 rgba(255, 255, 255, 0.5);
}

input[type=submit]::-moz-focus-inner {
	border: 0;
	padding: 0;
}



/****** Checkbox style ******/

.option-group.check label:before, .ie .option-group.check label .before {
	content: '';
	position: absolute;
	top: -1px;
	left: -30px;
	width: 15px;
	height: 15px;
	background-color: #fff;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#efefef)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #ffffff, #efefef); /* Chrome 10+, Saf5.1+, iOS 5+ */
	background-image:    -moz-linear-gradient(top, #ffffff, #efefef); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #ffffff, #efefef); /* IE10 */
	background-image:      -o-linear-gradient(top, #ffffff, #efefef); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #ffffff, #efefef);
	-pie-background:          linear-gradient(top, #ffffff, #efefef); /* IE6-IE9 */
	border: 1px solid #ccc;
	-moz-border-radius:    3px;
	-webkit-border-radius: 3px;
	border-radius:         3px;
	-moz-box-shadow:    0 1px 0 rgba(0, 0, 0, 0.5);
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.5);
	box-shadow:         0 1px 0 rgba(0, 0, 0, 0.5);
	-pie-watch-ancestors: 1;
	behavior: url(PIE.htc);
}

.ie .option-group.check label:before {
	display: none;
}

.option-group.check input[type=checkbox]:checked + label:after, .ie .option-group.check label.checked .after {
	content: '';
	position: absolute;
	top: 2px;
	left: -24px;
	width: 3px;
	height: 8px;
	border-bottom: 2px solid #444;
	border-right: 2px solid #444;
	-moz-transform:    rotate(45deg);
	-webkit-transform: rotate(45deg);
	-o-transform:      rotate(45deg);
	-ms-transform:     rotate(45deg);
	transform:         rotate(45deg);
	filter: progid:DXImageTransform.Microsoft.Matrix(   /* IE6�IE9 */ 
                   M11=0.7071067811865476, M12=-0.7071067811865475, M21=0.7071067811865475, M22=0.7071067811865476, sizingMethod='auto expand');
    zoom: 1;	
}

.ie .option-group.check label.checked .after {
	left: -26px;
}

.option-group.check input[type=checkbox]:checked + label:before, .ie .option-group.check label.checked .before {
	background-color: #f5f5f5;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#efefef), to(#ffffff)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #efefef, #ffffff); /* Chrome 10+, Saf5.1+, iOS 5+ */
	background-image:    -moz-linear-gradient(top, #efefef, #ffffff); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #efefef, #ffffff); /* IE10 */
	background-image:      -o-linear-gradient(top, #efefef, #ffffff); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #efefef, #ffffff);
	-pie-background:          linear-gradient(top, #efefef, #ffffff); /* IE6-IE9 */
	-moz-box-shadow:    inset 0 1px 1px rgba(0, 0, 0, 0.5);
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.5);
	box-shadow:         inset 0 1px 1px rgba(0, 0, 0, 0.5);
}




/****** Radio button style ******/

.option-group.radio label:before,  .ie .option-group.radio label .before {
	content: '';
	position: absolute;
	top: -2px;
	left: -30px;
	width: 15px;
	height: 15px;
	background-color: #fff;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#efefef)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #ffffff, #efefef); /* Chrome 10+, Saf5.1+, iOS 5+ */
	background-image:    -moz-linear-gradient(top, #ffffff, #efefef); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #ffffff, #efefef); /* IE10 */
	background-image:      -o-linear-gradient(top, #ffffff, #efefef); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #ffffff, #efefef);
	-pie-background:          linear-gradient(top, #ffffff, #efefef); /* IE6-IE9 */
	border: 1px solid #ccc;
	-moz-border-radius:    10px;
	-webkit-border-radius: 10px;
	border-radius:         10px;
	-moz-box-shadow:    0 1px 0 rgba(0, 0, 0, 0.5);
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.5);
	box-shadow:         0 1px 0 rgba(0, 0, 0, 0.5);
	-moz-background-clip:    padding;
	-webkit-background-clip: padding-box;
	background-clip:         padding-box;
	-pie-watch-ancestors: 1;
	behavior: url(PIE.htc);
}

.ie .option-group.radio label:before {
	display: none;
}

.option-group.radio input[type=radio]:checked + label:after, .ie .option-group.radio label .after {
	content: '';
	position: absolute;
	top: 3px;
	left: -25px;
	z-index: 2;
	width: 7px;
	height: 7px;
	background: #444;
	-moz-border-radius:    7px;
	-webkit-border-radius: 7px;
	border-radius:         7px;
	behavior: url(PIE.htc);
}

.ie .option-group.radio label:after, .ie .option-group.radio label .after  {
	display: none;
	border-radius: 0;
}

.ie .option-group.radio label.checked .after {
	display: block;
	border-radius: 7px;
}

.option-group.radio input[type=radio]:checked + label:before, .ie .option-group.radio label.checked .before {
	background-color: #f5f5f5;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#efefef), to(#ffffff)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #efefef, #ffffff); /* Chrome 10+, Saf5.1+, iOS 5+ */
	background-image:    -moz-linear-gradient(top, #efefef, #ffffff); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #efefef, #ffffff); /* IE10 */
	background-image:      -o-linear-gradient(top, #efefef, #ffffff); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #efefef, #ffffff);
	-pie-background:          linear-gradient(top, #efefef, #ffffff); /* IE6-IE9 */
	-moz-box-shadow:    inset 0 1px 1px rgba(0, 0, 0, 0.5);
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.5);
	box-shadow:         inset 0 1px 1px rgba(0, 0, 0, 0.5);
}




/****** Select box style ******/

.select-wrapper {
	background-color: #fff;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#efefef)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #ffffff, #efefef); /* Chrome 10+, Saf5.1+, iOS 5+ */
	background-image:    -moz-linear-gradient(top, #ffffff, #efefef); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #ffffff, #efefef); /* IE10 */
	background-image:      -o-linear-gradient(top, #ffffff, #efefef); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #ffffff, #efefef);
	-pie-background:          linear-gradient(top, #ffffff, #efefef); /* IE6-IE9 */
	-moz-border-radius:    5px;
	-webkit-border-radius: 5px;
	border-radius:         5px;
	-moz-box-shadow:    0 1px 1px rgba(0, 0, 0, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.5);
	-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.5);
	box-shadow:         0 1px 1px rgba(0, 0, 0, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.5);
	behavior: url(PIE.htc);
}

.selectTop {
	position: absolute;
	top: 0;
	left:0;
	bottom: 0;
	right: 0;
	/*padding: 4px 10px;*/
	font-size:13px;
	line-height:15px;
	height:100%;
	vertical-align:middle;
	display:table;
	width:95px;
}

.selectTop p{
	display:table-cell;
	vertical-align:middle;
	text-align:center;
}

.select-wrapper:before {
	content: '';
	position: absolute;
	top: 0;
	right: 0;
	width: 20px;
	height: 100%;
	pointer-events: none;
	background-color: #fff;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#efefef)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #ffffff, #efefef); /* Chrome 10+, Saf5.1+, iOS 5+ */
	background-image:    -moz-linear-gradient(top, #ffffff, #efefef); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #ffffff, #efefef); /* IE10 */
	background-image:      -o-linear-gradient(top, #ffffff, #efefef); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #ffffff, #efefef);
	-moz-border-radius:    5px;
	-webkit-border-radius: 5px;
	border-radius:         5px;
}

.select-wrapper:after {
	content: '';
	position: absolute;
	top: 50%;
	right: 10px;
	margin-top: -3px;
	border: 6px solid transparent;
	border-top: 6px solid #ccc;
	pointer-events: none;
}

.ie .select-wrapper:before, .ie9 .select-wrapper:before {
	display: none;
}

select {
	width: 200px;
	height: 24px;
	padding: 8px 0 4px 20px;
	border: 0;
	background: transparent none;
	outline: none;
	font: 14px 'Helvetica Neue', Helvetica, Arial, sans-serif;
	-moz-appearance:    none;
	-webkit-appearance: none;
	appearance:         none;
	-moz-box-sizing:    content-box;
    -webkit-box-sizing: content-box;
    box-sizing:         content-box;
}

select::-moz-focus-inner {
	border: 0;
}

select option {
	background: #ededed;
	height: 24px;
	width: 100%;
	padding: 10px 0 2px 10px;
	border-bottom: 1px solid #ccc;
	border-top: 1px solid #fff;
	-moz-appearance:    none;
	-webkit-appearance: none;
	appearance:         none;
	-moz-box-sizing:    content-box;
    -webkit-box-sizing: content-box;
    box-sizing:         content-box;
}
table.ctutable a{
	padding:6px 17px 6px 17px;
}
table.ctutable a:link {
	/*color: #666;*/
	
	font-weight: bold;
	text-decoration:none;
}
table.ctutable a:visited {
	/*color: #999999;*/
	font-weight:bold;
	text-decoration:none;
}
table.ctutable a:active,
table.ctutable a:hover {
	/*color: #bd5a35;*/
	text-decoration:none;
}
table.ctutable {
	font-family:Arial, Helvetica, sans-serif;
	width:1010px;
	/*color:#666;*/
	font-size:12px;
	text-shadow: 1px 1px 0px #fff;
	background:#eaebec;
	/*margin:20px;*/
	border:#ccc 1px solid;

	-moz-border-radius:8px;
	-webkit-border-radius:8px;
	border-radius:8px;

	-moz-box-shadow: 0 1px 2px #d1d1d1;
	-webkit-box-shadow: 0 1px 2px #d1d1d1;
	box-shadow: 0 1px 2px #d1d1d1;
}
table.ctutable-small {
	width:600px;
}
table.ctutable-large {
	width:auto;
}
table.ctutable-small td {
	font-weight:bold;
}
table.ctutable th {
	padding:21px 25px 22px 25px;
	border-top:1px solid #fafafa;
	border-bottom:1px solid #e0e0e0;
	vertical-align:middle;
	background: #ededed;
	background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
	background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
}
table.ctutable th:first-child {
	text-align:center;
	vertical-align:middle;
	padding-left:20px;
}
table.ctutable tr:first-child th:first-child {
	-moz-border-radius-topleft:5px;
	-webkit-border-top-left-radius:5px;
	border-top-left-radius:5px;
}
table.ctutable tr:first-child th:last-child {
	-moz-border-radius-topright:5px;
	-webkit-border-top-right-radius:5px;
	border-top-right-radius:5px;
}
table.ctutable tr {
	text-align: center;
	padding-left:20px;
}
table.ctutable td:first-child {
	text-align: center;
/*	padding-left:20px;*/
	border-left: 0;
}
table.ctutable td:last-child {
	padding:10px;
}
table.ctutable td {
	padding:10px;
	vertical-align:middle;
	border-top: 1px solid #ffffff;
	border-bottom:1px solid #e0e0e0;
	border-left: 1px solid #e0e0e0;

	background: #fafafa;
	background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
	background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
}
table.ctutable tr.even td {
	background: #f6f6f6;
	background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
	background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
}
table.ctutable tr:last-child td {
	border-bottom:0;
}
table.ctutable tr:last-child td:first-child {
	-moz-border-radius-bottomleft:8px;
	-webkit-border-bottom-left-radius:8px;
	border-bottom-left-radius:8px;
}
table.ctutable tr:last-child td:last-child {
	-moz-border-radius-bottomright:8px;
	-webkit-border-bottom-right-radius:8px;
	border-bottom-right-radius:8px;
}
table.ctutable tr:hover td {
	background: #f2f2f2;
	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
	background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	
}
table.ctutable .form-input {
	margin-bottom:0px;
	width:625px;
	border-radius:none;
}
table.ctutable td .form-input {
/*	margin-bottom:20px;*/
	width:625px;
	border-radius:none;
}

#section3b table.ctutable td .form-block  .form-input{
	margin-bottom:28px;
}

table.ctutable td .form-block  .form-input{
	margin-bottom:20px;
}

table.ctutable td .select-wrapper {
/*margin-bottom:20px;*/
}
table.ctutable .form-input-table {
	margin-bottom:0px;
	width:110px;
}
table.four .form-input-table {
	margin-bottom:0px;
	width:250px;
}
table.ctutable .datepick{
	/*margin-bottom:20px;*/
	width:80px;
}
.form-datepick {
	margin-bottom:20px !important;
}
table.ctutable textarea.form-input {
	/*width:800px;
	max-width:800px;
	resize:none;*/
}
table.ctutable .number {
	width:80px !important;
}
.large {
	font-size:18px;
}
.correct {
	color:green;
}
.incorrect {
	color:red;
}
.notyet {
	color:orange;
}


.error {
	border-color:red !important;
}
#dialog-confirm {
	display:none;
}
#dialog-delete {
	display:none;
}
#dialog-remove {
	display:none;
}
input#exit {
	margin-top:10px;
}
.errorwordcount {
	color:red;
}
/*SAMS ADDED EXTRA*/
.outline-table label, td
{
	margin-bottom:20px;
	vertical-align:middle;
	padding:0px;
}
.outline-table tr
{
	padding-bottom:20px;
}
.outline-table textarea
{
	width:500px;
	height:50px;
}

.spec textarea
{
width:500px !important;
height:50px;
}

.errortd label
{
width:200px !important;
}

.error
{
text-align:left;
}

select.error {
    border: 2px solid #CC0000
}

.errorlink
{
font-weight: bold;
text-decoration: none;
}

.valimg
{
top:3px;
padding-right:10px;
position:relative;
}
.errormessage
{
display:block;
padding:10px;
margin:20px;
background: #FFB6C1;
border: 2px #ff0000 solid;
font-weight: bold;
}

