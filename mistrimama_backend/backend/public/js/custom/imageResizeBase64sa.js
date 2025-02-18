/*  
----
image load and resize at frontend and convert to 
base64 then save into the 
file input and covert the input field into text 
--
Plugin author: Sabbir Hossain
*/

// function resizeImage(id) {
//     var file = event.target.files[0];
//     // Ensure it's an image
//     if (file.type.match(/image.*/)) {
//         //  console.log('An image has been loaded');
//         // Load the image
//         var reader = new FileReader();
//         reader.onload = function (readerEvent) {
//             var image = new Image();
//             image.onload = function (imageEvent) {

//                 // Resize the image
//                 var canvas = document.createElement('canvas'),
//                     // max_size = 544,// TODO : pull max size from a site config
//                     width = image.width,
//                     height = image.height;
//                 // if (width > height) {
//                 //     if (width > max_size) {
//                 //         height *= max_size / width;
//                 //         width = max_size;
//                 //     }
//                 // } else {
//                 //     if (height > max_size) {
//                 //         width *= max_size / height;
//                 //         height = max_size;
//                 //     }
//                 // }
//                 canvas.width = width;
//                 canvas.height = height;
//                 canvas.getContext('2d').drawImage(image, 0, 0, width, height);
//                 var dataUrl = canvas.toDataURL('image/jpeg');
//                 $.event.trigger({
//                     type: "imageResized",
//                     url: dataUrl,
//                     id: id
//                 });
//             }
//             image.src = readerEvent.target.result;
//         }
//         reader.readAsDataURL(file);
//     }
// }

// $(document).on("imageResized", function (event) {
//     if (event.url) {
//         $b64 = event.url;
//         $id = event.id;
//         $html = '<input type="text" name="'+$id+'" value="'+$b64+'">';
//         $('#'+ $id).after($html);
//     }
// });

$('input[type="file"]').change(function () {
    var id = $(this).attr('id');
    // resizeImage($id);
    var file = event.target.files[0];
    if (file.type.match(/image.*/)) {
        var reader = new FileReader();
        reader.onload = function (readerEvent) {
            var image = new Image();
            image.src = readerEvent.target.result;
            $('#'+ id).after('<input type="text" name="'+id+'" value="'+image.src+'">');
        }
        reader.readAsDataURL(file);
    }
    console.log(file);
});