<?php
/*
    Purpose: Online Store
    Author: LW
    Date: November 2026
 */

// if the form is submitted

if (isset($_POST['search']))
{
    $bookTitle =  $_POST['bookTitle'];
    
    // cookies are set to expire 30 days from now (given in seconds)
    // store the search term as a cookie

    $expire = time() + (60 * 60 * 24 * 30);
    setcookie('lastsearch', '$bookTitle', $expire);
}

// If a previous user is visting this page

elseif (isset($_COOKIE['lastsearch']))
{
    $bookTitle =  $_COOKIE['lastsearch'];
}

// If a user is visiting this page for the first time

else
{
    $bookTitle =  '';
}

include ('WEshopdata.php');

include ('header.php');

// get records that match the search term

$results = getBooksByTitle('$bookTitle');
$resultsCount = count($results);
?>

<!-- display the search form -->

<div style="margin-left:20px; margin-top:30px">
<a href="WEviewcart.php">View Cart</a>
</div>
<form action="WEshopsearch.php" method = "post">

    <h2 style="text-align: center">Movie Store</h2>

    <table align="center" border="1" style="font-size: large">
        <tr>
            <td>
                    Search by BookTitle name:
            </td>
            <td>
                    <input type="text" name="bookTitle" size="40" maxlength ="50" value="<?php echo $bookTitle; ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                    <input name = "search" type="submit" value="Search" />
            </td>
        </tr>
    </table>

</form>

<?php

// if the form was submitted (for a new search) or a previous user is revisiting this page,
// display results for the current search or his/her last search

if ((isset($_POST['search']) || isset($_COOKIE['lastsearch'])) && $resultsCount > 0)
{
    $counter = 0;

    $output = <<<ABC
<table style="margin-left: 35%" border="0" cellspacing="30" >
    <tr>
ABC;

    foreach ($results as $aResult)
    {
        extract($aResult);
        $bookPrice = number_format($bookPrice,2,'.',',');
        $output .= <<<ABC
<td>
ABC;
        if ($imagenamesmall != '')
        {
           $output .= <<<ABC
<img src="images/$imagenamesmall" border="0" style="float:left">
ABC;
        }
        $output .=<<<ABC
<strong> <a href="WEshopdetail.php?bookIDPK=$bookIDPK"> $bookTitle </a> <strong> <br />
<i> \$$bookPrice </i> <br />
</td>
ABC;
        $counter ++;

        if ($counter == $resultsCount)
        {
            $output .= <<<ABC
</tr> </table>
ABC;
        }
        elseif ($counter % 2 == 0)
        {
            $output .= <<<ABC
</tr><tr>
ABC;
        }
    }

    echo $output;
}
include ('footer.php');

?>
