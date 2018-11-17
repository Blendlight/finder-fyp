<style>
    a{
        display: block;
    }
    a.folder{
        margin-left: 20px;
        color: red;
    }
</style><?php
$files = glob('./*');
$files_output = "";
foreach($files as $file)
{
    if(is_dir($file))
    {
        $files_output .= '<a class="folder" href="#">Folder: '.$file.'</a>';
    }else{
        echo '<a href="'.$file.'">'.$file.'</a>';
    }
}
echo $files_output;