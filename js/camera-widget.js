function cameraWidgetLoad() {
    const base64 = document.querySelector('.base64image.cam');

    if(!base64) {
        console.log('Camera widget failed to get the url');
        return;
    }

    handleCameraWidgetImage(base64);
}

function handleCameraWidgetImage(base64) {
    const image = document.querySelector('img.camera_widget').src = base64.innerHTML;
    
    if (!image) {
        console.log('Camera_widget module could not attach the image for the img field.');
        return;
    }

    base64.remove();
}

cameraWidgetLoad();