<?php
    $var1 = "Alice";
    $var2 = "My friend is $var1";
    $var3 = 'My friend is $var1';

    echo "var2: $var2<br>";
    echo "var3: $var3<br>";

    $var4 = &$var1;
    echo "<br>До изменения:<br>";
    echo "var1: $var1<br>";
    echo "var4 (ссылка на var1): $var4<br>";

    $var1 = "Bob";
    echo "<br>После изменения:<br>";
    echo "var1: $var1<br>";
    echo "var4 (ссылка на var1): $var4<br>";

    $user = "Michael";
    $$user = "Jackson";
    
    echo "<br>Динамическая переменная:<br>";
    echo "\$user: $user<br>";
    echo "\$Michael: $Michael<br>";
?>