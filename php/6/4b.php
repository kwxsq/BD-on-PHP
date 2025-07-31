<?php
$align = isset($_POST['align']) ? $_POST['align'] : 'left';
$valign = isset($_POST['valign']) ? implode(' ', $_POST['valign']) : 'top';

$alignOptions = ['left', 'center', 'right'];
$valignOptions = ['top', 'middle', 'bottom'];

$align = in_array($align, $alignOptions) ? $align : 'left';
$validValign = array_intersect(explode(' ', $valign), $valignOptions);
$valign = !empty($validValign) ? reset($validValign) : 'top';

echo "<table border='1' width='300' height='300' style='border-collapse: collapse;'>
    <tr>
        <td align='$align' valign='$valign'>Ваш текст здесь</td>
    </tr>
</table>";

echo "<br><a href='4a.html'>Назад</a>";
?>
