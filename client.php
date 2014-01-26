<?php
    header("Content-Type: application/json");
    $data = file_get_contents('config.json');
    $config = json_decode($data);
    //connecting to database

    mysql_connect($config->{'server'},$config->{'username'},$config->{'password'}) or die(mysql_error());
    mysql_select_db($config->{'database'})  or die("erreur") ;

    //getting data from the database
    $request = "SELECT D1.domain AS 'target', D2.domain AS 'fake' , D2.has_malware AS 'malware', D1.protocol AS 'fake protocol', D2.protocol AS 'target protocol' FROM domain AS D1 LEFT JOIN domain AS D2 ON D1.domain = D2.target WHERE D2.is_fake =1 ;" ;
    $result = mysql_query($request) ;
    

    //creating json format
    $jsonData='{
';
    $n = mysql_num_rows($result) ;
    $i=0;
    while( $data = mysql_fetch_array($result) )
    {
        $i++;
        $jsonData .= ' "f'.$i.'":{ "domain":"'.$data['fake'].'", "origin":"'.$data['target'].'", "malware":"'.$data['malware'].'", "target_protocol":"'.$data['target protocol'].'", "fake_protocol":"'.$data['fake protocol'].'" } ' ;
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
