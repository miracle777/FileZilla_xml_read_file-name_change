<?php
# FileZilla.xml 読み込みファイ名変更　プログラム
# このファイルは、ルート直下に配置してください。

$file_list = array();

#ローカルPCのパス
$localpath ="/media/kumazaki/ubuntu-yobi/ココナラFTP/ココナラREPLUSバックアップ";
#xmlから取り出したファイル名

#XMLファイル指定
$filename = "./file/FileZilla.xml";

#変更前ファイル名保存
$wfilename ="./file/list.txt";

#変更後ファイル名保存
$rwfilename ="./file/Relist.txt";

// XMLデータをオブジェクトに変換
$xml_obj = simplexml_load_file($filename);

//オブジェクトをJSONに変換してから、連想配列に変換
$xml_array = json_decode(json_encode($xml_obj), true);


$FilenameArray = array_column($xml_array, 'Server');
$FilenameArray = array_column($FilenameArray, 'File');


$temp = end($FilenameArray[0]);
$file_end = $temp["LocalFile"];
$key = 0;
$file_list = "";


$paht_strl = mb_strlen($localpath) +1;


//ファイルがすでに存在する場合には処理を行わない
if(!file_exists($wfilename)){
    touch($wfilename);
}

//ファイルがすでに存在する場合には処理を行わない
if(!file_exists($rwfilename)){
    touch($rwfilename);
}

while($file_list <> $file_end ){
    $file_list_one = $FilenameArray[0][$key]; 
    $file_list = $file_list_one["LocalFile"];
    $file_name = $file_list_one["RemoteFile"];

    $file_path = "./" . ($substr4 = mb_substr($file_list, $paht_strl));
    print_r( $file_path);
    print "\n";
    //ファイル出力
    //ファイルOPEN
    $fp = fopen($wfilename,'a');
    $content = $file_path . "\n";
    file_put_contents($wfilename,$content,FILE_APPEND);
    //ファイルクローズ
    fclose ($fp);

    //拡張子
    $filename_point = mb_strpos($file_name,".");
    $file_type  = mb_substr( $file_name,$filename_point);
    //ファイル名を作りrename
    $basename = date('Y-m-d') . "-" . $key;
    //ディレクトリを作る
    $dir_point_start = mb_strlen($file_path);
    $dir_point_end = mb_strrpos($file_path,"/") +1;

    $redir = mb_substr($file_path,0, $dir_point_end);
    $rfile_name = $redir . $basename . $file_type;
  
    print_r($rfile_name);
    print "\n";
    echo "######";
    print "\n";
    //変更後ファイル出力
    //ファイルOPEN
    $fp = fopen($rwfilename,'a');
    $content = $rfile_name . "\n";
    file_put_contents($rwfilename,$content,FILE_APPEND);
    //ファイルクローズ
    fclose ($fp);

    //ファイル名変更
    
    $chk_rename = rename( $file_path , $rfile_name);

    if (!$chk_rename){
        print "rename エラー";
        exit;
    }
    

    

    
    //Key加算
    $key ++;

}

print "END";