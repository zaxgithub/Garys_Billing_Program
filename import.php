<?php 
session_start(); 


error_reporting (E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__))."\\includes\\constants.php");
require_once(realpath(dirname(__FILE__))."\\includes\\db.php");
require_once(realpath(dirname(__FILE__))."\\includes\\auth.php");
require_once(realpath(dirname(__FILE__))."\\includes\\header.php");
require_once(realpath(dirname(__FILE__))."\\includes\\menu.php");
?>

<div id="intQuad">
	<h2>HOME</h2>
	<div id="intQuadOuter">
		<div id="intQuadInner" align="center">
				<div style="min-height:300px;vertical-align:middle;text-align:center;width:auto;" align="center">

<?php
/***
    this is a simple and complete function and
    the easyest way i have found to allow you
    to add an image to a form that the user can
    verify before submiting

    if the user do not want this image and change
    his mind he can reupload a new image and we
    will delete the last

    i have added the debug if !move_uploaded_file
    so you can verify the result with your
    directory and you can use this function to
    destroy the last upload without uploading
    again if you want too, just add a value...
***/

function upload_back() { global $globals;

/***
    1rst set the images dir and declare a files
    array we will have to loop the images
    directory to write a new name for our picture
***/

  $uploaddir = 'uploads/'; $dir = opendir($uploaddir);
  $files = array();

/***
    if we are on a form who allow to reedit the
    posted vars we can save the image previously
    uploaded if the previous upload was successfull.
    so declare that value into a global var, we
    will rewrite that value in a hidden input later
    to post it again if we do not need to rewrite
    the image after the new upload and just... save
    the value...
***/

  if(!empty($_POST['attachement_loos'])) { $globals['attachement'] = $_POST['attachement_loos']; }

/***
    now verify if the file exists, just verify
    if the 1rst array is not empty. else you
    can do what you want, that form allows to
    use a multipart form, for exemple for a
    topic on a forum, and then to post an
    attachement with all our other values
***/

  if(isset($_FILES['attachement']) && !empty($_FILES['attachement']['name'])) {

/***
    now verify the mime, i did not find
    something more easy than verify the
    'image/' ty^pe. if wrong tell it!
***/

    if(!eregi('application/', $_FILES['attachement']['type'])) {

      echo 'The uploaded file is not an image please upload a valide file!';

    } else {

/***
    else we must loop our upload folder to find
    the last entry the count will tell us and will
    be used to declare the new name of the new
    image. we do not want to rewrite a previously
    uploaded image
***/

        while($file = readdir($dir)) { array_push($files,"$file"); echo $file; } closedir($dir);

/***
    now just rewrite the name of our uploaded file
    with the count and the extension, strrchr will
    return us the needle for the extension
***/

        $_FILES['attachement']['name'] = ceil(count($files)+'1').''.strrchr($_FILES['attachement']['name'], '.');
        $uploadfile = $uploaddir . basename($_FILES['attachement']['name']);

/***
    do same for the last uploaded file, just build
    it if we have a previously uploaded file
***/

        $previousToDestroy = empty($globals['attachement']) && !empty($_FILES['attachement']['name']) ? '' : $uploaddir . $files[ceil(count($files)-'1')];

// now verify if file was successfully uploaded

      if(!move_uploaded_file($_FILES['attachement']['tmp_name'], $uploadfile)) {

echo '<pre>
Your file was not uploaded please try again
here are your debug informations:
'.print_r($_FILES) .'
</pre>';

      } else {















//######################################################################
// FILE UPLOAD COMPLETE... MOVING ON
          echo 'File uploaded succesfully uploaded!';
          $url = "excel.php?filename=".$_FILES['attachement']['name']."&tslip_period_ending=".$_POST['tslip_period_ending']."&emp_id=".$_POST['emp_id']."&template=".$_POST['template'];
          if($_POST['template']=="jen_billing"){
          	$url = "excel_bslip.php?filename=".$_FILES['attachement']['name']."&tslip_period_ending=".$_POST['tslip_period_ending']."&emp_id=".$_POST['emp_id']."&template=".$_POST['template'];
          }
header("Location: ".$url);
//######################################################################








      }

/***
    and reset the globals vars if we maybe want to
    reedit the form: first the new image, second
    delete the previous....
***/

        $globals['attachement'] = $_FILES['attachement']['name'];
        if(!empty($previousToDestroy)) { unlink($previousToDestroy); }

    }

  }
}

upload_back();

/***
    now the form if you need it (with the global...):

    just add the hidden input when you write your
    preview script and... in the original form but!
    if you have send a value to advert your script
    than we are remaking the form. for exemple with a
    hidden input with "reedit" as value  or with a
    $_GET method who can verify that condition
***/

echo '<form action="javascript:validateImport();" name="import_form" value="import_form" method="post" enctype="multipart/form-data">
	<div style="width:400px;text-align:right;">
		<div style="padding:3px;">Period Ending: <input type="text" name="tslip_period_ending" id="tslip_period_ending" value="" /></div>
		<div style="padding:3px;"><div id="emp_id" style="float:right;"></div><div style="float:right;">Employee ID:</div></div>
 		<div style="clear:both;padding:8px;">File To Upload: <input type="file" name="attachement" name="attachement"></input></div>
 		<div style="clear:both;padding:8px;color:red;font-weight:bold;">Template Used: 
		<select name="template" id="template" style="color:red;border:solid 1px red;padding:3px;">
		<option value="">-----------------------------------</option>
		<option value="">[ TIME SHEETS ]</option>
		<option value="jen_time"> Jenifers Time Sheet </option>
		<option value="glenda_time"> Glendas Time Sheet </option>
		<option value="gary_time"> Garys Time Sheet </option>
		<option value=""> &nbsp; </option>
		<option value="">-----------------------------------</option>
		<option value="">[ BILLING SHEETS]</option>
		<option value="jen_billing"> Jenifers Billing Sheet </option>
		<option value=""> &nbsp; </option>
		<option value=""> &nbsp; </option>
		</select></div>
  	<input type="hidden" name="attachement_loos" name="attachement_loos" value="', $globals['attachement'] ,'"></input>
  	<input type="submit" value="Import File" name="import_save_button"></input>
	</div>
</form>';
?>

					
				</div>
		</div>
	</div>
</div>
			
<script type="text/javascript" language="Javascript">
function validateImport(){
	if(document.import_form.tslip_period_ending.value == ""){
		alert('Please enter the Period Ending date.');
		document.import_form.tslip_period_ending.focus();
	}else if(document.import_form.emp_id.value == ""){
		alert('Please enter the Employee that the data will be imported for.');
		document.import_form.emp_id.focus();
	}else if(document.import_form.attachement.value == ""){
		alert('Please enter the File you wish to upload.');
		document.import_form.attachement.focus();
	}else if(document.import_form.template.value == ""){
		alert('Please enter the Template matching your imported file above.');
		document.import_form.template.focus();
	}else{
		document.import_form.action = "<?php echo $_SERVER['PHP_SELF']; ?>";
		document.import_form.submit();
	}
	
}

</script>
<script type="text/javascript" language="Javascript">
$(document).ready(function() {
$("#tslip_period_ending").datepicker({
		defaultDate: new Date($(this).val()),
    dateFormat: $.datepicker.ISO_8601, 
    showOn: "button", 
    buttonImage:  "images/calendar_popup.gif", 
    buttonImageOnly: true 
});


var eid = $('#emp_id').flexbox("emp_lookup.php", {
allowInput: true,
autoCompleteFirstMatch: true,
arrowQuery: "null",
paging: false,
maxVisibleRows: 8
});
eid.setValue('');




});
</script>



<?php 
require_once(realpath(dirname(__FILE__))."\\includes\\footer.php");
?>