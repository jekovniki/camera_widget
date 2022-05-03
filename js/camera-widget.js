function cameraWidgetLoad() {
    const base64 = document.querySelector('.base64image.cam');
    const width = document.querySelector('.width.cam');
    const height = document.querySelector('.height.cam');


    if(!base64) {
        console.log('Camera widget failed to get the url');
        return;
    }

    handleCameraWidgetImage(base64, width, height);
}

function handleCameraWidgetImage(base64, width, height) {
    const image = document.querySelector('img.camera_widget').src = base64.innerHTML;
    document.querySelector('img.camera_widget').style = `width:${width.innerHTML};height:${height.innerHTML};`;
    
    if (!image) {
        console.log('Camera_widget module could not attach the image for the img field.');
        return;
    }

    base64.remove();
    width.remove();
    height.remove();
}

cameraWidgetLoad();