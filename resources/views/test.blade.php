<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.1.1/tinymce.min.js" integrity="sha512-bAtLCmEwg+N9nr6iVELr/SlDxBlyoF0iVdPxAvcOCfUiyi6RcuS6Lzawi78iPbAfbNyIUftvwK9HPWd+3p975Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>TinyMCE to HTML</title>
</head>
<body>
    <form action="{{ route('tambahSoalM') }}" method="post">
        @csrf
        <textarea id="mytextarea" name="content">Hello, World!</textarea>
        <button type="submit">Submit</button>
    </form>

    <script>
        tinymce.init({
            selector: '#mytextarea',
            promotion: false,
            plugins: 'preview importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
            editimage_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | print | pagebreak anchor codesample | ltr rtl",
            image_advtab: true,
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype === 'file' || meta.filetype === 'media') {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', '*');
                    input.onchange = function() {
                        var file = this.files[0];
                        callback(URL.createObjectURL(file), {
                            text: file.name
                        });
                    };
                    input.click();
                } else if (meta.filetype === 'image') {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function() {
                        var file = this.files[0];
                        callback(URL.createObjectURL(file), {
                            alt: file.name
                        });
                    };
                    input.click();
                }
            },
            height: '297mm',
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image table',
            skin: 'oxide',
            content_css: 'default',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });
    </script>
</body>
</html>
