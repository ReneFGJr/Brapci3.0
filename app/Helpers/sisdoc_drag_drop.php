<?php
function upload()
{
    $sx = '
            <div id="drop-area">
            <form class="my-form">
                <p>Upload multiple files with the file dialog or by dragging and dropping images onto the dashed region</p>
                <input type="file" id="fileElem" multiple accept="image/*" onchange="handleFiles(this.files)">
                <label class="button" for="fileElem">Select some files</label>
            </form>
            </div>
        ';

    $sx .= '
        <style>
            .box__dragndrop,
            .box__uploading,
            .box__success,
            .box__error {
            display: none;
            }

            #drop-area {
  border: 2px dashed #ccc;
  border-radius: 20px;
  width: 480px;
  font-family: sans-serif;
  margin: 100px auto;
  padding: 20px;
}
#drop-area.highlight {
  border-color: purple;
}
p {
  margin-top: 0;
}
.my-form {
  margin-bottom: 10px;
}
#gallery {
  margin-top: 10px;
}
#gallery img {
  width: 150px;
  margin-bottom: 10px;
  margin-right: 10px;
  vertical-align: middle;
}
.button {
  display: inline-block;
  padding: 10px;
  background: #ccc;
  cursor: pointer;
  border-radius: 5px;
  border: 1px solid #ccc;
}
.button:hover {
  background: #ddd;
}
#fileElem {
  display: none;
}

        </syle>
        ';
    return $sx;
}
