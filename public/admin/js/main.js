// ============ Upload Image With Preview ================
$(document).on('change', '.upload-img-input', function(){
    let self = $(this);
    const file = this.files[0];
    if (file){
        let reader = new FileReader();
        reader.onload = function(event){
            console.log(event.target.result);
            self.parents('.upload-with-preview').find('.preview-image').attr('src', event.target.result);
        }
        reader.readAsDataURL(file);
    }
});


$(document).ready(function() {
    // const self = $('.upload-with-preview');

    $('.upload-with-preview').each(function () {

        let self = $(this);

        // Prevent default behavior when dragging files over the drop zone
            self.on('dragover', function(e) {
            e.preventDefault();
                self.addClass('dragover');
        });

        // Remove the highlighting when leaving the drop zone
        self.on('dragleave', function() {
            self.removeClass('dragover');
        });

        // Handle the file drop
        self.on('drop', function(e) {
            e.preventDefault();
            self.removeClass('dragover');
            const files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
        });

        function handleFiles(files) {
            for (const file of files) {
                // Handle each file as needed, for example, upload or process it
                console.log(file.name);

                let reader = new FileReader();
                reader.onload = function(event){
                    console.log(event.target.result);
                    self.find('.preview-image').attr('src', event.target.result);
                };
                reader.readAsDataURL(file);
            }
        }

        // Listen for file input changes
        const fileInput = self.find('input');
        fileInput.on('change', function() {
            const files = fileInput[0].files;

            // Handle the uploaded files
            handleFiles(files);
        });
    });
});

