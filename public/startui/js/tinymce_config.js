tinymce.init({
    selector: '.richtext',
    height: 400,
    plugins: ["advlist autolink lists link image charmap print preview anchor", 
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste imagetools responsivefilemanager"
            ],
    // plugins: 'a11ychecker advcode casechange formatpainter linkchecker lists checklist media mediaembed pageembed permanentpen powerpaste tinycomments tinydrive tinymcespellchecker',
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | responsivefilemanager",
    // toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter insertfile pageembed permanentpen',
    toolbar_drawer: 'floating',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
//     external_filemanager_path:"../plugin/filemanager/",
//     filemanager_title:"File Manager" ,
//     external_plugins: { "filemanager" : "../filemanager/plugin.min.js" }
 });