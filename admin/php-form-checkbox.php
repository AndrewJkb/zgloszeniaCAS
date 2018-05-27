<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
	<title>PHP form check box example</title>
</head>

<body>

<?php
// Code downloaded from html-form-guide.com
// This code may be used and distributed freely without any charge.
//
// Disclaimer
// ----------
// This file is provided "as is" with no expressed or implied warranty.
// The author accepts no liability if it causes any damage whatsoever.
//

	if(isset($_POST['formSubmit'])) 
    {
		$aDoor = $_POST['formDoor'];
		
		if(isset($_POST['formWheelchair'])) 
        {
			echo("<p>You DO need wheelchair access.</p>\n");
		} 
        else 
        {
			echo("<p>You do NOT need wheelchair access.</p>\n");
		}
		
		if(empty($aDoor)) 
        {
			echo("<p>You didn't select any buildings.</p>\n");
		} 
        else 
        {
            $N = count($aDoor);

			echo("<p>You selected $N door(s): ");
			for($i=0; $i < $N; $i++)
			{
				echo($aDoor[$i] . " ");
			}
			echo("</p>");
		}
        
        //Checking whether a particular check box is selected
        //See the IsChecked() function below
        if(IsChecked('formDoor','A'))
        {
            echo ' A is checked. ';
        }
        if(IsChecked('formDoor','B'))
        {
            echo ' B is checked. ';
        }
        //and so on
	}
    
    function IsChecked($chkname,$value)
    {
        if(!empty($_POST[$chkname]))
        {
            foreach($_POST[$chkname] as $chkval)
            {
                if($chkval == $value)
                {
                    return true;
                }
            }
        }
        return false;
    }
?>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
	<p>
		Which buildings do you want access to?<br/>
		<input type="checkbox" name="formDoor[]" value="A" />Acorn Building<br />
		<input type="checkbox" name="formDoor[]" value="B" />Brown Hall<br />
		<input type="checkbox" name="formDoor[]" value="C" />Carnegie Complex<br />
		<input type="checkbox" name="formDoor[]" value="D" />Drake Commons<br />
		<input type="checkbox" name="formDoor[]" value="E" />Elliot House
	</p>
	<p>
		Do you need wheelchair access?
		<input type="checkbox" name="formWheelchair" value="Yes" />
	</p>
	<input type="submit" name="formSubmit" value="Submit" />
</form>

<p>
<a href='http://www.html-form-guide.com/php-form/php-form-checkbox.html'>Handling checkbox in a PHP form processor</a>
</p>

</body>
</html>