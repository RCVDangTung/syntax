<html> 
    <head> 
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 
        <script src="http://malsup.github.com/jquery.form.js"></script> 
        
        <script src="./ajaxform.js"></script> 
    </head>
    
    <body>
        <form id="myForm" action="comment.php" method="post"> 
            Name: <input type="text" name="name" /> 
            Comment: <textarea name="comment"></textarea> 
            <input type="submit" value="Submit Comment" /> 
        </form>
        <form id="myForm" action="comment.php" method="post"> 
            Name: <input type="text" name="name" /> 
            Comment: <textarea name="comment"></textarea> 
            <input type="submit" value="Submit Comment" /> 
        </form>
    </body>
</html>  


<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
function test() {
    $arr_kategori = array();
    $i = 0;
    $kategori_res = $this->set_model->get_all('kategori');
    foreach ($kategori_res as $row_k) {
        $arr_kategori[$i]['id_kategori'] = $row_k->id_kategori;
        $arr_kategori[$i]['nama_kategori'] = $row_k->nama_kategori;
        $res = $this->set_model->get_data('tag', array('id_kategori' => $arr_kategori[$i]['id_kategori'] ));
        foreach ($res->result_array() as $row_t ) {
            $arr_kategori[$i]['tag'][] = array(
                'id_tag' => $row_t['id_tag'],
                'nama_tag' => $row_t['nama_tag']
            );
        }
        $i++;
    }
    echo json_encode($arr_kategori);
}