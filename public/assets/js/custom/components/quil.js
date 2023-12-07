var toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote'],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'indent': '-1' }, { 'indent': '+1' }],
    [{ 'direction': 'rtl' }],
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    [{ 'color': [] }],
    [{ 'font': [] }],
    [{ 'align': [] }],
    ['image', 'link']
];

var quill = new Quill('#kt_docs_quill_basic', {
    modules: {
        toolbar: toolbarOptions
    },
    placeholder: 'Type your text here...',
    theme: 'snow',
});

quill.on('text-change', function (delta, oldDelta, source) {
    const deleted = getImgUrls(quill.getContents().diff(oldDelta));
    $('#terget-editor').val(quill.container.firstChild.innerHTML);
    if (deleted.length) removeImageFromServer(deleted);
});

function getImgUrls(delta) {
    return delta.ops.filter(i => i.insert && i.insert.image).map(i => i.insert.image);
}

function selectLocalImage() {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.click();

    input.onchange = () => {
        const file = input.files[0];
        if (/^image\//.test(file.type)) {
            saveToServer(file);
        } else {
            console.warn('You could only upload images.');
        }
    };
}

function saveToServer(file) {
    const fd = new FormData();
    fd.append('image', file);
    fd.append('bucket', 'editor');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', uploadUri, true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + personalToken);
    xhr.onload = () => {
        if (xhr.status === 200) {
            const name = JSON.parse(xhr.responseText)[0].filename;
            const url = `${hostUrl}/storage/editor/${name}`;
            insertToEditor(url);
        }
    };
    xhr.send(fd);
}

function insertToEditor(url) {
    const range = quill.getSelection();
    quill.insertEmbed(range.index, 'image', `${url}`);
}

function removeImageFromServer(urlList) {
    const url = urlList[0];
    const splitUrl = url.split('/');

    const fd = new FormData();
    fd.append('filename', splitUrl[splitUrl.length - 1]);
    fd.append('bucket', 'editor');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', deleteFileUri, true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + personalToken);
    xhr.onload = () => {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
        }
    };
    xhr.send(fd);
}

quill.getModule('toolbar').addHandler('image', () => {
    selectLocalImage();
});