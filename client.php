<?php
    header("Content-Type: application/json");
     $data = file_get_contents('config.json');
     $config = json_decode($data);
    //connecting to database

    mysql_connect($config->{'server'},$config->{'username'},$config->{'password'}) or die(mysql_error());
    mysql_select_db($config->{'database'})  or die("erreur") ;

    //getting data from the database
    $request = "SELECT D1.domain AS 'target', D2.domain AS 'fake' , D2.is_malware AS 'malware' FROM domain AS D1 LEFT JOIN domain AS D2 ON D1.domain = D2.targeted WHERE D2.is_fake =1 ;" ;
    $result = mysql_query($request) ;
    

    //creating json format
    $jsonData='{
';
    $n = mysql_num_rows($result) ;
    $i=0;
    while( $data = mysql_fetch_array($result) )
    {
        $i++;
        $jsonData .= ' "u'.$i.'":{ "domaine":"'.$data['fake'].'", "altern":"'.$data['target'].'", "malware":"'.$data['malware'].'" } ' ;
        if ($i != $n ) 
        {
          $jsonData .= ',
 ' ; 
        }
    }
     $jsonData .='
}';
    echo $jsonData;
?>
