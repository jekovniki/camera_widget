const hide = document.getElementById('canvas');

hide.style.opacity = "0";
hide.style.position = "absolute";

function generateUrl() {
    setTimeout(() => {
        const url = document.getElementById('photo').src;
        document.getElementById('generate_url').value = url;
    }, '500');
}

function handleUsedImage() {
    const defaultImage = document.getElementById('generate_url').value;

    if(defaultImage === 'Nothing to show') {
        // Do nothing
    } else {
        document.getElementById('photo').src = defaultImage;
    }
}

handleUsedImage();